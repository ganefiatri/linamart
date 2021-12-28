<!-- Header -->
<header class="container-fluid header">
    <div class="row mt-2">
        <div class="col-auto align-self-center">
            <button type="button" class="btn btn-link menu-btn text-color-theme">
                <i class="bi bi-list size-24"></i>
            </button>
        </div>
        <div class="col text-center">
            @if (!empty(config('global.company_logo')))
                @php
                    $img_params = [
                        'title' => config('global.site_name') . ' logo'
                    ];
                @endphp
                {{ get_image(config('global.company_logo'), null, 40, $img_params) }}
            @else
            <div class="logo-small">
                <i class="bi bi-shop size-32 mr-1"></i>
                <h6>{{ config('global.site_name') }}<br><small>{{ config('global.tag_line') }}</small></h6>
            </div>
            @endif
        </div>
        <div class="col-auto align-self-center">
            <a href="{{ route(Auth::user()->role .'.profile') }}" class="link text-color-theme">
                <i class="bi bi-person-circle size-22"></i>
            </a>
        </div>
    </div>
</header>
<!-- Header ends -->