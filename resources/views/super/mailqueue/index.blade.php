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
                        <h4 class="mb-2">{{ __('Mail Queue List') }}</h4>
                    </div>
                </div>
                <!-- search and add button -->
                <div class="row mb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form action="{{ route('super.mailqueues.index') }}" class="d-flex align-items-center add-contact__form my-sm-0 my-2">
                            <span data-feather="search"></span>
                            <input class="form-control mr-sm-2 border-0 box-shadow-none" type="search" name="q" placeholder="{{ __('Search by email or mail class') }}" aria-label="Search" value="{{ app('request')->input('q') }}">
                         </form>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#clearModal" data-bs-whatever="@mdo">
                                    <i class="bi bi-plus-square"></i> {{ __('Clear Old Data') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- endsearch -->
                <div class="card">
                    <div class="card-header color-dark fw-500">{{ __('Mail Queue List') }}</div>
                    <div class="card-body p-2">
                        <div class="userDatatable global-shadow border-0 bg-white w-100">
                            <div class="table-responsive">
                                <table class="table mb-2 table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>{{ __('Mail To') }}</th>
                                        <th>{{ __('Mail Class') }}</th>
                                        <th>{{ __('Executed') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th>{{ __('Executed At') }}</th>
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
                                            <td>{{ $item->mail_to }}</td>
                                            <td>{{ $item->mail_class }}<br/>{{ json_encode($item->mail_params ?? []) }}</td>
                                            <td class="text-center">{{ ($item->executed > 0) ? __('Yes') : __('No') }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->executed_at }}</td>
                                            <td class="text-center">
                                                <ul class="list-unstyled list-inline">
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('super.mailqueues.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            @if ($item->executed > 0)
                                                            <a role="button" href="javascript:void(0);" onclick="return reexecuteItem(this, false);" title="repeat execute">
                                                                <i class="bi bi-arrow-clockwise size-22"></i>
                                                            </a>
                                                            @else
                                                            <input type="hidden" name="force" value="1"/>
                                                            <a role="button" href="javascript:void(0);" onclick="return reexecuteItem(this, true);" title="execute now">
                                                                <i class="bi bi-play-btn size-22 text-warning"></i>
                                                            </a>
                                                            @endif
                                                        </form>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <form action="{{ route('super.mailqueues.destroy',$item->id) }}" method="POST">
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
<div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __('Clear Mail Queues') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('super.mailqueues.clear') }}">
            @csrf
        <div class="modal-body">
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">{{ __('More Than') }}:</label>
              @php
                  $ranges = [
                      'last_day' => __('Yesterday'),
                      'last_week' => __('Last Week'),
                      'last_month' => __('Last Month'),
                      'last_year' => __('Last Year'),
                      'last_2_year' => __('Last 2 Year'),
                      'last_3_year' => __('Last 3 Year'),
                  ];
              @endphp
              <select name="range" class="form-control">
                  <option value="">-</option>
                  @foreach ($ranges as $range => $range_name)
                  <option value="{{ $range }}">{{ $range_name }}</option>
                  @endforeach
              </select>
            </div>
            <div class="mb-3">
                <label for="recipient-mail_to" class="col-form-label">{{ __('Mail To') }}:</label>
                <input type="email" name="mail_to" class="form-control"/>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              {{ __('Cancel') }}
          </button>
          <button type="submit" class="btn btn-primary">{{ __('Execute Now') }}</button>
        </div>
        </form>
      </div>
    </div>
  </div>
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
    function reexecuteItem(dt, force)
    {   
        var message = "{{ __('Are you sure you want to repeat this?') }}";
        if (force) {
            var message = "{{ __('Are you sure you want to send email now?') }}";
        }
        if (confirm(message)) {
            $(dt).parent().submit();
        }
        return false;
    }
    </script>
@endsection