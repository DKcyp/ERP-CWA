@extends('layouts.layout')

@section('title', 'Stock Transfer List')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Transfer
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor, gudang asal/tujuan, atau PIC...">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="PREPARATION">Preparation</option>
                        <option value="SHIPMENT">Shipment</option>
                        <option value="TRANSFER">Transfer</option>
                    </select>
                </div>

                <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add-st">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Transfer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-st">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>No. Transfer</th>
                            <th class="text-center">Tanggal</th>
                            <th>Dari Gudang</th>
                            <th>Ke Gudang</th>
                            <th>PIC</th>
                            <th class="text-center">Total Item</th>
                            <th class="text-center">Total Qty</th>
                            <th style="width:110px;" class="text-center">Status</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('material-management.stock-transfer-list.form')
@endsection

@include('material-management.stock-transfer-list.javascript')
