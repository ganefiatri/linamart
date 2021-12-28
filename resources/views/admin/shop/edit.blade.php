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
                        <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Shops') }}
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Update Shop') }}</div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.shops.update', $shop->id) }}" >
                            @method('PATCH')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter shop name" name="name" value="{{ $shop->name }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="phone">Phone/Wa :</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Enter phone" name="phone" value="{{ $shop->phone }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="district_name">Kecamatan, Kabupaten, Propinsi</label>
                                    @if (is_using_district())
                                        <input type="text" name="district_name" class="form-control autocomplete  @error('district_name') is-invalid @enderror" id="district_name" 
                                            onclick="this.select();" placeholder="Kecamatan" value="{{ $districts[$shop->district_id] ?? '' }}" required>
                                    @else
                                        <input type="text" name="district_name" class="form-control  @error('district_name') is-invalid @enderror" id="district_name" 
                                            placeholder="Kecamatan" value="{{ get_district_value($shop) }}" autocomplete="off" required>
                                    @endif
                                    @error('district_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="address">{{ __('Address') }} :</label>
                                    <textarea class="form-control" id="address" name="address" placeholder="Enter full address">{{ $shop->address }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="postal_code">Kode Pos :</label>
                                    <input type="text" class="form-control" id="postal_code" placeholder="Enter postal code" name="postal_code" value="{{ $shop->postal_code }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-4">
                                    <label for="status">{{ __('Status') }} :</label>
                                    <select name="status" class="form-control">
                                        @foreach ($statusList as $status_code => $status_name)
                                            <option value="{{ $status_code }}" @if ($status_code == $shop->status) selected @endif>{{ $status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                            <input type="hidden" name="district_id" id="district-id" value="{{ $shop->district_id }}"/>
                            <div class="row">
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