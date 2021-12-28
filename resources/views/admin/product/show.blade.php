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
                        <h4 class="mb-2">{{ __('View Product') }} #{{ $product->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Product') }}
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 text-end">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil-square"></i> {{ __('Update Product') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Product Detail') }}</div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Product Name') }}</td>
                                    <td>{{ $product->title }}</td>
                                </tr>
                                @if ($product->shop)
                                <tr>
                                    <td>{{ __('Shop Name') }}</td>
                                    <td>
                                        <a href="{{ route('admin.shops.show', $product->shop_id) }}" target="_newtab">
                                        {{ $product->shop->name }}</td>
                                        </a>
                                </tr>
                                    @if ($product->shop->member)
                                    <tr>
                                        <td>{{ __('Shop Owner') }}</td>
                                        <td>
                                            <a href="{{ route('admin.members.show', $product->shop->member_id) }}" target="_newtab">
                                            {{ $product->shop->member->name }}</td>
                                            </a>
                                    </tr>
                                    @endif
                                @endif
                                @if ($product->description)
                                <tr>
                                    <td colspan="2">{{ __('Description') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">{!! $product->description !!}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Slug') }}</td>
                                    <td>{{ $product->slug }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Category') }}</td>
                                    <td>{{ $product->category->title }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Status') }}</td>
                                    <td>{{ $status_list[$product->active] }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Enabled') }}</td>
                                    <td>{{ ($product->enabled > 0)? __('Yes') : __('No') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Hidden') }}</td>
                                    <td>{{ ($product->hidden > 0)? __('Yes') : __('No') }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>{{ __('Normal Price') }}</td>
                                    <td>{{ to_money_format($product->price) }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>{{ __('Discount') }}</td>
                                    <td>{{ to_money_format($product->discount) }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>{{ __('Net Price') }}</td>
                                    <td>{{ to_money_format(($product->price - $product->discount) ?? 0) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Weight') }}</td>
                                    <td>{{ $product->weight }} {{ $product->unit }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Viewed') }}</td>
                                    <td>{{ $product->viewed }}X</td>
                                </tr>
                                @if ($product->orders)
                                <tr class="fw-bold">
                                    <td>{{ __('Ordered') }}</td>
                                    <td>{{ $product->orders()->count() }} pcs</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $product->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $product->updated_at !!}</td>
                                </tr>
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