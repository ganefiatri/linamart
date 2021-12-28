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
                        <h4 class="mb-2">{{ __('Product List') }}</h4>
                    </div>
                </div>
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#shop-modal" class="btn btn-outline-secondary">
                                    <i class="bi bi-plus-square"></i> {{ __('Add New Product') }}
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <a href="{{ route('admin.product-categories.index') }}" class="btn btn-outline-success">
                                    <i class="bi bi-list"></i> {{ __('List Categories') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('admin.products.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <div class="form-group mr-3">
                                <select name="shop_id" class="form-control" onchange="return chooseShop(this);">
                                    <option value="">{{ __('Choose Shop') }}</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}" @if (request('shop_id') == $shop->id) selected="selected" @endif>{{ $shop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <span data-feather="search"></span>
                                <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="{{ __('Search by name') }}" aria-label="Search" value="{{ app('request')->input('q') }}">
                            </div>
                         </form>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Product List') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Shop Name') }}</th>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>Status</th>
                                        <th class="text-center" width="100px">Action</th>
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
                                                <a href="{{ route('admin.shops.show', $item->shop_id) }}" class="view">
                                                    {{ !empty($item->shop) ? $item->shop->name : '-' }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $item->id) }}" class="view">
                                                    {{ $item->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="@if ($item->active > 0)bg-opacity-success color-success @else bg-opacity-danger color-danger @endif rounded-pill userDatatable-content-status active">
                                                    {{ $statusList[$item->active]}}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('admin.products.edit', $item->id) }}" title="edit">
                                                            <i class="bi bi-pencil-square size-22"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('admin.products.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a role="button" href="javascript:void(0);" onclick="return removeItem(this);" title="remove">
                                                                <i class="bi bi-trash size-22"></i>
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
<div class="modal fade" id="shop-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content product border-0 shadow-sm">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Choose Shop') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <form action="{{ route('admin.products.create') }}">
            <div class="modal-body">
                <div class="form-group my-3">
                    <select name="shop_id" class="form-control">
                        @foreach ($shops as $shop)
                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger text-white" id="btn-close-modal" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-default">{{ __('Next') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>
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
    function chooseShop(dt)
    {
        var shop_id = parseInt($(dt).val());
        if (shop_id > 0) {
            window.location.href = "{{ route('admin.products.index') }}?shop_id=" + shop_id;
        }
        return false;
    }
    </script>
@endsection