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
                        <h4 class="mb-2">{{ __('Member List') }}</h4>
                    </div>
                </div>
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <a href="{{ route('admin.members.create') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-plus-square"></i> {{ __('Add Member') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('admin.members.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <span data-feather="search"></span>
                            <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="{{ __('Search by name') }}" aria-label="Search" value="{{ app('request')->input('q') }}">
                         </form>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Member List') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Join Date') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Status') }}</th>
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
                                                <a href="{{ route('admin.members.show', $item->id) }}" class="view">
                                                    {{ $item->name }}
                                                </a>
                                            </td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ $item->getStatus() }}</td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <a href="{{ route('admin.members.edit', $item->id) }}" title="edit">
                                                            <i class="bi bi-pencil-square size-22"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('admin.members.destroy',$item->id) }}" method="POST">
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
    </script>
@endsection