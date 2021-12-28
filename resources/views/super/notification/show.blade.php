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
                        <h4 class="mb-2">{{ __('Notification') }} #{{ $notification->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('super.notification') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Notification') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Show Notification') }}</div>
                    <div class="card-body p-4">
                        {!! $notification->created_at !!}
                        <div class="alert alert-info mt-2">{{ $notification->message }}</div>
                        @if (is_array($notification->meta) && array_key_exists('url', $notification->meta))
                            <a href="{{ $notification->meta['url'] }}" class="btn btn-default">{{ $notification->meta['label'] ?? 'Detail' }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection