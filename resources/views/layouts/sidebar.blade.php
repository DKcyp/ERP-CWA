<ul class="menu">
    <li class="sidebar-item {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}" class="sidebar-link">
            <i class="bi bi-grid-fill"></i>
            <span>Main Dashboard</span>
        </a>
    </li>

    @foreach (getMenus() as $menu)
        @if (isMenuAllowed($menu))
            @php
                $isActive = isMenuOrChildActive($menu);
                $hasSub = $menu->children->isNotEmpty();
            @endphp
            <li class="sidebar-item {{ $isActive ? 'active' : '' }} {{ $hasSub ? 'has-sub' : '' }}">
                <a href="{{ $hasSub ? '#' : url($menu->url) }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                    <div>
                        <i class="{{ $menu->icon ?? 'bi bi-folder-fill' }}"></i>
                        <span>{{ $menu->name }}</span>
                    </div>
                    @if ($hasSub)
                        <i class="bi bi-chevron-down toggle-icon" style="font-size: 0.75rem;"></i>
                    @endif
                </a>

                @if ($hasSub)
                    <ul class="submenu" style="{{ $isActive ? 'display: block;' : '' }}">
                        @include('components.menu-recursive', ['menus' => $menu->children, 'isChild' => true])
                    </ul>
                @endif
            </li>
        @endif
    @endforeach
</ul>
