<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \PDF;
use function YoastSEO_Vendor\GuzzleHttp\Promise\all;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member === null) {
            abort(401);
        }

        $items = Invoice::where('member_id', $member->id)->orderBy('id', 'desc')
            ->paginate(10);

        if ($request->has('q') || $request->has('status')) {
            $nr = '';
            if (!empty($request->input('q'))) {
                $nr = (int) filter_var($request->input('q'), FILTER_SANITIZE_NUMBER_INT);
            }
            $query = Invoice::query();
            $query->where('member_id', $member->id);
            $query->where('status', 1);
            if ($nr  > 0) {
                $query->where('nr', 'like', '%'. $nr .'%');
            }

            if (!empty($status = $request->input('status'))) {
                $query->whereHas('orderProcesses', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->whereDoesntHave('orderProcesses', function ($q) use ($status) {
                    $q->where('status', '>', $status);
                    $q->orWhere('status', '<', 0);
                });
            }

            $items = $query->orderBy('id', 'desc')->paginate(10);
        }

        $statusList = Lookup::where('type', 'InvoiceStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        $orderStatusList = Lookup::where('type', 'OrderStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        $showFooter = true;

        return view('member.invoice.index', compact('items', 'statusList', 'showFooter', 'orderStatusList'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Invoice $invoice)
    {
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member === null) {
            abort(401);
        }

        if ($member->id != $invoice->member_id) {
            abort(401);
        }

        $orderStatusList = Lookup::where('type', 'OrderStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return View('member.invoice.show', compact('invoice', 'orderStatusList'));
    }

    public function prints(Invoice $invoice){
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member === null) {
            abort(401);
        }

        if ($member->id != $invoice->member_id) {
            abort(401);
        }

        $orderStatusList = Lookup::where('type', 'OrderStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        $pdf = PDF::loadview('member.invoice.printpdf', compact('invoice', 'orderStatusList'))->setPaper('A8', 'portrait');;
        return $pdf->stream('invoice.pdf');
    }

}
