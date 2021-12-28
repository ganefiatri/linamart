@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <div class="main-container container">

        @include('layouts.partial._balance')
        
        <!-- search -->
        <div class="row mb-4">
            <div class="col">
                <form method="GET" action="{{ route('member.order.search') }}">
                <div class="form-floating">
                    <input type="text" name="q" class="form-control is-valid" id="search" placeholder="Search" value="{{ request('q') }}" autocomplete="off">
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

        <!-- Products -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Products') }}</h5>
            </div>
            <div class="col-auto">
                <a href="" class="link text-color-theme">View All <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>

        <div class="row">
            @php
                $carts = cart_items();
                $districts = get_districts();
            @endphp
            @foreach ($products as $i => $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card shadow-sm product mb-4">
                    <div class="card-body">
                        <figure class="text-center">
                            {{ $product->getDefaultImage(250, 250, ['class' => 'img-fluid', 'id' => 'figure-' . $product->id], true) }}
                        </figure>
                        <p class="mb-1">
                            <small class="text-bold">{{ $product->shop->name }}</small>
                        </p>
                        <p class="mb-1">
                            <small class="text-opac">
                                {{ $product->shop->address }}
                                @if (is_using_district())
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
                                    <button class="accordion-button btn-small px-0 py-1 collapsed" type="button" data-bs-toggle="collapse" 
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
                                    <i class="bi bi-star-fill size-10 @if($i <= (int) $rate['value']) text-warning @endif"></i>
                                </li>
                                @endfor
                                <li class="list-inline-item"><small>({{ (int)$rate['value'] }}/{{ $rate['count'] }} reviews)</small></li>
                            </ul>
                        @endif
                        <a href="{{ route('member.order.product', ['slug' => $product->slug]) }}" class="text-normal">
                            <h6 class="text-color-theme">{{ $product->title }}</h6>
                        </a>
                        <div class="row">
                            <div class="col">
                                <p class="mb-0">
                                    @if ($product->discount > 0)
                                        <small class="text-decoration-line-through">{{ $product->getFormatedPrice() }}</small><br/>
                                    @endif
                                    <span class="fw-bold fs-12">{{ $product->getFormatedNetPrice() }}</span><br><small class="text-opac">{{ $product->weight ?? 1 }} {{ $product->unit }}</small>
                                </p>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle shadow btn-gradient btn-add-cart"
                                    data-bs-toggle="modal" data-bs-target="#addproductcart" attr-id="{{ $i }}" 
                                    attr-qty="{{ $carts[$product->id]['qty'] ?? 0 }}"
                                    attr-href="{{ route('member.order.addtocart', ['product' => $product]) }}">
                                    <i class="bi bi-plus size-22"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>

</main>
<!-- Page ends-->

@include('layouts.partial._filter_product')

<!-- add cart modal -->
<div class="modal fade" id="addproductcart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content product border-0 shadow-sm">
            <figure class="text-center mb-0 px-5 py-3" id="modal-figure">
                <img src="https://via.placeholder.com/50.webp" alt="" class="mw-100">
            </figure>
            <div class="modal-body">
                <p class="mb-1">
                    <small class="text-opac" id="modal-shop-name">Fresh</small>
                    <!--<small class="float-end"><span class="text-opac">4.5</span> <i class="bi bi-star-fill text-warning"></i></small>-->
                </p>
                <a href="#" class="text-normal">
                    <h6 class="text-color-theme" id="modal-product-name">Red Apple</h6>
                </a>
                <div class="row">
                    <div class="col">
                        <p class="mb-0"><span id="modal-product-price"></span><br>
                            <small class="text-opac">per 1 <span id="modal-product-unit">kg</span></small>
                        </p>
                    </div>
                    <div class="col-auto">
                        <!-- button counter increamenter-->
                        <div class="counter-number" attr-href="">
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                attr-id="" onclick="addToCart(this, '-');">
                                <i class="bi bi-dash size-22"></i>
                            </button>
                            <span id="qty">0</span>
                            <button class="btn btn-sm avatar avatar-30 p-0 rounded-circle" 
                                attr-id="" onclick="addToCart(this, '+');">
                                <i class="bi bi-plus size-22"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link text-color-theme" data-bs-dismiss="modal">Tambahkan</button>
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
    var items = @json($products->toArray()['data']);
    $('.btn-add-cart').click(function () {
        var id = $(this).attr('attr-id');
        var href = $(this).attr('attr-href');
        var qty = $(this).attr('attr-qty');
        $('#modal-shop-name').html(items[id]['shop']['name']);
        $('#modal-product-name').html(items[id]['title']);
        var price = parseInt(items[id]['price']) - parseInt(items[id]['discount']);
        if (price > 0) {
            price = price.toLocaleString('id');
        }
        $('#modal-product-price').html(price);
        $('#modal-product-unit').html(items[id]['unit']);
        var img = $('#figure-' + items[id]['id']);
        if (img.length > 0) {
            var src = img.attr('src');
            console.log(src);
            $('#modal-figure').find('img').attr('src', src);
        }
        $('.counter-number').find('button').attr('attr-id', id);
        $('.counter-number').attr('attr-href', href);
        $('.counter-number').find('#qty').html(qty);
    });
});
</script>
@endsection