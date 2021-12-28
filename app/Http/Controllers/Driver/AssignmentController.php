<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\MailQueue;
use App\Models\Notification;
use App\Models\OrderProcess;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $driver = (!empty(Auth::user())) ? Auth::user()->driver : null;
        if ($driver === null) {
            abort(401);
        }

        $invoices = Invoice::whereHas('orderProcesses', function ($q) use ($driver) {
            $q->where('status', 2);
            $q->where('driver_id', $driver->id);
        })
            ->whereDoesntHave('orderProcesses', function ($q) {
                $q->where('status', '>', 2);
                $q->orWhere('status', '<', 0);
            })
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->has('q')) {
            $nr = '';
            if (!empty($request->input('q'))) {
                $nr = (int) filter_var($request->input('q'), FILTER_SANITIZE_NUMBER_INT);
            }
            $invoices = Invoice::whereHas('orderProcesses', function ($q) use ($driver) {
                $q->where('status', 2);
                $q->where('driver_id', $driver->id);
            })
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $delivereds = Invoice::whereHas('orderProcesses', function ($q) use ($driver) {
            $q->where('status', 3);
            $q->where('driver_id', $driver->id);
        })
            ->orderBy('id', 'desc')->paginate(10);

        if ($request->has('r')) {
            $nr = '';
            if (!empty($request->input('r'))) {
                $nr = (int) filter_var($request->input('r'), FILTER_SANITIZE_NUMBER_INT);
            }
            $invoices = Invoice::whereHas('orderProcesses', function ($q) use ($driver) {
                $q->where('status', 3);
                $q->where('driver_id', $driver->id);
            })
                ->where('nr', $nr)
                ->orderBy('id', 'desc')->paginate(10);
        }

        $showFooter = true;

        return view('driver.assignment.index', compact('invoices', 'delivereds', 'showFooter'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $assignment
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Invoice $assignment)
    {
        $shippings = Shipping::OrderBy('distance_from', 'asc')->get();

        return view('driver.assignment.show', compact('assignment', 'shippings'));
    }

    /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $assignment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Invoice $assignment)
    {
        $driver = (!empty(Auth::user())) ? Auth::user()->driver : null;
        if ($driver === null) {
            return redirect()->back()->with('warning', __('You need to login as driver'));
        }

        if (($lastProcess = $assignment->lastOrderProcess) instanceof \App\Models\OrderProcess) {
            if ($lastProcess->status < 2) {
                return redirect()->back()->with('warning', __('Unable to update this order'));
            }
        } else {
            return redirect()->back()->with('warning', __('Unable to update this order'));
        }

        // double check has completed status
        $deliveredCheck = OrderProcess::where('invoice_id', $assignment->id)->where('status', 3)->count();
        if ($deliveredCheck <= 0) {
            $create_data = [
                'status' => 3,
                'notes' => __('Delivered to buyer by courier'),
                'user_id' => (!empty(Auth::user())) ? Auth::user()->id : 0,
                'driver_id' => $driver->id
            ];

            $create = $assignment->orderProcesses()->create($create_data);
            if ($create instanceof \App\Models\OrderProcess) {
                $assignment->orders()->update(['status' => 3]);
                // send the admin mail notification
                $admins = User::where('role', 'admin')->get();
                if ($admins->count() > 0) {
                    $admins->each(function ($admin) use ($assignment) {
                        MailQueue::create([
                            'mail_to' => $admin->email,
                            'mail_class' => '\App\Mail\DriverDeliveredOrder',
                            'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $assignment->id],
                            'priority' => 1
                        ]);
                        // Add notice to admin as well
                        Notification::create([
                            'user_id' => $admin->id,
                            'message' => 'Order ' . $assignment->getInvoiceNumber()
                                . ' saat ini sudah diterima oleh pembeli',
                            'priority' => 1,
                            'meta' => [
                                'url' => route('admin.orders.show', $assignment->id),
                                'label' => __('Order Detail')
                            ]
                        ]);
                    });
                }
            }
        }

        return redirect()->back()->with('update', 'Order telah berhasil diterima pembeli.');
    }
}
