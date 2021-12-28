<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\MailQueue;
use App\Models\Notification;
use App\Models\OrderProcess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $shop = $this->getShop();
        $pendings = Invoice::where('shop_id', $shop->id)
            ->where('status', 1)
            ->doesntHave('orderProcesses')
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->has('q')) {
            $nr = '';
            if (!empty($request->input('q'))) {
                $nr = (int) filter_var($request->input('q'), FILTER_SANITIZE_NUMBER_INT);
            }
            $pendings = Invoice::where('shop_id', $shop->id)
                ->where('status', 1)
                ->doesntHave('orderProcesses')
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $completes = Invoice::where('shop_id', $shop->id)
            ->where('status', 1)
            ->whereHas('orderProcesses', function ($q) {
                $q->where('status', 4);
            })
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->has('r')) {
            $nr = '';
            if (!empty($request->input('r'))) {
                $nr = (int) filter_var($request->input('r'), FILTER_SANITIZE_NUMBER_INT);
            }
            $completes = Invoice::where('shop_id', $shop->id)
            ->whereHas('orderProcesses', function ($q) {
                $q->where('status', 4);
            })
            ->where('nr', $nr)
            ->orderBy('id', 'desc')->paginate(10);
        }

        $approveds = Invoice::where('shop_id', $shop->id)
            ->whereHas('orderProcesses')
            ->whereDoesntHave('orderProcesses', function ($q) {
                $q->where('status', '>', 3);
                $q->orWhere('status', '<', 0);
            })
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->has('s')) {
            $nr = '';
            if (!empty($request->input('s'))) {
                $nr = (int) filter_var($request->input('s'), FILTER_SANITIZE_NUMBER_INT);
            }
            $approveds = Invoice::where('shop_id', $shop->id)
                ->whereHas('orderProcesses', function ($q) {
                    $q->where('status', 1);
                })
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $showFooter = true;
        
        return view('member.customer-order.index', compact('pendings', 'completes', 'showFooter', 'approveds'));
    }

    /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $customer_order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Invoice $customer_order)
    {
        // if pickup method
        if ($customer_order->shipping_id <= 0) {
            $customer_order->orderProcesses()->create([
                'status' => 4,
                'notes' => __('Marked as delivered by seller'),
                'user_id' => (!empty(Auth::user())) ? Auth::user()->id : 0
            ]);

            // update each order status
            $customer_order->orders()->update(['status' => 4]);
            if (($member = $customer_order->member) instanceof \App\Models\Member) {
                if (($memberUser = $member->user) instanceof \App\Models\User) {
                    $customer_order->orders()->each(function ($ord) use ($memberUser) {
                        // notify buyer to review product
                        Notification::create([
                            'user_id' => $memberUser->id,
                            'message' => 'Order Anda ' . $ord->invoice->getInvoiceNumber() . ' telah selesai. 
                                Mohon berikan ulasan Anda untuk produk ' . $ord->product->title,
                            'priority' => 1,
                            'meta' => [
                                'url' => route('member.review.create', $ord->product_id) .'?order=' . $ord->id,
                                'label' => __('Create Review Now')
                            ]
                        ]);
                    });
                }
            }
            
            return redirect()->back()->with('update', 'Order telah diterima oleh pembeli.');
        }

        // double check has approved status
        $approvedCheck = OrderProcess::where('invoice_id', $customer_order->id)->where('status', 1)->count();
        if ($approvedCheck <= 0) {
            $create_data = [
                'status' => 1,
                'notes' => __('Approved by seller'),
                'user_id' => (!empty(Auth::user())) ? Auth::user()->id : 0,
                'driver_id' => 0
            ];
    
            $create = $customer_order->orderProcesses()->create($create_data);
            if ($create instanceof \App\Models\OrderProcess) {
                $customer_order->orders()->update(['status' => 1]);
                // Send mail to admin to assign driver if need shipping
                $shipping_id = $customer_order->shipping_id ?? 0;
                if ($shipping_id > 0) {
                    $admins = User::where('role', 'admin')->get();
                    if ($admins->count() > 0) {
                        $admins->each(function ($admin) use ($customer_order) {
                            MailQueue::create([
                                'mail_to' => $admin->email,
                                'mail_class' => '\App\Mail\ShopApproveOrder',
                                'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $customer_order->id],
                                'priority' => 2
                            ]);
                            // Add notice to admin as well
                            Notification::create([
                                'user_id' => $admin->id,
                                'message' => 'Mohon persetujuan Admin dan assign Driver untuk order baru di toko '
                                    . $customer_order->seller_name
                                    . ' dengan nomor order ' . $customer_order->getInvoiceNumber(),
                                'priority' => 2,
                                'meta' => [
                                    'url' => route('admin.orders.show', $customer_order->id),
                                    'label' => __('More Detail')
                                ]
                            ]);
                        });
                    }
                }
            }
        }
 
        return redirect()->back()->with('update', 'Order telah berhasil disetujui.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $customer_order
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Invoice $customer_order)
    {
        $shop = $this->getShop();
        if ($shop->id != $customer_order->shop_id) {
            abort(401);
        }

        return view('member.customer-order.show', ['invoice' => $customer_order]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Invoice $invoice)
    {
        $shop = $this->getShop();
        if ($shop->id != $invoice->shop_id) {
            return redirect()->back()->with('warning', __('Unauthorized page'));
        }

        $message = __('Your data is successfully deleted');
        $invoice->delete();

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Get shop model
     *
     * @return \App\Models\Shop
     */
    private function getShop()
    {
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member === null) {
            abort(401);
        } else {
            if ($member->shop === null) {
                abort(401);
            }
        }

        return $member->shop;
    }

    /**
     * Cancel order the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, Invoice $invoice)
    {
        if ($invoice->orderProcesses->count() > 0) {
            return redirect()->back()->with('warning', __('Unable to cancel your order'));
        } else {
            $request->validate(
                [
                    'reason' => 'required',
                ],
                [
                    'reason.required' => 'Alasan pembatalan tidak boleh dikosongi.',
                ]
            );

            $update = $invoice->update([
                'base_refund' => (-1 * $invoice->base_income),
                'status' => -1,
                'notes' => $request->input('reason'),
                'refunded_at' => date('Y-m-d H:i:s')
            ]);
            if ($update) {
                $status_data = [
                    'status' => -2,
                    'notes' => $request->input('reason'),
                    'user_id' => (!empty(Auth::user())) ? Auth::user()->id : 0,
                    'driver_id' => 0
                ];

                $invoice->orderProcesses()->create($status_data);

                $member = $invoice->member;
                if ($member instanceof \App\Models\Member) {
                    // send the buyer mail notification
                    MailQueue::create([
                        'mail_to' => $member->email,
                        'mail_class' => '\App\Mail\OrderCanceled',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $invoice->id],
                        'priority' => 1
                    ]);

                    $memberUser = $member->user;
                    if ($memberUser instanceof \App\Models\User) {
                        // Add notice to buyer as well
                        Notification::create([
                            'user_id' => $memberUser->id,
                            'message' => 'Order Anda di toko ' . $invoice->seller_name
                                . ' dengan nomor order ' . $invoice->getInvoiceNumber()
                                . ' telah dibatalkan karena ' . $invoice->notes,
                            'priority' => 2,
                            'meta' => [
                                'url' => route('member.invoice.show', $invoice->id),
                                'label' => __('More Detail')
                            ]
                        ]);
                    }
                }

                if ($invoice->shop instanceof \App\Models\Shop) {
                    $shopMember = $invoice->shop->member;
                    if ($shopMember instanceof \App\Models\Member) {
                        // send the shop mail notification
                        MailQueue::create([
                            'mail_to' => $shopMember->email,
                            'mail_class' => '\App\Mail\ShopOrderCanceled',
                            'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $invoice->id],
                            'priority' => 1
                        ]);

                        $shopUser = $shopMember->user;
                        if ($shopUser instanceof \App\Models\User) {
                            // Add notice to seller as well
                            Notification::create([
                                'user_id' => $shopUser->id,
                                'message' => 'Order ' . $invoice->getInvoiceNumber()
                                    . ' di toko Anda ' . $invoice->seller_name
                                    . ' telah dibatalkan karena ' . $invoice->notes,
                                'priority' => 2,
                                'meta' => [
                                    'url' => route('member.customerorder.show', $invoice->id),
                                    'label' => __('More Detail')
                                ]
                            ]);
                        }
                    }
                }

                // send all admin mail notification
                $admins = User::where('role', 'admin')->get();
                $admins->each(function ($admin) use ($invoice) {
                    MailQueue::create([
                        'mail_to' => $admin->email,
                        'mail_class' => '\App\Mail\AdminOrderCanceled',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $invoice->id],
                        'priority' => 2
                    ]);
                    // Add notice to admin as well
                    Notification::create([
                        'user_id' => $admin->id,
                        'message' => 'Order di toko ' . $invoice->seller_name
                            . ' dengan nomor order ' . $invoice->getInvoiceNumber()
                            . ' telah dibatalkan karena ' . $invoice->notes,
                        'priority' => 2,
                        'meta' => [
                            'url' => route('admin.orders.show', $invoice->id),
                            'label' => __('More Detail')
                        ]
                    ]);
                });
            }
        }

        return redirect()->back()->with('update', __('Order has been successfully canceled.'));
    }
}
