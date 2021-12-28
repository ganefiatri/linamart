@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <!-- main page content -->
    <div class="main-container container top-20">
        <div class="row mt-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Detail Order') }} #{{ $assignment->getInvoiceNumber() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if ($assignment->orders)

            <div class="row mb-3 d-flex justify-content-center mt-4">
                <div class="col-12 col-md-10 col-lg-10">
                    @if ($assignment->shipping_id > 0 || $assignment->shipping_fee > 0)
                    <h5 class="mb-0">{{ __('Shipped from shop') }} :</h5>
                    @else
                    <h5 class="mb-0">{{ __('Pickup from shop') }} :</h5>
                    @endif
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @php
                                        $shop = $assignment->shop;
                                    @endphp
                                    <p><b>{{ __('Shop Name') }} :</b> <a href="{{ route('driver.shops.show', $shop->id) }}" title="{{ __('Show Shop Detail') }}">{{ $assignment->seller_name }}</a>
                                        <br/><b>{{ __('Seller Address') }} :</b></p>
                                    <p>{{ $assignment->seller_address }}</p>
                                    @if (!empty($shop))
                                    @if ($shop->district_id > 0 && is_using_district())
                                        <p>{{ $shop->district->name }}, {{ $shop->district->city->name }}, {{ $shop->district->city->province->name }}</p>
                                    @else
                                        <p>{{ get_district_value($shop) }}</p>
                                    @endif
                                    
                                    <p><a href="{{ route('driver.shops.show', $shop->id) }}" title="{{ __('Show Shop Detail') }}">{{ __('Show Shop Detail') }}</a></p>
                                    <a href="{{ wa_url($assignment->seller_phone) }}" title="{{ __('Chat Seller') }}" target="_blank" class="m-1">
                                        <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Seller') }}
                                    </a>
                                    {{--<a href="{{ phone_url($assignment->seller_phone) }}" title="{{ __('Call Seller') }}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="bi bi-phone size-22"></i> {{ __('Call Seller') }}
                                    </a>--}}
                                    @endif
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-geo-alt text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                        @php
                            $notes = $assignment->meta['driver_notes'] ?? null;
                        @endphp
                        @if (!empty($notes))
                        <div class="card-footer p-2 px-3">
                            <b>Catatan Untuk Driver :</b>
                            <div class="alert alert-info mt-2">{{ $notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($assignment->shipping_id > 0 || $assignment->shipping_fee > 0)
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Sent To') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @php
                                        $member = $assignment->member;
                                    @endphp
                                    <p><b>{{ __('Buyer Name') }} : </b> <a href="{{ route('driver.members.show', $member->id) }}" title="{{ __('Show Buyer Detail') }}">{{ $assignment->buyer_name }}</a>
                                        <br/><b>{{ __('Buyer Address') }} : </b></p>
                                    <p>{{ $assignment->buyer_address }}</p>
                                    @if (!empty($member))
                                    
                                    @if ($member->district_id > 0 && is_using_district())
                                        <p>{{ $member->district->name }}, {{ $member->district->city->name }}, {{ $member->district->city->province->name }}</p>
                                    @else
                                        <p>{{ get_district_value($member) }}</p>
                                    @endif
                                    <p><a href="{{ route('driver.members.show', $member->id) }}" title="{{ __('Show Buyer Detail') }}">{{ __('Show Buyer Detail') }}</a></p>
                                    <a href="{{ wa_url($assignment->buyer_phone) }}" title="{{ __('Chat Buyer') }}" target="_blank" class="m-1">
                                        <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Buyer') }}
                                    </a>
                                    {{--<a href="{{ phone_url($assignment->buyer_phone) }}" title="{{ __('Call Buyer') }}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="bi bi-phone size-22"></i> {{ __('Call Buyer') }}
                                    </a>--}}
                                    @endif
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-geo-alt text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Shipping Cost') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @foreach ($shippings as $shipping)
                                    <div class="form-check">
                                        <input class="form-check-input ship-cost" type="radio" name="shipping"  
                                            @if ($assignment->shipping_id == $shipping->id) checked @endif disabled>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            {{ $shipping->title }} ({{ $shipping->distance_from }} - {{ $shipping->distance_to }} = {{ $shipping->getFormatedCost() }})
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-cash-stack text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Buyer Information') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    <p>{{ $assignment->buyer_name }}<br/>{{ $assignment->buyer_address }}</p>
                                    @php
                                        $member = $assignment->member;
                                    @endphp
                                    @if (!empty($member))
                                    <p>{{ $member->district->name }}, {{ $member->district->city->name }}, {{ $member->district->city->province->name }}</p>
                                    <a href="{{ wa_url($assignment->buyer_phone) }}" title="{{ __('Chat Buyer') }}" target="_blank" class="btn btn-outline-success m-1">
                                        <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Buyer') }}
                                    </a>
                                    <a href="{{ phone_url($assignment->buyer_phone) }}" title="{{ __('Call Buyer') }}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="bi bi-phone size-22"></i> {{ __('Call Buyer') }}
                                    </a>
                                    @endif
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-geo-alt text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- pricing -->
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Order Items') }} :</h5>
                </div>
            </div>
            
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    @php
                        $tot_price = 0;
                        $tot_discount = 0;
                    @endphp
                    @foreach ($assignment->orders as $item)
                        <div class="row mb-2">
                            <div class="col">
                                <b>{{ $item->title }}</b> x {{ $item->quantity }}
                                <p class="px-2">{{ to_money_format($item->price) }}</p>
                            </div>
                            <div class="col-auto">
                                {{ to_money_format($item->price * $item->quantity) }}
                            </div>
                        </div>
                        @php
                            $tot_price += $item->price * $item->quantity;
                            $tot_discount += $item->discount * $item->quantity;
                        @endphp
                    @endforeach
                    @php
                        $sub_total = $tot_price - $tot_discount;
                    @endphp
                    <div class="row mt-4 mb-3">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>{{ __('Total Price') }}</p>
                        </div>
                        <div class="col-auto cart-total-price">{{ to_money_format($tot_price) }}</div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col">
                            <p>{{ __('Total Discount') }}</p>
                        </div>
                        <div class="col-auto">{{ to_money_format($tot_discount) }}</div>
                    </div>
        
                    <div class="row fw-bold mb-4">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>Sub Total</p>
                        </div>
                        <div class="col-auto">{{ to_money_format($sub_total) }}</div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col">
                            <p>{{ __('Shipping Fee') }}</p>
                        </div>
                        <div class="col-auto cart-shipping-fee">{{ to_money_format($assignment->shipping_fee) }}</div>
                    </div>
        
                    <div class="row fw-bold mb-4">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>{{ __('Total Payment') }}</p>
                        </div>
                        @php
                            $tot_payment = $sub_total + $assignment->shipping_fee;
                        @endphp
                        <div class="col-auto cart-total">{{ to_money_format($tot_payment) }}</div>
                    </div>
                </div>
            </div>
        @else
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm product mb-3">
                    <div class="card-body">
                        <div class="alert alert-warning mt-3">
                            {{ __('No data found') }}.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
    <!-- main page content ends -->

</main>
<!-- Page ends-->
@endsection
