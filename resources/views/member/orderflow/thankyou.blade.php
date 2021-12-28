@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <!-- main page content -->
    <div class="main-container container">
        @include('layouts.partial._balance')

        <div class="row mb-3 d-flex justify-content-center">
            @if ($invoice->status > 0)
            <div class="col-12 col-md-10 col-lg-10">
                <div class="alert alert-light mt-3 text-center">
                    <h6>Terimakasih {{ $member->name }}!</h6>
                    <p>Saldo Anda telah dipotong sebesar {{ $cartTotal['formated']['total'] }}</p>.
                    @if ($invoice->shipping_id > 0)
                        <p>Kami sedang mencarikan Driver untuk Anda.<br/>Mohon ditunggu.</p>
                        <p>Kami Akan menghubungi Anda via WhatsApp jika driver sudah menuju ke toko untuk penjemputan.</p>
                    @endif
                    <p>Mohon cek secara rutin chat WhatsApp dan Email dari kami.</p>
                </div>
                <div class="row align-content-center mt-4">
                    @if ($invoice->shipping_id > 0)
                        <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center mb-3">
                            <a href="{{ route('member.home') }}" class="btn btn-info btn-lg shadow-sm">
                                {{ __('Back to Store') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 d-flex justify-content-center mb-3">
                            <a href="{{ wa_url(config('global.admin_wa')) }}" class="btn btn-default btn-lg shadow-sm" target="_blank">
                                {{ __('Chat us via WhatsApp') }}
                            </a>
                        </div>
                    @else
                        <div class="col-12 col-md-4 col-lg-4 d-flex justify-content-center mb-3">
                            <a href="{{ wa_url($invoice->seller_phone) }}" class="btn btn-default btn-lg shadow-sm" target="_blank">
                                {{ __('Chat shop via WhatsApp') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 d-flex justify-content-center mb-3">
                            <a href="{{ phone_url($invoice->seller_phone) }}" class="btn btn-primary btn-lg shadow-sm">
                                {{ __('Call Shop') }}
                            </a>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4 d-flex justify-content-center mb-3">
                            <a href="{{ route('member.home') }}" class="btn btn-info btn-lg shadow-sm">
                                {{ __('Back to Store') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @else
            <div class="col-12 col-md-10 col-lg-10">
                <div class="alert alert-warning mt-3 text-center">
                    <h6>Mohon maaf {{ $member->name }}!</h6>
                    <p>Transaksi Anda tidak dapat diproses karena alasan berikut :</p>
                    <p>"{{ $invoice->notes }}"</p>
                    <p>Silakan klik link berikut untuk melanjutkan belanja. Terimakasih.</p>
                    <a href="{{ route('member.home') }}" class="btn btn-info btn-lg shadow-sm mt-3">
                        {{ __('Back to Store') }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

</main>
<!-- Page ends-->

@endsection
