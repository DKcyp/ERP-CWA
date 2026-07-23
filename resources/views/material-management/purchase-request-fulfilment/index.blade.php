@extends('layouts.layout')

@section('title', 'Purchase Request Fulfilment Report')

@section('content')
<div class="page-content">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari PR
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor PR, requester, atau department...">
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
                        <option value="FULFILLED">Fulfilled</option>
                    </select>
                </div>

                <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable Card --}}
    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-fulfilment">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>No. PR</th>
                            <th class="text-center">Tanggal</th>
                            <th>Requester</th>
                            <th>Department</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Progress Fulfilment</th>
                            <th style="width:80px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Detail PR - <span id="detail-pr-number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal</small>
                        <span id="detail-pr-date" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Requester</small>
                        <span id="detail-pr-requester" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Department</small>
                        <span id="detail-pr-department" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Status</small>
                        <span id="detail-pr-status">-</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Catatan</small>
                        <span id="detail-pr-note" class="fw-semibold">-</span>
                    </div>
                </div>
                <hr>
                <h6 class="fw-semibold mb-3">Daftar Item</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width:40px;">No</th>
                                <th>Nama Material</th>
                                <th class="text-center" style="width:90px;">Qty</th>
                                <th class="text-center" style="width:100px;">Qty Fulfilled</th>
                                <th style="width:100px;">Satuan</th>
                                <th class="text-center" style="width:140px;">Progress</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items-tbody"></tbody>
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

@include('material-management.purchase-request-fulfilment.javascript')
