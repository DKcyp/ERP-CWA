@extends('layouts.layout')

@section('title', 'Stock Transfer Fulfilment')

@section('content')
<div class="page-content">

    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari Transfer
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor, gudang, atau PIC...">
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
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-fulfilment">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th>No. Transfer</th>
                            <th class="text-center">Tanggal</th>
                            <th>Dari Gudang</th>
                            <th>Ke Gudang</th>
                            <th>PIC</th>
                            <th class="text-center">Total Item</th>
                            <th class="text-center">Fulfilled</th>
                            <th style="width:160px;">Progress</th>
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
                <h5 class="modal-title fw-semibold">Detail Transfer - <span id="detail-number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal</small>
                        <span id="detail-date" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Dari Gudang</small>
                        <span id="detail-from" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Ke Gudang</small>
                        <span id="detail-to" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">PIC</small>
                        <span id="detail-pic" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Status</small>
                        <span id="detail-status">-</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan</small>
                        <span id="detail-note" class="fw-semibold">-</span>
                    </div>
                </div>
                <hr>
                <h6 class="fw-semibold mb-3">Daftar Material</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width:40px;">No</th>
                                <th>Material</th>
                                <th class="text-center" style="width:80px;">Qty</th>
                                <th class="text-center" style="width:90px;">Qty Fulfilled</th>
                                <th style="width:90px;">Satuan</th>
                                <th style="width:130px;">Progress</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items-tbody"></tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="2" class="text-end">Total</td>
                                <td id="detail-total-qty" class="text-center">0</td>
                                <td id="detail-total-fulfilled" class="text-center">0</td>
                                <td colspan="2"></td>
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

@include('material-management.stock-transfer-fulfilment.javascript')
