@foreach ($menus as $menu)
    @if (isMenuAllowed($menu))
        @php
            $isActive = isMenuOrChildActive($menu);
            $hasSub = $menu->children->isNotEmpty();
        @endphp
        <li class="sidebar-item {{ $isActive ? 'active' : '' }} {{ $hasSub ? 'has-sub' : '' }}">
            <a href="{{ $hasSub ? '#' : url($menu->url) }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                <div class="d-flex align-items-center gap-2">
                    <i class="{{ $menu->icon ?? 'bi bi-file-earmark' }}"></i>
                    <span>{{ $menu->name }}</span>
                </div>
                @if ($hasSub)
                    <i class="bi bi-chevron-down toggle-icon ms-1"></i>
                @endif
            </a>

            @if ($hasSub)
                <ul class="submenu" style="{{ $isActive ? 'display: block;' : '' }}">
                    @include('components.menu-recursive', ['menus' => $menu->children])
                </ul>
            @endif
        </li>
    @endif
@endforeach
