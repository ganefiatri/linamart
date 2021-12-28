@component('mail::message')
# Order {{ $invoice->getInvoiceNumber() }}

@if (!empty($invoice->shop) && !empty($invoice->shop->member))
Hi {{ $invoice->shop->member->name }},<br/>
@else
Hi Admin Toko,<br/>
@endif
Ada order baru dari {{ $invoice->buyer_name }} untuk toko {{ $invoice->seller_name }}.

<br/>
<b>Data Order :</b> <br/><br/>

@if ($invoice->orders)
<p>
Nama Pembeli : {{ $invoice->buyer_name }}<br/>
No Telp : {{ $invoice->buyer_phone }}<br/>
Alamat : {{ $invoice->buyer_address }}, {{ $invoice->buyer_city }} {{ $invoice->buyer_postal_code }}<br/>
Cara Pengiriman : <b>{{ ($invoice->shipping_id > 0) ? 'Antar Via Kurir' : 'Ambil Di Toko' }}</b>
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
Klik tombol berikut untuk memproses order :<br/>

@component('mail::button', ['url' => route('member.customerorder.show', $invoice->id)])
Proses Order Sekarang
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
