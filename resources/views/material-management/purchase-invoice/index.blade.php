@extends('layouts.layout')

@section('title', 'Purchase Invoice List')

@section('content')
<div class="page-content">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Invoice
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor invoice, STBJ, atau supplier...">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="DRAFT">Draft</option>
                        <option value="PENDING">Pending</option>
                        <option value="APPROVED">Approved</option>
                        <option value="REJECTED">Rejected</option>
                        <option value="PAID">Paid</option>
                    </select>
                </div>

                <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-add-inv">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-inv">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>No. Invoice</th>
                            <th class="text-center">Tanggal</th>
                            <th>No. STBJ</th>
                            <th>Supplier</th>
                            <th class="text-center">Total Item</th>
                            <th class="text-end">Total Amount</th>
                            <th style="width:110px;" class="text-center">Status</th>
                            <th style="width:100px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('material-management.purchase-invoice.form')
@endsection

@include('material-management.purchase-invoice.javascript')
