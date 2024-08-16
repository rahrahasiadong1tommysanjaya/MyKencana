@php
    use Illuminate\Support\Facades\Route;
    use App\Helpers\MenuHelper;

    $configData = Helper::appClasses();

    // Mendapatkan nama rute saat ini
    $currentRouteName = Route::currentRouteName();

    // Mendapatkan daftar parent yang harus aktif
    $activeParents = MenuHelper::getActiveParents($menuData[0]['menu'], $currentRouteName);
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                    @include('_partials.macros', ['height' => 20])
                </span>
                <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData[0]['menu'] as $menu)
            {{-- menu headers --}}
            @if (isset($menu['menuHeader']))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ __($menu['menuHeader']) }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $active = $configData['layout'] === 'vertical' ? 'active open' : 'active';

                    if (
                        $currentRouteName === $menu['slug'] ||
                        in_array($menu['id'], $activeParents) ||
                        MenuHelper::isRelatedRoute($currentRouteName, $menu['slug'])
                    ) {
                        $activeClass = 'active open';
                    }
                @endphp

                {{-- main menu --}}
                <li class="menu-item {{ $activeClass }}">
                    <a href="{{ isset($menu['url']) ? url($menu['url']) : 'javascript:void(0);' }}"
                        class="{{ isset($menu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if (isset($menu['target']) and !empty($menu['target'])) target="_blank" @endif>
                        @isset($menu['icon'])
                            <i class="{{ $menu['icon'] }}"></i>
                        @endisset
                        <div>{{ isset($menu['menu']) ? __($menu['menu']) : '' }}</div>
                        @isset($menu['badge'])
                            <div class="badge bg-{{ $menu['badge'][0] }} rounded-pill ms-auto">{{ $menu['badge'][1] }}</div>
                        @endisset
                    </a>

                    {{-- submenu --}}
                    @isset($menu['submenu'])
                        @include('layouts.sections.menu.submenu', ['menu' => $menu['submenu']])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>
</aside>
