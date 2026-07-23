{{-- Modal Form Purchase Return --}}
<div class="modal fade" id="modal-pr" tabindex="-1" aria-labelledby="modal-prLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-prLabel">Tambah Purchase Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-pr" action="javascript:onSavePR()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="pr_id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="pr_number" class="form-label fw-semibold">Id <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pr_number" name="return_number"
                                   placeholder="Cth: PR-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_date" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="pr_date" name="return_date">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="pr_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="APPROVED">Approved</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="pr_warehouse" class="form-label fw-semibold">Warehouse</label>
                            <input type="text" class="form-control" id="pr_warehouse" name="warehouse"
                                   placeholder="Nama gudang" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_supplier" class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pr_supplier" name="supplier_name"
                                   placeholder="Nama supplier" maxlength="200">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_supplier_id" class="form-label fw-semibold">Supplier Id</label>
                            <input type="text" class="form-control" id="pr_supplier_id" name="supplier_id"
                                   placeholder="Cth: SUP-001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_currency" class="form-label fw-semibold">Curr.</label>
                            <input type="text" class="form-control" id="pr_currency" name="currency"
                                   placeholder="Cth: IDR" maxlength="10" value="IDR">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_term" class="form-label fw-semibold">Term</label>
                            <input type="text" class="form-control" id="pr_term" name="term"
                                   placeholder="Cth: Net 14" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_price_list" class="form-label fw-semibold">Price List</label>
                            <input type="text" class="form-control" id="pr_price_list" name="price_list"
                                   placeholder="Cth: PL-001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_user" class="form-label fw-semibold">User</label>
                            <input type="text" class="form-control" id="pr_user" name="user_name"
                                   placeholder="Nama pengguna" maxlength="150">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_account" class="form-label fw-semibold">Account</label>
                            <input type="text" class="form-control" id="pr_account" name="account"
                                   placeholder="Cth: Bank Mandiri" maxlength="100">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_disc_percent" class="form-label fw-semibold">Disc %</label>
                            <input type="number" class="form-control" id="pr_disc_percent" name="discount_percent"
                                   placeholder="0" min="0" max="100">
                        </div>

                        <div class="col-md-4">
                            <label for="pr_disc_amount" class="form-label fw-semibold">Disc. Amount</label>
                            <input type="number" class="form-control" id="pr_disc_amount" name="discount_amount"
                                   placeholder="0" min="0">
                        </div>

                        <div class="col-12">
                            <label for="pr_note" class="form-label fw-semibold">Note</label>
                            <textarea class="form-control" id="pr_note" name="note" rows="2"
                                      placeholder="Catatan retur"></textarea>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-semibold mb-3">Daftar Item Return</h6>
                    <div class="table-responsive mb-2">
                        <table class="table table-bordered align-middle" id="table-items">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;" class="text-center">No</th>
                                    <th>Nama Material <span class="text-danger">*</span></th>
                                    <th style="width:100px;" class="text-center">Qty <span class="text-danger">*</span></th>
                                    <th style="width:90px;">Satuan</th>
                                    <th style="width:130px;" class="text-end">Harga <span class="text-danger">*</span></th>
                                    <th style="width:140px;" class="text-end">Subtotal</th>
                                    <th style="width:50px;" class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-success" id="btn-add-item">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="items-tbody">
                                <tr class="empty-row">
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="bi bi-inbox me-1"></i> Belum ada item. Klik (+) untuk menambah.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="5" class="text-end">Total</td>
                                    <td id="total-amount" class="text-end">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
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
