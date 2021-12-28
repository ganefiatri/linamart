@auth
@php
    $cartCounter = 0;
    if (has_cart()) {
        $cartCounter = cart_items()->sum('qty');
    }
@endphp
<!-- Sidebar main menu -->
<div class="sidebar-wrap  sidebar-pushcontent">
    <!-- Add pushcontent or fullmenu instead overlay -->
    <div class="closemenu text-opac">Close Menu</div>
    <div class="sidebar">
        @php
            $currentRoute = Route::current()->getName();
            $notif_counter = notif_counter();
            $role = Auth::user()->role;
            $usr = Auth::user();
            if ($role == 'member') {
                $usr = $usr->member;
            } elseif ($role == 'driver') {
                $usr = $usr->driver;
            }
        @endphp
        <div class="row mt-4 mb-3">
            <div class="col-auto">
                <figure class="avatar avatar-60 rounded mx-auto my-1">
                    {{ $usr->getImage(50, 50, ['class' => 'img-fluid'], true) }}
                </figure>
            </div>
            <div class="col align-self-center ps-0">
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                <p class="text-opac">{{ $role }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link @if($currentRoute == $role .'.dashboard') active @endif" aria-current="page" href="{{ route($role .'.dashboard') }}">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-house-door"></i></div>
                            <div class="col">{{ __('Dashboard') }}</div>
                            <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                        </a>
                    </li>
                    @if (($role ?? false) == 'member')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-shop"></i></div>
                            <div class="col">{{ __('Shop') }}</div>
                            <div class="arrow"><i class="bi bi-plus plus"></i> <i class="bi bi-dash minus"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'member.home') active @endif" href="{{ route('member.home') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-bag"></i></div>
                                    <div class="col">{{ __('Shopping') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'shops.index') active @endif" href="{{ route('shops.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-shop-window"></i></div>
                                    <div class="col">{{ __('My Shop') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            @php
                                $member = Auth::user()->member;
                                $has_shop = false;
                                if (!empty($member) && !empty($member->shop)) {
                                    $has_shop = true;
                                }
                            @endphp
                            @if ($has_shop)
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'products.index') active @endif" href="{{ route('products.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-binoculars"></i></div>
                                    <div class="col">{{ __('Product') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'member.order.cart') active @endif" href="{{ route('member.order.cart') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-basket3"></i></div>
                                    <div class="col">
                                        {{ __('Cart') }}
                                        @if ($cartCounter > 0)
                                        <span class="badge bg-danger fw-light countercart">{{ $cartCounter }}</span>
                                        @endif
                                    </div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'member.invoice.index') active @endif" href="{{ route('member.invoice.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-journal-check"></i></div>
                                    <div class="col">{{ __('My Orders') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            @if ($has_shop)
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'member.customerorder.index') active @endif" href="{{ route('member.customerorder.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-receipt"></i></div>
                                    <div class="col">{{ __('Incoming Orders') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            @endif
                            @php
                                $has_order = false;
                                if (!empty($member) && !empty($member->orders)) {
                                    $has_order = true;
                                }
                            @endphp
                            @if ($has_shop || $has_order)
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'member.review.index') active @endif" href="{{ route('member.review.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-chat-square-text"></i></div>
                                    <div class="col">{{ __('Reviews') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (($role ?? false) == 'admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-tools"></i></div>
                            <div class="col">{{ __('Manage') }}</div>
                            <div class="arrow"><i class="bi bi-plus plus"></i> <i class="bi bi-dash minus"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.drivers.index') active @endif" href="{{ route('admin.drivers.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-truck"></i></div>
                                    <div class="col">{{ __('Driver') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.orders.index') active @endif" href="{{ route('admin.orders.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-receipt"></i></div>
                                    <div class="col">{{ __('Order') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.shops.index') active @endif" href="{{ route('admin.shops.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-shop"></i></div>
                                    <div class="col">{{ __('Shop') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.members.index') active @endif" href="{{ route('admin.members.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-people"></i></div>
                                    <div class="col">{{ __('Member') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.products.index') active @endif" href="{{ route('admin.products.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-binoculars"></i></div>
                                    <div class="col">{{ __('Product') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.product-categories.index') active @endif" href="{{ route('admin.product-categories.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-bookmarks"></i></div>
                                    <div class="col">{{ __('Product Category') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.product-units.index') active @endif" href="{{ route('admin.product-units.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-hourglass-split"></i></div>
                                    <div class="col">{{ __('Product Unit') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'admin.shippings.index') active @endif" href="{{ route('admin.shippings.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-cash-stack"></i></div>
                                    <div class="col">{{ __('Shipping Fee') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($currentRoute == 'admin.options.index') active @endif" href="{{ route('admin.options.index') }}">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-gear-fill"></i></div>
                            <div class="col">{{ __('Site Settings') }}</div>
                            <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                        </a>
                    </li>
                    @endif
                    @if (($role ?? false) == 'driver')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('driver.assignments.index') }}" tabindex="-1">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-box-seam"></i></div>
                            <div class="col">{{ __('Assignments') }}</div>
                            <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                        </a>
                    </li>
                    @endif

                    @if (($role ?? false) == 'super')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-tools"></i></div>
                            <div class="col">{{ __('Manage') }}</div>
                            <div class="arrow"><i class="bi bi-plus plus"></i> <i class="bi bi-dash minus"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.admins.index') active @endif" href="{{ route('super.admins.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-person-bounding-box"></i></div>
                                    <div class="col">{{ __('Admin') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.members.index') active @endif" href="{{ route('super.members.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-people"></i></div>
                                    <div class="col">{{ __('Member') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.drivers.index') active @endif" href="{{ route('super.drivers.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-truck"></i></div>
                                    <div class="col">{{ __('Driver') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.shippings.index') active @endif" href="{{ route('super.shippings.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-cash-stack"></i></div>
                                    <div class="col">{{ __('Shipping Fee') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.options.index') active @endif" href="{{ route('super.options.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-gear-fill"></i></div>
                                    <div class="col">{{ __('Site Settings') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-file-earmark-code"></i></div>
                            <div class="col">{{ __('Debugging') }}</div>
                            <div class="arrow"><i class="bi bi-plus plus"></i> <i class="bi bi-dash minus"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.failedapies.index') active @endif" href="{{ route('super.failedapies.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-clipboard-x"></i></div>
                                    <div class="col">{{ __('Failed Api Request') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link @if($currentRoute == 'super.mailqueues.index') active @endif" href="{{ route('super.mailqueues.index') }}">
                                    <div class="avatar avatar-40 rounded icon"><i class="bi bi-inboxes"></i></div>
                                    <div class="col">{{ __('Mail Queues') }}</div>
                                    <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link @if($currentRoute == $role . '.notification') active @endif" href="{{ route($role . '.notification') }}" tabindex="-1">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-bell"></i></div>
                            <div class="col">
                                {{ __('Notification') }}
                                @if ($notif_counter > 0)
                                    <span class="badge bg-danger fw-light countercart">{{ $notif_counter }}</span>
                                @endif
                            </div>
                            <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" tabindex="-1" 
                            onclick="event.preventDefault();if (confirm('{{ __('Are you sure to logout?') }}')){document.getElementById('logout-form').submit();}else{event.stopPropagation();};">
                            <div class="avatar avatar-40 rounded icon"><i class="bi bi-box-arrow-right"></i></div>
                            <div class="col">{{ __('Logout') }}</div>
                            <div class="arrow"><i class="bi bi-arrow-right"></i></div>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar main menu ends -->
@endauth