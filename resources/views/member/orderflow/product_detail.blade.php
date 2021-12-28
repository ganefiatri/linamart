@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/swiperjs-6.6.2/swiper-bundle.min.css') }}">
@endsection

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <!-- main page content -->
    <div class="main-container container"> 
        @include('layouts.partial._balance')
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body pb-0 position-relative">
                        <div class="position-absolute top-0 end-0 m-3 z-index-9 d-none">
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle btn-outline-info me-2"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-share"></i>
                            </button>
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle btn-outline-danger">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                        <div class="swiper-container imageswiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                @if ($product->images()->count() > 0)
                                    @foreach ($product->images as $image)
                                    <div class="swiper-slide text-center mb-0">
                                        {{ $image->getThumbnail(480, null, ['class' => 'h-190 mb-2']) }}
                                    </div>
                                    @endforeach
                                @else
                                <div class="swiper-slide text-center mb-0">
                                    <img src="https://via.placeholder.com/200.webp" alt="" class="h-190 mb-2">
                                </div>
                                @endif
                            </div>
                            <div class="swiper-pagination imageswiper-pagination "></div>
                        </div>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <div class="card-footer justify-content-center text-center">
                            <p class="mb-1 text-opac">Share product with</p>
                            <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="btn btn-link text-color-theme"><i class="bi bi-google"></i></a>
                        </div>
                    </div>
                </div>
                <p class="mb-1">
                    <span class="text-bold">{{ $product->shop->name }}</span>
                </p>
                <p class="mb-1">
                    <small class="text-opac">
                        {{ $product->shop->address }}
                        @if (is_using_district())
                            @php
                                $districts = get_districts();
                            @endphp
                            @if ($districts)
                                , {{ $districts[$product->shop->district_id] ?? '' }}
                            @endif
                        @else
                            {{ get_district_value($product->shop) }}
                        @endif
                    </small>
                </p>

                @php
                    $info = shop_open_info($product->shop);
                @endphp
                @if (($info['is_open'] ?? false)  && (!empty($info['daily_open'] ?? null))) 
                    <div class="accordion accordion-flush" id="accordionFlushExample-{{ $product->id }}">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne-{{ $product->id }}">
                            <button class="accordion-button btn-small px-1 py-1 collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#flush-collapseOne-{{ $product->id }}" aria-expanded="false" aria-controls="flush-collapseOne-{{ $product->id }}">
                                {{ $info['today'] }}
                            </button>
                            </h2>
                            <div id="flush-collapseOne-{{ $product->id }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-{{ $product->id }}" data-bs-parent="#accordionFlushExample-{{ $product->id }}">
                                <div class="accordion-body">
                                    <table>
                                    @foreach (($info['daily_open'] ?? []) as $d => $daily_open)
                                        <tr>
                                            <td><small class="text-opac">{{ trans($daily_open['day_name']) }}</small></td>
                                            <td><small class="text-opac">{{ $daily_open['open'] }} - {{ $daily_open['closed'] }}</small></td>
                                        </tr>
                                    @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @php
                    $rate = $product->rate();
                @endphp
                @if ($rate['value'] > 0)
                    <ul class="list-inline mt-0 mb-1">
                        @for ($i = 1; $i < 6; $i++)
                        <li class="list-inline-item" style="margin-right: 0px;">
                            <i class="bi bi-star-fill size-10 @if($i <= (int)$rate['value']) text-warning @endif"></i>
                        </li>
                        @endfor
                        <li class="list-inline-item"><small>({{ (int)$rate['value'] }}/{{ $rate['count'] }} reviews)</small></li>
                    </ul>
                @endif
                <h4 class="text-color-theme mb-3">{{ $product->title }}</h4>
                <div class="row mb-4">
                    <div class="col">
                        <h5 class="mb-0">{{ $product->getFormatedNetPrice() }} 
                            @if ($product->discount > 0)
                            <s class="text-opac fw-light">{{ $product->getFormatedPrice() }}</s>
                            @endif
                        </h5>
                        <p class="text-opac">per 1 {{ $product->unit }}</p>
                    </div>
                    @php
                        $carts = cart_items();
                    @endphp
                    <div class="col-auto align-self-center">
                        <!-- button counter increamenter-->
                        <div class="counter-number" attr-href="{{ route('member.order.addtocart', ['product' => $product]) }}">
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                attr-id="{{ $product->id }}" onclick="addToCart(this, '-');">
                                <i class="bi bi-dash size-22"></i>
                            </button>
                            <span id="qty">{{ $carts[$product->id]['qty'] ?? 0 }}</span>
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                attr-id="{{ $product->id }}" onclick="addToCart(this, '+');">
                                <i class="bi bi-plus size-22"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--
        <!-- delivery time -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <figure class="text-center mb-0 avatar avatar-50 page-bg rounded">
                                    <i class="bi bi-clock size-24 text-color-theme"></i>
                                </figure>
                            </div>
                            <div class="col align-self-center">
                                <h6 class="mb-1">San Jose, USA
                                    <span class="text-color-theme float-end small">Change <i class="bi bi-chevron-right"></i></span>
                                </h6>
                                <p><span class="text-opac">Delivery on:</span> <strong>7 Dec 2021</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        --}}

        <!-- description -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Product Detail') }}</h5>
            </div>
        </div>

        <!-- description of vitamins -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <p class="text-opac">{{ $product->description }}.</p>
                    </div>
                </div>
                {{-- <p class="text-opac small">** Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque
                    sollicitudin dignissim nisi, eget malesuada ligula ultricies sit amet. Suspendisse
                    efficitur ex eu est placerat mattis.</p> --}}
            </div>
        </div>

        @if ($otherProducts->count() > 0)
        <!-- Related Items -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Other Products') }}</h5>
            </div>
        </div>
        <!-- trending -->
        <div class="swiper-container trendingslides">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach ($otherProducts as $other)
                <div class="swiper-slide">
                    <div class="card shadow-sm product mb-4">
                        <figure class="text-center mb-0 bg-light-warning">
                            {{ $other->getDefaultImage(200, 200, ['class' => 'img-fluid'], true) }}
                        </figure>
                        <div class="card-body">
                            <p class="mb-1">
                                <small class="text-opac">{{ $other->category->title }}</small>
                                @php
                                    $otherRate = $other->rate();
                                @endphp
                                @if ($otherRate['value'] > 0)
                                <small class="float-end"><span class="text-opac">{{ $otherRate['value'] }}</span> <i class="bi bi-star-fill text-warning"></i></small>
                                @endif
                            </p>
                            <a href="{{ route('member.order.product', ['slug' => $other->slug]) }}" class="text-normal">
                                <h6 class="text-color-theme">{{ $other->title }}</h6>
                            </a>
                            <div class="row">
                                <div class="col">
                                    <p class="mb-0">
                                        @if ($other->discount > 0)
                                            <small class="text-decoration-line-through">{{ $other->getFormatedPrice() }}</small><br/>
                                        @endif
                                        <span class="fw-bold fs-12">{{ $other->getFormatedNetPrice() }}</span><br><small class="text-opac">per 1 {{ $other->unit }}</small>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <!-- button counter increamenter-->
                                    <div class="counter-number" attr-href="{{ route('member.order.addtocart', ['product' => $other]) }}">
                                        <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                            attr-id="{{ $other->id }}" onclick="addToCart(this, '-');">
                                            <i class="bi bi-dash size-22"></i>
                                        </button>
                                        <span id="qty">{{ $carts[$other->id]['qty'] ?? 0 }}</span>
                                        <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                            attr-id="{{ $other->id }}" onclick="addToCart(this, '+');">
                                            <i class="bi bi-plus size-22"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                @endforeach
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
$(window).on('load', function () {
    if ($('.imageswiper').length > 0) {
        var swiper5 = new Swiper(".imageswiper", {
            slidesPerView: "1",
            spaceBetween: 12,
            pagination: {
                el: ".imageswiper-pagination",
            },
        });
    }

    if ($('.trendingslides').length > 0) {
        var swiper3 = new Swiper(".trendingslides", {
            slidesPerView: "auto",
            spaceBetween: 26,
        });
    }

    if ($('.shopslides').length > 0) {
        var swiper4 = new Swiper(".shopslides", {
            slidesPerView: "auto",
            spaceBetween: 0,
        });
    }
});
</script>
@endsection