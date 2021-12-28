@extends('layouts.fe')

@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
@endsection

@section('content')
<!-- Begin page -->
<main class="h-100 has-header has-footer">

    @php
        $member = Auth::user()->member;
    @endphp
    <!-- Header -->
    <header class="container-fluid header">
        <div class="row h-100">
            <div class="col-auto align-self-center">
                <a href="{{ route(Auth::user()->role .'.home') }}" class="btn btn-link back-btn text-color-theme">
                    <i class="bi bi-arrow-left size-20"></i>
                </a>
            </div>
            <div class="col text-center align-self-center">
                <h5 class="mb-0">{{ __('My Profile') }}</h5>
            </div>
            <div class="col-auto align-self-center">
                <a href="{{ route(Auth::user()->role .'.home') }}" class="link text-color-theme">
                    <i class="bi bi-house size-22"></i>
                </a>
            </div>
        </div>
    </header>
    <!-- Header ends -->

    <!-- main page content -->
    <div class="main-container container">

        @include('layouts.partial._message')
        
        <!-- profile picture -->
        <div class="row mb-4">
            <div class="col-auto">
                <figure class="avatar avatar-100 rounded mx-auto">
                    {{ $member->getImage(100, 100, ['class' => 'img-fluid'], true) }}
                </figure>
            </div>
            <div class="col align-self-center">
                <h5 class="">{{ $member->name }}</h5>
                <p class="text-opac">{{ $member->address }}</p>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- profile information -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Basic Information') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('member.updateprofile') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                            value="{{ $member->name }}" placeholder="Name" id="names">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="names">{{ __('Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                            value="{{ $member->email }}" placeholder="Email" id="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="email">Email</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating  mb-3">
                                        <input type="test" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                            value="{{ $member->phone }}" placeholder="Phone" id="phone">

                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="phone">{{ __('Phone') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating mb-3">
                                        <input type="file" class="form-control @error('file_name') is-invalid @enderror" id="image" name="file_name">
                                        @error('file_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="image">{{ __('Profile Photo') }}</label>
                                    </div>
                                </div>
                                {{--<div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group form-floating ">
                                        <input type="file" class="form-control" id="fileupload">
                                        <label for="fileupload">Uplaod File</label>
                                    </div>
                                </div>--}}
                                <div class="d-grid mt-3">
                                    <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                                        <button type="submit" class="btn btn-lg btn-default shadow-sm w-100">{{ __('Update Profile') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- add edit address form -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Address Change') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('member.updateaddress') }}">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-12 col-md-6 col-lg-6 mb-3">
                                    <div class="form-group form-floating">
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                            value="{{ $member->address }}" id="address2" placeholder="Address Line 1">

                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label class="form-control-label" for="address2">Alamat Lengkap (Desa RT RW)</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="form-floating mb-3">
                                        @if (is_using_district())
                                            <input type="text" name="district_name" class="form-control autocomplete  @error('district_name') is-invalid @enderror" id="district_name" 
                                                onclick="this.select();" placeholder="Kecamatan" value="{{ $district_name }}" required>
                                        @else
                                            <input type="text" name="district_name" class="form-control  @error('district_name') is-invalid @enderror" id="district_name" 
                                                placeholder="Kecamatan" value="{{ $district_name }}" autocomplete="off" required>
                                        @endif
                                        @error('district_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="country">Kecamatan, Kabupaten, Propinsi</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="form-group form-floating">
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ $member->phone }}" placeholder="Nomor Telepon" id="phone">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label class="form-control-label" for="phone">No Telepon/Handphone</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="form-group form-floating">
                                        <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                                            value="{{ $member->postal_code }}" placeholder="Kode pos" id="postal-code">
                                        @error('postal_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label class="form-control-label" for="postal-code">{{ __('Postal Code') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="form-group form-floating">
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror" id="datalistOptions">
                                            <option value="1" @if ($member->gender == 1) selected="selected"@endif>{{ __('Male') }}</option>
                                            <option value="2" @if ($member->gender == 2) selected="selected"@endif>{{ __('Female') }}</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label class="form-control-label" for="gender">{{ __('Gender') }}</label>
                                    </div>
                                </div>
                                <input type="hidden" name="district_id" id="district-id" value="{{ $member->district_id }}"/>
                                <div class="d-grid mt-3">
                                    <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                                        <button type="submit" class="btn btn-lg btn-default shadow-sm w-100">{{ __('Update Address') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- change password -->
        <div class="row mb-3">
            <div class="col">
                <h5 class="mb-0">{{ __('Change Password') }}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-light shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('member.password') }}">
                            @csrf
                            <div class="row h-100 mt-3">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-floating  mb-3">
                                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror"
                                            value="{{ old('old_password') }}" placeholder="Password Lama" id="old-password">
                                        @error('old_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="old-password">{{ __('Old Password') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-floating  mb-3">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                            value="{{ old('password') }}" placeholder="Password Baru" id="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="new-password">{{ __('New Password') }}</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-floating ">
                                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                            value="{{ old('password_confirmation') }}" placeholder="Confirm Password" id="confirmpassword">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="confirmpassword">{{ __('New Password Confirm') }}</label>
                                    </div>
                                </div>
    
                                <div class="d-grid mt-3">
                                    <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 mx-auto align-self-center">
                                        <button type="submit" class="btn btn-lg btn-default shadow-sm w-100">{{ __('Reset Password') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- main page content ends -->

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