<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    protected function index()
    {
        $user = Auth::user();
        if ($user === null) {
            return redirect()->back();
        }

        $member = $user->member;
        $districts = [];
        $district_name = '';
        if (is_using_district()) {
            $district = new District();
            $districts = $district->getListDistricts();
            if ($member !== null) {
                $district_name = ($member->district_id > 0) ? $districts[$member->district_id] : null;
            }
        } else {
            $district_name = get_district_value($member);
        }

        return view('member.profile', compact('districts', 'district_name'));
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
                'old_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
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

        return redirect()->route($user->role . '.profile')->with('success', $success);
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
                'email' => 'required|email|unique:users,email,' . $user->id,
                'file_name' => 'file|image|mimes:jpeg,png,jpg|max:4096',
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
                $member = $user->member;
                if ($member !== null) {
                    $update_member = $member->update($request->only('name', 'email', 'phone'));
                    // update image if any
                    $file = $request->file('file_name');
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $file_name = $member->member_id . '.' . $file->extension();

                        $upload_path = 'profiles';
                        $path = Storage::putFileAs(
                            'public/' . $upload_path,
                            $file,
                            $file_name
                        );

                        if ($path == 'public/' . $upload_path . '/' . $file_name) {
                            $meta = $member->meta ?? [];
                            $image_meta = [
                                'path' => $upload_path,
                                'file_name' => $file_name,
                                'original_name' => $file->getClientOriginalName(),
                                'extension' => $file->getClientOriginalExtension(),
                                'size' => $file->getSize(),
                                'mime_type' => $file->getMimeType(),
                            ];
                            $meta = array_merge($meta, $image_meta);
                            $member->update(['meta' => $meta]);
                        }
                    }
                }
                $success = 'Data Anda telah berhasil diubah.';
            }
        }

        return redirect()->route($user->role . '.profile')->with('success', $success);
    }

    /**
     * Change address execution
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function updateAddress(Request $request)
    {
        $user = Auth::user();
        if ($user === null) {
            return redirect()->back();
        }

        $rules = [
            'fields' => [
                'address' => 'required|min:8',
                'district_name' => 'required',
                'district_id' => 'required',
                'phone' => 'required',
                'gender' => 'required',
                'postal_code' => 'required',
            ],
            'messages' => [
                'address.required' => 'Alamat tidak boleh dikosongi.',
                'address.min' => 'Alamat minimal 8 karakter.',
                'phone.required' => 'Nomor telepon tidak boleh dikosongi.',
                'gender.required' => 'Jenis kelamin tidak boleh dikosongi.',
                'postal_code.required' => 'Kode pos tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
        }

        $request->validate($rules['fields'], $rules['messages']);

        $success = null;
        $member = $user->member;
        if ($member !== null) {
            $updateData = $request->only(
                'address',
                'district_id',
                'district_name',
                'phone',
                'gender',
                'postal_code'
            );
            $update_member = $member->update($updateData);
            if ($update_member) {
                $success = 'Data Anda telah berhasil diubah.';
            }
        }

        $back_to = $user->role . '.profile';
        if ($request->has('back_to')) {
            $back_to = $request->input('back_to');
        }

        return redirect()->route($back_to)->with('success', $success);
    }

    /**
     * Reload/refresh balance information
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function reloadBalance(Request $request)
    {
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member instanceof \App\Models\Member) {
            balance_sync(0, true);
        }

        return redirect()->back();
    }
}
