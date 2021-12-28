<!-- Footer -->
@php
    $role = Auth::user()->role;
    $totCart = 0;
    if (has_cart()) {
        $totCart = cart_items()->sum('qty');
    }
    $currentRoute = Route::current()->getName();
    if ($currentRoute == 'member.order.paymentproceed') {
        $totCart = 0;
    }
@endphp
<footer class="footer">
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li class="nav-item">
                <a class="nav-link @if($currentRoute == $role . '.dashboard') active @endif" href="{{ route($role .'.dashboard') }}">
                    <span>
                        <i class="nav-icon bi bi-house"></i>
                        <span class="nav-text">{{ __('Dashboard') }}</span>
                    </span>
                </a>
            </li>
            @if ($role == 'member')
            <li class="nav-item">
                <a class="nav-link @if($currentRoute == 'member.home') active @endif" href="{{ route('member.home') }}">
                    <span>
                        <i class="nav-icon bi bi-bag"></i>
                        <span class="nav-text">{{ __('Shopping') }}</span>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if($currentRoute == 'member.invoice.index') active @endif" href="{{ route('member.invoice.index') }}">
                    <span>
                        <i class="nav-icon bi bi-journal-check"></i>
                        <span class="nav-text">{{ __('My Orders') }}</span>
                    </span>
                </a>
            </li>
            <li class="nav-item center-item @if ($totCart <= 0) d-none @endif" id="cart-menu">
                <a class="nav-link @if($currentRoute == 'member.order.cart') active @endif" href="{{ route('member.order.cart') }}">
                    <span>
                        <i class="nav-icon bi bi-basket3"></i>
                        <span class="nav-text">{{ __('Cart') }}</span>
                        <span class="countercart">{{ $totCart }}</span>
                    </span>
                </a>
            </li>
            @endif

            @if ($role == 'admin')
            <li class="nav-item">
                <a class="nav-link @if($currentRoute == 'admin.orders.index') active @endif" href="{{ route('admin.orders.index') }}">
                    <span>
                        <i class="nav-icon bi bi-receipt"></i>
                        <span class="nav-text">{{ __('Orders') }}</span>
                    </span>
                </a>
            </li>
            @endif

            @if ($role == 'driver')
            @php
                $totAssign = assignment_counter();
            @endphp
            <li class="nav-item @if($totAssign > 0)center-item @endif">
                <a class="nav-link @if($currentRoute == 'driver.assignments.index') active @endif" href="{{ route('driver.assignments.index') }}">
                    <span>
                        {{--<i class="nav-icon bi bi-box-seam"></i>--}}
                        @if($totAssign > 0)
                        <img src="{{ asset('img/gosend-icon-white.png') }}">
                        @else 
                        <img src="{{ asset('img/gosend-icon.png') }}">
                        @endif
                        <span class="nav-text">{{ __('Assignments') }}</span>
                        @if($totAssign > 0)
                        <span class="countercart">{{ $totAssign }}</span>
                        @endif
                    </span>
                </a>
            </li>
            @endif

            <li class="nav-item @if ($totCart > 0) d-none @endif" id="notif-menu">
                <a class="nav-link @if($currentRoute == $role . '.notification') active @endif" href="{{ route($role . '.notification') }}">
                    <span>
                        <i class="nav-icon bi bi-bell"></i>
                        <span class="nav-text">{{ __('Notification') }}</span>
                    </span>
                    @php
                        $notif_counter = 0;
                        if ($totCart <= 0) {
                            $notif_counter = notif_counter();
                        }
                    @endphp
                    @if ($notif_counter > 0)
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                        {{ $notif_counter }}
                    </span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</footer>
<!-- Footer ends-->