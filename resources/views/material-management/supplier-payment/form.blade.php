{{-- Modal Form Supplier Payment --}}
<div class="modal fade" id="modal-sp" tabindex="-1" aria-labelledby="modal-spLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-spLabel">Tambah Supplier Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-sp" action="javascript:onSaveSP()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="sp_id">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="sp_number" class="form-label fw-semibold">No. Payment <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sp_number" name="payment_number"
                                   placeholder="Cth: SP-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_date" class="form-label fw-semibold">Tanggal Payment <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sp_date" name="payment_date">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="sp_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="PENDING">Pending</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="PAID">Paid</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sp_supplier" class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sp_supplier" name="supplier_name"
                                   placeholder="Nama supplier" maxlength="200">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_supplier_id" class="form-label fw-semibold">Supplier Id</label>
                            <input type="text" class="form-control" id="sp_supplier_id" name="supplier_id"
                                   placeholder="Cth: SUP-001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_currency" class="form-label fw-semibold">Curr.</label>
                            <input type="text" class="form-control" id="sp_currency" name="currency"
                                   placeholder="Cth: IDR" maxlength="10" value="IDR">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_payment_type" class="form-label fw-semibold">Payment Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="sp_payment_type" name="payment_type">
                                <option value="Regular">Regular</option>
                                <option value="Down">Down</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sp_account" class="form-label fw-semibold">Account</label>
                            <input type="text" class="form-control" id="sp_account" name="account"
                                   placeholder="Cth: Bank Mandiri - 1234567890" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_user" class="form-label fw-semibold">User</label>
                            <input type="text" class="form-control" id="sp_user" name="user_name"
                                   placeholder="Nama pengguna" maxlength="150">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_complete_date" class="form-label fw-semibold">Complete Date</label>
                            <input type="date" class="form-control" id="sp_complete_date" name="complete_date">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_stbj" class="form-label fw-semibold">No. STBJ</label>
                            <input type="text" class="form-control" id="sp_stbj" name="stbj_number"
                                   placeholder="Cth: STBJ-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sp_invoice" class="form-label fw-semibold">No. Invoice</label>
                            <input type="text" class="form-control" id="sp_invoice" name="invoice_number"
                                   placeholder="Cth: INV-2026-0001" maxlength="50">
                        </div>

                        <div class="col-12">
                            <label for="sp_note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="sp_note" name="note" rows="2"
                                      placeholder="Catatan payment"></textarea>
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
