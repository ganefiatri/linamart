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
                        <h4 class="mb-2">{{ __('View Shop') }} #{{ $shop->id }}</h4>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary mr-3">
                            <i class="bi bi-list-ul"></i> {{ __('List Shop') }}
                        </a>
                        <a href="#product" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Product') }}
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <a href="{{ route('admin.shops.edit', $shop->id) }}" class="btn btn-outline-secondary float-start">
                                    <i class="bi bi-pencil"></i> {{ __('Edit Shop') }}
                                </a>
                            </div>
                            @if ($shop->member_id > 0)
                            <div class="col-lg-8 col-md-8 col-sm-12">
                                <form method="POST" action="{{ route('admin.loginasmember', $shop->member) }}">
                                    @csrf
                                    <button class="btn btn-default float-end" onclick="return jumpConfirmation(this);">
                                        {{ __('Login As') }} "{{ $shop->member->name }}"
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 col-lg-6 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Shop Information') }}</div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td>{{ $shop->name }}</td>
                                        </tr>
                                        @if ($shop->member_id > 0)
                                        <tr>
                                            <td>{{ __('Owner') }}</td>
                                            <td>{{ $shop->member->name }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>{{ __('Phone') }}</td>
                                            <td>{{ $shop->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Address') }}</td>
                                            <td>{{ $shop->address }}</td>
                                        </tr>
                                        @if ($shop->district_id > 0 && is_using_district())
                                        <tr>
                                            <td>{{ __('District') }}</td>
                                            <td>{{ $shop->district->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('City') }}</td>
                                            <td>{{ $shop->district->city->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('District') }}</td>
                                            <td>{{ $shop->district->city->province->name }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td>{{ __('District City') }}</td>
                                            <td>{{ get_district_value($shop) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>{{ __('Postal Code') }}</td>
                                            <td>{{ $shop->postal_code }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Status') }}</td>
                                            <td>{{ $shop->getStatus() }}</td>
                                        </tr>
                                        @if ($shop->meta)
                                            @foreach ($shop->meta as $metaName => $metaValue)
                                            <tr>
                                                <td>@if ($metaName == 'daily_open') {{ __('Daily Open') }} @else {{ ucfirst($metaName) }} @endif</td>
                                                @if ($metaName == 'daily_open' && is_array($metaValue))
                                                    <td>
                                                    <ul>
                                                    @foreach ($metaValue as $d => $item)
                                                        <li>{{ trans($item['day_name']) }} ({{ $item['open'] }} - {{ $item['closed'] }})</li>
                                                    @endforeach
                                                    </ul>
                                                    </td>
                                                @else 
                                                <td>{{ (!is_array($metaValue)) ? $metaValue : json_encode($metaValue) }}</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>{{ __('Created At') }}</td>
                                            <td>{!! $shop->created_at !!}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Last Update') }}</td>
                                            <td>{!! $shop->updated_at !!}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($shop->member)
                    <div class="col-12 col-lg-6 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-header color-dark fw-500">{{ __('Member Information') }}</div>
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td>{{ $shop->member->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td>{{ $shop->member->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Phone') }}</td>
                                            <td>{{ $shop->member->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Address') }}</td>
                                            <td>{{ $shop->member->address }}</td>
                                        </tr>
        
                                        @if ($shop->member->district_id > 0 && is_using_district())
                                        <tr>
                                            <td>{{ __('District') }}</td>
                                            <td>{{ $shop->member->district->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('City') }}</td>
                                            <td>{{ $shop->member->district->city->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('District') }}</td>
                                            <td>{{ $shop->member->district->city->province->name }}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td>{{ __('District City') }}</td>
                                            <td>{{ get_district_value($shop) }}</td>
                                        </tr>
                                        @endif
                                        
                                        <tr>
                                            <td>{{ __('Postal Code') }}</td>
                                            <td>{{ $shop->member->postal_code }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Gender') }}</td>
                                            <td>{{ $shop->member->getGender() }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Created At') }}</td>
                                            <td>{!! $shop->member->created_at !!}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Last Update') }}</td>
                                            <td>{!! $shop->member->updated_at !!}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
               
                @if ($products)
                <div class="row" id="product">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header color-dark fw-500">
                                <div class="row">
                                    <div class="col">
                                        <h3>{{ __('Product List') }}</h3>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-end">
                                            <form action="{{ route('admin.shops.show', $shop->id) }}#product" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                                                <span data-feather="search"></span>
                                                <input class="form-control mr-sm-2 box-shadow-none" type="search" name="q" placeholder="{{ __('Search') }}" aria-label="Search" value="{{ app('request')->input('q') }}">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <div class="userDatatable global-shadow border-0 bg-white w-100">
                                    <div class="table-responsive">
                                        <table class="table mb-2 table-striped">
                                            <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Discount') }}</th>
                                                <th>Status</th>
                                                <th>{{ __('Created At') }}</th>
                                                {{--<th class="text-center" width="100px">Action</th>--}}
                                            </tr>
                                            </thead>
                                            @php
                                                $i = 1;
                                            @endphp
                                            <tbody>
                                            @foreach ($products as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $i + (($products->currentPage()-1) * $products->perPage()) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.products.show', $item->id) }}" target="_newtab">
                                                        {{ $item->title }}
                                                        </a>
                                                    </td>
                                                    <td style="text-align:right;">
                                                        {{ to_money_format($item->price, '') }}
                                                    </td>
                                                    <td style="text-align:right;">
                                                        {{ to_money_format($item->discount, '') }}
                                                    </td>
                                                    <td>
                                                        <span class="@if ($item->active > 0)bg-opacity-success color-success @else bg-opacity-danger color-danger @endif rounded-pill userDatatable-content-status active">
                                                            {{ $item->getStatus() }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $item->created_at }}
                                                    </td>
                                                    {{--<td class="text-center">
                                                        <ul class="list-unstyled list-inline">
                                                            <li class="list-inline-item">
                                                                <a href="{{ route('products.edit', $item->id) }}" title="edit">
                                                                    <i class="bi bi-pencil-square size-22"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <form action="{{ route('products.destroy',$item->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a role="button" href="javascript:void(0);" onclick="return removeItem(this);" title="remove">
                                                                        <i class="bi bi-trash size-22"></i>
                                                                    </a>
                                                                </form>
                                                            </li>
                                                         </ul>
                                                    </td>--}}
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
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection

@section('js')
    <script type="text/javascript">
    function jumpConfirmation(dt)
    {
        if (confirm("{{ __('Are you sure you want to jump as member from admin?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection