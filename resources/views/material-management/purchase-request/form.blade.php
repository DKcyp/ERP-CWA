{{-- Modal Form Purchase Request --}}
<div class="modal fade" id="modal-pr" tabindex="-1" aria-labelledby="modal-prLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-prLabel">Tambah Purchase Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-pr" action="javascript:onSavePR()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="pr_id">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="pr_number" class="form-label fw-semibold">No. PR <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pr_number" name="pr_number"
                                   placeholder="Cth: PR-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_date" class="form-label fw-semibold">Tanggal PR <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="pr_date" name="pr_date">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="pr_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="PENDING">Pending</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                                <option value="FULFILLED">Fulfilled</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="pr_requester" class="form-label fw-semibold">Requester <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pr_requester" name="requester"
                                   placeholder="Nama requester" maxlength="100">
                        </div>

                        <div class="col-md-6">
                            <label for="pr_department" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pr_department" name="department"
                                   placeholder="Cth: Produksi" maxlength="100">
                        </div>

                        <div class="col-12">
                            <label for="pr_note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="pr_note" name="note" rows="2"
                                      placeholder="Catatan permintaan pembelian"></textarea>
                        </div>
                    </div>

                    {{-- Dynamic Items Table --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label class="form-label fw-semibold mb-1">Daftar Item</label>
                                    <div class="small text-muted">Tambahkan material dan jumlah yang diminta untuk PR ini.</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-success" id="btn-add-item">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Item
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0" id="table-items">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th style="width:40px;" class="text-center">No</th>
                                            <th>Nama Material <span class="text-danger">*</span></th>
                                            <th style="width:120px;" class="text-center">Qty <span class="text-danger">*</span></th>
                                            <th style="width:130px;">Satuan</th>
                                            <th style="width:60px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-tbody">
                                        <tr id="row-empty">
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                <span class="small">Belum ada item. Klik <strong>"Tambah Item"</strong> untuk menambahkan.</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
