<?php

namespace App\Mail;

use App\Models\Driver;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAssignDriver extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invoice object
     *
     * @var Invoice
     */
    public $invoice;

    /**
     * Driver object
     *
     * @var Driver|null
     */
    public $driver;

    /**
     * Create a new message instance.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->driver = $invoice->getCourier();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invoiceId = $this->invoice->getInvoiceNumber();
        return $this->subject('Kirim Barang Untuk Order ' . $invoiceId)
            ->markdown('emails.orders.admin_assign');
    }
}
