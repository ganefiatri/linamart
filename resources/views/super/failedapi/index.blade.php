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
                        <h4 class="mb-2">{{ __('Failed Api Request') }}</h4>
                    </div>
                </div>
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('super.failedapies.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <span data-feather="search"></span>
                            <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="{{ __('Search') }}" aria-label="Search" value="{{ app('request')->input('q') }}">
                         </form>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Failed Api Requests') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Member Id') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Failed At') }}</th>
                                        <th class="text-center" width="100px">
                                            {{ __('Action') }}
                                        </th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i = 1;
                                    @endphp
                                    <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td class="text-center">
                                                {{ $i + (($items->currentPage()-1) * $items->perPage()) }}
                                            </td>
                                            <td>
                                                {{ $item->member->name }} ({{ $item->member->email }})
                                            </td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->exception }}</td>
                                            <td>{{ $item->failed_at }}</td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('super.failedapies.reexecute', $item->id) }}" method="POST">
                                                            @csrf
                                                            <a role="button" href="javascript:void(0);" onclick="return reexecuteItem(this);" title="repeat execute">
                                                                <i class="bi bi-arrow-clockwise size-22"></i>
                                                            </a>
                                                        </form>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('super.failedapies.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a role="button" href="javascript:void(0);" onclick="return removeItem(this);" title="remove">
                                                                <i class="bi bi-trash size-22"></i>
                                                            </a>
                                                        </form>
                                                    </li>
                                                 </ul>
                                            </td>
                                        </tr>
                                        @php ++$i @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $items->appends(request()->input())->links() }}
                            </div>
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
    function removeItem(dt)
    {
        if (confirm("{{ __('Are you sure you want to delete this?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    function reexecuteItem(dt)
    {   
        if (confirm("{{ __('Are you sure you want to repeat this?') }}")) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection