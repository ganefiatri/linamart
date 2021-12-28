@component('mail::message')
# Delivery Order {{ $invoice->getInvoiceNumber() }}

Hi Admin,<br/>
Order dari {{ $invoice->buyer_name }} di toko {{ $invoice->seller_name }} 
yang dikirimkan oleh {{ $driver->name }} telah diterima oleh pembeli.
<br/><br/>

Klik tombol berikut untuk menyelesaikan proses order :<br/>
@component('mail::button', ['url' => route('admin.orders.show', $invoice->id)])
Selesaikan Order
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
