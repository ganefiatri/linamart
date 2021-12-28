@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <div class="main-container container">

        @include('layouts.partial._message')

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('View Order') }} #{{ $invoice->id }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <a href="{{ route('member.invoice.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-list-ul"></i> {{ __('List Order') }}
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <a href="#order-processes" class="btn btn-outline-secondary">
                    <i class="bi bi-list-ul"></i> {{ __('Order Processes') }}
                </a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-header color-dark fw-500">{{ __('Detail Invoice') }}</div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Invoice Number') }}</td>
                                    <td>{{ $invoice->getInvoiceNumber() }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>{{ __('Status') }}</td>
                                    <td>
                                        @if (!empty($invoice->lastOrderProcess))
                                            {{ $orderStatusList[$invoice->lastOrderProcess->status ?? 0] }}
                                        @else
                                            {{ __('Pending') }}
                                        @endif
                                    </td>
                                </tr>
                                @if ($invoice->notes)
                                <tr>
                                    <td>{{ __('Notes') }}</td>
                                    <td>{{ $invoice->notes }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Total') }}</td>
                                    <td>{{ to_money_format($invoice->base_income) }}</td>
                                </tr>
                                @php
                                    $courier = null;
                                @endphp
                                @if ($invoice->shipping_id > 0)
                                    @php
                                        $courier = $invoice->getCourier();
                                    @endphp
                                    @if ($courier !== null)
                                        <tr>
                                            <td>{{ __('Driver') }}</td>
                                            <td>
                                                <b>{{ $courier->name }}</b>
                                            </td>
                                        </tr>
                                    @endif
                                <tr>
                                    <td>{{ __('Shipping Fee') }}</td>
                                    <td>{{ to_money_format($invoice->shipping_fee) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Seller Name') }}</td>
                                    <td>
                                        <a href="{{ route('member.shops.show', $invoice->shop_id) }}" target="_newtab" title="{{ __('Show Shop Detail') }}">{{ $invoice->seller_name }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Seller Phone') }}</td>
                                    <td>{{ $invoice->seller_phone }}</td>
                                </tr>
                                <tr>
                                    <td>{{ (!is_using_district()) ? __('District City') : __('Seller City') }}</td>
                                    <td>{{ $invoice->seller_city }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Seller Address') }}</td>
                                    <td>{{ $invoice->seller_address }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! date("d M Y H:i", strtotime($invoice->created_at)) !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! date("d M Y H:i", strtotime($invoice->updated_at)) !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{--
                    <div class="card-footer p-2">
                        <a href="{{ wa_url($invoice->seller_phone) }}" title="{{ __('Chat Seller') }}" target="_blank" class="btn btn-outline-success m-1">
                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Seller') }}
                        </a>
                        <a href="{{ phone_url($invoice->seller_phone) }}" title="{{ __('Call Seller') }}" target="_blank" class="btn btn-outline-secondary m-1">
                            <i class="bi bi-phone size-22"></i> {{ __('Call Seller') }}
                        </a>
                        @if ($courier !== null)
                        <a href="{{ wa_url($courier->phone) }}" title="{{ __('Chat Driver') }}" target="_blank" class="btn btn-outline-success m-1">
                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Driver') }}
                        </a>
                        <a href="{{ phone_url($courier->phone) }}" title="{{ __('Call Driver') }}" target="_blank" class="btn btn-outline-secondary m-1">
                            <i class="bi bi-phone size-22"></i> {{ __('Call Driver') }}
                        </a>
                        @endif
                    </div>
                    --}}
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Item Order') }}</div>
                    <div class="card-body p-2">
                        @if ($invoice->orders)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                @php
                                    $subtotal = 0;
                                @endphp
                            @foreach ($invoice->orders as $order)
                            <tr>
                                <td>{{ $order->title }}</td>
                                @php
                                    $price = $order->price - $order->discount;
                                    $subtotal += $price * $order->quantity;
                                @endphp
                                <td class="text-end">{{ $order->quantity }} x {{ to_money_format($price, '') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="text-end">{{ __('Sub Total') }}</td>
                                <td class="text-end">{{ to_money_format($subtotal, '') }}</td>
                            </tr>
                            <tr>
                                <td class="text-end">{{ __('Shipping Fee') }}</td>
                                <td class="text-end">{{ to_money_format($invoice->shipping_fee, '') }}</td>
                            </tr>
                            <tr>
                                <td class="text-end text-bold"><b>{{ __('Total') }}</b></td>
                                <td class="text-end text-bold"><b>{{ to_money_format($subtotal + $invoice->shipping_fee, '') }}</b></td>
                            </tr>
                            </table>
                        </div>                        
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="order-processes">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-header color-dark fw-500">{{ __('Order Process') }}</div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Note') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ date("d M Y H:i", strtotime($invoice->created_at)) }}</td>
                                    <td>{{ __('Order Created') }}</td>
                                </tr>
                                @if ($invoice->orderProcesses()->count() > 0)
                                    @foreach ($invoice->orderProcesses as $process)
                                        <tr>
                                            <td>{{ date("d M Y H:i", strtotime($process->created_at)) }}</td>
                                            <td>{{ $process->getStatus() }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection