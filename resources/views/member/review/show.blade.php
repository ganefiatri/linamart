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
                        <h4 class="mb-2">{{ __('Review Detail') }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('member.review.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Review') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Review Detail') }}</div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                @if ($review->order)
                                <tr>
                                    <td>{{ __('No Order') }}</td>
                                    <td>{{ $review->order->invoice->getInvoiceNumber() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Product Name') }}</td>
                                    <td>{{ $review->product->title }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Rating') }}</td>
                                    <td>
                                        @for ($i = 1; $i < 6; $i++)
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0);" id="{{ $i }}" class="text-muted star-rate">
                                                <i class="bi bi-star-fill size-22 @if($i <=  $review->rating) text-warning @endif"></i>
                                            </a>
                                        </li>
                                        @endfor
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="2">{{ __('Comment') }}</td>
                                </tr>
                                <tr class="chat-list">
                                    <td colspan="2" class="left-chat">
                                        <div class="chat-block"> 
                                            {!! $review->comment !!}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $review->created_at->format('d F Y H:i') !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if ($is_owner)
                        <form method="POST" action="{{ route('member.review.update', $review) }}">
                            @csrf
                            @method('PATCH')
                            @if ($review->status > 0)
                                <input type="hidden" name="status" value="0"/>
                                <button type="submit" class="btn btn-warning">{{ __('Disable Review') }}</button>
                            @else
                                <input type="hidden" name="status" value="0"/>
                                <button type="submit" class="btn btn-info">{{ __('Enable Review') }}</button>
                            @endif
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection