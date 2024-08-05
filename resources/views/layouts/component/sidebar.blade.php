<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ asset('') }}assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">@auth
                    {{ Auth::user()->name }}
                @endauth
            </a>
        </div>
    </div>
    <!-- Sidebar Menu -->
    @php
        use App\Http\Helpers\MenuHelper;
    @endphp
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link {{ Route::is('home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        Beranda
                    </p>
                </a>
            </li>
            @foreach (MenuHelper::Menu() as $item)
                @if (isset($item['menu']->submenus))
                    @if (count($item['menu']->submenus) > 0)
                        <li
                            class="nav-item {{ in_array(Route::currentRouteName(), $item['route']) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ in_array(Route::currentRouteName(), $item['route']) ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['menu']->icon }}"></i>
                                <p>
                                    {{ $item['menu']->name }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($item['menu']->submenus as $value)
                                    @if (in_array($value->routes->permission_name ?? '', MenuHelper::Permissions()))
                                        <li class="nav-item">
                                            <a href="{{ route($value->route) }}"
                                                class="nav-link {{ Route::currentRouteName() == $value->route ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ $value->name }}</p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route($item['menu']->route) }}"
                                class="nav-link {{ in_array(Route::currentRouteName(), $item['route']) ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['menu']->icon }}"></i>
                                <p>
                                    {{ $item['menu']->name }}
                                </p>
                            </a>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </nav>
</div>
