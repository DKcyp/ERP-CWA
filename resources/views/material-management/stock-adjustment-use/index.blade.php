@extends('layouts.layout')

@section('title', 'Stock Adjustment - Internal Use')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Adjustment
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor, warehouse, atau alasan...">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="DRAFT">Draft</option>
                        <option value="APPROVED">Approved</option>
                        <option value="COMPLETED">Completed</option>
                    </select>
                </div>

                <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add-sau">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Adjustment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-sau">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Id</th>
                            <th class="text-center">Date</th>
                            <th>Warehouse</th>
                            <th class="text-center">Items</th>
                            <th class="text-end">Total Cost Diff</th>
                            <th>Alasan</th>
                            <th style="width:110px;" class="text-center">Status</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('material-management.stock-adjustment-use.form')
@endsection

@include('material-management.stock-adjustment-use.javascript')
