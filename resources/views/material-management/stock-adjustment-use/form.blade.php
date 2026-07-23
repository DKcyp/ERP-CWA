{{-- Modal Form Stock Adjustment Internal Use --}}
<div class="modal fade" id="modal-sau" tabindex="-1" aria-labelledby="modal-sauLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-sauLabel">Tambah Adjustment (Internal Use)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-sau" action="javascript:onSaveSAU()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="sau_id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="sau_number" class="form-label fw-semibold">No. Adjustment <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sau_number" name="adjustment_number"
                                   placeholder="Cth: SA-2026-0001" maxlength="50">
                        </div>

                        <div class="col-md-4">
                            <label for="sau_date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="sau_date" name="adjustment_date">
                        </div>

                        <div class="col-md-4">
                            <label for="sau_status" class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="sau_status" name="status">
                                <option value="DRAFT">Draft</option>
                                <option value="APPROVED">Approved</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="sau_warehouse" class="form-label fw-semibold">Warehouse</label>
                            <input type="text" class="form-control" id="sau_warehouse" name="warehouse"
                                   placeholder="Nama gudang" maxlength="100">
                        </div>

                        <div class="col-12">
                            <label for="sau_reason" class="form-label fw-semibold">Alasan Penggunaan</label>
                            <textarea class="form-control" id="sau_reason" name="reason" rows="2"
                                      placeholder="Alasan pemakaian internal"></textarea>
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
                            <tfoot>
                                <tr class="table-light fw-bold">
                                    <td colspan="6" class="text-end">Total Cost Diff</td>
                                    <td id="total-cost-diff" class="text-end">Rp 0</td>
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
