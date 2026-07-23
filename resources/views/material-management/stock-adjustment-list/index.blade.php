@extends('layouts.layout')

@section('title', 'Stock Adjustment List')

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
                    <button type="button" class="btn btn-primary" id="btn-add-sal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Adjustment
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" id="adjustmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-all" data-type="all" type="button" role="tab">
                Semua
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-standard" data-type="STANDARD" type="button" role="tab">
                Standard
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-internal" data-type="INTERNAL_USE" type="button" role="tab">
                Internal Use
            </button>
        </li>
    </ul>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-sal">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Id</th>
                            <th class="text-center">Date</th>
                            <th>Warehouse</th>
                            <th>Department</th>
                            <th class="text-center">Type</th>
                            <th>Use For</th>
                            <th>Transfer to TA</th>
                            <th>Product Group</th>
                            <th>PIC</th>
                            <th>Note</th>
                            <th style="width:110px;" class="text-center">Status</th>
                            <th>User Id</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('material-management.stock-adjustment-list.form')
@endsection

@include('material-management.stock-adjustment-list.javascript')
