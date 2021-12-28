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
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <a href="{{ route('driver.assignments.index') }}" title="Lihat daftar tugas pengiriman">
                <div class="card shadow-sm product mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <div class="avatar avatar-50 rounded bg-danger text-white">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                            </div>
                            <div class="col ps-0 align-self-center">
                                <span class="small text-opac mb-0">{{ __('Total Delivery') }}</span>
                                <p class="mb-1">{{ $stats['total_delivery'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <a href="{{ route('driver.assignments.index') }}" title="Lihat daftar diterima pelanggan">
                <div class="card shadow-sm product mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <div class="avatar avatar-50 rounded bg-success text-white">
                                    <i class="bi bi-person-check"></i>
                                </div>
                            </div>
                            <div class="col ps-0 align-self-center">
                                <span class="small text-opac mb-0">{{ __('Delivered To Customer') }}</span>
                                <p class="mb-1">{{ $stats['total_delivered'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <a href="{{ route('driver.notification') }}" title="Lihat pemberitahuan">
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
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('driver.assignments.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="{{ __('Search by invoice number') }}" 
                                aria-label="Search" value="{{ app('request')->input('q') }}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block">
                                    <i class="nav-icon bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('New Assignments') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('No Order') }}</th>
                                        <th>{{ __('Chat Seller') }}</th>
                                        <th>{{ __('Chat Buyer') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($invoices->currentPage()-1) * $invoices->perPage()) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('driver.assignments.show', $invoice->id) }}">{{ $invoice->getInvoiceNumber() }}</a> - {{ $invoice->buyer_name }}
                                            </td>
                                            <td>
                                                <a href="{{ wa_url($invoice->seller_phone) }}" title="{{ __('Chat Seller') }}" target="_blank">
                                                    <i class="bi bi-whatsapp size-22"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ wa_url($invoice->buyer_phone) }}" title="{{ __('Chat Buyer') }}" target="_blank">
                                                    <i class="bi bi-whatsapp size-22"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('driver.assignments.update', $invoice->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <a role="button" href="javascript:void(0);" onclick="return markAsComplete(this);" title="{{ __('Mark as complete') }}" class="text-success">
                                                                <i class="bi bi-check-square size-22"></i>
                                                            </a>
                                                        </form>
                                                    </li>
                                                 </ul>
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $invoices->appends(request()->input())->links() }}
                            </div>
                        </div>
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
    function markAsComplete(dt)
    {
        if (confirm("{{ __('Are you sure this order already delivered?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection