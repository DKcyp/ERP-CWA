@extends('layouts.layout')

@section('title', 'Supplier Balance Summary')

@section('content')
<div class="page-content">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Supplier
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari berdasarkan kode atau nama supplier...">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select id="filter-status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="OUTSTANDING">Tertunggak</option>
                        <option value="PARTIAL">Angsuran</option>
                        <option value="PAID">Lunas</option>
                    </select>
                </div>

                <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                    <button type="button" class="btn btn-success" id="btn-export">
                        <i class="bi bi-download me-1"></i>Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm hz-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-receipt text-primary fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Invoice</small>
                            <strong class="fs-5" id="total-invoice">Rp 0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm hz-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cash-stack text-success fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Terbayar</small>
                            <strong class="fs-5" id="total-paid">Rp 0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm hz-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Tertunggak</small>
                            <strong class="fs-5" id="total-balance">Rp 0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm hz-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Supplier</small>
                            <strong class="fs-5" id="total-suppliers">0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-supplier-balance">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Kode Supplier</th>
                            <th>Nama Supplier</th>
                            <th class="text-end">Total Invoice</th>
                            <th class="text-end">Total Terbayar</th>
                            <th class="text-end">Sisa Saldo</th>
                            <th style="width:120px;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end fw-bold">Grand Total</th>
                            <th class="text-end fw-bold" id="grand-invoice">Rp 0</th>
                            <th class="text-end fw-bold" id="grand-paid">Rp 0</th>
                            <th class="text-end fw-bold" id="grand-balance">Rp 0</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@includeIf('master.supplier-balance-summary.javascript')
