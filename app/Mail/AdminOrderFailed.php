<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminOrderFailed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invoice object
     *
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invoiceId = $this->invoice->getInvoiceNumber();
        return $this->subject('Order '. $invoiceId .' Gagal')
            ->markdown('emails.orders.admin_failed');
    }
}
