@php
    use Illuminate\Support\Facades\Route;
    use App\Helpers\MenuHelper;

    $currentRouteName = Route::currentRouteName();
    $activeParents = MenuHelper::getActiveParents($menu, $currentRouteName);
@endphp

<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)
            @php
                $activeClass = null;
                $active = $configData['layout'] === 'vertical' ? 'active open' : 'active';

                if (
                    $currentRouteName === $submenu['slug'] ||
                    in_array($submenu['id'], $activeParents) ||
                    MenuHelper::isRelatedRoute($currentRouteName, $submenu['slug'])
                ) {
                    $activeClass = 'active open';
                }
            @endphp

            <li class="menu-item {{ $activeClass }}">
                <a href="{{ isset($submenu['url']) ? url($submenu['url']) : 'javascript:void(0)' }}"
                    class="{{ isset($submenu['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
                    @if (isset($submenu['target']) and !empty($submenu['target'])) target="_blank" @endif>
                    @if (isset($submenu['icon']))
                        <i class="{{ $submenu['icon'] }}"></i>
                    @endif
                    <div>{{ isset($submenu['menu']) ? __($submenu['menu']) : '' }}</div>
                    @isset($submenu['badge'])
                        <div class="badge bg-{{ $submenu['badge'][0] }} rounded-pill ms-auto">{{ $submenu['badge'][1] }}
                        </div>
                    @endisset
                </a>

                {{-- submenu --}}
                @if (isset($submenu['submenu']))
                    @include('layouts.sections.menu.submenu', ['menu' => $submenu['submenu']])
                @endif
            </li>
        @endforeach
    @endif
</ul>
