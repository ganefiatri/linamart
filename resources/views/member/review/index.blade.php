@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <div class="main-container container">

        @include('layouts.partial._message')

        <div class="row">
            @if (!empty($myProductReviews))
            <div class="col-12 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Your Product Reviews') }}</h4>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Your Product Reviews') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th class="text-center" width="100px">Action</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($myProductReviews as $myProductReview)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($myProductReviews->currentPage()-1) * $myProductReviews->perPage()) }}
                                            </td>
                                            <td>
                                                {{ $myProductReview->product->title }}
                                            </td>
                                            <td>
                                                {{ $myProductReview->rating }}
                                            </td>
                                            <td>{{ $myProductReview->created_at->format('d M Y H:i') }}</td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('member.review.show', $myProductReview->id) }}" title="{{ __('Show review detail') }}">
                                                            <i class="bi bi-eye size-22"></i>
                                                        </a>
                                                    </li>
                                                 </ul>
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $myProductReviews->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-12 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Waiting Review') }}</h4>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Waiting For Your Review') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>{{ __('Order Date') }}</th>
                                        <th class="text-center" width="100px">Action</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($pendings as $pending)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($pendings->currentPage()-1) * $pendings->perPage()) }}
                                            </td>
                                            <td>
                                                {{ $pending->product->title }}
                                            </td>
                                            <td>{{ $pending->created_at->format('d F Y H:i') }}</td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('member.review.create', $pending->product_id) }}?order={{ $pending->id }}" title="{{ __('Write a review') }}">
                                                            <i class="bi bi-pencil-square size-22"></i>
                                                        </a>
                                                    </li>
                                                 </ul>
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $pendings->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- start of history -->
            <div class="col-12 col-lg-6 col-md-6 col-sm-6 mb-3">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Review History') }}</h4>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Your Review History') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>{{ __('Rating') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th class="text-center" width="100px">Action</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($completeds as $completed)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($completeds->currentPage()-1) * $completeds->perPage()) }}
                                            </td>
                                            <td>
                                                {{ $completed->product->title }}
                                            </td>
                                            <td>
                                                {{ $completed->rating }}
                                            </td>
                                            <td>{{ $completed->created_at->format('d F Y H:i') }}</td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('member.review.show', $completed->id) }}" title="{{ __('Show review detail') }}">
                                                            <i class="bi bi-eye size-22"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $completeds->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of history -->
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection
