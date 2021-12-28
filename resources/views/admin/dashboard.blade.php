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
                        <h4 class="mb-2">{{ __('Dashboard') }}</h4>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('admin.products.index') }}" title="Lihat daftar produk">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-danger text-white">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Products') }}</span>
                                        <p class="mb-1">{{ number_format($stats['total_products'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('admin.orders.index') }}" title="Lihat daftar order">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-success text-white">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Sales') }}</span>
                                        <p class="mb-1">{{ number_format($stats['total_orders'] ?? 0, 0, ',', '.') }} orders ({{ to_money_format($stats['total_sales'] ?? 0) }})</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('admin.shops.index') }}" title="Lihat daftar toko">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-info text-white">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Active Shop') }}</span>
                                        <p class="mb-1">{{ number_format($stats['total_shop'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('admin.members.index') }}" title="Lihat daftar member">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-primary text-white">
                                            <i class="bi bi-people"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Member') }}</span>
                                        <p class="mb-1">{{ number_format($stats['total_members'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('admin.drivers.index') }}" title="Lihat daftar driver">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-secondary text-white">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Total Driver') }}</span>
                                        <p class="mb-1">{{ number_format($stats['total_drivers'] ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <a href="{{ route('admin.notification') }}" title="Lihat pemberitahuan">
                        <div class="card shadow-sm product mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 rounded bg-warning text-white">
                                            <i class="bi bi-bell"></i>
                                        </div>
                                    </div>
                                    <div class="col ps-0 align-self-center">
                                        <span class="small text-opac mb-0">{{ __('Notification') }}</span>
                                        <p class="mb-1">
                                            @php
                                                $notif = notif_counter();
                                            @endphp
                                            @if ($notif > 0)
                                                {{ $notif . ' ' . __('New Notification') }}
                                            @else
                                                {{ __('No new notification found') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<!-- Page ends-->

@endsection
