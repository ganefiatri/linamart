@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('public/vendor/swiperjs-6.6.2/swiper-bundle.min.css') }}">
@endsection

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <!-- main page content -->
    <div class="main-container container">

        @include('layouts.partial._balance')

        <!-- search -->
        <div class="row mb-4">
            <div class="col">
                <form method="GET" action="{{ route('member.order.search') }}">
                <div class="form-floating">
                    <input type="text" name="q" class="form-control is-valid" id="search" placeholder="Search" autocomplete="off">
                    <label for="search">{{ __('Search') }}</label>
                    <button type="submit" class="btn btn-link tooltip-btn d-block text-color-theme">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                </form>
            </div>
            <div class="col-auto">
                <button class="btn btn-lg btn-theme shadow-sm filter-btn">
                    <i class="bi bi-filter size-22"></i>
                </button>
            </div>
        </div>

        <!-- categories -->
        <div class="swiper-container categoriesswiper mb-3">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach ($categories as $category)
                <div class="swiper-slide">
                    <a href="{{ route('member.order.category', ['slug' => $category->slug]) }}">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                {{ $category->getImage(50, 50, ['class' => 'img-fluid'], true) }}
                            </div>
                        </div>
                        <p class="categoryname">{{ Str::words($category->title, 1, null) }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        @php
            $carts = cart_items();
            $districts = get_districts();
            $shop_open_info = [];
        @endphp
        @if ($latestProducts->count() > 0)
        <!-- trending items -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Latest Products') }}</h5>
            </div>
        </div>
        <!-- trending -->
        <div class="swiper-container trendingslides">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach ($latestProducts as $latestProduct)
                <div class="swiper-slide">
                    <div class="card shadow-sm product mb-4">
                        <figure class="text-center mb-0 bg-light-warning">
                            {{ $latestProduct->getDefaultImage(200, 200, ['class' => 'img-fluid'], true) }}
                        </figure>
                        <div class="card-body">
                            <p class="mb-1">
                                <small class="text-bold">{{ $latestProduct->shop->name }}</small>
                            </p>
                            <p class="mb-1">
                                <small class="text-opac">
                                    {{ $latestProduct->shop->address }}
                                    @if (is_using_district())
                                        @if ($districts)
                                            , {{ $districts[$latestProduct->shop->district_id] ?? '' }}
                                        @endif
                                    @else
                                         {{ get_district_value($latestProduct->shop) }}
                                    @endif
                                </small>
                            </p>

                            @php
                                $info = $shop_open_info[$latestProduct->shop->id] ?? shop_open_info($latestProduct->shop);
                            @endphp
                            @if (($info['is_open'] ?? false)  && (!empty($info['daily_open'] ?? null)))
                            <div class="accordion accordion-flush" id="accordionFlushExample-{{ $latestProduct->id }}">
                                <div class="accordion-item">
                                  <h2 class="accordion-header" id="flush-headingOne-{{ $latestProduct->id }}">
                                    <button class="accordion-button btn-small px-0 py-1 collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne-{{ $latestProduct->id }}" aria-expanded="false" aria-controls="flush-collapseOne-{{ $latestProduct->id }}">
                                        {{ $info['today'] }}
                                    </button>
                                  </h2>
                                  <div id="flush-collapseOne-{{ $latestProduct->id }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-{{ $latestProduct->id }}" data-bs-parent="#accordionFlushExample-{{ $latestProduct->id }}">
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
                            $rate = $latestProduct->rate();
                            @endphp
                            @if ($rate['value'] > 0)
                                <ul class="list-inline mt-0 mb-1">
                                    @for ($i = 1; $i < 6; $i++)
                                    <li class="list-inline-item" style="margin-right: 0px;">
                                        <i class="bi bi-star-fill size-10 @if($i <= (int) $rate['value']) text-warning @endif"></i>
                                    </li>
                                    @endfor
                                    <li class="list-inline-item"><small>({{ (int)$rate['value'] }}/{{ $rate['count'] }} reviews)</small></li>
                                </ul>
                            @endif
                            <a href="{{ route('member.order.product', ['slug' => $latestProduct->slug]) }}" class="text-normal">
                                <h6 class="text-color-theme">{{ $latestProduct->title }}</h6>
                            </a>
                            <div class="row">
                                <div class="col">
                                    <p class="mb-0">
                                        @if ($latestProduct->discount > 0)
                                            <small class="text-decoration-line-through">{{ $latestProduct->getFormatedPrice() }}</small><br/>
                                        @endif
                                            <span class="fw-bold fs-12" id="show1" style="display: none">{{ $latestProduct->getFormatedNetPrice() }}</span><br>
                                            <span class="fw-bold fs-12" id="show2" style="display: none">{{ $latestProduct->getFormatedNetPrice_second() }}</span><br>
                                            <span class="fw-bold fs-12" id="show3" style="display: none">{{ $latestProduct->getFormatedNetPrice_third() }}</span><br>
                                            <select class="form-select form-select-sm" style="width:auto;" id="myselection" aria-label="Default select example">
                                                <option value="1">{{ $latestProduct->weight ?? 1 }} {{ $latestProduct->unit }}</option>
                                                <option value="2">{{ $latestProduct->weight ?? 1 }} {{ $latestProduct->unit2 }}</option>
                                                <option value="3">{{ $latestProduct->weight ?? 1 }} {{ $latestProduct->unit3 }}</option>
                                            </select>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <!-- button counter increamenter-->
                                    <div class="counter-number" attr-href="{{ route('member.order.addtocart', ['product' => $latestProduct]) }}">
                                        <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle"
                                            attr-id="{{ $latestProduct->id }}" onclick="addToCart(this, '-');">
                                            <i class="bi bi-dash size-22"></i>
                                        </button>
                                        <span id="qty">{{ $carts[$latestProduct->id]['qty'] ?? 0 }}</span>
                                        <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle"
                                            attr-id="{{ $latestProduct->id }}" onclick="addToCart(this, '+');">
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

        @if ($bestProducts->count() > 0)
        <!-- trending items -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Favorite Products') }}</h5>
            </div>
        </div>
        <!-- trending -->
        <div class="swiper-container trendingslides">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach ($bestProducts as $bestProduct)
                <div class="swiper-slide">
                    <div class="card shadow-sm product mb-4">
                        <figure class="text-center mb-0 bg-light-warning">
                            {{ $bestProduct->getDefaultImage(200, 200, ['class' => 'img-fluid'], true) }}
                        </figure>
                        <div class="card-body">
                            <p class="mb-1">
                                <small class="text-bold">{{ $bestProduct->shop->name }}</small>
                            </p>
                            <p class="mb-1">
                                <small class="text-opac">
                                    {{ $bestProduct->shop->address }}
                                    @if (is_using_district())
                                        @if ($districts)
                                            , {{ $districts[$bestProduct->shop->district_id] ?? '' }}
                                        @endif
                                    @else
                                         {{ get_district_value($bestProduct->shop) }}
                                    @endif
                                </small>
                            </p>

                            @php
                                $info = $shop_open_info[$bestProduct->shop->id] ?? shop_open_info($bestProduct->shop);
                            @endphp
                            @if (($info['is_open'] ?? false) && (!empty($info['daily_open'] ?? null)))
                            <div class="accordion accordion-flush" id="accordionFlushExample-{{ $bestProduct->id }}">
                                <div class="accordion-item">
                                  <h2 class="accordion-header" id="flush-headingOne-{{ $bestProduct->id }}">
                                    <button class="accordion-button btn-small px-0 py-1 collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne-{{ $bestProduct->id }}" aria-expanded="false" aria-controls="flush-collapseOne-{{ $bestProduct->id }}">
                                        {{ $info['today'] }}
                                    </button>
                                  </h2>
                                  <div id="flush-collapseOne-{{ $bestProduct->id }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-{{ $bestProduct->id }}" data-bs-parent="#accordionFlushExample-{{ $latestProduct->id }}">
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
                            $rate = $bestProduct->rate();
                            @endphp
                            @if ($rate['value'] > 0)
                                <ul class="list-inline mt-0 mb-1">
                                    @for ($i = 1; $i < 6; $i++)
                                    <li class="list-inline-item" style="margin-right: 0px;">
                                        <i class="bi bi-star-fill size-10 @if($i <= (int) $rate['value']) text-warning @endif"></i>
                                    </li>
                                    @endfor
                                    <li class="list-inline-item"><small>({{ (int)$rate['value'] }}/{{ $rate['count'] }} reviews)</small></li>
                                </ul>
                            @endif
                            <a href="{{ route('member.order.product', ['slug' => $bestProduct->slug]) }}" class="text-normal">
                                <h6 class="text-color-theme">{{ $bestProduct->title }}</h6>
                            </a>
                            <div class="row">
                                <div class="col">
                                    <p class="mb-0">
                                        @if ($bestProduct->discount > 0)
                                            <small class="text-decoration-line-through">{{ $bestProduct->getFormatedPrice() }}</small><br/>
                                        @endif
                                        <span class="fw-bold fs-12">{{ $bestProduct->getFormatedNetPrice() }}</span><br><small class="text-opac">{{ $bestProduct->weight ?? 1 }} {{ $bestProduct->unit }}</small>
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <!-- button counter increamenter-->
                                    <div class="counter-number" attr-href="{{ route('member.order.addtocart', ['product' => $bestProduct]) }}">
                                        <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle"
                                            attr-id="{{ $bestProduct->id }}" onclick="addToCart(this, '-');">
                                            <i class="bi bi-dash size-22"></i>
                                        </button>
                                        <span id="qty">{{ $carts[$bestProduct->id]['qty'] ?? 0 }}</span>
                                        <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle"
                                            attr-id="{{ $bestProduct->id }}" onclick="addToCart(this, '+');">
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

        <!-- Near by shops -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('All Shop') }}</h5>
            </div>
        </div>
        <!-- shop slides -->
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container shopslides mb-4">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        @foreach ($shops as $shop)
                        <div class="swiper-slide">
                            <div class="card shadow-sm ">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto">
                                            <figure class="text-center mb-0 avatar avatar-60 page-bg rounded">
                                                <i class="bi bi-shop size-24 text-color-theme"></i>
                                            </figure>
                                        </div>
                                        <div class="col">
                                            <a href="{{ route('member.order.shop', ['slug' => $shop->slug]) }}" class="text-normal text-color-theme">
                                                <h6 class="mb-1">{{ Str::words($shop->name, 1, null) }} <i class="bi bi-arrow-up-right-circle text-color-theme float-end"></i></h6>
                                            </a>
                                            <p class="mb-1">{{ ($shop->district_id > 0)? $shop->district->city->name : $shop->district_name }}</p>
                                            <p class="small d-none">
                                                <span class="text-opac">08:00 - 20:00</span>
                                                <span class="float-end">
                                                    <span class="text-opac">2.5km</span> <i class="bi bi-geo-alt"></i>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main page content ends -->

</main>
<!-- Page ends-->

@include('layouts.partial._filter_product')

<!-- add cart modal -->
<div class="modal fade" id="addproductcart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content product border-0 shadow-sm">
            <figure class="text-center mb-0 px-5 py-3">
                <img src="https://via.placeholder.com/50.webp" alt="" class="mw-100">
            </figure>
            <div class="modal-body">
                <p class="mb-1">
                    <small class="text-opac">Fresh</small>
                    <!--<small class="float-end"><span class="text-opac">4.5</span> <i class="bi bi-star-fill text-warning"></i></small>-->
                </p>
                <a href="product.html" class="text-normal">
                    <h6 class="text-color-theme">Red Apple</h6>
                </a>
                <div class="row">
                    <div class="col">
                        <p class="mb-0">$12.00<br><small class="text-opac">per 1 kg</small></p>
                    </div>
                    <div class="col-auto">
                        <!-- button counter increamenter-->
                        <div class="counter-number">
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle">
                                <i class="bi bi-dash size-22"></i>
                            </button>
                            <span>1</span>
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle">
                                <i class="bi bi-plus size-22"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link text-color-theme" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>
<!-- add cart modal ends -->

@endsection

@section('js')
<script src="{{ mix('js/cart.min.js') }}"></script>
<script type="text/javascript">
$(window).on('load', function () {
    if ($('.categoriesswiper').length > 0) {
        var swiper1 = new Swiper(".categoriesswiper", {
            slidesPerView: "auto",
            spaceBetween: 12,
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

<script type="text/javascript">
    $(document).ready(function(){
        $("#myselection").change(function(){
            $(this).find("option:selected").each(function(){
                var val = $(this).attr("value");
                if(val){
                    $("#show").not("#show" + val).hide();
                    $("#show" + val).show();
                } else{
                    $("#show").hide();
                }
            });
        }).change();
    });
</script>
@endsection
