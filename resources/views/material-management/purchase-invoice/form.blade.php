{{-- Modal Form Purchase Invoice --}}
<div class="modal fade" id="modal-inv" tabindex="-1" aria-labelledby="modal-invLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-invLabel">Tambah Purchase Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-inv" action="javascript:onSaveINV()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="inv_id">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="inv_number" class="form-label fw-semibold">No. Invoice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inv_number" name="invoice_number"
                                   placeholder="Cth: INV-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="inv_date" class="form-label fw-semibold">Tanggal Invoice <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="inv_date" name="invoice_date">
                        </div>

                        <div class="col-md-4">
                            <label for="inv_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="inv_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="PENDING">Pending</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="PAID">Paid</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="inv_po" class="form-label fw-semibold">No. PO <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inv_po" name="po_number"
                                   placeholder="Cth: PO-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-8">
                            <label for="inv_supplier" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inv_supplier" name="supplier_name"
                                   placeholder="Nama supplier" maxlength="200">
                        </div>

                        <div class="col-12">
                            <label for="inv_note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="inv_note" name="note" rows="2"
                                      placeholder="Catatan invoice"></textarea>
                        </div>
                    </div>

                    {{-- Dynamic Items Table --}}
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">Daftar Item</label>
                            <button type="button" class="btn btn-sm btn-success" id="btn-add-item">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Item
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" id="table-items">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="width:40px;" class="text-center">No</th>
                                        <th>Nama Material <span class="text-danger">*</span></th>
                                        <th style="width:100px;" class="text-center">Qty <span class="text-danger">*</span></th>
                                        <th style="width:100px;">Satuan</th>
                                        <th style="width:130px;">Harga Satuan</th>
                                        <th style="width:60px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-tbody">
                                    <tr id="row-empty">
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            <span class="small">Belum ada item. Klik <strong>"Tambah Item"</strong> untuk menambahkan.</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
