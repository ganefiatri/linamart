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
                        <h4 class="mb-2">{{ __('Dashboard') }}</h4>
                    </div>
                </div>

                <div class="row">
                    @if ($hasShop)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('products.index') }}" title="Lihat daftar produk">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-danger text-white">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Products') }}</span>
                                        <p class="mb-1">{{ number_format($products->total(), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    @php
                        $tot_sales = $stats['total_sales'] ?? 0;
                    @endphp
                    @if ($tot_sales > 0)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('member.customerorder.index') }}" title="Lihat daftar order">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-success text-white">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Sales') }}</span>
                                        <p class="mb-1">{{ number_format($invoices->total(), 0, ',', '.') }} orders ({{ to_money_format($stats['total_sales'] ?? 0) }})</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    @endif
                    @endif
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('member.invoice.index') }}" title="Lihat daftar pesanan saya">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-info text-white">
                                            <i class="bi bi-cart2"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Order') }}</span>
                                        <p class="mb-1">{{ number_format($orders->total(), 0, ',', '.') }} orders ({{ to_money_format($stats['total_orders'] ?? 0) }})</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('member.notification') }}" title="Lihat pemberitahuan">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-warning text-white">
                                            <i class="bi bi-bell"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Notification') }}</span>
                                        <p class="mb-1">
                                            @php
                                                $notif = notif_counter();
                                            @endphp
                                            @if ($notif > 0)
                                                {{ $notif . ' ' . __('New Notification') }}
                                            @else
                                                {{ __('No new notification found') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-success text-white">
                                            <i class="bi bi-cash"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Balance') }}</span>
                                        <form method="POST" id="reload-balance-form" action="{{ route('member.reloadbalance') }}">
                                            @csrf
                                            <p class="mb-1">
                                                {{ balance_info(true) }}
                                                <button type="submit" class="btn btn-outline-light text-black-50 py-0" title="Refresh" 
                                                    onclick="if (confirm('Segarkan informasi saldo?')) {$('#reload-balance-form').submit();} else {return false;}">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($hasShop && ($products->count() > 0))
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $shop = $member->shop;
                                        $shop_status = $shop->status ?? 0;
                                    @endphp
                                    <div class="col-auto pr-0 align-self-center text-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                id="settingscheck1" attr-id="{{ $shop->id }}" @if($shop_status > 0) checked @endif>
                                            <label class="form-check-label" for="settingscheck1"></label>
                                        </div>
                                    </div>
                                    <div class="col ps-0">
                                        <h6 class="mb-1">{{ __('Shop Status') }} (
                                            @if($shop_status > 0)
                                            <span class="text-success">{{ __('Open') }}</span>
                                            @else 
                                            <span class="text-danger">{{ __('Close') }}</span>
                                            @endif
                                        )
                                        </h6>
                                        <p class="text-opac small d-block mb-2">
                                            Klik tombol switch di sebelah kiri untuk mengubah status toko Anda
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="row">
                    @if ($hasShop)
                    <div class="col-md-6 col-sm-6 col-12">
                        <!-- Product list -->
                        <div class="row mb-2 mt-4">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4 class="mb-2">{{ __('Your Product') }}</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <form action="{{ route('member.dashboard') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="qp"
                                            placeholder="{{ __('Search product') }}" aria-label="Input group example" 
                                            aria-describedby="btnGroupAddon2" value="{{ app('request')->input('qp') }}" autocomplete="off">
                                        <div class="input-group-text" id="btnGroupAddon2">
                                            <a href="{{ route('products.create') }}">
                                                <i class="bi bi-plus size-22"></i>
                                            </a>
                                        </div>
                                      </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Product List') }}</div>
                            <div class="card-body p-2">
                                <div class="userDatatable global-shadow border-0 bg-white w-100">
                                    <div class="table-responsive">
                                        <table class="table mb-2 table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>{{ __('Product Name') }}</th>
                                                <th>{{ __('Stock') }}</th>
                                                <th class="text-center" width="100px">{{ __('Detail') }}</th>
                                            </tr>
                                            </thead>
                                            @php
                                                $i = 1;
                                            @endphp
                                            <tbody>
                                            @foreach ($products as $product)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $i + (($products->currentPage()-1) * $products->perPage()) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('products.show', $product->id) }}" class="view">
                                                            {{ $product->title }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $product->stock }}
                                                    </td>
                                                    <td class="text-center">
                                                        <ul class="list-unstyled list-inline mb-0">
                                                            <li class="list-inline-item">
                                                                <a href="{{ route('products.edit', $product->id) }}" title="edit">
                                                                    <i class="bi bi-arrow-right size-22"></i>
                                                                </a>
                                                            </li>
                                                         </ul>
                                                    </td>
                                                </tr>
                                                @php ++$i @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $products->appends(request()->input())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End product list -->
                    </div>

                    <div class="col-md-6 col-sm-6 col-12">
                        <!-- Sales list -->
                        <div class="row mb-2 mt-4">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4 class="mb-2">{{ __('Your Sales') }}</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <form action="{{ route('member.dashboard') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="qs"
                                            placeholder="{{ __('Search on sales') }}" aria-label="Input group example" 
                                            aria-describedby="btnGroupAddon2" value="{{ app('request')->input('qs') }}" autocomplete="off">
                                      </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Your Sales') }}</div>
                            <div class="card-body p-2">
                                <div class="userDatatable global-shadow border-0 bg-white w-100">
                                    <div class="table-responsive">
                                        <table class="table mb-2 table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>{{ __('No. Order') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                            </thead>
                                            @php
                                                $j = 1;
                                            @endphp
                                            <tbody>
                                            @foreach ($invoices as $invoice)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $j + (($invoices->currentPage()-1) * $invoices->perPage()) }}
                                                    </td>
                                                    <td>
                                                        {{ $invoice->getInvoiceNumber() }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ to_money_format($invoice->base_income, '') }}
                                                    </td>
                                                    <td>
                                                        {{ $invoice->getStatus() }}
                                                    </td>
                                                </tr>
                                                @php ++$j @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $invoices->appends(request()->input())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End sale list -->
                    </div>
                    @endif

                    <div class="col-md-6 col-sm-6 col-12">
                        <!-- Purchase list -->
                        <div class="row mb-2 mt-4">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <h4 class="mb-2">{{ __('Your Purchase') }}</h4>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <form action="{{ route('member.dashboard') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="qo"
                                            placeholder="{{ __('Search by invoice number') }}" aria-label="Input group example" 
                                            aria-describedby="btnGroupAddon2" value="{{ app('request')->input('qo') }}">
                                      </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Your Purchase') }}</div>
                            <div class="card-body p-2">
                                <div class="userDatatable global-shadow border-0 bg-white w-100">
                                    <div class="table-responsive">
                                        <table class="table mb-2 table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>{{ __('No. Order') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                            </thead>
                                            @php
                                                $j = 1;
                                            @endphp
                                            <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $j + (($orders->currentPage()-1) * $orders->perPage()) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('member.invoice.show', $order->id) }}">
                                                        {{ $order->getInvoiceNumber() }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">
                                                        {{ to_money_format($order->base_income, '') }}
                                                    </td>
                                                    <td>
                                                        {{ $order->getStatus() }}
                                                    </td>
                                                </tr>
                                                @php ++$j @endphp
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $orders->appends(request()->input())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End purchase list -->
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->

@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $("#settingscheck1").click(function (){
        if (confirm('{{ __("Are you sure to switch your shop status?") }}')) {
            var shop_id = $(this).attr('attr-id');
            $.ajax({
                url: "{{ route('member.shops.status') }}",
                dataType: "json",
                method: "patch",
                data: {
                    id: shop_id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function( data ) {
                    if (data.status == 'success') {
                        window.location.reload(true);
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            return false;
        }
    });
});
</script>
@endsection
