<html>
<head>
    <title>Invoice</title>
    <style>

        #tabel
        {
            font-size:5px;
            border-collapse:collapse;
        }
        #tabel  td
        {
            padding-left:0px;
            border: 1px solid black;
        }
    </style>
</head>
<body style='font-family:tahoma; font-size:8pt;'>

<center><table style=' font-size:10pt; font-family:calibri; border-collapse: collapse;' border = '0'>
        <td width='10%' align='CENTER'><span style='color:black;'>
<b>{{$invoice->seller_name}}</b><br>
            {{$invoice->seller_address}}</span></br>


        <span style='font-size:8pt'>No. : {{ $invoice->seller_phone }}, {{!! date("d M Y H:i", strtotime($invoice->created_at)) !!}}</span></br>
        </td>
    </table>
    <style>
        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 1px;
        }
    </style>
    <table cellspacing='0' cellpadding='0' style='margin-left: -20px; font-size:7pt; font-family:calibri;  border-collapse: collapse;' border='0'>

        <tr align='center'>
            <td width='10%'>Item</td>
            <td width='13%'>Price</td>
            <td width='4%'>Qty</td>
            <td width='7%'>Unit</td>
            <td width='13%'>Total</td><tr>
            <td colspan='5'><hr></td></tr>
        </tr>
        @if ($invoice->orders)
            @php
                $subtotal = 0;
            @endphp
        <tr>@foreach ($invoice->orders as $order)

            <td style='vertical-align:top'>{{$order->title}}</td>
                @php
                    $price = $order->price - $order->discount;
                    $subtotal += $price * $order->quantity;
                @endphp

            <td style='vertical-align:top; text-align:right; padding-right:10px'>{{$order->price}}</td>
            <td style='vertical-align:top; text-align:right; padding-right:10px'>{{$order->quantity}}</td>
            <td style='vertical-align:top; text-align:right; padding-right:10px'>{{$order->unit}}</td>
            @endforeach
            <td style='text-align:right; vertical-align:top'>{{ to_money_format($subtotal, '') }}</td>

        </tr>
        @endif
        <tr>
            <td colspan='5'><hr></td>
        </tr>
        <tr>
            <td colspan = '4'><div style='text-align:right'>Shipping Fee </div></td><td style='text-align:right; font-size:7pt;'>{{to_money_format($invoice->shipping_fee, '')}}</td>
        </tr>
        <tr>
            <td colspan = '4'><div style='text-align:right'>Disc</div></td><td style='text-align:right; font-size:7pt;'>{{$order->discount}}</td>
        </tr>
        <tr>
            <td colspan = '4'><div style='text-align:right; color:black'>Total : </div></td><td style='text-align:right; font-size:7pt; color:black'>{{ to_money_format($subtotal + $invoice->shipping_fee, '') }}</td>
        </tr>

    </table>
    <table style='width:10; font-size:10pt;' cellspacing='2'><tr></br><td align='center'>****** TERIMAKASIH ******</br></td></tr></table></center></body>
</html>
