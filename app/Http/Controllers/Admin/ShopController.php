<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Lookup;
use App\Models\Member;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $items = Shop::orderBy('created_at', 'desc')->paginate(10);
        if (null !== $request->input('q')) {
            $items = Shop::where('name', 'like', '%'. $request->input('q') .'%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $statusList = Lookup::where('type', 'ShopStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        $showFooter = true;
        
        return view('admin.shop.index', compact('items', 'statusList', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $district = new District();
        $districts = $district->getListDistricts();

        $statusList = Lookup::where('type', 'ShopStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        $members = Member::where('status', 1)
            ->whereDoesntHave('shop')
            ->pluck('name', 'id')
            ->toArray();

        return view('admin.shop.create', compact('districts', 'statusList', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'fields' => [
                'name' => 'required|min:4',
                'phone' => 'required',
                'district_name' => 'required',
                'district_id' => 'required',
                'member_name' => 'required',
                'member_id' => 'required',
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'phone.required' => 'No Telepon/WA tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
                'member_name.required' => 'Nama owner tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
        }

        $request->validate($rules['fields'], $rules['messages']);

        $create_data = $request->all();
        if (empty($create_data['district_id'])) {
            $create_data['district_id'] = 0;
        }
        // create slug
        $create_data['slug'] = Str::slug($create_data['name']);
        $checkSlug = Shop::where('slug', $create_data['slug'])->first();
        if ($checkSlug !== null) {
            $create_data['slug'] = $create_data['slug'] . '-' . time();
        }
        Shop::create($create_data);
 
        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request, Shop $shop)
    {
        $products = Product::where('shop_id', $shop->id)->paginate(10);

        if (null !== $request->input('q')) {
            $products = Product::where('title', 'like', '%' . $request->input('q') . '%')
                ->where('shop_id', $shop->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.shop.show', compact('shop', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Shop $shop)
    {
        $district = new District();
        $districts = $district->getListDistricts();

        $statusList = Lookup::where('type', 'ShopStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return view('admin.shop.edit', compact('shop', 'districts', 'statusList'));
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
                'name' => 'required|min:4',
                'phone' => 'required',
                'district_name' => 'required',
                'district_id' => 'required'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'phone.required' => 'No Telepon/WA tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
        }
        
        $request->validate($rules['fields'], $rules['messages']);

        $update_data = $request->all();
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shop $shop)
    {
        $message = __('Your data is successfully deleted');
        if ($shop->products->count() == 0) {
            $shop->delete();
        } else {
            $message = __('Unable to delete this data due to any relationship');
        }

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Change shop status
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    public function status(Request $request)
    {
        $result = [
            'status' => false,
            'message' => __('Failed to change shop status')
        ];
        if ($request->has('id')) {
            $shop = Shop::findOrFail($request->input('id'));
            if ($shop instanceof \App\Models\Shop) {
                $status = (int)$request->input('status');
                $update = $shop->update(['status' => $status]);
                if ($update) {
                    $result['status'] = 'success';
                    $result['message'] = __('Shop status has been successfully updated');
                }
            }
        }

        return response()->json($result);
    }
}
