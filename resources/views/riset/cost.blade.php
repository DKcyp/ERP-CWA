@extends('layouts.layout')
@section('title', 'Cost')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Cost</h4>
        <small class="text-muted">Riset - Komponen Biaya Riset</small>
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
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari nama biaya...">
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
                    <th>Nama Biaya</th>
                    <th class="text-end">Biaya</th>
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
                <h6 class="modal-title"><i class="bi bi-cash-stack me-1"></i><span id="modalTitle">Tambah Biaya</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Nama Biaya <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="nama_biaya" placeholder="Contoh: Overhead Lab" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Biaya (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm" id="biaya" placeholder="0" min="0" required>
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
    {id:1, nama_biaya:'Overhead Laboratorium', biaya:500000},
    {id:2, nama_biaya:'Operasional Mixer', biaya:150000},
    {id:3, nama_biaya:'Pengujian Eksternal (SNI)', biaya:2500000},
    {id:4, nama_biaya:'Biaya Energi Listrik', biaya:200000},
    {id:5, nama_biaya:'Biaya Pengiriman Sampel', biaya:100000},
    {id:6, nama_biaya:'Jasa Konsultan QC', biaya:3000000},
    {id:7, nama_biaya:'Biaya Cetak Laporan', biaya:50000},
];

let editingId = null;

function formatRupiah(val) {
    return 'Rp ' + Number(val).toLocaleString('id-ID');
}

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${d.nama_biaya}</td>
            <td class="text-end"><strong>${formatRupiah(d.biaya)}</strong></td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRecord(${d.id})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(${d.id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `).join('');
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.nama_biaya.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    renderTable(data);
}

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Biaya');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id) {
    const d = data.find(x => x.id === id);
    if (!d) return;
    editingId = id;
    $('#modalTitle').text('Edit Biaya');
    $('#formId').val(id);
    $('#nama_biaya').val(d.nama_biaya);
    $('#biaya').val(d.biaya);
    new bootstrap.Modal('#formModal').show();
}

function saveForm() {
    const nama = $('#nama_biaya').val().trim();
    const biaya = parseInt($('#biaya').val()) || 0;
    if (!nama) { alert('Nama Biaya wajib diisi'); return; }
    if (biaya < 0) { alert('Biaya tidak boleh negatif'); return; }

    if (editingId) {
        const d = data.find(x => x.id === editingId);
        if (d) { d.nama_biaya = nama; d.biaya = biaya; }
    } else {
        const newId = Math.max(...data.map(x => x.id)) + 1;
        data.push({ id: newId, nama_biaya: nama, biaya: biaya });
    }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(id) {
    if (!confirm('Hapus biaya ini?')) return;
    data = data.filter(x => x.id !== id);
    renderTable(data);
}

$(function() {
    renderTable(data);
    $('#filterSearch').on('keyup', filterData);
});
</script>
@endpush
