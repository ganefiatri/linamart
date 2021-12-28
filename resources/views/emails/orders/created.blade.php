@component('mail::message')
# Halo {{ $invoice->member->name }}

Terimakasih telah melakukan Order di {{ $invoice->shop->name }} dengan nomor Order <b>{{ $invoice->getInvoiceNumber() }}</b>.
<br/><br/>
Data Order Anda : <br/>

@if ($invoice->orders)
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
Order Anda akan segera kami proses. Mohon cek email Anda secara berkala.<br/><br/>
@if ($invoice->shipping_id > 0)
<b>Alamat Pengiriman :</b><br>
{{ $invoice->buyer_name }}<br/>
{{ $invoice->buyer_address }}, {{ $invoice->buyer_city }} {{ $invoice->buyer_postal_code }}<br/>
Telepon : {{ $invoice->buyer_phone }}
@endif

@component('mail::button', ['url' => route('member.invoice.show', $invoice->id)])
Lihat Detail Tagihan
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
