@extends('layouts.layout')
@section('title', 'Riset Result Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Riset Result Report</h4>
        <small class="text-muted">Riset - Hasil Pengujian & Rilis Formula Standar Baru</small>
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
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari Doc ID, LHR, Product...">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()"><i class="bi bi-x-circle me-1"></i>Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Doc ID</th>
                        <th>Date</th>
                        <th>Last Status Update</th>
                        <th>No. LHR</th>
                        <th>Status</th>
                        <th>Riset ID</th>
                        <th>Product</th>
                        <th>Name</th>
                        <th>FA</th>
                        <th>Revisi</th>
                        <th>Substart</th>
                        <th>Pemakaian</th>
                        <th>Instruksi Penyaringan</th>
                        <th>Jenis Saringan</th>
                        <th>User</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-clipboard-data me-1"></i><span id="modalTitle">Tambah Result Report</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header Informasi</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Riset ID</label><input type="text" class="form-control form-control-sm" id="riset_id" placeholder="RST-XXXX"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">No. LHR</label><input type="text" class="form-control form-control-sm" id="no_lhr" placeholder="LHR-XXXX"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Product ID</label><input type="text" class="form-control form-control-sm" id="product_id" placeholder="PRD-XXXX"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Nama Formula</label><input type="text" class="form-control form-control-sm" id="nama_formula" placeholder="Nama formula"></div>
                                <div class="col-md-1"><label class="form-label form-label-sm">FA</label><input type="text" class="form-control form-control-sm" id="fa" placeholder="FA"></div>
                                <div class="col-md-1"><label class="form-label form-label-sm">Rev</label><input type="number" class="form-control form-control-sm" id="rev" min="0" value="0"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Report ID</label><input type="text" class="form-control form-control-sm" id="report_id" placeholder="RPT-XXXX"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Created Date</label><input type="date" class="form-control form-control-sm" id="created_date"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Status</label><select class="form-select form-select-sm" id="status"><option>Draft</option><option>Reviewed</option><option>Approved</option><option>Rejected</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">User ID</label><input type="text" class="form-control form-control-sm" id="user_id" placeholder="User ID"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Substart</label><input type="text" class="form-control form-control-sm" id="substart" placeholder="Substart"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Pemakaian</label><input type="text" class="form-control form-control-sm" id="pemakaian" placeholder="Pemakaian"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Hapus STD Lama</label><select class="form-select form-select-sm" id="hapus_std"><option value="Ya">Ya</option><option value="Tidak">Tidak</option></select></div>
                                <div class="col-md-4"><label class="form-label form-label-sm">Last Status Update</label><input type="text" class="form-control form-control-sm" id="last_status" placeholder="Last status update" readonly></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="openDetailFromForm()"><i class="bi bi-eye me-1"></i>Buka Detail</button>
                <button type="button" class="btn btn-sm btn-success" onclick="saveForm()"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let data = [
    {doc_id:'RES-001', date:'2026-08-15', last_status:'2026-08-18', no_lhr:'LHR-001', status:'Approved', riset_id:'RST-001', product:'PRD-1001', name:'Cat Tembok Putih Premium', fa:'FA-001', revisi:2, substart:'Cat Dasar', pemakaian:'Interior', hapus_std:'Tidak', instruksi_saringan:'Gunakan mesh 200', jenis_saringan:'Mesh 200', user:'Rina', notes:'Lulus semua spesifikasi'},
    {doc_id:'RES-002', date:'2026-08-16', last_status:'2026-08-19', no_lhr:'LHR-002', status:'Reviewed', riset_id:'RST-002', product:'PRD-1002', name:'Cat Solvent Merah Export', fa:'FA-002', revisi:1, substart:'Cat Anti Karat', pemakaian:'Eksterior', hapus_std:'Ya', instruksi_saringan:'Saring 2x mesh 100', jenis_saringan:'Mesh 100', user:'Dika', notes:'Perlu review ulang pH'},
    {doc_id:'RES-003', date:'2026-08-18', last_status:'2026-08-20', no_lhr:'LHR-003', status:'Draft', riset_id:'RST-003', product:'PRD-1003', name:'Primer Anti-Korosi', fa:'FA-003', revisi:0, substart:'Primer Zinc', pemakaian:'Marine', hapus_std:'Tidak', instruksi_saringan:'Mesh 150', jenis_saringan:'Mesh 150', user:'Andi', notes:'Prototipe baru'},
];

let editingId = null;

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => {
        const badge = d.status === 'Approved' ? 'bg-success' : d.status === 'Reviewed' ? 'bg-info' : d.status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark';
        return `
        <tr style="cursor:pointer" onclick="openDetail('${d.doc_id}')">
            <td>${i + 1}</td>
            <td><strong>${d.doc_id}</strong></td>
            <td>${d.date}</td>
            <td>${d.last_status}</td>
            <td>${d.no_lhr}</td>
            <td><span class="badge ${badge}">${d.status}</span></td>
            <td>${d.riset_id}</td>
            <td>${d.product}</td>
            <td>${d.name}</td>
            <td>${d.fa}</td>
            <td class="text-center">${d.revisi}</td>
            <td>${d.substart}</td>
            <td>${d.pemakaian}</td>
            <td>${d.instruksi_saringan}</td>
            <td>${d.jenis_saringan}</td>
            <td>${d.user}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="event.stopPropagation();editRecord('${d.doc_id}')"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();deleteRecord('${d.doc_id}')"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    }).join('');
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.doc_id.toLowerCase().includes(s) || d.no_lhr.toLowerCase().includes(s) || d.product.toLowerCase().includes(s) || d.name.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() { document.getElementById('filterSearch').value = ''; renderTable(data); }

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Result Report');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(docId) {
    const d = data.find(x => x.doc_id === docId);
    if (!d) return;
    editingId = docId;
    $('#modalTitle').text('Edit Result Report');
    $('#formId').val(docId);
    $('#riset_id').val(d.riset_id); $('#no_lhr').val(d.no_lhr); $('#product_id').val(d.product);
    $('#nama_formula').val(d.name); $('#fa').val(d.fa); $('#rev').val(d.revisi);
    $('#report_id').val(d.doc_id); $('#created_date').val(d.date); $('#status').val(d.status);
    $('#user_id').val(d.user); $('#substart').val(d.substart); $('#pemakaian').val(d.pemakaian);
    $('#hapus_std').val(d.hapus_std); $('#last_status').val(d.last_status);
    new bootstrap.Modal('#formModal').show();
}

function openDetailFromForm() {
    const pid = $('#product_id').val().trim() || 'PRD-XXXX';
    window.open('{{ url("/riset-result-report") }}/new/detail', '_blank');
}

function saveForm() {
    const pid = $('#product_id').val().trim();
    const pname = $('#nama_formula').val().trim();
    if (!pid) { alert('Product ID wajib diisi'); return; }
    if (!pname) { alert('Nama Formula wajib diisi'); return; }
    const payload = {
        doc_id: $('#report_id').val().trim() || 'RES-' + String(data.length+1).padStart(3,'0'),
        date: $('#created_date').val(), last_status: $('#last_status').val() || '-',
        no_lhr: $('#no_lhr').val().trim(), status: $('#status').val(),
        riset_id: $('#riset_id').val().trim(), product: pid, name: pname,
        fa: $('#fa').val().trim(), revisi: parseInt($('#rev').val())||0,
        substart: $('#substart').val().trim(), pemakaian: $('#pemakaian').val().trim(),
        hapus_std: $('#hapus_std').val(), instruksi_saringan: '-', jenis_saringan: '-',
        user: $('#user_id').val().trim(), notes: '-'
    };
    if (editingId) { const d = data.find(x => x.doc_id === editingId); if(d) Object.assign(d,payload); }
    else { data.push(payload); }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(docId) { if(!confirm('Hapus report ini?')) return; data = data.filter(x=>x.doc_id!==docId); renderTable(data); }

$(function() { renderTable(data); $('#filterSearch').on('keyup', filterData); });
</script>
@endpush
