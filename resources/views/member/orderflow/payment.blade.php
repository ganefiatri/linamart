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
                    <a href="{{ route('member.order.cart') }}" class="wizard-link">
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
                    <a href="{{ route('member.order.payment') }}" class="wizard-link active">
                        <i class="bi bi-credit-card shadow-sm"></i>
                        <span class="wizard-text">3. {{ __('Payment') }}</span>
                    </a>
                </div>
            </div>
            
            @include('layouts.partial._balance')

            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    @if (session('success'))
                        <div class="alert alert-success mt-3 mb-4">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3 mb-4">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h5 class="mb-0">{{ __('Shipped from shop') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    <p>{{ $shop['address'] ?? '' }}</p>
                                    <p>{{ $districtName }}</p>
                                    <p>Kode Pos {{ $shop['postal_code'] ?? '' }}</p>
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
                    <h5 class="mb-0">{{ __('Delivery') }} :</h5>
                </div>
            </div>

            <form method="POST" action="{{ route('member.order.paymentproceed') }}" id="payment-form">
                @csrf
            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    <div class="form-check">
                                        <input class="form-check-input ship-method" type="radio" name="delivery" id="flexRadioDefault1" 
                                            value="pickup" @if (old('delivery') == 'pickup') checked @endif required>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            {{ __('Pickup') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input ship-method" type="radio" name="delivery" id="flexRadioDefault2" 
                                            value="courier" @if (old('delivery') == 'courier') checked @endif required>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            {{ __('Courier Delivery') }}
                                        </label>
                                    </div>
                                    @error('delivery')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-box-seam text-color-theme"></i>
                                </div>
                            </div>

                            <div class="row p-3 @if (old('delivery') != 'courier')d-none @endif" id="member-address-info">
                                <div class="col align-self-center alert alert-dark mb-0">
                                    <h6>{{ __('Your Address') }}</h6>
                                    <p>{{ $member->address }}</p>
                                    <p>{{ $memberDistrictName }}</p>
                                    <p>Kode Pos {{ $member->postal_code }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3 d-flex justify-content-center ongkir @if (old('delivery') != 'courier')d-none @endif">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Shipping Cost') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10 ongkir @if (old('delivery') != 'courier')d-none @endif">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @php
                                        $cartShipping = cart_shipping();
                                    @endphp
                                    @foreach ($shippings as $shipping)
                                    <div class="form-check">
                                        <input class="form-check-input ship-cost" type="radio" name="shipping_id" id="{{ $shipping->id }}" value="{{ $shipping->id }}" 
                                            @if (!empty($cartShipping['id']) && $cartShipping['id'] == $shipping->id) checked @endif required>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            {{ $shipping->title }} ({{ $shipping->distance_from }} - {{ $shipping->distance_to }} km = {{ $shipping->getFormatedCost() }})
                                        </label>
                                    </div>
                                    @endforeach

                                    @error('shipping_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-cash-stack text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="form-check mt-3 mb-3">
                        <input class="form-check-input" type="checkbox" name="aggreement" id="flexCheckDefault" required>
                        <label class="form-check-label" for="flexCheckDefault" id="aggreement-driver">
                            Saya menyetujui jika jarak pengantaran tidak sesuai dengan jarak yang saya pilih, 
                            maka saya bersedia menambah biaya tambahan kepada driver.
                        </label>
                        <label class="form-check-label d-none" for="flexCheckDefault" id="aggreement-pickup">
                            Saya menyetujui pemesanan ini dan saya mengerti bahwa pesanan tidak dapat dibatalkan.
                        </label>

                        @error('aggreement')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- pricing -->
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Pricing') }} :</h5>
                </div>
            </div>
            @php
                $cartTotal = cart_total();
            @endphp
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
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
                            <p>Sub Total</p>
                        </div>
                        <div class="col-auto cart-subtotal">{{ $cartTotal['formated']['sub_total'] }}</div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col">
                            <p>{{ __('Shipping Fee') }}</p>
                        </div>
                        <div class="col-auto cart-shipping-fee">{{ $cartTotal['formated']['shipping_fee'] }}</div>
                    </div>
        
                    <div class="row fw-bold mb-4">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>{{ __('Total Payment') }}</p>
                        </div>
                        <div class="col-auto cart-total">{{ $cartTotal['formated']['total'] }}</div>
                    </div>
        
                    <!-- Button -->
                    <div class="row mb-3">
                        <div class="col align-self-center d-grid">
                            <button type="button" class="btn btn-default btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                {{ __('Pay With Saldo') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
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

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">{{ __('Payment Confirmation') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning mt-3">
                <p class="text-center">{{ __('This order can not be canceled.') }}<br/>
                    {{ __('Are you sure to continue this order?') }}</p>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
          <button type="button" class="btn btn-success text-white" id="btn-aggree">{{ __('Yes, I Agree') }}</button>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ mix('js/cart.min.js') }}"></script>
<script type="text/javascript">
$(function () {
    $('.ship-method').click(function () {
        if ($(this).val() == 'pickup') {
            $('#member-address-info').addClass('d-none');
            $('.ongkir').addClass('d-none');
            $('#aggreement-driver').addClass('d-none');
            $('#aggreement-pickup').removeClass('d-none');
            usePickupMethod();
        } else {
            $('#member-address-info').removeClass('d-none');
            $('.ongkir').removeClass('d-none');
            $('#aggreement-pickup').addClass('d-none');
            $('#aggreement-driver').removeClass('d-none');
        }
    });
    $('.ship-cost').click(function () {
        var id = $(this).attr('id');
        $.ajax({
            method: "POST",
            url: "{{ route('member.order.shipping') }}",
            data: { id: id, _token: $('meta[name="csrf-token"]').attr('content') }
        }).done(function (msg) {
            if (msg.success) {
                reloadCartInfo(msg, 0, 10);
            } else {
                $(".ship-cost").prop('checked', false);
                alert(msg.message);
            }
            console.log(msg);
        });
    });
    $('#btn-aggree').click(function (e) {
        e.preventDefault();
        $('#payment-form').trigger('submit');
    });
});
function usePickupMethod() {
    $.ajax({
        method: "POST",
        url: "{{ route('member.order.shipping') }}",
        data: { id: 0, _token: $('meta[name="csrf-token"]').attr('content') }
    }).done(function (msg) {
        reloadCartInfo(msg, 0, 10);
        $(".ship-cost").prop('checked', false); 
        console.log(msg);
    });
}
</script>
@endsection