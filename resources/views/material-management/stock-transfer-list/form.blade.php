{{-- Modal Form Stock Transfer --}}
<div class="modal fade" id="modal-st" tabindex="-1" aria-labelledby="modal-stLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-stLabel">Tambah Stock Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-st" action="javascript:onSaveST()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="st_id">

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="st_number" class="form-label fw-semibold">No. Transfer <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="st_number" name="transfer_number"
                                   placeholder="Cth: ST-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="st_date" class="form-label fw-semibold">Tanggal Transfer <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="st_date" name="transfer_date">
                        </div>

                        <div class="col-md-4">
                            <label for="st_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="st_status" name="status">
                                <option value="PREPARATION">Preparation</option>
                                <option value="SHIPMENT">Shipment</option>
                                <option value="TRANSFER">Transfer</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="st_from" class="form-label fw-semibold">Dari Gudang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="st_from" name="from_warehouse"
                                   placeholder="Gudang asal" maxlength="100">
                        </div>

                        <div class="col-md-6">
                            <label for="st_to" class="form-label fw-semibold">Ke Gudang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="st_to" name="to_warehouse"
                                   placeholder="Gudang tujuan" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="st_pic" class="form-label fw-semibold">PIC</label>
                            <input type="text" class="form-control" id="st_pic" name="pic"
                                   placeholder="Penanggung jawab" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="st_user_id" class="form-label fw-semibold">User Id</label>
                            <input type="text" class="form-control" id="st_user_id" name="user_id"
                                   placeholder="Cth: USR001" maxlength="50">
                        </div>

                        <div class="col-12">
                            <label for="st_reason" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="st_reason" name="reason" rows="2"
                                      placeholder="Alasan transfer"></textarea>
                        </div>
                    </div>

                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">Daftar Material</label>
                            <button type="button" class="btn btn-sm btn-success" id="btn-add-item">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Item
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" id="table-items">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="width:40px;" class="text-center">No</th>
                                        <th>Material <span class="text-danger">*</span></th>
                                        <th style="width:100px;" class="text-center">Qty</th>
                                        <th style="width:100px;">Satuan</th>
                                        <th>Catatan Item</th>
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
