@extends('layouts.layout')

@section('title', 'Monthly Purchase by Supplier Report')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-calendar-month me-1"></i>Bulan
                    </label>
                    <select class="form-select" id="filter-month">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07" selected>Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-calendar-year me-1"></i>Tahun
                    </label>
                    <select class="form-select" id="filter-year">
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                        <option value="2027">2027</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="btn-filter">
                        <i class="bi bi-search me-1"></i>Tampilkan
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4" id="summary-cards">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Periode</div>
                    <div class="fs-6 fw-bold" id="summary-period">-</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Total Supplier</div>
                    <div class="fs-4 fw-bold text-primary" id="summary-suppliers">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Total Pembelian</div>
                    <div class="fs-5 fw-bold text-success" id="summary-amount">Rp 0</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-monthly">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>Nama Supplier</th>
                            <th class="text-center">Total Invoice</th>
                            <th class="text-center">Total Item</th>
                            <th class="text-end">Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@include('material-management.monthly-purchase-by-supplier-report.javascript')
