@component('mail::message')
# Halo {{ $invoice->shop->member->name }}

Order <b>{{ $invoice->getInvoiceNumber() }}</b> di toko Anda ({{ $invoice->shop->name }}) telah dibatalkan.
<br/><br/>
<b>Alasan pembatalan :</b> <br/>
<p>{{ $invoice->notes }}</p>

@component('mail::button', ['url' => route('member.customerorder.show', $invoice->id)])
Lihat Detail Order
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
