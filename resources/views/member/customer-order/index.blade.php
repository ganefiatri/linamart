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
                        <h4 class="mb-2">{{ __('Manage Order') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('member.customerorder.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="Cari berdasarkan no order" 
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
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('No Order') }}</th>
                                        <th>{{ __('Chat Buyer') }}</th>
                                        <th>{{ __('Order Type') }}</th>
                                        <th class="text-center" width="100px">{{ __('Process') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($pendings as $pending)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($pendings->currentPage()-1) * $pendings->perPage()) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('member.customerorder.show', $pending->id) }}">{{ $pending->getInvoiceNumber() }}</a> - {{ $pending->buyer_name }}
                                            </td>
                                            <td>
                                                <a href="{{ wa_url($pending->buyer_phone) }}" target="_blank">
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
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('member.customerorder.destroy', $pending->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a role="button" href="javascript:void(0);" onclick="return removeItem(this);" title="remove" class="text-danger">
                                                                <i class="bi bi-trash size-22"></i>
                                                            </a>
                                                        </form>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('member.customerorder.update', $pending->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            @if ($pending->shipping_id > 0)
                                                            <a role="button" href="javascript:void(0);" onclick="return approveItem(this, 1);" title="{{ __('Approve') }}" class="text-success">
                                                                <i class="bi bi-check-square size-22"></i>
                                                            </a>
                                                            @else
                                                            <a role="button" href="javascript:void(0);" onclick="return approveItem(this, 0);" title="{{ __('Mark as delivered') }}" class="text-info">
                                                                <i class="bi bi-person-check-fill size-22"></i>
                                                            </a>
                                                            @endif
                                                        </form>
                                                    </li>
                                                 </ul>
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
            <!-- start of finished order -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('member.customerorder.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="r" placeholder="{{ __('Search by invoice number') }}" 
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
                                        <th>{{ __('Order Type') }}</th>
                                        <th>{{ __('Status') }}</th>
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
                                                <a href="{{ route('member.customerorder.show', $complete->id) }}">
                                                    {{ $complete->getInvoiceNumber() }}
                                                </a> - {{ $complete->buyer_name }}
                                            </td>
                                            <td>
                                                @if ($complete->shipping_id > 0)
                                                    {{ __('Courier') }}
                                                @else
                                                    {{ __('Shop') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (empty($complete->lastOrderProcess))
                                                    {{ $complete->getStatus() }}
                                                @else
                                                    {{ $complete->lastOrderProcess->getStatus() }}
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
            <!-- start of onprogress order -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-4">
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('member.customerorder.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="r" 
                                placeholder="{{ __('Search by invoice number') }}" aria-label="Search" value="{{ app('request')->input('s') }}" autocomplete="off">
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
                                        <th>{{ __('Order Type') }}</th>
                                        <th>{{ __('Status') }}</th>
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
                                                <a href="{{ route('member.customerorder.show', $approved->id) }}">
                                                    {{ $approved->getInvoiceNumber() }}
                                                </a> - {{ $approved->buyer_name }}
                                            </td>
                                            <td>
                                                @if ($approved->shipping_id > 0)
                                                    {{ __('Courier') }}
                                                @else
                                                    {{ __('Shop') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (empty($approved->lastOrderProcess))
                                                    {{ $approved->getStatus() }}
                                                @else
                                                    {{ $approved->lastOrderProcess->getStatus() }}
                                                @endif
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
            <!-- end of onprogress order -->
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
    function approveItem(dt, approve)
    {
        var msg = "{{ __('Are you sure you want to approve this order?') }}";
        if (approve <= 0) {
            var msg = "{{ __('Are you sure you want to mark this order as delivered?') }}";
        }
        if (confirm(msg)) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection