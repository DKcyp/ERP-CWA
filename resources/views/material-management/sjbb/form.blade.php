{{-- Modal Form SJBB --}}
<div class="modal fade" id="modal-sjbb" tabindex="-1" aria-labelledby="modal-sjbbLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-sjbbLabel">Tambah SJBB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-sjbb" action="javascript:onSaveSJBB()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="sjbb_id">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="sjbb_number" class="form-label fw-semibold">No. SJBB <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sjbb_number" name="sjbb_number"
                                   placeholder="Cth: SJBB-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sjbb_date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sjbb_date" name="sjbb_date">
                        </div>

                        <div class="col-md-4">
                            <label for="sjbb_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="sjbb_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="APPROVED">Approved</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sjbb_supplier" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sjbb_supplier" name="supplier_name"
                                   placeholder="Nama supplier" maxlength="200">
                        </div>

                        <div class="col-md-4">
                            <label for="sjbb_supplier_id" class="form-label fw-semibold">Supplier Id</label>
                            <input type="text" class="form-control" id="sjbb_supplier_id" name="supplier_id"
                                   placeholder="Cth: SUP-001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sjbb_type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="sjbb_type" name="type">
                                <option value="IN">IN (Masuk)</option>
                                <option value="OUT">OUT (Keluar)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="sjbb_notes" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="sjbb_notes" name="notes" rows="3"
                                      placeholder="Keterangan barter"></textarea>
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
