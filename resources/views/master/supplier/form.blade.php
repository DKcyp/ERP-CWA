{{-- Modal Form Supplier --}}
<div class="modal fade" id="modal-supplier" tabindex="-1" aria-labelledby="modal-supplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-supplierLabel">Tambah Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-supplier" action="javascript:onSaveSupplier()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="supplier_id">

                    <div class="row g-3">
                        {{-- Kode Supplier --}}
                        <div class="col-md-4">
                            <label for="supplier_code" class="form-label fw-semibold">Kode Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_code" name="supplier_code"
                                   placeholder="Cth: SUP-001" maxlength="50">
                        </div>

                        {{-- Nama Supplier --}}
                        <div class="col-md-8">
                            <label for="supplier_name" class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_name" name="name"
                                   placeholder="Nama lengkap supplier" maxlength="150">
                        </div>

                        {{-- Grup Supplier --}}
                        <div class="col-md-6">
                            <label for="supplier_group_id" class="form-label fw-semibold">Grup Supplier</label>
                            <select name="supplier_group_id" id="supplier_group_id" class="form-control" style="width:100%;"></select>
                        </div>

                        {{-- Center Supplier --}}
                        <div class="col-md-6">
                            <label for="supplier_center_id" class="form-label fw-semibold">Center / Area</label>
                            <select name="supplier_center_id" id="supplier_center_id" class="form-control" style="width:100%;"></select>
                        </div>

                        {{-- No. Telp --}}
                        <div class="col-md-6">
                            <label for="supplier_phone" class="form-label fw-semibold">No. Telp</label>
                            <input type="text" class="form-control" id="supplier_phone" name="phone"
                                   placeholder="08xx-xxxx-xxxx" maxlength="30">
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="supplier_email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="supplier_email" name="email"
                                   placeholder="supplier@example.com" maxlength="100">
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label for="supplier_address" class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control" id="supplier_address" name="address" rows="3"
                                      placeholder="Alamat lengkap supplier"></textarea>
                        </div>

                        {{-- TOP --}}
                        <div class="col-md-4">
                            <label for="supplier_top" class="form-label fw-semibold">Term of Payment (Hari)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="supplier_top" name="term_of_payment"
                                       placeholder="0" min="0" value="0">
                                <span class="input-group-text">Hari</span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch ms-1 mb-2">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="supplier_status" name="status" value="1" checked>
                                <label class="form-check-label fw-semibold" for="supplier_status">Aktif</label>
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
