@extends('layouts.layout')

@section('title', 'Supplier Master')

@section('content')
<div class="page-content">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                {{-- Grup Supplier --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-people me-1"></i>Grup Supplier
                    </label>
                    <select id="filter-group" class="form-control" style="width:100%;">
                        <option value="">Semua Grup</option>
                    </select>
                </div>

                {{-- Center --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-geo-alt me-1"></i>Center / Area
                    </label>
                    <select id="filter-center" class="form-control" style="width:100%;">
                        <option value="">Semua Center</option>
                    </select>
                </div>

                {{-- Status --}}
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-12 col-md-4 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add-supplier">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Supplier
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-supplier">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Kode</th>
                            <th>Nama Supplier</th>
                            <th>Grup</th>
                            <th>Center</th>
                            <th>No. Telp</th>
                            <th>Email</th>
                            <th style="width:60px;" class="text-center">TOP</th>
                            <th style="width:90px;" class="text-center">Status</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('master.supplier.form')
@endsection

@include('master.supplier.javascript')
