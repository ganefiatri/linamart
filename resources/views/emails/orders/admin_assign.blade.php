@component('mail::message')
# Order {{ $invoice->getInvoiceNumber() }}

Hi {{ $driver->name }},<br/>
Mohon kirimkan barang dari di toko {{ $invoice->seller_name }} ke alamat pembeli dengan data berikut :.<br/>

<br/>
<b>Data Pembeli :</b> <br/><br/>

@if ($invoice->orders)
<p>
    Nama Pembeli : {{ $invoice->buyer_name }}<br/>
    No Telp : {{ $invoice->buyer_phone }}<br/>
    Alamat : {{ $invoice->buyer_address }}, {{ $invoice->buyer_city }} {{ $invoice->buyer_postal_code }}
</p>
<br/>
<b>Data Toko :</b> <br/><br/>
<p>
    Nama Toko : {{ $invoice->seller_name }}<br/>
    No Telp : {{ $invoice->seller_phone }}<br/>
    Alamat : {{ $invoice->seller_address }}, {{ $invoice->seller_city }}<br/>
</p>
@php
    $subtotal = 0;
@endphp
@component('mail::table')
| Nama Produk   | Jumlah   | Harga    |
| ------------- |:--------:| --------:|
@foreach ($invoice->orders as $order)
@php
    $price = $order->price - $order->discount;
    $subtotal += $price * $order->quantity;
@endphp
| {{ $order->title }} | {{ $order->quantity }} | {{ to_money_format($price, '') }} |
@endforeach
|  | {{ __('Sub Total') }} | {{ to_money_format($subtotal, '') }} |
@if ($invoice->shipping_fee > 0)
|  | {{ __('Shipping Fee') }} | {{ to_money_format($invoice->shipping_fee, '') }} |
@endif
|  | {{ __('Total') }} | {{ to_money_format($subtotal + $invoice->shipping_fee, '') }} |
@endcomponent                       
@endif
<br/>
Terimakasih.<br/><br/>

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
