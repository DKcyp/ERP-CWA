<div class="modal fade" id="modalMenu" tabindex="-1" aria-labelledby="modalMenuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formMenu" method="POST" action="{{ route('menu.store') }}">
            @csrf
            <input type="hidden" name="id" id="menu_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMenuLabel">Tambah Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Menu</label>
                        <input type="text" class="form-control" name="code" id="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label">URL / Route</label>
                        <input type="text" class="form-control" name="url" id="url">
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">Pilih Icon</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="icon" id="icon" placeholder="bi bi-gear" required>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                                Pilih Icon
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="main_menu" class="form-label">Parent Menu</label>
                        <select class="form-select" name="main_menu" id="main_menu">
                            <option value="">- Tidak Ada -</option>
                            @foreach ($menuOptions as $menu)
                            <option value="{{ $menu['id'] }}">{{ $menu['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sort" class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="sort" id="sort" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Status</label>
                        <select class="form-select" name="active" id="active">
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmitMenu">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="iconSearch" class="form-control mb-3" placeholder="Cari icon...">
                <div id="iconList"></div>
            </div>
        </div>
    </div>
</div>
