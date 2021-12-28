@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
@endsection

@section('content')
<!-- Begin page content -->
<main class="container-fluid h-100 main-container">
    <div class="overlay-image text-end">
        <img src="{{ asset('img/applelogo.png') }}" class="orange-slice" alt="">
    </div>

    <div class="row h-100">
        <div class="col-12 text-center">
            <div class="logo-small">
                <img src="{{ asset('img/logo.png') }}" alt="" class="img">
                <h6>{{ config('global.site_name') }}<br><small>{{ config('global.tag_line') }}</small></h6>
            </div>
        </div>
        <div class="col-12 mx-auto text-center">
            <div class="row h-100">
                <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                    <h2 class="text-center mb-4">Ciptakan tokomu sekarang!</h2>
                    <div class="card card-light shadow-sm mb-4">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-unstyled">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('member.doregister') }}">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Nama Anda" value="{{ old('name') }}" required>
                                    <label for="name">Nama Anda</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="name" placeholder="Alamat email" value="{{ old('email') }}" required>
                                    <label for="name">Email Anda</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" name="shop_name" class="form-control" id="name" placeholder="Nama Toko" value="{{ old('shop_name') }}" required>
                                    <label for="name">Nama Toko</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" name="district_name" class="form-control autocomplete" id="district_name" placeholder="Kecamatan" value="{{ old('district_name') }}" required>
                                    <label for="name">Kecamatan</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea name="address" class="form-control" id="address" placeholder="Alamat lengkap (nama jalan, desa)">{{ old('address') }}</textarea>
                                    <label for="address">Alamat Toko</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" name="gmap" class="form-control" id="postal" value="{{ old('gmap') }}" placeholder="Link Google Map (direkomendasikan)">
                                    <label for="postal">Link Google Map</label>
                                </div>
                                <input type="hidden" name="district_id" id="district-id" value="{{ old('district_id') }}"/>
                                <input type="hidden" name="member_id" value="{{ $member_id }}"/>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-default shadow-sm">Buat Toko Sekarang</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 text-center align-self-end py-2">
            <div class="row">
                <div class="col text-center">
                    Already have account? <a href="signin.html" class="btn btn-link px-0 ms-2">Sign in <i class="bi bi-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</main>
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