@component('mail::message')
# Halo {{ $invoice->member->name }}

Order Anda di {{ $invoice->shop->name }} dengan nomor Order <b>{{ $invoice->getInvoiceNumber() }}</b> telah dibatalkan.
<br/><br/>
<b>Alasan pembatalan :</b> <br/>
<p>{{ $invoice->notes }}</p>

@component('mail::button', ['url' => route('member.invoice.show', $invoice->id)])
Lihat Detail Order
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
