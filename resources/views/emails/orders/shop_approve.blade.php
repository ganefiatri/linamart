@component('mail::message')
# Order {{ $invoice->getInvoiceNumber() }}

Hi Admin,<br/>
Ada order baru dari {{ $invoice->buyer_name }} untuk toko {{ $invoice->seller_name }}.<br/>
Mohon bantuannya untuk Assign Driver agar barang dapat segera dikirim.

<br/>
<b>Data Order :</b> <br/><br/>

@if ($invoice->orders)
<p>
    Nama Pembeli : {{ $invoice->buyer_name }}<br/>
    No Telp : {{ $invoice->buyer_phone }}<br/>
    Alamat : {{ $invoice->buyer_address }}, {{ $invoice->buyer_city }} {{ $invoice->buyer_postal_code }}<br/>
    Cara Pengiriman : <b>{{ ($invoice->shipping_id > 0) ? 'Antar Via Kurir' : 'Ambil Di Toko' }}</b>
</p>
<br/>
<p>
    Dikirim dari toko : <br/>
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
Klik tombol berikut untuk memproses order :<br/>

@component('mail::button', ['url' => route('admin.orders.show', $invoice->id)])
Proses Order Sekarang
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
