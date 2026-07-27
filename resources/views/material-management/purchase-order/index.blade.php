@extends('layouts.layout')

@section('title', 'Purchase Order List')

@section('content')
<div class="page-content">

    {{-- Filter Card --}}
    <div class="card border-0 shadow-sm hz-card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold mb-1 small text-muted">
                        <i class="bi bi-search me-1"></i>Cari PO
                    </label>
                    <input type="text" class="form-control" id="filter-search" placeholder="Cari nomor PO atau supplier...">
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
                    <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah PO</button>
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
                <table class="table table-hover align-middle w-100" id="table-po">
                    <thead class="table-light">
                            <tr>
                                <th style="width:40px;" class="text-center">No</th>
                                <th>No. PR</th>
                                <th>No. PO</th>
                                <th class="text-center">Tanggal</th>
                                <th>Supplier</th>
                                <th class="text-center">Total Item</th>
                                <th class="text-end">Total Amount</th>
                                <th style="width:110px;" class="text-center">Status</th>
                                <th style="width:100px;" class="text-center">Aksi</th>
                            </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah/Edit --}}
<div class="modal fade" id="modal-po" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah PO</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-po" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="po_id">
                <div class="row g-3 mb-4">
                    <div class="col-4"><label class="form-label fw-semibold">No. PR <span class="text-danger">*</span></label><input type="text" class="form-control" name="pr_number" id="f_pr_number" maxlength="50" placeholder="PR-2026-0001"></div>
                    <div class="col-4"><label class="form-label fw-semibold">No. PO <span class="text-danger">*</span></label><input type="text" class="form-control" name="po_number" id="f_po_number" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label><input type="date" class="form-control" name="po_date" id="f_po_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="DRAFT">Draft</option><option value="PENDING">Pending</option><option value="APPROVED">Approved</option><option value="REJECTED">Rejected</option><option value="FULFILLED">Fulfilled</option></select></div>
                    <div class="col-4"><label class="form-label fw-semibold">Supplier</label><input type="text" class="form-control" name="supplier_name" id="f_supplier" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Kode Supplier</label><input type="text" class="form-control" name="supplier_code" id="f_supplier_code" maxlength="50"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Catatan</label><textarea class="form-control" name="note" id="f_note" rows="2" maxlength="500"></textarea></div>
                </div>
                {{-- Dynamic Items Table --}}
                <div class="card shadow-sm border-0 mb-4"><div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><label class="form-label fw-semibold mb-1">Daftar Item</label><div class="small text-muted">Tambahkan material, qty, satuan, dan harga.</div></div>
                        <button type="button" class="btn btn-sm btn-success" id="btn-add-item"><i class="bi bi-plus-lg me-1"></i>Tambah Item</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="table-items">
                            <thead class="table-secondary">
                                <tr><th class="text-center" style="width:40px;">No</th><th>Nama Material <span class="text-danger">*</span></th><th class="text-center" style="width:100px;">Qty <span class="text-danger">*</span></th><th style="width:120px;">Satuan</th><th class="text-end" style="width:150px;">Harga <span class="text-danger">*</span></th><th class="text-end" style="width:150px;">Subtotal</th><th class="text-center" style="width:50px;">Aksi</th></tr>
                            </thead>
                            <tbody id="items-tbody">
                                <tr id="row-empty"><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i><span class="small">Belum ada item. Klik <strong>"Tambah Item"</strong>.</span></td></tr>
                            </tbody>
                            <tfoot><tr class="table-light fw-bold"><td colspan="5" class="text-end">Total</td><td id="po-total-items" class="text-end">Rp 0</td><td></td></tr></tfoot>
                        </table>
                    </div>
                </div></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Detail PO - <span id="detail-po-number"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">No. PR</small>
                        <span id="detail-po-pr" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal</small>
                        <span id="detail-po-date" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Supplier</small>
                        <span id="detail-po-supplier" class="fw-semibold">-</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Status</small>
                        <span id="detail-po-status">-</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan</small>
                        <span id="detail-po-note" class="fw-semibold">-</span>
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
                                <th class="text-center" style="width:80px;">Qty</th>
                                <th style="width:90px;">Satuan</th>
                                <th class="text-end" style="width:130px;">Harga Satuan</th>
                                <th class="text-end" style="width:140px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail-items-tbody"></tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="5" class="text-end">Total Amount</td>
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

@include('material-management.purchase-order.javascript')
