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
        <div class="row mb-2">
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                <a href="{{ route('member.customerorder.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-list-ul"></i> {{ __('List Order') }}
                </a>
                @php
                    $is_pending_order = ($invoice->orderProcesses->count() == 0);
                @endphp
                @if ($is_pending_order)
                    <button type="button" class="btn btn-danger text-white float-end" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        {{ __('Cancel Order') }}
                    </button>
                @endif
            </div>
            @if (empty($invoice->lastOrderProcess))
            <div class="col-lg-6 col-md-6 col-sm-12">
                <form action="{{ route('member.customerorder.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <a role="button" href="javascript:void(0);" onclick="return approveItem(this);" 
                        title="@if($invoice->shipping_id) {{ __('approve') }} @else {{ __('Mark as delivered') }}@endif" 
                        class="btn btn-outline-success">
                        @if ($invoice->shipping_id > 0)
                        <i class="bi bi-check-square size-22"></i> {{ __('Approve Order') }}
                        @else
                        <i class="bi bi-person-check-fill size-22"></i> {{ __('Mark as delivered') }}
                        @endif
                    </a>
                </form>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="card mb-3">
                    <div class="card-header color-dark fw-500">{{ __('Detail Order') }}</div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('No Order') }}</td>
                                    <td>{{ $invoice->getInvoiceNumber() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>
                                        {{ $invoice->getStatus() }}
                                        @if (!empty($invoice->lastOrderProcess))
                                            / {{ $invoice->lastOrderProcess->getStatus() }}
                                        @endif
                                    </td>
                                </tr>
                                @if ($invoice->status == -1)
                                <tr>
                                    <td><b>{{ __('Canceled Reason') }}</b></td>
                                    <td>
                                        @if (!empty($invoice->notes))
                                        <span class="text-danger">{{ $invoice->notes }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Refunded At') }}</td>
                                    <td>{!! $invoice->refunded_at !!}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Total') }}</td>
                                    <td>{{ to_money_format($invoice->base_income) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Shipping Method') }}</td>
                                    <td>{{ ($invoice->shipping_id > 0) ? __('Courier Delivery') : __('Pickup') }}</td>
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
                                    <td>{{ $invoice->seller_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Buyer Name') }}</td>
                                    <td>{{ $invoice->buyer_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Buyer Phone') }}</td>
                                    <td>{{ $invoice->buyer_phone }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Buyer Address') }}</td>
                                    <td>{{ $invoice->buyer_address }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('District City') }}</td>
                                    <td>{{ $invoice->buyer_city ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $invoice->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $invoice->updated_at !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @php
                        $notes = $invoice->meta['notes'] ?? null;
                    @endphp
                    @if (!empty($notes))
                    <div class="card-footer p-2">
                        <b>Catatan Pembeli :</b>
                        <div class="alert alert-info mt-2">{{ $notes }}</div>
                    </div>
                    @endif
                    {{--
                    <div class="card-footer p-2">
                        <a href="{{ wa_url($invoice->buyer_phone) }}" title="{{ __('Chat Buyer') }}" target="_blank" class="btn btn-outline-success m-1">
                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Buyer') }}
                        </a>
                        <a href="{{ phone_url($invoice->buyer_phone) }}" title="{{ __('Call Buyer') }}" target="_blank" class="btn btn-outline-secondary m-1">
                            <i class="bi bi-phone size-22"></i> {{ __('Call Buyer') }}
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
    </div>
</main>
<!-- Page ends-->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">{{ __('Cancel Order') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('member.customerorder.cancel', $invoice->id) }}">
            @csrf
            @method('PATCH')
        <div class="modal-body">
            <div class="alert alert-warning">
                {{ __('Are you sure you want to cancel this order?') }}
            </div>
            <div class="mb-3">
              <label class="col-form-label">{{ __('Reason') }}:</label>
              <textarea name="reason" class="form-control" placeholder="{{ __('Please type the reason why') }}" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">{{ __('Cancel Now') }}</button>
        </div>
        </form>
      </div>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript">
    function approveItem(dt)
    {
        if (confirm("{{ __('Are you sure you want to do this?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection