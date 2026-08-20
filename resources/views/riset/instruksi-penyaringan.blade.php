@extends('layouts.layout')
@section('title', 'Instruksi Penyaringan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-funnel me-2"></i>Instruksi Penyaringan</h4>
        <small class="text-muted">Riset - Standar Prosedur Penyaringan</small>
    </div>
    <div>
        <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari instruksi...">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()"><i class="bi bi-x-circle me-1"></i>Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;">
            <thead class="table-dark">
                <tr>
                    <th width="20">#</th>
                    <th>Instruksi Penyaringan</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-funnel me-1"></i><span id="modalTitle">Tambah Instruksi Penyaringan</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Instruksi Penyaringan <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" id="instruksi" rows="4" placeholder="Masukkan instruksi penyaringan..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="saveForm()"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let data = [
    {id:1, instruksi:'Gunakan saringan mesh 200 untuk penyaringan bahan baku pigmen. Pastikan saringan dalam keadaan bersih sebelum digunakan. Ulangi penyaringan maksimal 3 kali jika masih ada endapan.'},
    {id:2, instruksi:'Untuk produk base, gunakan saringan mesh 100. Saring minimal 2 kali untuk memastikan tidak ada gumpalan. Catat volume saringan sebelum dan sesudah proses.'},
    {id:3, instruksi:'Penyaringan kemasan: periksa kondisi kemasan sebelum diisi. Tolak kemasan yang penyok, bocor, atau memiliki cacat visual. Gunakan UV light untuk inspeksi kemasan transparan.'},
    {id:4, instruksi:'Saringan halus mesh 325 hanya untuk produk khusus (cat premium). Lakukan di ruang terkontrol dengan suhu 22-25C. Dokumentasikan setiap batch yang disaring.'},
    {id:5, instruksi:'Setelah penyaringan, bersihkan saringan dengan solvent yang sesuai. Lap dengan kain bersih dan simpan dalam wadah tertutup. Laporkan kerusakan saringan ke QC.'},
];

let editingId = null;

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${d.instruksi}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRecord(${d.id})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(${d.id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `).join('');
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.instruksi.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    renderTable(data);
}

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Instruksi Penyaringan');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id) {
    const d = data.find(x => x.id === id);
    if (!d) return;
    editingId = id;
    $('#modalTitle').text('Edit Instruksi Penyaringan');
    $('#formId').val(id);
    $('#instruksi').val(d.instruksi);
    new bootstrap.Modal('#formModal').show();
}

function saveForm() {
    const instruksi = $('#instruksi').val().trim();
    if (!instruksi) { alert('Instruksi Penyaringan wajib diisi'); return; }

    if (editingId) {
        const d = data.find(x => x.id === editingId);
        if (d) d.instruksi = instruksi;
    } else {
        const newId = Math.max(...data.map(x => x.id)) + 1;
        data.push({ id: newId, instruksi: instruksi });
    }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(id) {
    if (!confirm('Hapus instruksi ini?')) return;
    data = data.filter(x => x.id !== id);
    renderTable(data);
}

$(function() {
    renderTable(data);
    $('#filterSearch').on('keyup', filterData);
});
</script>
@endpush
