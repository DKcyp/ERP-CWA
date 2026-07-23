{{-- Modal Form Supplier Group --}}
<div class="modal fade" id="modal-supplier-group" tabindex="-1" aria-labelledby="modal-supplier-groupLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-supplier-groupLabel">Tambah Supplier Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-supplier-group" action="javascript:onSaveSupplierGroup()">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="supplier_group_id">

                    <div class="row g-3">
                        {{-- Kode Group --}}
                        <div class="col-md-4">
                            <label for="sg_code" class="form-label fw-semibold">Kode Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sg_code" name="code"
                                   placeholder="Cth: GRP-001" maxlength="50">
                        </div>

                        {{-- Nama Group --}}
                        <div class="col-md-8">
                            <label for="sg_name" class="form-label fw-semibold">Nama Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sg_name" name="name"
                                   placeholder="Nama lengkap group" maxlength="100">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-12">
                            <label for="sg_description" class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="sg_description" name="description" rows="3"
                                      placeholder="Deskripsi group (opsional)"></textarea>
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
