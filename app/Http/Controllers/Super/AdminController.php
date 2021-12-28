<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Lookup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $items = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (null !== $request->input('q')) {
            $items = User::where('role', 'admin')
                ->where('name', 'like', '%'. $request->input('q') .'%')
                ->orWhere('email', 'like', '%'. $request->input('q') .'%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $showFooter = true;
        
        return view('super.admin.index', compact('items', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('super.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:4',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8',
            ],
            [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
                'password.required' => 'Password baru tidak boleh dikosongi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Mohon ulangi password Anda dengan benar.',
                'password_confirmation.required' => 'Konfirmasi password tidak boleh dikosongi.',
                'password_confirmation.min' => 'Password diulang dengan minimal 8 karakter.',
            ]
        );

        $create_data = $request->all();
        $create_data['password'] = Hash::make($create_data['password']);
        $create_data['role'] = 'admin';
        User::create($create_data);
 
        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(User $admin)
    {
        return view('super.admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(User $admin)
    {
        $statusList = Lookup::where('type', 'UserStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return view('super.admin.edit', compact('admin', 'statusList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $admin)
    {
        $request->validate(
            [
                'name' => 'required|min:4',
                'email' => 'required|email|unique:users,email,' . $admin->id,
                'password' => 'nullable|min:8|confirmed',
                'password_confirmation' => 'required_with:password',
            ],
            [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Mohon ulangi password Anda dengan benar.',
            ]
        );

        $update_data = $request->all();
        if (empty($update_data['password'])) {
            unset($update_data['password']);
        } else {
            $update_data['password'] = Hash::make($update_data['password']);
        }
        $update_data['updated_at'] = date('c');
        $admin->update($update_data);
 
        return redirect()->back()->with('update', 'Data Anda telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $admin)
    {
        $message = __('Your data is successfully deleted');
        $admin->delete();

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Jump login as admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(Request $request, User $admin)
    {
        Auth::logout();
        Auth::login($admin, true);
        $message = 'Selamat datang di '. config('global.site_name') .'.';
        
        return redirect(route('admin.dashboard'))->with('message', $message);
    }
}
