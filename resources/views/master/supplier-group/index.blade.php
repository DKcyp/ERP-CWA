@extends('layouts.layout')

@section('title', 'Supplier Group')

@section('content')
<div class="page-content">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Group
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari berdasarkan kode atau nama...">
                </div>

                <div class="col-12 col-md-6 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add-supplier-group">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Group
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-supplier-group">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Kode</th>
                            <th>Nama Group</th>
                            <th>Deskripsi</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('master.supplier-group.form')
@endsection

@include('master.supplier-group.javascript')
