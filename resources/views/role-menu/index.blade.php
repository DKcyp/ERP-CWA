
@extends('layouts.layout')

@section('title', 'Role & Menu Manager')

@push('after-style')
<style>
  /* ====== Layout 2 kolom (responsif) ====== */
  @media (min-width: 992px){
    .col-left  { flex: 0 0 auto; width: 46%; }
    .col-right { flex: 0 0 auto; width: 54%; }
  }

  /* ====== TREE UI untuk Role->Menu hak akses ====== */
  .menu-tree .list-unstyled { margin-left: 0; }
  .menu-tree .list-unstyled > li { border-left: 1px dashed #e5e7eb; padding-left: .5rem; }
  .menu-tree .toggle-branch { line-height: 1; padding: .1rem .35rem; }
  .menu-tree .d-flex .form-check-label { line-height: 1.2; }

  /* ====== Modal z-index (dua modal bersamaan) ====== */
  .modal-backdrop + .modal-backdrop { z-index: 1061 !important; }
  #iconPickerModal { z-index: 1065; }

  /* ====== Drag & drop helpers untuk Menu ====== */
  #sortable-menu, .sortable-submenu { list-style: none; margin: 0; padding: 0; }
  #sortable-menu > li { margin-bottom: .35rem; }
  .menu-item .drag-handle { cursor: grab; }
  .menu-item .drag-handle:active { cursor: grabbing; }
  .sortable-submenu { margin-left: 0; }
  .sortable-submenu.sortable-empty{
    min-height: 2rem; padding: .75rem; border: 1px dashed #ced4da; border-radius: .5rem; background-color: #f8f9fa;
  }
  .ui-sortable-placeholder{
    border: 1px dashed #0d6efd; background-color: rgba(13,110,253,.12); visibility: visible !important; min-height: 3rem; border-radius: .5rem;
  }
</style>
@endpush

@section('content')
<div class="page-heading mb-4">
  <h3>Role & Menu Manager</h3>
</div>

<div class="page-content">
  <div class="row g-3">
    {{-- ===================== KIRI: ROLE ===================== --}}
    <div class="col-12 col-left">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Role</h6>
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddRole">
            + Tambah Role
          </button>
        </div>
        <div class="card-body pt-2">
          <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0 mt-2" id="data-table">
              <thead style="background-color:#f8f9fb;">
              <tr class="text-center">
                <th class="text-center" style="width:40px;">No</th>
                <th class="text-center">Name</th>
                <th class="text-center" style="width:160px;">Action</th>
              </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- Modal2 & JS Role (REUSE punyamu) --}}
      @includeIf('role-menu.form')
    </div>

    {{-- ===================== KANAN: MENU ===================== --}}
    <div class="col-12 col-right">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-bottom py-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="mb-0">Menu</h6>
            <div class="d-flex gap-2">

              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalMenu">
                + Tambah Menu
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
        <br>
        <button id="save-order" class="btn btn-sm btn-success">
           <i class="bi bi-check2-circle me-1"></i> Simpan Urutan
        </button>
        <p>
          <ul id="sortable-menu" class="list-group">
            @foreach($menus as $menu)
              @include('menu.menu-item', [
                'menu' => $menu,
                'level' => 0,
                'parentId' => null
              ])
            @endforeach
          </ul>
          {{-- HAPUS tombol Simpan Urutan di sini --}}
        </div>
      </div>

      @include('menu.form')
    </div>

  </div>
</div>
@endsection

@push('after-script')
  {{-- JS Role (DataTables + tree hak akses) --}}
  @include('role-menu.javascript')

  {{-- JS Menu (drag & drop, simpan urutan, icon picker, dll) --}}
  @include('menu.javascript')
@endpush
