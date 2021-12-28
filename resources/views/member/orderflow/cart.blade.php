@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <!-- main page content -->
    <div class="main-container container top-20">
        @if (has_cart())
            <!-- wizard links -->
            <div class="row justify-content-between wizard-wrapper mb-4 shadow-sm">
                <div class="col">
                    <a href="{{ route('member.order.cart') }}" class="wizard-link active">
                        <i class="bi bi-bag shadow-sm"></i>
                        <span class="wizard-text">1. {{ __('Products') }}</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('member.order.checkout') }}" class="wizard-link">
                        <i class="bi bi-geo-alt shadow-sm"></i>
                        <span class="wizard-text">2. {{ __('Address') }}</span>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('member.order.payment') }}" class="wizard-link">
                        <i class="bi bi-credit-card shadow-sm"></i>
                        <span class="wizard-text">3. {{ __('Payment') }}</span>
                    </a>
                </div>
            </div>
            @include('layouts.partial._balance')
            <!-- cart items -->
            @php
                $carts = cart_items();
            @endphp
            <div class="row mb-3">
                <div class="col align-self-center">
                    <h5 class="mb-0"><span class="tot-cart-items">{{ $carts->count() }}</span> {{ __('products in cart') }}</h5>
                </div>
                <div class="col-auto pe-0 align-self-center">
                    <a href="{{ route('member.order.search') }}" class="link text-color-theme">
                        {{ __('Shop more') }} <i class="bi bi-chevron-right"></i></a>
                </div>
            </div>
            <div class="row mb-2">
                @foreach ($carts as $id => $cart)
                <div class="col-12 col-md-6 col-lg-4" id="cart-items-{{ $id }}">
                    <div class="card shadow-sm product mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <figure class="text-center avatar-90 avatar">
                                        {{ $product->getDefaultImage(50, 50, ['class' => 'img-fluid'], true, $id) }}
                                    </figure>
                                </div>
                                <div class="col ps-0">
                                    @if (!empty($cart['category']))
                                    <p class="mb-0">
                                        <small class="text-opac">{{ $cart['category'] }}</small>
                                        <a href="{{ route('member.order.deletecart', ['product' => $id]) }}" 
                                            onclick="return deleteCart(this);" attr-id="{{ $id }}" class="float-end text-danger" id="rm-cart-{{ $id }}">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </p>
                                    @endif
                                    <a href="{{ route('member.order.product', ['slug' => $cart['slug']]) }}" class="text-normal">
                                        <h6 class="text-color-theme">{{ $cart['title']}}</h6>
                                    </a>
                                    <div class="row">
                                        <div class="col">
                                            <p class="mb-0">
                                                @if ($cart['discount'] > 0)
                                                    <span class="text-muted"><strike>{{ $cart['formated_price'] }}</strike></span><br/>{{ $cart['formated_net_price'] }}
                                                @else
                                                    {{ $cart['formated_net_price'] }}
                                                @endif
                                                @if (!empty($cart['unit']))
                                                <br><small class="text-opac">per 1 {{ $cart['unit'] }}</small>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="counter-number" attr-href="{{ route('member.order.addtocart', ['product' => $id]) }}">
                                                <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                                    attr-id="{{ $id }}" onclick="addToCart(this, '-');">
                                                    <i class="bi bi-dash size-22"></i>
                                                </button>
                                                <span id="qty">{{ $cart['qty'] }}</span>
                                                <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                                    attr-id="{{ $id }}" onclick="addToCart(this, '+');">
                                                    <i class="bi bi-plus size-22"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- notes -->
            <div class="row mb-3">
                <div class="col-12 col-lg-6 col-sm-6">
                    <div class="form-group form-floating">
                        <textarea name="notes" id="order-notes" class="form-control" placeholder="Tambahkan catatan jika diperlukan">{{ cart_note() ?? '' }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <label class="form-control-label" for="order-notes">Tambahkan Catatan (Optional)</label>
                    </div>
                </div>
            </div>

            <!-- pricing -->
            <div class="row mb-3">
                <div class="col align-self-center">
                    <h5 class="mb-0">{{ __('Pricing') }}</h5>
                </div>
            </div>
            @php
                $cartTotal = cart_total();
            @endphp
            <div class="row mb-3">
                <div class="col">
                    <p>{{ __('Total Price') }}</p>
                </div>
                <div class="col-auto cart-total-price">{{ $cartTotal['formated']['total_price'] }}</div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <p>{{ __('Total Discount') }}</p>
                </div>
                <div class="col-auto cart-total-discount">{{ $cartTotal['formated']['total_discount'] }}</div>
            </div>

            <div class="row fw-bold mb-4">
                <div class="mb-3 col-12">
                    <div class="dashed-line"></div>
                </div>
                <div class="col">
                    <p>Total</p>
                </div>
                <div class="col-auto cart-subtotal">{{ $cartTotal['formated']['sub_total'] }}</div>
            </div>

            <!-- Button -->
            <div class="row mb-3">
                <div class="col align-self-center d-grid">
                    <a href="{{ route('member.order.checkout') }}" class="btn btn-default btn-lg shadow-sm" id="next-to-payment">
                        {{ __('Next') }}
                    </a>
                </div>
            </div>
        @else
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm product mb-3">
                    <div class="card-body">
                        <div class="alert alert-warning mt-3">
                            {{ __('Your cart is still empty') }}.
                        </div>
                        <div class="align-self-center d-grid col-md-4 col-12">
                            <a href="{{ route('member.order.search') }}" class="btn btn-default btn-lg shadow-sm">{{ __('Next To Shop') }}</a>
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

@section('js')
<script src="{{ mix('js/cart.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    $('#next-to-payment').click(function () {
        const notes = $('#order-notes').val();
        if (notes.length > 0) {
            $.ajax({
                url: "{{ route('member.order.notes') }}",
                dataType: "json",
                method: "post",
                data: {
                    notes: notes,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    });
});
</script>
@endsection