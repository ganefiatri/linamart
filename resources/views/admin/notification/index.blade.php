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
                        <h4 class="mb-2">{{ __('Notification') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if ($items->count() > 0)
                @php
                    $unread = notif_counter();
                @endphp
                @if ($unread > 0)
                <form action="{{ route('admin.notification.markasread') }}" method="POST" class="mb-3" id="mark-read-form">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-outline-primary" 
                        onclick="if (confirm('Anda yakin ingin melakukan ini?')) {
                            $('#mark-read-form').submit();
                        }">
                        {{ __('Mark all as read') }}
                    </button>
                </form>
                @endif

                <div class="list-group list-group-flush bg-none rounded-0 mb-3">
                    @foreach ($items as $item)
                    <a href="{{ route('admin.notification.show', $item->id) }}" class="list-group-item">
                        <div class="row">
                            <div class="col align-self-center">
                                <p class="lh-small mb-0 @if($item->status == 0) text-success @else text-muted @endif">
                                    {{ Str::limit($item->message, 50) }}
                                </p>
                                <p class="small text-opac">{{ $item->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>    
                    @endforeach
                </div>
                {{ $items->appends(request()->input())->links() }}
                @else
                <div class="alert alert-warning">{{ __('You have no notifications at this time') }}</div>
                @endif
            </div>
        </div>
    </div>
</main>
<!-- Page ends-->
@endsection
