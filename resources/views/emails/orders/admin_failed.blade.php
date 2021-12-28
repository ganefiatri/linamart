@component('mail::message')
# Halo Admin

Order <b>{{ $invoice->getInvoiceNumber() }}</b> di toko {{ $invoice->shop->name }} gagal.
<br/><br/>
<b>Alasan Gagal :</b> <br/>
<p>{{ $invoice->notes }}</p>

@component('mail::button', ['url' => route('admin.orders.show', $invoice->id)])
Lihat Detail Order
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
