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
                        <h4 class="mb-2">{{ __('Order List') }}</h4>
                    </div>
                </div>
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <form action="{{ route('member.invoice.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-2">
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="{{ __('Search by order number') }}" 
                                aria-label="Search" value="{{ app('request')->input('q') }}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                @php
                                    $currentStatus = app('request')->input('status');
                                @endphp
                                <select class="form-control mr-sm-2 border-0 box-shadow-none" name="status">
                                    <option value="" @if (is_null($currentStatus)) selected="selected" @endif>{{ __('All Status') }}</option>
                                    @foreach ($orderStatusList as $status_code => $status_name)
                                        <option value="{{ $status_code }}" @if (!is_null($currentStatus) && $currentStatus == $status_code) selected="selected" @endif>{{ $status_name }}</option>
                                    @endforeach
                                </select>
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
                    <div class="card-header color-dark fw-500">{{ __('Order List') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Inv Number') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th>{{ __('Order Date') }}</th>
                                        <th>Status</th>
                                        <th class="text-center" width="100px">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($items->currentPage()-1) * $items->perPage()) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('member.invoice.show', $item->id) }}" title="show">
                                                {{ $item->getInvoiceNumber() }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ to_money_format($item->base_income) }}
                                            </td>
                                            <td>
                                                {{ $item->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td>
                                                @if (!empty($item->lastOrderProcess))
                                                {{ $item->lastOrderProcess->getStatus() }}
                                                @else
                                                {{ __('Pending') }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('member.invoice.show', $item->id) }}" title="show">
                                                            <i class="bi bi-eye size-22"></i>
                                                        </a>
                                                    </li>
                                                 </ul>
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $items->appends(request()->input())->links() }}
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
    function removeItem(dt)
    {
        if (confirm("{{ __('Are you sure you want to delete this?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection