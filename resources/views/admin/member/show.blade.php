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
                        <h4 class="mb-2">{{ __('View Member') }} #{{ $member->id }}</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-ul"></i> {{ __('List Member') }}
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form method="POST" action="{{ route('admin.loginasmember', $member) }}">
                            @csrf
                            <button class="btn btn-default float-end" onclick="return jumpConfirmation(this);">
                                {{ __('Login As') }} "{{ $member->name }}"
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('View Member') }}</div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $member->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Email') }}</td>
                                    <td>{{ $member->email }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Phone') }}</td>
                                    <td>{{ $member->phone }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Address') }}</td>
                                    <td>{{ $member->address }}</td>
                                </tr>

                                @if ($member->district_id > 0 && is_using_district())
                                <tr>
                                    <td>{{ __('District') }}</td>
                                    <td>{{ $member->district->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('City') }}</td>
                                    <td>{{ $member->district->city->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('District') }}</td>
                                    <td>{{ $member->district->city->province->name }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{ __('District City') }}</td>
                                    <td>{{ get_district_value($member) }}</td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <td>{{ __('Postal Code') }}</td>
                                    <td>{{ $member->postal_code }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Gender') }}</td>
                                    <td>{{ $member->getGender() }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Created At') }}</td>
                                    <td>{!! $member->created_at !!}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Last Update') }}</td>
                                    <td>{!! $member->updated_at !!}</td>
                                </tr>
                            </table>
                        </div>
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
    function jumpConfirmation(dt)
    {
        if (confirm("{{ __('Are you sure you want to jump as member from admin?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection