{{-- Modal Form Stock Adjustment List --}}
<div class="modal fade" id="modal-sal" tabindex="-1" aria-labelledby="modal-salLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-salLabel">Tambah Stock Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-sal" action="javascript:onSaveSAL()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="sal_id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="sal_number" class="form-label fw-semibold">Id <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sal_number" name="adjustment_number"
                                   placeholder="Cth: SA-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_date" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sal_date" name="adjustment_date">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="sal_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="APPROVED">Approved</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sal_warehouse" class="form-label fw-semibold">Warehouse</label>
                            <input type="text" class="form-control" id="sal_warehouse" name="warehouse"
                                   placeholder="Nama gudang" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_department" class="form-label fw-semibold">Department</label>
                            <input type="text" class="form-control" id="sal_department" name="department"
                                   placeholder="Departemen" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_type" class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="sal_type" name="adjustment_type">
                                <option value="STANDARD">Standard</option>
                                <option value="INTERNAL_USE">Internal Use</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sal_use_for" class="form-label fw-semibold">Use For</label>
                            <input type="text" class="form-control" id="sal_use_for" name="use_for"
                                   placeholder="Tujuan penggunaan" maxlength="200">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_transfer_ta" class="form-label fw-semibold">Transfer to TA</label>
                            <input type="text" class="form-control" id="sal_transfer_ta" name="transfer_to_ta"
                                   placeholder="Cth: TA-001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_product_group" class="form-label fw-semibold">Product Group</label>
                            <input type="text" class="form-control" id="sal_product_group" name="product_group"
                                   placeholder="Grup produk" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_pic" class="form-label fw-semibold">PIC</label>
                            <input type="text" class="form-control" id="sal_pic" name="pic"
                                   placeholder="Penanggung jawab" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="sal_user_id" class="form-label fw-semibold">User Id</label>
                            <input type="text" class="form-control" id="sal_user_id" name="user_id"
                                   placeholder="Cth: USR001" maxlength="50">
                        </div>

                        <div class="col-12">
                            <label for="sal_reason" class="form-label fw-semibold">Note</label>
                            <textarea class="form-control" id="sal_reason" name="reason" rows="2"
                                      placeholder="Catatan adjustment"></textarea>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-semibold mb-3">Daftar Material</h6>
                    <div class="table-responsive mb-2">
                        <table class="table table-bordered align-middle" id="table-items">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;" class="text-center">No</th>
                                    <th>Material <span class="text-danger">*</span></th>
                                    <th style="width:100px;" class="text-center">System Qty</th>
                                    <th style="width:100px;" class="text-center">Physical Qty</th>
                                    <th style="width:100px;" class="text-center">Qty Diff</th>
                                    <th style="width:130px;" class="text-end">Cost/Unit</th>
                                    <th style="width:130px;" class="text-end">Total Cost Diff</th>
                                    <th style="width:50px;" class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-item">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody">
                                <tr class="empty-row">
                                    <td colspan="8" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
