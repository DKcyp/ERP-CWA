{{-- Modal Form STBJ --}}
<div class="modal fade" id="modal-stbj" tabindex="-1" aria-labelledby="modal-stbjLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-stbjLabel">Tambah STBJ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-stbj" action="javascript:onSaveSTBJ()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="stbj_id">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="stbj_number" class="form-label fw-semibold">No. STBJ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="stbj_number" name="stbj_number"
                                   placeholder="Cth: STBJ-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="stbj_date" class="form-label fw-semibold">Tanggal STBJ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="stbj_date" name="stbj_date">
                        </div>

                        <div class="col-md-4">
                            <label for="stbj_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="stbj_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="PENDING">Pending</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="PAID">Paid</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="stbj_supplier" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="stbj_supplier" name="supplier_name"
                                   placeholder="Nama supplier" maxlength="200">
                        </div>

                        <div class="col-md-6">
                            <label for="stbj_po" class="form-label fw-semibold">No. PO</label>
                            <input type="text" class="form-control" id="stbj_po" name="po_number"
                                   placeholder="Cth: PO-2026-0001" maxlength="50">
                        </div>

                        <div class="col-12">
                            <label for="stbj_note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="stbj_note" name="note" rows="2"
                                      placeholder="Catatan STBJ"></textarea>
                        </div>
                    </div>

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
                                        <th>Deskripsi <span class="text-danger">*</span></th>
                                        <th style="width:160px;">Jumlah (Rp)</th>
                                        <th style="width:60px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="items-tbody">
                                    <tr id="row-empty">
                                        <td colspan="4" class="text-center py-4 text-muted">
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
