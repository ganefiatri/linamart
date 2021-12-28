<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MailQueue;
use App\Models\Member;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Show the member registration form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if ($request->has('idm') && $request->has('token') && $request->has('name')) {
            $member_id = $request->input('idm');
            $member = Member::where('member_id', $member_id)->first();

            $need_api_check = true;
            if ($member instanceof \App\Models\Member && $member->email == 'member@test.com') {
                $need_api_check = false;
                if (!array_key_exists('token', $member->meta ?? [])) {
                    $meta = $member->meta ?? [];
                    $meta['token'] = $request->input('token');
                    $member->update(['meta' => $meta]);
                    $member->fresh();
                }
            }

            $saldo = 0.0;
            $token = $request->input('token');
            $name = null;
            $email = null;
            $phone = null;
            if ($need_api_check) {
                //result : [IDMEMBER, NAMA, SALDO, TOKEN]
                $login_data = $this->linaLoginCheck($request);
                if (!is_array($login_data)) {
                    abort(404);
                } else {
                    $name = $login_data['NAMA'] ?? null;
                    $email = $login_data['EMAIL'] ?? null;
                    $saldo = floatval($login_data['SALDO'] ?? 0);
                    $token = $login_data['TOKEN'] ?? null;
                    if (empty($token)) {
                        abort(401);
                    }
                    $phone = $login_data['PHONE'] ?? null;
                }
            }

            $user = null;
            if ($member !== null) {
                $message = 'Member dengan ID '. $member_id .' telah terdaftar.';
                // save the token if it has dinamic value
                $meta = $member->meta ?? [];
                $meta['token'] = $token;
                $member->update(['meta' => $meta]);
                $user = $member->user;
                if ($member->user instanceof \App\Models\User) {
                    $user = $member->user;
                }
            } else {
                $data = $request->all();
                $member = Member::create([
                    'member_id' => $member_id,
                    'name' => $name ?? 'Member ' . $member_id,
                    'email' => $email ?? $member_id . '@email.com',
                    'phone' => $phone,
                    'meta' => ['token' => $token ?? null, 'pass' => uniqid()],
                    'status' => 1
                ]);
        
                if ($member !== false) {
                    $user_data = [
                        'name' => $member->name,
                        'email' => $member->email,
                        'password' => Hash::make($member->meta['pass'] ?? ''),
                        'role' => 'member',
                        'member_id' => $member->id
                    ];
                    $user = User::create($user_data);
                    if ($member->email != $member_id . '@email.com') {
                        // send welcome email
                        MailQueue::create([
                            'mail_to' => $member->email,
                            'mail_class' => '\App\Mail\MemberSignup',
                            'mail_params' => ['model' => '\App\Models\Member', 'id' => $member->id],
                            'priority' => 1
                        ]);
                    }
                }
            }

            // add saldo if any
            if ($saldo > 0) {
                balance_adjust($saldo, $member->id);
            }

            if ($user instanceof \App\Models\User) {
                if ($member->status > 0) {
                    Auth::login($user, true);
                    $message = 'Selamat datang di '. config('global.site_name') .'.';
                    return redirect(route('member.dashboard'))->with('message', $message);
                } else {
                    abort(401);
                }
            }
        }

        abort(404);
    }

    /**
     * Create a new member instance after a valid registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function create(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:members,email',
                'member_id' => 'required|unique:members,member_id',
                'shop_name' => 'required',
                'district_id' => 'required',
                'gmap' => 'nullable|active_url'
            ],
            [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. misal: anda@gmail.com',
                'email.unique' => 'Email ini pernah terdaftar.',
                'member_id.required' => 'Member tidak boleh dikosongi.',
                'member_id.unique' => 'Member ini pernah terdaftar.',
                'shop_name.required' => 'Nama toko tidak boleh dikosongi.',
                'district_id.required' => 'Kecamatan tidak boleh dikosongi.',
            ]
        );

        $data = $request->all();
        $member = Member::create([
            'member_id' => $data['member_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'district_id' => $data['district_id']
        ]);

        if ($member !== false) {
            $shop_data = [
                'name' => $data['shop_name'],
                'slug' => Str::slug($data['shop_name']),
                'district_id' => $data['district_id'],
                'address' => $data['address']
            ];
            $shop = Shop::create($shop_data);
            if ($shop !== false) {
                // also add new user for this member
                $user_data = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('12345678'),
                    'role' => 'member',
                    'member_id' => $member->id
                ];
                $user = User::create($user_data);
                if ($user !== false) {
                    // add reset password data
                    $token = app('auth.password.broker')->createToken($user);

                    $message = 'Data toko Anda telah berhasil disimpan. 
                        Mohon setup password baru untuk akun login Anda.';
                    return redirect(route('member.setpassword', ['token' => $token]) .'?email='. $user->email)
                        ->with('message', $message);
                }
            }
        }
    }

    /**
     * Set new password on successfully regstration
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function setPassword(Request $request, $token)
    {
        $email = $request->input('email');

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $email]
        );
    }

    /**
     * Verify login to Lina API
     *
     * @param Request $request
     * @param Boolean $direct_api_check
     * @return array
     */
    private function linaLoginCheck(Request $request, $direct_api_check = false)
    {
        $tgl = date("YmdHis");
        $method = "profile";
        $idm = $request->input('idm');

        // true if hosting support curl IP Address with Port
        if (!$direct_api_check) {
            $token = $request->input('token');
            $explode = explode($idm, $token);
            if (is_array($explode) && count($explode) <= 1) {
                return [];
            }
            $token = (is_array($explode)) ? ($explode[0] ?? $token) : $token;
            $saldo = (is_array($explode)) ? floatval($explode[1] ?? 0) : 0.0;

            return [
                'NAMA' => $request->input('name'),
                'EMAIL' => $request->has('email') ? $request->input('email') : null,
                'SALDO' => $saldo,
                'TOKEN' => $token,
                'PHONE' => $request->has('phone') ? $request->input('phone') : null,
            ];
        } else {
            $sign = md5(strtolower($method . $idm . $tgl . getenv('LINA_API_KEY')));
            $body = json_encode(array(
                'method' => $method,
                'data' => $idm,
                'usertoken' => $request->input('token'),
                'tgl' => $tgl,
                'sign' => $sign
            ));
    
            if (($url = getenv('LINA_API_URL')) === false) {
                return [];
            }
    
            $curl = curl_init($url);
            if ($curl !== false) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json"
                ));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                $result = curl_exec($curl);
        
                if (curl_errno($curl)) {
                    abort(404, curl_error($curl));
                }
        
                curl_close($curl);
        
                if (!is_bool($result)) {
                    $result = json_decode($result, true);
                    $stat = $result["Status"];
                    if ($stat == 200) {
                        return $result["Table"][0] ?? false;
                    }
                }
            }
            return [];
        }
    }

    /**
     * Redirect to relay server.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function relay(Request $request)
    {
        if ($request->has('idm') && $request->has('token')) {
            $query = http_build_query($request->all());
            return redirect()->away(getenv('LINA_API_RELAY_URL') . 'relay.php?' . $query);
        }

        abort(404);
    }
}
