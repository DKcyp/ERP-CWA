@extends('layouts.layout')

@section('title', 'Daily Supplier Payment Report')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-calendar me-1"></i>Dari Tanggal
                    </label>
                    <input type="date" class="form-control" id="filter-start-date">
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-calendar me-1"></i>Sampai Tanggal
                    </label>
                    <input type="date" class="form-control" id="filter-end-date">
                </div>

                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Payment
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor, supplier, atau invoice...">
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

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="button" class="btn btn-primary flex-fill" id="btn-filter">
                        <i class="bi bi-search me-1"></i>Cari
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4" id="summary-cards">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Total Payment</div>
                    <div class="fs-4 fw-bold text-primary" id="summary-total-payments">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Total Amount</div>
                    <div class="fs-4 fw-bold text-success" id="summary-total-amount">Rp 0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Total Item</div>
                    <div class="fs-4 fw-bold text-info" id="summary-total-items">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100">
                <div class="card-body text-center">
                    <div class="text-muted small mb-1">Status</div>
                    <div class="d-flex justify-content-center gap-2 small flex-wrap" id="summary-status">
                        <span>-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-daily">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>No. Payment</th>
                            <th class="text-center">Date</th>
                            <th>Supplier</th>
                            <th class="text-center">Curr.</th>
                            <th class="text-end">Total Paid</th>
                            <th>Account</th>
                            <th style="width:110px;" class="text-center">Status</th>
                            <th style="width:80px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Detail Payment - <span id="detail-payment-number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal Payment</small>
                        <span id="detail-payment-date" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Supplier</small>
                        <span id="detail-payment-supplier" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Status</small>
                        <span id="detail-payment-status">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Currency</small>
                        <span id="detail-payment-currency" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Account</small>
                        <span id="detail-payment-account" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">User</small>
                        <span id="detail-payment-user" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. Invoice</small>
                        <span id="detail-payment-invoice" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. STBJ</small>
                        <span id="detail-payment-stbj" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Payment Type</small>
                        <span id="detail-payment-type" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Complete Date</small>
                        <span id="detail-payment-complete" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Catatan</small>
                        <span id="detail-payment-note" class="fw-semibold">-</span>
                    </div>
                </div>
                <hr>
                <h6 class="fw-semibold mb-3">Daftar Item</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width:40px;">No</th>
                                <th>Deskripsi</th>
                                <th class="text-end" style="width:140px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items-tbody"></tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="2" class="text-end">Total Amount</td>
                                <td id="detail-total-amount" class="text-end">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@include('material-management.supplier-daily-payment-report.javascript')
