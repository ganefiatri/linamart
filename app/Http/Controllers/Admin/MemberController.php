<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Lookup;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $items = Member::orderBy('created_at', 'desc')->paginate(10);
        if (null !== $request->input('q')) {
            $items = Member::where('name', 'like', '%'. $request->input('q') .'%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $showFooter = true;
        
        return view('admin.member.index', compact('items', 'showFooter'));
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

        $genders = Lookup::where('type', 'Gender')
        ->orderBy('position')->pluck('name', 'code')
        ->toArray();

        $statusList = Lookup::where('type', 'ClientStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return view('admin.member.create', compact('districts', 'genders', 'statusList'));
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
                'email' => 'required|email|unique:members,email',
                'phone' => 'required',
                'district_name' => 'required',
                'district_id' => 'required'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
                'phone.required' => 'No Telepon/WA tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
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
        $create_data['member_id'] = time();
        $member = Member::create($create_data);
        if ($member instanceof \App\Models\Member) {
            User::create(
                [
                    'name' => $member->name,
                    'email' => $member->email,
                    'password' => Hash::make('12345678'),
                    'role' => 'member',
                    'member_id' => $member->id
                ]
            );
        }
 
        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Member $member)
    {
        return view('admin.member.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Member $member)
    {
        $district = new District();
        $districts = $district->getListDistricts();

        $genders = Lookup::where('type', 'Gender')
        ->orderBy('position')->pluck('name', 'code')
        ->toArray();

        $statusList = Lookup::where('type', 'ClientStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return view('admin.member.edit', compact('member', 'districts', 'genders', 'statusList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Member $member)
    {
        $rules = [
            'fields' => [
                'name' => 'required|min:4',
                'email' => 'required|email|unique:members,email,' . $member->id,
                'phone' => 'required',
                'district_name' => 'required',
                'district_id' => 'required'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
                'phone.required' => 'No Telepon/WA tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
        }
        
        $request->validate($rules['fields'], $rules['messages']);

        $update_data = $request->all();
        $update_data['updated_at'] = date('c');
        $member->update($update_data);
 
        return redirect()->back()->with('update', 'Data Anda telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Member $member)
    {
        $message = __('Your data is successfully deleted');
        if (($member->orders->count() == 0) && ($member->shop === null)) {
            if ($member->user !== null) {
                $member->user()->delete();
            }
            $member->delete();
        } else {
            $message = __('Unable to delete this data due to any relationship');
        }

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Jump login as member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(Request $request, Member $member)
    {
        $user = $member->user;
        if ($user instanceof \App\Models\User) {
            Auth::logout();
            Auth::login($user, true);
            $message = 'Selamat datang di '. config('global.site_name') .'.';
            
            return redirect(route('member.dashboard'))->with('message', $message);
        }

        return redirect()->back()->with('message', 'Unable to login.');
    }
}
