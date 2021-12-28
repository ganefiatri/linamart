<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "creating" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function creating(Invoice $invoice)
    {
        $nr = Invoice::where(['status' => $invoice->status, 'shop_id' => $invoice->shop_id])->max('nr');
        $invoice->serie = ($invoice->status > -1) ? config('global.invoice_serie') : config('global.invoice_cn_series');
        $invoice->nr = (int) $nr + 1;
        $invoice->hash = md5($invoice->getInvoiceNumber());
    }

    /**
     * Handle the Invoice "created" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        if ($invoice->status == 1) {
            $invoice->update(['paid_at' => date('Y-m-d H:i:s')]);
            balance_reduce(floatval($invoice->base_income), $invoice->id, $invoice->member_id);
            $transfered_balance = floatval($invoice->base_income - $invoice->shipping_fee);
            if ($transfered_balance > 0 && $invoice->shop instanceof \App\Models\Shop) {
                if (($shop_member_id = $invoice->shop->member_id) > 0) {
                    balance_add($transfered_balance, $invoice->id, $shop_member_id);
                    balance_transfer(
                        $invoice->member_id,
                        $shop_member_id,
                        floatval($invoice->base_income),
                        $invoice->shipping_fee ?? 0.0,
                        $invoice
                    );
                }
            }
        } elseif ($invoice->status == 0) {
            $dueDays = strtotime('+' . config('global.invoice_due_days') . ' days');
            if ($dueDays === false) {
                $dueDays = strtotime('+5 days');
            }
            $invoice->update([
                'due_at' => date('Y-m-d H:i:s', $dueDays),
                'reminded_at' => date('Y-m-d H:i:s', strtotime('+3 days'))
            ]);
        } else {
            $invoice->update(['refunded_at' => date('Y-m-d H:i:s')]);
        }
    }

    /**
     * Handle the Invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        $invoice->orders()->each(function ($order) {
            $order->delete();
        });

        $invoice->orderProcesses()->each(function ($orderProcess) {
            $orderProcess->delete();
        });
    }

    /**
     * Handle the Invoice "restored" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        //
    }
}
