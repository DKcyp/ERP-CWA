@extends('layouts.layout')

@section('title', 'Manajemen Menu')

@section('content')
<style>
    /* Modal kedua tetap muncul di atas modal pertama */
    .modal-backdrop + .modal-backdrop {
        z-index: 1061 !important;
    }

    #iconPickerModal {
        z-index: 1065;
    }
</style>
<style>
    /* Drag & drop helpers */
    #sortable-menu, .sortable-submenu { list-style: none; margin: 0; padding: 0; }
    #sortable-menu > li { margin-bottom: .35rem; }
    .menu-item .drag-handle { cursor: grab; }
    .menu-item .drag-handle:active { cursor: grabbing; }
    .sortable-submenu { margin-left: 0; }
    .sortable-submenu.sortable-empty {
        min-height: 2rem;
        padding: .75rem;
        border: 1px dashed #ced4da;
        border-radius: .5rem;
        background-color: #f8f9fa;
        transition: background-color .15s ease, border-color .15s ease;
    }
    .sortable-submenu.sortable-hover {
        border-color: #0d6efd;
        background-color: rgba(13,110,253,.08);
    }
    li.list-group-item.drag-target > .menu-item {
        background-color: rgba(13,110,253,.12);
        border-left: 3px solid #0d6efd;
        border-radius: .45rem;
        transition: background-color .15s ease, border-left-color .15s ease;
    }
    .sortable-chosen > .menu-item {
        box-shadow: 0 4px 12px rgba(13,110,253,.18);
        background-color: rgba(13,110,253,.1);
    }
    .sortable-ghost {
        opacity: .4;
    }
</style>
<div class="page-heading">
    <h3>Manajemen Menu</h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-title">
                    Drag and drop untuk mengatur urutan menu dan submenu.
                </div>
                <button type="button" class="btn btn-primary" id="btnAddMenu">
                    + Tambah Menu
                </button>
            </div>
        </div>

        <div class="card-body">
        <ul id="sortable-menu" class="list-group">
            @foreach($menus as $menu)
                @include('menu.menu-item', [
                    'menu' => $menu,
                    'level' => 0,  // Initialize level at 0 for root items
                    'parentId' => null  // Explicit parent ID
                ])
            @endforeach
</ul>
            <button id="save-order" class="btn btn-primary mt-4">Simpan Urutan</button>
        </div>
    </div>
</div>

@include('menu.form')

@endsection

@include('menu.javascript')

