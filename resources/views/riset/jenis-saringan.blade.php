@extends('layouts.layout')
@section('title', 'Jenis Saringan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-grid me-2"></i>Jenis Saringan</h4>
        <small class="text-muted">Riset - Tipe & Ukuran Saringan</small>
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
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari jenis saringan...">
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
                    <th>Jenis Saringan</th>
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
                <h6 class="modal-title"><i class="bi bi-grid me-1"></i><span id="modalTitle">Tambah Jenis Saringan</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Jenis Saringan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="jenis_saringan" placeholder="Contoh: Mesh 200, Filter Cloth, dll." required>
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
    {id:1, jenis_saringan:'Mesh 100 (150 micron)'},
    {id:2, jenis_saringan:'Mesh 200 (75 micron)'},
    {id:3, jenis_saringan:'Mesh 325 (44 micron)'},
    {id:4, jenis_saringan:'Filter Cloth - Nylon'},
    {id:5, jenis_saringan:'Filter Cloth - Polyester'},
    {id:6, jenis_saringan:'Filter Bag - Stainless Steel'},
    {id:7, jenis_saringan:'Saringan Kain Katun'},
];

let editingId = null;

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => `
        <tr>
            <td>${i + 1}</td>
            <td><strong>${d.jenis_saringan}</strong></td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRecord(${d.id})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(${d.id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `).join('');
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.jenis_saringan.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    renderTable(data);
}

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Jenis Saringan');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id) {
    const d = data.find(x => x.id === id);
    if (!d) return;
    editingId = id;
    $('#modalTitle').text('Edit Jenis Saringan');
    $('#formId').val(id);
    $('#jenis_saringan').val(d.jenis_saringan);
    new bootstrap.Modal('#formModal').show();
}

function saveForm() {
    const jenis = $('#jenis_saringan').val().trim();
    if (!jenis) { alert('Jenis Saringan wajib diisi'); return; }

    if (editingId) {
        const d = data.find(x => x.id === editingId);
        if (d) d.jenis_saringan = jenis;
    } else {
        const newId = Math.max(...data.map(x => x.id)) + 1;
        data.push({ id: newId, jenis_saringan: jenis });
    }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(id) {
    if (!confirm('Hapus jenis saringan ini?')) return;
    data = data.filter(x => x.id !== id);
    renderTable(data);
}

$(function() {
    renderTable(data);
    $('#filterSearch').on('keyup', filterData);
});
</script>
@endpush
