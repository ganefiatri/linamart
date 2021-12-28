<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $invoice = $order->invoice;
        if (($invoice instanceof \App\Models\Invoice) && ($invoice->status == 1)) {
            $product = $order->product;
            if ($product instanceof \App\Models\Product) {
                $stock = $product->stock - $order->quantity;
                $product->update(['stock' => $stock]);
            }
        }
    }
}
