<?php

use App\Models\ApiRequest;
use App\Models\Invoice;
use App\Models\MailQueue;
use App\Models\Member;
use App\Models\MemberBalance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

if (!function_exists('balance_info')) {
    /**
     * Get member balance
     *
     * @param boolean $money_format
     * @param integer $member_id
     * @return integer|string
     */
    function balance_info($money_format = false, $member_id = 0)
    {
        $balance = 0;
        $member = null;
        if ($member_id > 0) {
            $member = Member::find($member_id);
        } else {
            $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        if ($member !== null) {
            $memberBalance = MemberBalance::where('member_id', $member->id)->orderBy('id', 'desc')->first();
            if ($memberBalance instanceof \App\Models\MemberBalance) {
                $balance = (int) $memberBalance->end_balance;
            }
        }

        return ($money_format) ? 'Rp ' . number_format($balance, 0, ',', '.') : $balance;
    }
}

if (!function_exists('balance_adjust')) {
    /**
     * Adjust member balance
     *
     * @param double $nominal
     * @param integer $member_id
     * @return boolean
     */
    function balance_adjust($nominal = 0.0, $member_id = 0)
    {
        if ($nominal < 0) {
            return false;
        }

        $member = null;
        if ($member_id > 0) {
            $member = Member::find($member_id);
        } else {
            $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        if ($member !== null) {
            $memberBalance = MemberBalance::where('member_id', $member->id)->orderBy('id', 'desc')->first();
            $end_balance = 0;
            if ($memberBalance instanceof \App\Models\MemberBalance) {
                $end_balance = $memberBalance->end_balance;
            }

            if ($end_balance != $nominal) {
                MemberBalance::create([
                    'member_id' => $member->id,
                    'start_balance' => $end_balance,
                    'end_balance' => $nominal,
                    'invoice_id' => 0,
                    'last_sync' => date('Y-m-d H:i:s')
                ]);
            } else {
                if ($memberBalance instanceof \App\Models\MemberBalance) {
                    $memberBalance->update([
                        'last_sync' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        return true;
    }
}

if (!function_exists('balance_reduce')) {
    /**
     * Reduce member balance
     *
     * @param double $nominal
     * @param integer $invoice_id
     * @param integer $member_id
     * @return boolean
     */
    function balance_reduce($nominal = 0.0, $invoice_id = 0, $member_id = 0)
    {
        $member = null;
        if ($member_id > 0) {
            $member = Member::find($member_id);
        } else {
            $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        if ($member !== null) {
            $memberBalance = MemberBalance::where('member_id', $member->id)->orderBy('id', 'desc')->first();
            $end_balance = 0;
            if ($memberBalance instanceof \App\Models\MemberBalance) {
                $end_balance = $memberBalance->end_balance;
            }

            $notes = null;
            if ($invoice_id > 0) {
                $invoice = Invoice::find($invoice_id);
                if ($invoice !== null) {
                    $notes = 'Payment of invoice #' . $invoice->getInvoiceNumber();
                }
            }
            MemberBalance::create([
                'member_id' => $member->id,
                'start_balance' => ($start_balance = ($end_balance > 0) ? $end_balance : $nominal),
                'end_balance' => ($start_balance - $nominal),
                'invoice_id' => $invoice_id,
                'last_sync' => date('Y-m-d H:i:s'),
                'notes' => $notes,
                'meta' => ['nominal' => $nominal]
            ]);
        }

        return true;
    }
}

if (!function_exists('balance_add')) {
    /**
     * Add member balance
     *
     * @param double $nominal
     * @param integer $invoice_id
     * @param integer $member_id
     * @return boolean
     */
    function balance_add($nominal = 0.0, $invoice_id = 0, $member_id = 0)
    {
        $member = null;
        if ($member_id > 0) {
            $member = Member::find($member_id);
        } else {
            $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        if ($member !== null) {
            $memberBalance = MemberBalance::where('member_id', $member->id)->orderBy('id', 'desc')->first();
            $start_balance = 0.0;
            $end_balance = 0.0;
            if ($memberBalance instanceof \App\Models\MemberBalance) {
                $start_balance = $memberBalance->end_balance;
                $end_balance = $start_balance + $nominal;
            }

            if ($end_balance > 0) {
                $notes = null;
                if ($invoice_id > 0) {
                    $invoice = Invoice::find($invoice_id);
                    if ($invoice !== null) {
                        $notes = 'Add from invoice #' . $invoice->getInvoiceNumber();
                    }
                }

                MemberBalance::create([
                    'member_id' => $member->id,
                    'start_balance' => $start_balance,
                    'end_balance' => $end_balance,
                    'invoice_id' => $invoice_id,
                    'last_sync' => date('Y-m-d H:i:s'),
                    'notes' => $notes,
                    'meta' => ['nominal' => $nominal]
                ]);
            }
        }

        return true;
    }
}

if (!function_exists('balance_info_api')) {
    /**
     * Get balance information from API
     *
     * @param integer $member_id
     * @return double
     */
    function balance_info_api($member_id = 0)
    {
        if (getenv('DISABLE_LINA_API') == 'true') {
            return -1.0;
        }

        $member = null;
        if ($member_id > 0) {
            $member = Member::find($member_id);
        } else {
            $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        $balance = -1.0;
        $failed_message = null;
        if ($member instanceof \App\Models\Member) {
            $params = [
                'idm' => $member->member_id,
                'token' => (!empty($member->meta)) ? ($member->meta['token'] ?? '') : ''
            ];

            $response = Http::asForm()
                ->acceptJson()
                ->post(getenv('LINA_API_RELAY_URL') . 'profile.php', $params);

            if ($response->successful()) {
                $result = $response->json();
                $balance = floatval($result['message']['SALDO'] ?? -1.0);
                // new role on phase 3 #21, minus balance set to 0
                if ($balance < -1) {
                    $balance = 0;
                }

                if ($balance < 0) {
                    $failed_message = $response->body();
                }

                if (($result['response'] ?? 200) == 404) {
                    if (str_contains(strtolower($result['message'] ?? ''), 'token')) {
                        session(['expired_token' => true]);
                    }
                }
            } else {
                $failed_message = $response->body();
            }
        }

        if (!empty($failed_message)) {
            ApiRequest::create([
                'member_id' => $member->id ?? $member_id,
                'type' => 'balance_info_api',
                'success' => 0,
                'exception' => $failed_message,
                'meta' => [
                    'params' => [
                        'member_id' => $member->id ?? $member_id
                    ]
                ],
                'failed_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $balance;
    }
}

if (!function_exists('balance_sync')) {
    /**
     * Sync balance from API
     *
     * @param integer $member_id
     * @param boolean $force
     * @return boolean
     */
    function balance_sync($member_id = 0, $force = false)
    {
        if (getenv('DISABLE_LINA_API') == 'true') {
            return false;
        }

        $member = null;
        if ($member_id > 0) {
            $member = Member::find($member_id);
        } else {
            $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        if (!$member instanceof \App\Models\Member) {
            return false;
        } else {
            if (is_array($member->meta)) {
                if (!array_key_exists('token', $member->meta)) {
                    return false;
                }
            }
        }

        $memberBalance = $member->balances()->where('invoice_id', 0)->orderBy('id', 'desc')->first();
        if ($memberBalance instanceof \App\Models\MemberBalance) {
            $time = time() - strtotime($memberBalance->last_sync);
            if (!$force && ($time < 180)) {
                return false;
            }
        }

        $balance = balance_info_api($member->id);
        if (Session::has('expired_token')) {
            return false;
        }

        return balance_adjust($balance, $member->id);
    }
}

if (!function_exists('balance_transfer')) {
    /**
     * Transfer balance to API
     *
     * @param integer $member_from_id
     * @param integer $member_to_id
     * @param double $nominal
     * @param double $fee
     * @param \App\Models\Invoice|null $invoice
     * @return boolean
     */
    function balance_transfer($member_from_id = 0, $member_to_id = 0, $nominal = 0.0, $fee = 0.0, $invoice = null)
    {
        if (getenv('DISABLE_LINA_API') == 'true') {
            return false;
        }

        if ($nominal <= 0) {
            return false;
        }

        $member_from = null;
        if ($member_from_id > 0) {
            $member_from = Member::find($member_from_id);
        } else {
            $member_from = (!empty(Auth::user())) ? Auth::user()->member : null;
        }

        if (!$member_from instanceof \App\Models\Member) {
            return false;
        }

        $member_to = null;
        if ($member_to_id > 0) {
            $member_to = Member::find($member_to_id);
        }

        if (!$member_to instanceof \App\Models\Member) {
            return false;
        }

        $params = [
            'idm' => $member_from->member_id,
            'token' => (!empty($member_from->meta)) ? ($member_from->meta['token'] ?? '') : '',
            'ids' => $member_to->member_id,
            'nominal' => $nominal,
            'fee' => $fee
        ];

        $response = Http::asForm()
            ->acceptJson()
            ->post(getenv('LINA_API_RELAY_URL') . 'debet.php', $params);

        $failed_message = null;
        if ($response->successful()) {
            $result = $response->json();
            if ($result['response'] == 200) {
                $balance_from = floatval($result['message']['PENGIRIM_AKHIR'] ?? -1.0);
                //$balance_to = floatval($result['message']['TUJUAN_AKHIR'] ?? -1.0);
                balance_adjust($balance_from, $member_from->id);
                //balance_adjust($balance_to, $member_to->id); //somehow wrong nominal, still price + cost
                // just test : delete me
                ApiRequest::create([
                    'member_id' => $member_from->id,
                    'type' => 'balance_transfer',
                    'success' => 1,
                    'exception' => $response->body()
                ]);
                
                return true;
            } else {
                $failed_message = $response->body();
            }
        } else {
            $failed_message = $response->body();
        }

        if (!empty($failed_message)) {
            ApiRequest::create([
                'member_id' => $member_from->id,
                'type' => 'balance_transfer',
                'success' => 0,
                'exception' => $failed_message,
                'meta' => [
                    'params' => [
                        'member_from_id' => $member_from->id,
                        'member_to_id' => $member_to->id,
                        'nominal' => $nominal,
                        'fee' => $fee
                    ]
                ],
                'failed_at' => date('Y-m-d H:i:s')
            ]);

            if ($invoice instanceof \App\Models\Invoice) {
                $invoice->status = 0;
                $result = $response->json();
                if (!empty($errorMessage = $result['message'] ?? null)) {
                    $invoice->notes = $errorMessage;
                } else {
                    $invoice->notes = 'Sistem gagal dalam memotong saldo.';
                }
                $nr = Invoice::where(['status' => 0, 'shop_id' => $invoice->shop_id])->max('nr');
                $invoice->nr = (int) $nr + 1;
                $invoice->serie = 'UNP';
                $invoice->save();
                // notify admin regarding this issue
                $admins = User::where('role', 'admin')->get();
                $admins->each(function ($admin) use ($invoice) {
                    MailQueue::create([
                        'mail_to' => $admin->email,
                        'mail_class' => '\App\Mail\AdminOrderFailed',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $invoice->id],
                        'priority' => 2
                    ]);
                });
                // resync balance
                balance_sync($member_to->id, true);
            }
        }

        return false;
    }
}
