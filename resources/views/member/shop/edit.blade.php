@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery.timepicker.min.css') }}">
@endsection

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
                        <h4 class="mb-2">{{ __('Update Shop') }} #{{ $shop->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('shops.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-eye"></i> {{ __('View Shop') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Update Shop') }}</div>
                    <div class="card-body p-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('shops.update', $shop->id) }}" >
                            @method('PATCH')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Masukkan nama toko" 
                                        onchange="setTheSlug(this)" name="name" value="{{ $shop->name }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="slug">Slug :</label>
                                    <input type="text" class="form-control" id="slug" placeholder="Masukkan slug, contoh: the-product-name" name="slug" value="{{ $shop->slug }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="phone">{{ __('Phone') }} :</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Masukkan nomor telepon" name="phone" value="{{ $shop->phone }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="district">{{ __('District') }} :</label>
                                    @if(is_using_district())
                                    <input type="text" name="district_name" class="form-control autocomplete" id="district" 
                                        onclick="this.select();" placeholder="Kecamatan" value="{{ $districts[$shop->district_id] }}" required>
                                    @else
                                    <input type="text" name="district_name" class="form-control" id="district" 
                                    placeholder="Kecamatan" value="{{ get_district_value($shop) }}" required>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="address">{{ __('Address') }} :</label>
                                    <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat">{{ $shop->address }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="postal-code">{{ __('Postal Code') }} :</label>
                                    <input type="text" class="form-control" id="postal-code" placeholder="Masukkan kode pos" name="postal_code" value="{{ $shop->postal_code }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="gmap">{{ __('Link Google Map') }} :</label>
                                    <input type="text" class="form-control" id="gmap" placeholder="Link Google Map (direkomendasikan" 
                                        name="gmap" value="{{ ($shop->meta) ? ($shop->meta['gmap'] ?? '') : '' }}">
                                </div>
                            </div>
                            @if ($shop->products()->count() > 0)
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="postal-code">{{ __('Shop Status') }} :</label>
                                    <select name="status" class="form-control">
                                        <option value="0" @if($shop->status == 0) selected="selected" @endif>{{ __('Close') }}</option>
                                        <option value="1" @if($shop->status == 1) selected="selected" @endif>{{ __('Open') }}</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="postal-code">{{ __('Daily Open') }} :</label>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Choose') }}</th>
                                                    <td>{{ __('Day') }}</td>
                                                    <td>{{ __('Open At') }}</td>
                                                    <td>{{ __('Closed At') }}</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $daily_open = $shop->meta['daily_open'] ?? [];
                                                @endphp
                                                @for ($i = 0; $i < 7; $i++)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="daily_open[{{ $i }}]" class="mr-2" 
                                                            onclick="choose(this)" attr-id="{{ $i }}" @if(array_key_exists($i, $daily_open)) checked="checked" @endif/>
                                                    </td>
                                                    <td>{{ trans(jddayofweek($i, 1)) }}</td>
                                                    <td>
                                                        <div class="md-form">
                                                            @php
                                                                $open_from[$i] = $daily_open[$i]['open'] ?? '';
                                                            @endphp
                                                            <input placeholder="{{ __('From') }}" type="text" name="open_from[{{ $i }}]" id="input-from-{{ $i }}" 
                                                                class="form-control timepicker" value="{{ $open_from[$i] }}" @if(empty($open_from[$i])) readonly @endif/>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="md-form">
                                                            @php
                                                                $open_to[$i] = $daily_open[$i]['closed'] ?? '';
                                                            @endphp
                                                            <input placeholder="{{ __('To') }}" type="text" name="open_to[{{ $i }}]" id="input-to-{{ $i }}" 
                                                                class="form-control timepicker" value="{{ $open_to[$i] }}" @if(empty($open_to[$i])) readonly @endif/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="district_id" id="district-id" value="{{ $shop->district_id }}"/>
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Update Now') }}</button>
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
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
<script type="text/javascript">
    function setTheSlug(dt){
        var str = $(dt).val();
        if (str.length > 0) {
            var slug = str.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
            $('#slug').val(slug);
        } else {
            $('#slug').val('');
        }
    }

    $(document).ready(function() {
        //var district_names = @json(array_values($districts));
        var district_ids = @json(array_flip($districts));
        $(".autocomplete").autocomplete({
            //source: district_names,
            source: function( request, response ) {
                $.ajax({
                    url: "{{ route('districts') }}",
                    dataType: "json",
                    method: "post",
                    data: {
                        q: request.term,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 3,
            select: function (event, ui) {
                var dist_id = district_ids[ui.item.label];
                if (parseInt(dist_id) > 0) {
                    $('#district-id').val(dist_id);
                }
            }
        });

        $('.timepicker').timepicker({
            'timeFormat': 'H:i',
            'step': 30
        }); 
    });

    function choose(dt) {
        var id = $(dt).attr('attr-id');
        if (dt.checked) {
            $('#input-from-' + id).removeAttr('readonly').attr('required', 'required');
            $('#input-to-' + id).removeAttr('readonly').attr('required', 'required');
        } else {
            $('#input-from-' + id).attr('readonly', 'readonly').removeAttr('required');
            $('#input-to-' + id).attr('readonly', 'readonly').removeAttr('required');
        }
    }
</script>
@endsection