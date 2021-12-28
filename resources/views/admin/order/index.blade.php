@extends('layouts.fe')

@section('meta')
<meta http-equiv="refresh" content="30">
@endsection
@section('loader', 'd-none')

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
                        <h4 class="mb-2">{{ __('Manage Order') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('admin.orders.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="Cari berdasar no order" 
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
                    <div class="card-header color-dark fw-500">{{ __('New Orders') }}</div>
                    <div class="card-body p-2">
                        @if (!empty($incomingOrders) && count($incomingOrders) > 0)
                            <div class="alert alert-info">Ada tambahan {{ count($incomingOrders) }} order yang baru masuk.</div>
                        @endif
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('No Order') }}</th>
                                        <th>{{ __('Chat Seller') }}</th>
                                        <th>{{ __('Order Type') }}</th>
                                        <th>{{ __('Shop Status') }}</th>
                                        <th>{{ __('Order Date') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($pendings as $pending)
                                        <tr @if (in_array($pending->id, $incomingOrders)) class="table-info" @endif>
                                            <td class="text-center">
                                                {{ $i + (($pendings->currentPage()-1) * $pendings->perPage()) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $pending->id) }}">{{ $pending->getInvoiceNumber() }}</a> - {{ $pending->buyer_name }}
                                            </td>
                                            <td>
                                                <a href="{{ wa_url($pending->seller_phone) }}" target="_blank">
                                                    <i class="bi bi-whatsapp size-22"></i>
                                                </a>
                                            </td>
                                            <td>
                                                @if ($pending->shipping_id > 0)
                                                    {{ __('Courier') }}
                                                @else
                                                    {{ __('Shop') }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ __('Pending') }}
                                            </td>
                                            <td>
                                                {{ $pending->created_at->format("d/m/Y H:i") }}
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $pendings->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- start of approved order -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('admin.orders.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="s" placeholder="Cari berdasar no order" 
                                aria-label="Search" value="{{ app('request')->input('s') }}" autocomplete="off">
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
                    <div class="card-header color-dark fw-500">{{ __('Approved Orders') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('No Order') }}</th>
                                        <th class="text-center">{{ __('Process') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($approveds as $approved)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($approveds->currentPage()-1) * $approveds->perPage()) }}
                                            </td>
                                            <td>
                                                @php
                                                    $courier = $approved->getCourier();
                                                @endphp
                                                @if ($courier !== null)
                                                <a href="{{ route('admin.orders.show', $approved->id) }}">{{ $approved->getInvoiceNumber() }}</a> - {{ $courier->name }}
                                                <br/>
                                                @else
                                                    @if ($approved->shipping_id > 0)
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <a href="{{ route('admin.orders.show', $approved->id) }}">{{ $approved->getInvoiceNumber() }}</a>
                                                        </span>
                                                        <select name="driver_id" class="form-control" onchange="return chooseDriver(this);" attr-id="{{ $approved->id }}">
                                                            <option value="">- {{ __('Choose Driver') }}</option>
                                                            @foreach ($drivers as $driver)
                                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @else
                                                    <a href="{{ route('admin.orders.show', $approved->id) }}">{{ $approved->getInvoiceNumber() }}</a> - {{ __('Self Pickup') }}
                                                    @endif
                                                @endif
                                                <b>{{ __('Order Date') }}</b> : {{ $approved->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ wa_url($approved->seller_phone) }}" title="{{ __('Chat seller') }}" target="_blank">
                                                            <i class="bi bi-whatsapp size-22"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('admin.orders.destroy', $approved->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a role="button" href="javascript:void(0);" onclick="return removeItem(this);" title="remove" class="text-danger">
                                                                <i class="bi bi-trash size-22"></i>
                                                            </a>
                                                        </form>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('admin.orders.update', $approved->id) }}" method="POST">
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
                                {{ $approveds->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of aproved order -->
            <!-- start of finished order -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('admin.orders.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="r" placeholder="Cari berdasar no order" 
                                aria-label="Search" value="{{ app('request')->input('r') }}" autocomplete="off">
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
                    <div class="card-header color-dark fw-500">{{ __('Finished Orders') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('No Order') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Order Date') }}</th>
                                        <th>{{ __('Order Complete') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($completes as $complete)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($completes->currentPage()-1) * $completes->perPage()) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $complete->id) }}">{{ $complete->getInvoiceNumber() }}</a> - {{ $complete->buyer_name }}
                                            </td>
                                            <td>
                                                {{ (!empty($complete->lastOrderProcess)) ? $complete->lastOrderProcess->getStatus() : __('Pending') }}
                                            </td>
                                            <td>
                                                {{ $complete->created_at->format("d/m/Y H:i") }}
                                            </td>
                                            <td>
                                                @php
                                                    $completedAt = $complete->getCompletedAt();
                                                @endphp
                                                @if (!empty($completedAt))
                                                    {{ $completedAt->format("d/m/Y H:i") }}
                                                @endif
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $completes->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of finished order -->
            <!-- start of canceled order -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('admin.orders.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="t" placeholder="Cari berdasar no order" 
                                aria-label="Search" value="{{ app('request')->input('t') }}" autocomplete="off">
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
                    <div class="card-header color-dark fw-500">{{ __('Canceled Orders') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('No Order') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Order Date') }}</th>
                                        <th>{{ __('Canceled Date') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($canceleds as $canceled)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($canceleds->currentPage()-1) * $canceleds->perPage()) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $canceled->id) }}">{{ $canceled->getInvoiceNumber() }}</a> - {{ $canceled->buyer_name }}
                                            </td>
                                            <td>
                                                {{ (!empty($canceled->lastOrderProcess)) ? $canceled->lastOrderProcess->getStatus() : __('Pending') }}
                                            </td>
                                            <td>
                                                {{ $canceled->created_at->format("d/m/Y H:i") }}
                                            </td>
                                            @php
                                            $processDate = $canceled->getProcessDate(-2) ?? null;
                                            @endphp
                                            <td>
                                            @if (!empty($processDate))
                                            {{ $processDate->format("d/m/Y H:i") }}
                                            @endif
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $canceleds->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of canceled order -->
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection

@section('js')
    <script type="text/javascript">
    function removeItem(dt)
    {
        if (confirm("{{ __('Are you sure you want to delete this?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    function markAsComplete(dt)
    {
        if (confirm("{{ __('Are you sure you want to mark this order as complete?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    function chooseDriver(dt, invoice_id)
    {
        $.ajax({
            method: "POST",
            url: "{{ route('admin.orders.setdriver') }}",
            data: { 
                driver_id: $(dt).val(),
                invoice_id: $(dt).attr('attr-id'),
                _token: $('meta[name="csrf-token"]').attr('content') 
            }
        }).done(function (msg) {
            var _toast = $('#toastprouctaddedtiny');
            if (msg.success) {
                _toast.removeClass('bg-warning').addClass('bg-success');
            } else {
                _toast.addClass('bg-warning').removeClass('bg-success');
            }
            _toast.find('#toast-msg').html(msg.message);
            _toast.toast('show');

            setTimeout(function () {
                window.location.href = "{{ route('admin.orders.index') }}";
            }, 2000);
        });
    }
    </script>
@endsection