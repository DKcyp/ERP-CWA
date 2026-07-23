<li class="list-group-item menu-draggable @if($level > 0) ms-{{ $level * 3 }} @endif"
    data-id="{{ $menu->id }}"
    data-code="{{ e($menu->code) }}"
    data-name="{{ e($menu->name) }}"
    data-url="{{ e($menu->url) }}"
    data-icon="{{ e($menu->icon) }}"
    data-main-menu="{{ $menu->main_menu ?? '' }}"
    data-active="{{ (int) $menu->active }}"
    data-sort="{{ (int) $menu->sort }}">
    <div class="menu-item d-flex justify-content-between align-items-center gap-2">
        <span class="d-flex align-items-center gap-2">
            <span class="text-muted drag-handle" title="Geser untuk memindahkan"><i class="bi bi-grip-vertical"></i></span>
            @if ($menu->icon)
                <i class="{{ $menu->icon }}"></i>
            @endif
            <span class="fw-semibold">{{ $menu->name }}</span>
        </span>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary rounded-pill">{{ $menu->sort }}</span>
            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-menu" title="Edit menu">
                <i class="bi bi-pencil-square"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-menu" title="Hapus menu">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    @php
        $children = $menu->children ?? collect();
    @endphp

    <ul class="list-group sortable-submenu mt-1 @if($children->isEmpty()) sortable-empty @endif" data-parent="{{ $menu->id }}">
        @foreach($children as $child)
            @include('menu.menu-item', [
                'menu' => $child,
                'level' => $level + 1,
                'parentId' => $menu->id
            ])
        @endforeach
    </ul>
</li>
