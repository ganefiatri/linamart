@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
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
                        <h4 class="mb-2">{{ __('Create New Shop') }}</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Create Shop') }}</div>
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
                        <form method="POST" action="{{ route('shops.store') }}" >
                            @method('POST')
                            @csrf
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="name">{{ __('Name') }} :</label>
                                    <input type="text" class="form-control" id="name" placeholder="Masukkan nama toko" 
                                        name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="phone">{{ __('Phone') }} :</label>
                                    <input type="text" class="form-control" id="phone" placeholder="Masukkan nomor telepon" name="phone" value="{{ old('phone') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="district">{{ __('District') }} :</label>
                                    @if(is_using_district())
                                    <input type="text" name="district_name" class="form-control autocomplete" id="district" 
                                        onclick="this.select();" placeholder="Kecamatan" value="{{ old('district_name') }}" required>
                                    @else 
                                    <input type="text" name="district_name" class="form-control" id="district" 
                                        placeholder="Kecamatan" value="{{ old('district_name') }}" autocomplete="off" required>
                                    @endif
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="postal-code">{{ __('Postal Code') }} :</label>
                                    <input type="text" class="form-control" id="postal-code" placeholder="Masukkan kode pos" name="postal_code" value="{{ old('postal_code') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-12">
                                    <label for="address">{{ __('Address') }} :</label>
                                    <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat">{{ old('address') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="form-group col-sm-6">
                                    <label for="gmap">{{ __('Link Google Map') }} :</label>
                                    <input type="text" class="form-control" id="gmap" placeholder="Link Google Map (direkomendasikan)" 
                                        name="gmap" value="{{ old('gmap') }}">
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="district_id" id="district-id" value="{{ old('district_id') }}"/>
                                <div class="form-group col-sm-12">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">{{ __('Save Now') }}</button>
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
});
</script>
@endsection