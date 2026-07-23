{{-- Modal Form Supplier Center --}}
<div class="modal fade" id="modal-supplier-center" tabindex="-1" aria-labelledby="modal-supplier-centerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-supplier-centerLabel">Tambah Supplier Center</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-supplier-center" action="javascript:onSaveSupplierCenter()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="supplier_center_id">

                    <div class="row g-3">
                        {{-- Kode Center --}}
                        <div class="col-md-4">
                            <label for="sc_code" class="form-label fw-semibold">Kode Center <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sc_code" name="code"
                                   placeholder="Cth: CTR-001" maxlength="50">
                        </div>

                        {{-- Nama Center --}}
                        <div class="col-md-8">
                            <label for="sc_name" class="form-label fw-semibold">Nama Center <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sc_name" name="name"
                                   placeholder="Nama lengkap center" maxlength="100">
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
