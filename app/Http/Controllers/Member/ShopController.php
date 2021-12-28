<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Lookup;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display current shop.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $member = $this->getMember();
        if ($member === null) {
            abort(401);
        }
        
        $shop = Shop::where('member_id', $member->id)->first();

        return view('member.shop.index', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $statusList = Lookup::where('type', 'ProductStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        $district = new District();
        $districts = $district->getListDistricts();

        return view('member.shop.create', compact('statusList', 'districts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $member = $this->getMember();
        if ($member === null) {
            abort(401);
        }

        $request->request->add(['member_id' => $member->id]);
        $rules = [
            'fields' => [
                'name' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $alpha_num_space = preg_match('/^[a-zA-Z0-9\s]+$/', $value);
                        if ($alpha_num_space == 0) {
                            $fail(ucfirst($attribute) . ' hanya diperbolehkan karakter huruf, angka dan spasi.');
                        }
                    }
                ],
                'district_name' => 'required',
                'district_id' => 'required',
                'gmap' => 'nullable|active_url',
                'member_id' => 'unique:shops,member_id'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
                'district_id.required' => 'Kecamatan tidak boleh dikosongi.',
                'member_id.unique' => 'Anda hanya diperbolehkan memiliki 1 toko saja.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
            unset($rules['messages']['district_id.required']);
        }

        $request->validate($rules['fields'], $rules['messages']);

        $create_data = $request->all();
        if (empty($create_data['district_id'])) {
            $create_data['district_id'] = 0;
        }
        // create slug
        $create_data['slug'] = Str::slug($create_data['name']);
        $checkShop = Shop::where('slug', $create_data['slug'])->first();
        if ($checkShop !== null) {
            $create_data['slug'] = $create_data['slug'] . '-' . time();
        }

        if (!empty($create_data['gmap'])) {
            $create_data['meta'] = ['gmap' => $create_data['gmap']];
        }
        // auto active at the moments
        $create_data['status'] = 1;
        Shop::create($create_data);
 
        return redirect(route('products.index'))->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Shop $shop)
    {
        $statusList = Lookup::where('type', 'ProductStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        $district = new District();
        $districts = $district->getListDistricts();

        return view('member.shop.edit', compact('shop', 'statusList', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Shop $shop)
    {
        $rules = [
            'fields' => [
                'name' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $alpha_num_space = preg_match('/^[a-zA-Z0-9\s]+$/', $value);
                        if ($alpha_num_space == 0) {
                            $fail(ucfirst($attribute) . ' hanya diperbolehkan karakter huruf, angka dan spasi.');
                        }
                    }
                ],
                'slug' => [
                    'required', 'unique:shops,slug,'. $shop->id,
                    function ($attribute, $value, $fail) {
                        $check = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
                        if ($check == 0) {
                            $fail('Format :attribute salah. Pisahkan spasi dengan tanda "-".');
                        }
                    }
                ],
                'district_name' => 'required',
                'district_id' => 'required',
                'gmap' => 'nullable|active_url'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
                'district_id.required' => 'Kecamatan tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
            unset($rules['messages']['district_id.required']);
        }

        $request->validate($rules['fields'], $rules['messages']);
        
        $update_data = $request->all();
        if (!empty($update_data['gmap'])) {
            $shop->meta = array_merge(($shop->meta ?? []), ['gmap' => $update_data['gmap']]);
        }

        // check has daily open
        if (!empty($update_data['daily_open'])) {
            $daily_open = [];
            foreach ($update_data['daily_open'] as $day => $on) {
                $daily_open[$day] = [
                    'day' => $day,
                    'day_name' => jddayofweek($day, 1),
                    'open' => $update_data['open_from'][$day] ?? '08:00',
                    'closed' => $update_data['open_to'][$day] ?? '17:00',
                ];
            }
            if (count($daily_open) > 0) {
                $shop->meta = array_merge(($shop->meta ?? []), ['daily_open' => $daily_open]);
            }
        }

        $update_data['updated_at'] = date('c');
        $shop->update($update_data);
 
        return redirect()->back()->with('update', 'Data Anda telah berhasil diubah.');
    }

    /**
     * Get member object
     *
     * @return \App\Models\Member|null
     */
    private function getMember()
    {
        $user = Auth::user();
        if ($user !== null) {
            return $user->member;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Shop $shop)
    {
        return view('member.shop.show', compact('shop'));
    }

    /**
     * Change shop status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function status(Request $request)
    {
        $result = [
            'status' => false,
            'message' => __('Failed to change your shop status')
        ];
        if ($request->has('id')) {
            $shop = Shop::findOrFail($request->input('id'));
            if ($shop instanceof \App\Models\Shop) {
                $status = ($shop->status == 0)? 1 : 0;
                $update = $shop->update(['status' => $status]);
                if ($update) {
                    $result['status'] = 'success';
                    $result['message'] = __('Your shop status has been successfully updated');
                }
            }
        }

        return response()->json($result);
    }
}
