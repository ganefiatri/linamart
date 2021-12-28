@extends('layouts.fe')

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @include('layouts.partial._header')

    <!-- main page content -->
    <div class="main-container container top-20 pt-3">

        @include('layouts.partial._message')

        <div class="row mt-4">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-wrap justify-content-center mb-3">
                    <div class="d-flex align-items-center">
                        <h4 class="mb-2">{{ __('Detail Order') }} #{{ $order->getInvoiceNumber() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if ($order->orders)
            <div class="row mb-3 d-flex justify-content-center mt-4">
                @php
                    $is_pending_order = ($order->orderProcesses->count() == 0);
                @endphp
                @if ($order->status == -1)
                    <div class="col-12 col-md-10 col-lg-10">
                        <div class="alert alert-warning my-3">
                            <h5>Status : {{ (!empty($order->lastOrderProcess)) ? $order->lastOrderProcess->getStatus() : __('Pending') }}</h5>
                            @if (!empty($order->notes))
                                Alasan : <i>{{ $order->notes }}</i>
                            @endif
                        </div>
                    </div>
                @endif
                <div class="@if ($is_pending_order) col-5 @else col-12 col-md-10 col-lg-10 @endif">
                    @if ($order->shipping_id > 0 || $order->shipping_fee > 0)
                    <h5 class="mb-0">{{ __('Shipped from shop') }} :</h5>
                    @else
                    <h5 class="mb-0">{{ __('Pickup from shop') }} :</h5>
                    @endif
                </div>
                @if ($is_pending_order)
                    <div class="col-5">
                        <button type="button" class="btn btn-danger text-white float-end" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            {{ __('Cancel Order') }}
                        </button>
                    </div>
                @endif
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    <ul class="list-unstyled mt-0">
                                        <li><b>{{ __('Order Date') }}</b> : {{ $order->created_at->format('d M Y H:i') }}</li>
                                        @php
                                            $processDates = $order->getProcessDate() ?? [];
                                        @endphp
                                        @foreach ($processDates as $id_status => $processDate)
                                        <li><b>{{ __('Date') }} {{ $orderStatusList[$id_status] ?? '' }}</b> : {{ $processDate->format('d M Y H:i') }}</li>
                                        @endforeach
                                    </ul>
                                    <hr/>
                                    @php
                                        $shop = $order->shop;
                                    @endphp
                                    <p><b>{{ __('Shop Name') }} :</b> <a href="{{ route('admin.shops.show', $shop->id) }}" target="_newtab" title="{{ __('Show Shop Detail') }}">{{ $order->seller_name }}</a>
                                        <br/><b>{{ __('Seller Address') }} :</b></p>
                                    <p>{{ $order->seller_address }}</p>
                                    @if (!empty($shop))
                                        @if ($shop->district_id > 0 && is_using_district())
                                            <p>{{ $shop->district->name }}, {{ $shop->district->city->name }}, {{ $shop->district->city->province->name }}</p>
                                        @else
                                            <p>{{ get_district_value($shop) }}</p>  
                                        @endif
                                    <p><a href="{{ route('admin.shops.show', $shop->id) }}" target="_newtab" title="{{ __('Show Shop Detail') }}">{{ __('Show Shop Detail') }}</a></p>
                                    <a href="{{ wa_url($order->seller_phone) }}" title="{{ __('Chat Seller') }}" target="_blank" class="btn btn-outline-success mr-1">
                                        <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Seller') }}
                                    </a>
                                    <a href="{{ phone_url($order->seller_phone) }}" title="{{ __('Call Seller') }}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="bi bi-phone size-22"></i> {{ __('Call Seller') }}
                                    </a>
                                    @endif
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-geo-alt text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                        @php
                            $notes = $order->meta['notes'] ?? null;
                        @endphp
                        @if (!empty($notes))
                        <div class="card-footer">
                            <b>Catatan Pembeli :</b>
                            <div class="alert alert-info mt-2">{{ $notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>


            @php
                $courier = $order->getCourier();
                $driver_items = [];
            @endphp
            @if ($order->shipping_id > 0 || $order->shipping_fee > 0)
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Sent To') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @php
                                        $member = $order->member;
                                    @endphp
                                    <p><b>{{ __('Buyer Name') }} : </b> <a href="{{ route('admin.members.show', $member->id) }}" title="{{ __('Show Buyer Detail') }}">{{ $order->buyer_name }}</a>
                                        <br/><b>{{ __('Buyer Address') }} : </b></p>
                                    <p>{{ $order->buyer_address }}</p>
                                    @if (!empty($member))
                                    @if (!empty($member->district))
                                        <p>{{ $member->district->name }}, {{ $member->district->city->name }}, {{ $member->district->city->province->name }}</p>
                                    @else
                                        <p>{{ $member->district_name ?? '' }}</p>
                                    @endif
                                    <p><a href="{{ route('admin.members.show', $member->id) }}" title="{{ __('Show Buyer Detail') }}">{{ __('Show Buyer Detail') }}</a></p>
                                    <p class="mb-3">
                                        <a href="{{ wa_url($order->buyer_phone) }}" title="{{ __('Chat Buyer') }}" target="_blank" class="btn btn-outline-success mr-1">
                                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Buyer') }}
                                        </a>
                                        <a href="{{ phone_url($order->buyer_phone) }}" title="{{ __('Call Buyer') }}" target="_blank" class="btn btn-outline-secondary">
                                            <i class="bi bi-phone size-22"></i> {{ __('Call Buyer') }}
                                        </a>
                                    </p>
                                    @endif

                                    @if ($courier !== null)
                                        <p>
                                            <a href="{{ route('admin.orders.show', $order->id) }}">{{ __('Driver') }}</a> - {{ $courier->name }}
                                        </p>
                                        <a href="{{ wa_url($courier->phone) }}" title="{{ __('Chat Driver') }}" target="_blank" class="btn btn-outline-success mr-1">
                                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Driver') }}
                                        </a>  
                                        <a href="{{ phone_url($courier->phone) }}" title="{{ __('Call Driver') }}" target="_blank" class="btn btn-outline-secondary">
                                            <i class="bi bi-phone size-22"></i> {{ __('Call Driver') }}
                                        </a>
                                    @else
                                        @if ($order->shipping_id > 0)
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <a href="{{ route('admin.orders.show', $order->id) }}">{{ __('Driver') }}</a>
                                                </span>
                                                <select name="driver_id" class="form-control" onchange="return chooseDriver(this);" attr-id="{{ $order->id }}">
                                                    <option value="">- {{ __('Choose Driver') }}</option>
                                                    @foreach ($drivers as $driver)
                                                        @php
                                                            $driver_items[$driver->id] = array_merge($driver->toArray(), ['wa_url' => wa_url($driver->phone)]);
                                                        @endphp
                                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @else
                                            <a href="{{ route('admin.orders.show', $order->id) }}">{{ __('Driver') }}</a> - {{ __('Self Pickup') }}
                                        @endif
                                    @endif
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-geo-alt text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                        @php
                            $driverNotes = $order->meta['driver_notes'] ?? null;
                        @endphp
                        <div class="card-footer">
                            <b>Catatan Untuk Driver :</b>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('admin.orders.setdrivernotes', $order->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <div class="form-group form-floating">
                                    <textarea name="driver_notes" id="driver-notes" class="form-control" placeholder="Tambahkan catatan jika diperlukan">{{ $driverNotes ?? '' }}</textarea>
                                    <label class="form-control-label" for="order-notes">Tambahkan catatan untuk driver (Optional)</label>
                                </div>
                                <div class="form-group mt-2">
                                    <input type="submit" class="btn btn-warning text-white" value="{{ (!empty($driverNotes)) ? 'Ubah Catatan':'Buat Catatan' }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Shipping Cost') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @foreach ($shippings as $shipping)
                                    <div class="form-check">
                                        <input class="form-check-input ship-cost" type="radio" name="shipping"  
                                            @if ($order->shipping_id == $shipping->id) checked @endif disabled>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            {{ $shipping->title }} ({{ $shipping->distance_from }} - {{ $shipping->distance_to }} = {{ $shipping->getFormatedCost() }})
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-auto align-self-center">
                                    <i class="bi bi-cash-stack text-color-theme"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Buyer Information') }} :</h5>
                </div>
            </div>

            <div class="row mb-2 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <div class="card shadow-sm mb-3 product text-normal">
                        <div class="card-body">
                            <div class="row">
                                <div class="col align-self-center">
                                    @php
                                        $member = $order->member;
                                    @endphp
                                    <p><b>{{ __('Buyer Name') }} : </b> <a href="{{ route('admin.members.show', $member->id) }}" title="{{ __('Show Buyer Detail') }}">{{ $order->buyer_name }}</a>
                                        <br/><b>{{ __('Buyer Address') }} : </b></p>
                                    <p>{{ $order->buyer_address }}</p>
                                    @if (!empty($member))
                                        @if (!empty($member->district) && is_using_district())
                                            <p>{{ $member->district->name }}, {{ $member->district->city->name }}, {{ $member->district->city->province->name }}</p>
                                        @else
                                            <p>{{ get_district_value($member) }}</p>
                                        @endif
                                    <p><a href="{{ route('admin.members.show', $member->id) }}" title="{{ __('Show Buyer Detail') }}">{{ __('Show Buyer Detail') }}</a></p>
                                    <p class="mb-3">
                                        <a href="{{ wa_url($order->buyer_phone) }}" title="{{ __('Chat Buyer') }}" target="_blank" class="btn btn-outline-success mr-1">
                                            <i class="bi bi-whatsapp size-22"></i> {{ __('Chat Buyer') }}
                                        </a>
                                        <a href="{{ phone_url($order->buyer_phone) }}" title="{{ __('Call Buyer') }}" target="_blank" class="btn btn-outline-secondary">
                                            <i class="bi bi-phone size-22"></i> {{ __('Call Buyer') }}
                                        </a>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- pricing -->
            <div class="row mb-3 d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    <h5 class="mb-0">{{ __('Order Items') }} :</h5>
                </div>
            </div>
            
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10 col-lg-10">
                    @php
                        $tot_price = 0;
                        $tot_discount = 0;
                    @endphp
                    @foreach ($order->orders as $item)
                        <div class="row mb-2">
                            <div class="col">
                                <b>{{ $item->title }}</b> x {{ $item->quantity }}
                                <p class="px-2">{{ to_money_format($item->price) }}</p>
                            </div>
                            <div class="col-auto">
                                {{ to_money_format($item->price * $item->quantity) }}
                            </div>
                        </div>
                        @php
                            $tot_price += $item->price * $item->quantity;
                            $tot_discount += $item->discount * $item->quantity;
                        @endphp
                    @endforeach
                    @php
                        $sub_total = $tot_price - $tot_discount;
                    @endphp
                    <div class="row mt-4 mb-3">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>{{ __('Total Price') }}</p>
                        </div>
                        <div class="col-auto cart-total-price">{{ to_money_format($tot_price) }}</div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col">
                            <p>{{ __('Total Discount') }}</p>
                        </div>
                        <div class="col-auto">{{ to_money_format($tot_discount) }}</div>
                    </div>
        
                    <div class="row fw-bold mb-4">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>Sub Total</p>
                        </div>
                        <div class="col-auto">{{ to_money_format($sub_total) }}</div>
                    </div>
        
                    <div class="row mb-3">
                        <div class="col">
                            <p>{{ __('Shipping Fee') }}</p>
                        </div>
                        <div class="col-auto cart-shipping-fee">{{ to_money_format($order->shipping_fee) }}</div>
                    </div>
        
                    <div class="row fw-bold mb-4">
                        <div class="mb-3 col-12">
                            <div class="dashed-line"></div>
                        </div>
                        <div class="col">
                            <p>{{ __('Total Payment') }}</p>
                        </div>
                        @php
                            $tot_payment = $sub_total + $order->shipping_fee;
                        @endphp
                        <div class="col-auto cart-total">{{ to_money_format($tot_payment) }}</div>
                    </div>
                </div>
            </div>
        @else
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm product mb-3">
                    <div class="card-body">
                        <div class="alert alert-warning mt-3">
                            {{ __('No data found') }}.
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
<button id="open-driver-modal" class="d-none" data-bs-toggle="modal" data-bs-target="#driver-modal">Open Modal</button>
<div class="modal fade" id="driver-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content product border-0 shadow-sm">
            <div class="modal-body">
                <div class="alert alert-warning">
                    <p>Apakah Anda yakin ingin gunakan driver <span id="driver-name" class="text-bold"></span>? Mohon hubungi driver dahulu</p>
                </div>
                <p>
                    <center>
                    <a href="#" id="driver-wa" class="btn btn-outline-success" target="_blank"><i class="bi bi-whatsapp size-22"></i> Chat Driver</a>
                    </center>
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger text-white" id="btn-close-modal" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default" id="set-driver-button" driver-id="" invoice-id="" onclick="return submitDriver(this);">Ya, Saya Yakin</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">{{ __('Cancel Order') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}">
            @csrf
            @method('PATCH')
        <div class="modal-body">
            <div class="alert alert-warning">
                {{ __('Are you sure you want to cancel this order?') }}
            </div>
            <div class="mb-3">
              <label class="col-form-label">{{ __('Reason') }}:</label>
              <textarea name="reason" class="form-control" placeholder="{{ __('Please type the reason why') }}" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">{{ __('Cancel Now') }}</button>
        </div>
        </form>
      </div>
    </div>
</div>
@endsection

@section('js')
    <script type="text/javascript">
    function chooseDriver(dt)
    {
        var driver_id = $(dt).val();
        if (driver_id > 0) {
            $('#set-driver-button').attr('driver-id', driver_id);
            var invoice_id = $(dt).attr('attr-id');
            $('#set-driver-button').attr('invoice-id', invoice_id);
            var driver_ids = @json($driver_items);
            $('#driver-name').html(driver_ids[driver_id]['name']);
            $('#driver-wa').attr("href", driver_ids[driver_id]['wa_url']);
            $('#open-driver-modal').click();
        }

        return false;
    }

    function submitDriver(dt) {
        var driver_id = $(dt).attr('driver-id');
        var invoice_id = $(dt).attr('invoice-id');
        if (driver_id > 0 && invoice_id > 0) {
            $.ajax({
                method: "POST",
                url: "{{ route('admin.orders.setdriver') }}",
                data: { 
                    driver_id: driver_id,
                    invoice_id: invoice_id,
                    _token: $('meta[name="csrf-token"]').attr('content') 
                }
            }).done(function (msg) {
                $('#btn-close-modal').click();
                var _toast = $('#toastprouctaddedtiny');
                if (msg.success) {
                    _toast.removeClass('bg-warning').addClass('bg-success');
                } else {
                    _toast.addClass('bg-warning').removeClass('bg-success');
                }
                _toast.find('#toast-msg').html(msg.message);
                _toast.toast('show');
                setTimeout(function () {
                    window.location.href = "{{ route('admin.orders.index') }}";
                }, 2000);
            });
        }
        return false;
    }
    </script>
@endsection