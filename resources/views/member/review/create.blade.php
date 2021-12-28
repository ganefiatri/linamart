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
                        <h4 class="mb-2">{{ __('Create Review') }}</h4>
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
                    <div class="card-header color-dark fw-500">{{ __('Create Review') }}</div>
                    <div class="card-body p-3">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('member.review.store', $product->id) }}" >
                            @method('POST')
                            @csrf

                            <div class="row mb-3">
                                <div class="col-auto">
                                    <figure class="text-center">
                                        {{ $product->getDefaultImage(80, 80, ['class' => 'img-fluid'], true) }}
                                    </figure>
                                </div>
                                <div class="col ps-0 align-self-center">
                                    <h6 class="text-opac mb-0">{{ $product->title }}</h6>
                                </div>
                                @if ($order)
                                <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Rate') }} :</label>
                                    @php
                                        $rating = old('rating');
                                    @endphp
                                    <ul class="list-unstyled list-inline">
                                        @for ($i = 1; $i < 6; $i++)
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0);" id="{{ $i }}" class="text-muted star-rate">
                                                <i class="bi bi-star-fill size-22 @if($i <= $rating) text-warning @endif"></i>
                                            </a>
                                        </li>
                                        @endfor
                                    </ul>
                                    <input type="hidden" name="rating" value="{{ old('rating') }}" id="rating"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="content">{{ __('Comment') }} :</label>
                                    <textarea class="form-control" id="content" name="comment" placeholder="Bagaimana pendapat Anda tentang produk dan pelayanan penjual">{{ old('comment') }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Submit Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function() {
    $(".star-rate").click(function (){
        var id = parseInt($(this).attr("id"));
        $('#rating').val(id);
        $('.star-rate').each(function (){
            var _id = parseInt($(this).attr("id"));
            if (_id <= id) {
                $(this).find('i').addClass('text-warning');
            } else {
                $(this).find('i').removeClass('text-warning');
            }
        });
    });
});
</script>
@endsection