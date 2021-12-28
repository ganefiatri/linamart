@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
@endsection

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
                    <a href="{{ route('member.order.checkout') }}" class="wizard-link active">
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
            
            <div class="row mt-4 mb-3">
                <div class="col align-self-center">
                    <h5 class="mb-0">{{ __('Your Address') }}</h5>
                </div>
                <div class="col-auto pe-0 align-self-center">
                    <a href="{{ route('member.order.search') }}" class="link text-color-theme">
                        {{ __('Shop more') }} <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
            <!-- add edit address form -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-light shadow-sm mb-4">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success mb-4">{{ session('success') }}</div>
                            @endif

                            @if (session('warning'))
                                <div class="alert alert-warning mb-4">{{ session('warning') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('member.updateaddress') }}" id="address-form">
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-12 col-md-6 col-lg-6 mb-3">
                                        <div class="form-group form-floating">
                                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                                value="{{ $member->address }}" id="address2" placeholder="Address Line 1">

                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label class="form-control-label" for="address2">Alamat Lengkap (Desa RT RW)</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            @if (is_using_district())
                                                <input type="text" name="district_name" class="form-control autocomplete  @error('district_name') is-invalid @enderror" id="district_name" 
                                                    onclick="this.select();" placeholder="Kecamatan" value="{{ $district_name }}" required>
                                            @else
                                                <input type="text" name="district_name" class="form-control  @error('district_name') is-invalid @enderror" id="district_name" 
                                                    placeholder="Kecamatan" value="{{ $district_name }}" autocomplete="off" required>
                                            @endif
                                            @error('district_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label for="country">Kecamatan, Kabupaten, Propinsi</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="form-group form-floating">
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                value="{{ $member->name }}" placeholder="Nama Lengkap" id="name">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label class="form-control-label" for="name">{{ __('Full Name') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="form-group form-floating">
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ $member->phone }}" placeholder="Nomor Telepon" id="phone">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label class="form-control-label" for="phone">No Telepon/Handphone</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="form-group form-floating">
                                            <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                                                value="{{ $member->postal_code }}" placeholder="Kode pos" id="postal-code">
                                            @error('postal_code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label class="form-control-label" for="postal-code">Kode Pos</label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="gender" value="{{ $member->gender }}"/>
                                    <input type="hidden" name="district_id" id="district-id" value="{{ $member->district_id }}"/>
                                    <input type="hidden" name="back_to" value="member.order.checkout"/>
                                    <div class="d-grid mt-3">
                                        <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                                            <button type="submit" class="btn btn-lg btn-info shadow-sm w-100">{{ __('Update Address') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                    @if ($member->hasCompleteAddress())
                    <a href="{{ route('member.order.payment') }}" class="btn btn-default btn-lg shadow-sm">
                        {{ __('Next') }}
                    </a>
                    @else
                        <a href="javascript:void(0);" onclick="return addressWarning();" class="btn btn-dark btn-lg shadow-sm">{{ __('Next') }}</a>
                    @endif
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
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    //var district_names = @json(array_values($districts));
    var district_ids = @json(array_flip($districts));
    $(".autocomplete").autocomplete({
        //source: district_names,
        source: function( request, response ) {
            $.ajax({
                url: "{{ route('districts') }}",
                dataType: "json",
                method: "post",
                data: {
                    q: request.term,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
            var dist_id = district_ids[ui.item.label];
            if (parseInt(dist_id) > 0) {
                $('#district-id').val(dist_id);
            }
        }
    });
});
function addressWarning()
{
    var _toast = $('#toastprouctaddedtiny');
    _toast.addClass('bg-warning').removeClass('bg-success');
    _toast.find('#toast-msg').html("Mohon lengkapi Alamat Anda agar bisa melanjutkan proses pemesanan!");
    _toast.toast('show');
    return false;
}
</script>
@endsection