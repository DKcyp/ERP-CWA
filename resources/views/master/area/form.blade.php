<div class="modal fade" id="modal-area" tabindex="-1" aria-labelledby="modal-areaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-areaLabel">Tambah Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="form-area" action="javascript:onSave()" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="area_id">

                    <div class="form-group">
                        <label for="kategori_areaid" class="col-sm-4 control-label">Kategori Area</label>
                        <div class="col-sm-12">
                            <select name="kategori_areaid" id="kategori_areaid" class="form-control" style="width:100%;"></select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nama_area" class="form-label">Nama Area</label>
                        <input type="text" class="form-control" id="nama_area" name="nama_area" placeholder="Masukkan Nama Area">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-floppy"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>