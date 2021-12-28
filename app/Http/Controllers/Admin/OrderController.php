<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Lookup;
use App\Models\MailQueue;
use App\Models\Notification;
use App\Models\OrderProcess;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $pendings = Invoice::where('status', 1)
            ->doesntHave('orderProcesses')
            ->orderBy('id', 'desc')->paginate(10);
        $latestPending = $pendings->pluck('id')->toArray();
        $incomingOrders = [];
        if (Session::has('latestPending')) {
            $incomingOrders = array_diff($latestPending, Session::get('latestPending'));
        }

        session(['latestPending' => $latestPending]);

        if ($request->has('q')) {
            $nr = '';
            if (!empty($request->input('q'))) {
                $nr = (int) filter_var($request->input('q'), FILTER_SANITIZE_NUMBER_INT);
            }
            $pendings = Invoice::where('status', 1)
                ->doesntHave('orderProcesses')
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $approveds = Invoice::whereHas('orderProcesses')
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
            $approveds = Invoice::whereHas('orderProcesses', function ($q) {
                $q->where('status', 1);
            })
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $completes = Cache::remember("completedOrders", 300, function () {
            return Invoice::whereHas('orderProcesses', function ($q) {
                $q->where('status', 4);
            })
                ->orderBy('id', 'desc')->paginate(10);
        });

        if ($request->has('r')) {
            $nr = '';
            if (!empty($request->input('r'))) {
                $nr = (int) filter_var($request->input('r'), FILTER_SANITIZE_NUMBER_INT);
            }
            $completes = Invoice::whereHas('orderProcesses', function ($q) {
                $q->where('status', 4);
            })
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $canceleds = Cache::remember("canceledOrders", 400, function () {
            return Invoice::whereHas('orderProcesses', function ($q) {
                $q->where('status', -2);
            })
                ->orderBy('id', 'desc')->paginate(10);
        });

        if ($request->has('t')) {
            $nr = '';
            if (!empty($request->input('t'))) {
                $nr = (int) filter_var($request->input('t'), FILTER_SANITIZE_NUMBER_INT);
            }
            $canceleds = Invoice::whereHas('orderProcesses', function ($q) {
                $q->where('status', -2);
            })
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $drivers = Cache::remember("drivers", 600, function () {
            return Driver::where('status', 1)->orderBy('name', 'asc')->get();
        });
        $showFooter = true;

        return view(
            'admin.order.index',
            compact('pendings', 'approveds', 'completes', 'drivers', 'showFooter', 'canceleds', 'incomingOrders')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $order
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Invoice $order)
    {
        $shippings = Shipping::OrderBy('distance_from', 'asc')->get();
        $drivers = Driver::where('status', 1)->orderBy('name', 'asc')->get();
        $orderStatusList = Lookup::where('type', 'OrderStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        return view('admin.order.show', compact('order', 'shippings', 'drivers', 'orderStatusList'));
    }

    /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Invoice $order)
    {
        if (($lastProcess = $order->lastOrderProcess) instanceof \App\Models\OrderProcess) {
            if ($lastProcess->status < 2) {
                return redirect()->back()->with('warning', __('Unable to complete your order'));
            }
        } else {
            return redirect()->back()->with('warning', __('Unable to complete your order'));
        }

        // double check has completed status
        $completeCheck = OrderProcess::where('invoice_id', $order->id)->where('status', 4)->count();
        if ($completeCheck <= 0) {
            $create_data = [
                'status' => 4,
                'notes' => __('Completed by admin'),
                'user_id' => (!empty(Auth::user())) ? Auth::user()->id : 0,
                'driver_id' => 0
            ];

            $create = $order->orderProcesses()->create($create_data);
            if ($create instanceof \App\Models\OrderProcess) {
                $order->orders()->update(['status' => 4]);
                if (($member = $order->member) instanceof \App\Models\Member) {
                    if (($memberUser = $member->user) instanceof \App\Models\User) {
                        $order->orders()->each(function ($ord) use ($memberUser) {
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
            }
        }

        return redirect()->back()->with('update', 'Order telah berhasil disetujui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Invoice $invoice)
    {
        $message = __('Your data is successfully deleted');
        //$invoice->delete();

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Assign driver for courier
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function setDriver(Request $request)
    {
        $request->validate(
            [
                'driver_id' => 'required',
                'invoice_id' => 'required',
            ],
            [
                'driver_id.required' => 'Driver id tidak boleh dikosongi.',
                'invoice_id.required' => 'Invoice id tidak boleh dikosongi.',
            ]
        );

        $success = false;
        $message = __('Failed to set driver');
        if ($request->has('driver_id')) {
            $driver = Driver::findOrFail($request->input('driver_id'));
            if ($driver instanceof \App\Models\Driver) {
                $orderProcess = OrderProcess::create([
                    'invoice_id' => $request->input('invoice_id'),
                    'status' => 2,
                    'notes' => 'Assigned driver by admin',
                    'user_id' => (!empty(Auth::user())) ? Auth::user()->id : 0,
                    'driver_id' => $driver->id,
                ]);

                if ($orderProcess !== null) {
                    $success = true;
                    $message = __('Successfully set the driver');

                    // send the driver mail notification
                    MailQueue::create([
                        'mail_to' => $driver->email,
                        'mail_class' => '\App\Mail\AdminAssignDriver',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $request->input('invoice_id')],
                        'priority' => 1
                    ]);

                    if (($driver_user = $driver->user) instanceof \App\Models\User) {
                        if (($invoice = $orderProcess->invoice) instanceof \App\Models\Invoice) {
                            // Add notice to driver
                            Notification::create([
                                'user_id' => $driver_user->id,
                                'message' => 'Ada tugas pengantaran baru dari toko ' . $invoice->seller_name
                                    . ' dengan nomor order ' . $invoice->getInvoiceNumber()
                                    . ' ke ' . $invoice->buyer_address,
                                'priority' => 2,
                                'meta' => [
                                    'url' => route('driver.assignments.show', $invoice->id),
                                    'label' => __('More Detail')
                                ]
                            ]);
                            if ($invoice->member instanceof \App\Models\Member
                                && ($buyer_user = $invoice->member->user) instanceof \App\Models\User
                            ) {
                                // Add notice to buyer as well
                                Notification::create([
                                    'user_id' => $buyer_user->id,
                                    'message' => 'Order Anda di toko ' . $invoice->seller_name
                                        . ' dengan nomor order ' . $invoice->getInvoiceNumber()
                                        . ' akan dikirimkan ke alamat Anda oleh driver kami ' . $driver->name,
                                    'priority' => 1
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * Cancel order the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, Invoice $order)
    {
        if ($order->orderProcesses->count() > 0) {
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

            $update = $order->update([
                'base_refund' => (-1 * $order->base_income),
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

                $order->orderProcesses()->create($status_data);

                $member = $order->member;
                if ($member instanceof \App\Models\Member) {
                    // send the driver mail notification
                    MailQueue::create([
                        'mail_to' => $member->email,
                        'mail_class' => '\App\Mail\OrderCanceled',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $order->id],
                        'priority' => 1
                    ]);

                    $memberUser = $member->user;
                    if ($memberUser instanceof \App\Models\User) {
                        // Add notice to buyer as well
                        Notification::create([
                            'user_id' => $memberUser->id,
                            'message' => 'Order Anda di toko ' . $order->seller_name
                                . ' dengan nomor order ' . $order->getInvoiceNumber()
                                . ' telah dibatalkan karena ' . $order->notes,
                            'priority' => 2,
                            'meta' => [
                                'url' => route('member.invoice.show', $order->id),
                                'label' => __('More Detail')
                            ]
                        ]);
                    }
                }

                if ($order->shop instanceof \App\Models\Shop) {
                    $shopMember = $order->shop->member;
                    if ($shopMember instanceof \App\Models\Member) {
                        // send the shop mail notification
                        MailQueue::create([
                            'mail_to' => $shopMember->email,
                            'mail_class' => '\App\Mail\ShopOrderCanceled',
                            'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $order->id],
                            'priority' => 1
                        ]);

                        $shopUser = $shopMember->user;
                        if ($shopUser instanceof \App\Models\User) {
                            // Add notice to seller as well
                            Notification::create([
                                'user_id' => $shopUser->id,
                                'message' => 'Order ' . $order->getInvoiceNumber()
                                    . ' di toko Anda ' . $order->seller_name
                                    . ' telah dibatalkan karena ' . $order->notes,
                                'priority' => 2,
                                'meta' => [
                                    'url' => route('member.customerorder.show', $order->id),
                                    'label' => __('More Detail')
                                ]
                            ]);
                        }
                    }
                }

                // send all admin mail notification
                $admins = User::where('role', 'admin')->get();
                $admins->each(function ($admin) use ($order) {
                    MailQueue::create([
                        'mail_to' => $admin->email,
                        'mail_class' => '\App\Mail\AdminOrderCanceled',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $order->id],
                        'priority' => 2
                    ]);
                    // Add notice to admin as well
                    Notification::create([
                        'user_id' => $admin->id,
                        'message' => 'Order di toko ' . $order->seller_name
                            . ' dengan nomor order ' . $order->getInvoiceNumber()
                            . ' telah dibatalkan karena ' . $order->notes,
                        'priority' => 2,
                        'meta' => [
                            'url' => route('admin.orders.show', $order->id),
                            'label' => __('More Detail')
                        ]
                    ]);
                });
            }
        }

        return redirect()->back()->with('update', __('Order has been successfully canceled.'));
    }

    /**
     * Set notes to the driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDriverNotes(Request $request, Invoice $order)
    {
        $request->validate(
            [
                'driver_notes' => 'required|min:5',
            ],
            [
                'driver_notes.required' => 'Catatan driver tidak boleh dikosongi.',
            ]
        );

        if ($request->has('driver_notes')) {
            $meta = $order->meta ?? [];
            $meta['driver_notes'] = $request->input('driver_notes');
            $order->update(['meta' => $meta]);
        }

        return redirect()->back()->with('update', 'Catatan untuk driver telah berhasil ditambahkan.');
    }
}
