<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index()
    {
        return view('super.profile');
    }

    /**
     * Change password execution
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function password(Request $request)
    {
        $user = Auth::user();
        if ($user === null) {
            return redirect()->back();
        }

        $request->validate(
            [
                'old_password' => ['required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password ?? '')) {
                            $fail('Password lama salah.');
                        }
                    }
                ],
                'password' => 'required|min:8|different:old_password|confirmed',
                'password_confirmation' => 'required|min:8',
            ],
            [
                'old_password.required' => 'Password lama tidak boleh dikosongi.',
                'password.required' => 'Password baru tidak boleh dikosongi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.different' => 'Password baru tidak boleh sama dengan password lama.',
                'password.confirmed' => 'Mohon ulangi password Anda dengan benar.',
                'password_confirmation.required' => 'Konfirmasi password tidak boleh dikosongi.',
                'password_confirmation.min' => 'Password minimal 8 karakter.',
            ]
        );

        $success = null;
        $userModel = User::find($user->id);
        if ($userModel !== null) {
            $update = $userModel->update(
                ['password' => Hash::make($request->password)]
            );
    
            if ($update) {
                $success = 'Password Anda telah berhasil diubah.';
            }
        }

        return redirect()->route($user->role .'.profile')->with('success', $success);
    }

    /**
     * Change profile execution
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(Request $request)
    {
        $user = Auth::user();
        if ($user === null) {
            return redirect()->back();
        }
        
        $request->validate(
            [
                'name' => 'required|min:4',
                'email' => 'required|email|unique:users,email,'. $user->id,
            ],
            [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
            ]
        );

        $success = null;

        $userModel = User::find($user->id);
        if ($userModel !== null) {
            $update = $userModel->update($request->only('name', 'email'));
            if ($update) {
                $success = 'Data Anda telah berhasil diubah.';
            }
        }

        return redirect()->route($user->role .'.profile')->with('success', $success);
    }
}
