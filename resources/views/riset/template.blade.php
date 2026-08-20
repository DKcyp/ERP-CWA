@extends('layouts.layout')
@section('title', 'Template')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Template</h4>
        <small class="text-muted">Riset - Template Formulasi Produk (BOM Riset)</small>
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
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari template, product, FA...">
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
                        <th>ID</th>
                        <th>Date</th>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>FA</th>
                        <th>Rev</th>
                        <th>Status</th>
                        <th>User</th>
                        <th>Notes</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL FORM TEMPLATE --}}
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-file-earmark-text me-1"></i><span id="modalTitle">Tambah Template</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header Informasi</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Product ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="product_id" placeholder="PRD-XXXX" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="product_name" placeholder="Nama produk" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">FA</label>
                                    <input type="text" class="form-control form-control-sm" id="fa" placeholder="FA-XXX">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label form-label-sm">Rev</label>
                                    <input type="number" class="form-control form-control-sm" id="rev" min="0" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Date</label>
                                    <input type="date" class="form-control form-control-sm" id="date">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">User</label>
                                    <input type="text" class="form-control form-control-sm" id="user" placeholder="User ID">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Status</label>
                                    <select class="form-select form-select-sm" id="status">
                                        <option value="Draft">Draft</option>
                                        <option value="Active">Active</option>
                                        <option value="Archived">Archived</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm">Notes</label>
                                    <input type="text" class="form-control form-control-sm" id="notes" placeholder="Catatan...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="bi bi-list-ol me-1"></i>Komponen Formula</h6>
                            <button type="button" class="btn btn-sm btn-success" onclick="addRow()"><i class="bi bi-plus-lg me-1"></i>Tambah Baris</button>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered table-sm mb-0" style="font-size:0.82rem;">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="30">#</th>
                                        <th>Kode Bahan</th>
                                        <th>Nama Bahan</th>
                                        <th width="120">Formula %</th>
                                        <th width="60">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="formulaBody"></tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end"><strong>Total Formulasi</strong></td>
                                        <td class="text-end" style="width:120px"><strong id="totalFormula">0.00%</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
    {id:'TPL-001', date:'2026-08-01', product_id:'PRD-1001', name:'Cat Tembok Putih Premium', fa:'FA-001', rev:2, status:'Active', user:'Rina', notes:'Formula standar WB putih'},
    {id:'TPL-002', date:'2026-08-05', product_id:'PRD-1002', name:'Cat Solvent Merah Export', fa:'FA-002', rev:1, status:'Active', user:'Dika', notes:'Untuk pasar ekspor'},
    {id:'TPL-003', date:'2026-08-10', product_id:'PRD-1003', name:'Primer Anti-Korosi', fa:'FA-003', rev:0, status:'Draft', user:'Andi', notes:'Prototipe baru'},
    {id:'TPL-004', date:'2026-08-12', product_id:'PRD-1004', name:'Top Coat Clear Glossy', fa:'FA-004', rev:3, status:'Active', user:'Rina', notes:'Revisi ke-3'},
    {id:'TPL-005', date:'2026-08-15', product_id:'PRD-1005', name:'Pasta Printing CMYK', fa:'FA-005', rev:1, status:'Archived', user:'Budi', notes:'Sudah tidak dipakai'},
];

let editingId = null;
let rowCounter = 0;

const defaultFormula = [
    {kode:'BB-001', nama:'Titanium Dioxide (TiO2)', persen:15.0},
    {kode:'BB-002', nama:'Resin Acrylic Emulsion', persen:35.0},
    {kode:'BB-003', nama:'Calcium Carbonate', persen:25.0},
    {kode:'BB-004', nama:'Water', persen:20.0},
    {kode:'BB-005', nama:'Additive', persen:5.0},
];

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => {
        const badge = d.status === 'Active' ? 'bg-success' : d.status === 'Draft' ? 'bg-warning text-dark' : 'bg-secondary';
        return `
        <tr>
            <td>${i + 1}</td>
            <td><strong>${d.id}</strong></td>
            <td>${d.date}</td>
            <td>${d.product_id}</td>
            <td>${d.name}</td>
            <td>${d.fa}</td>
            <td class="text-center">${d.rev}</td>
            <td><span class="badge ${badge}">${d.status}</span></td>
            <td>${d.user}</td>
            <td>${d.notes}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRecord('${d.id}')"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord('${d.id}')"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    }).join('');
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.id.toLowerCase().includes(s) || d.product_id.toLowerCase().includes(s) || d.name.toLowerCase().includes(s) || d.fa.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    renderTable(data);
}

function renderFormula(rows) {
    const tbody = document.getElementById('formulaBody');
    tbody.innerHTML = rows.map((r, i) => `
        <tr>
            <td>${i + 1}</td>
            <td><input type="text" class="form-control form-control-sm" value="${r.kode}" placeholder="Kode"></td>
            <td><input type="text" class="form-control form-control-sm" value="${r.nama}" placeholder="Nama bahan"></td>
            <td><input type="number" class="form-control form-control-sm formula-input" value="${r.persen}" min="0" max="100" step="0.01" oninput="calcTotal()"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x"></i></button></td>
        </tr>
    `).join('');
    calcTotal();
}

function calcTotal() {
    const inputs = document.querySelectorAll('.formula-input');
    let total = 0;
    inputs.forEach(inp => total += parseFloat(inp.value) || 0);
    const el = document.getElementById('totalFormula');
    el.textContent = total.toFixed(2) + '%';
    el.className = total === 100 ? 'text-success' : total > 100 ? 'text-danger' : 'text-warning';
}

function addRow() {
    const tbody = document.getElementById('formulaBody');
    rowCounter++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${tbody.children.length + 1}</td>
        <td><input type="text" class="form-control form-control-sm" placeholder="Kode"></td>
        <td><input type="text" class="form-control form-control-sm" placeholder="Nama bahan"></td>
        <td><input type="number" class="form-control form-control-sm formula-input" value="0" min="0" max="100" step="0.01" oninput="calcTotal()"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x"></i></button></td>
    `;
    tbody.appendChild(tr);
    calcTotal();
}

function removeRow(btn) {
    btn.closest('tr').remove();
    document.querySelectorAll('#formulaBody tr').forEach((tr, i) => tr.children[0].textContent = i + 1);
    calcTotal();
}

function getFormulaFromTable() {
    const rows = document.querySelectorAll('#formulaBody tr');
    return Array.from(rows).map(tr => ({
        kode: tr.children[1].querySelector('input').value,
        nama: tr.children[2].querySelector('input').value,
        persen: parseFloat(tr.children[3].querySelector('input').value) || 0,
    }));
}

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Template');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    $('#date').val(new Date().toISOString().split('T')[0]);
    renderFormula(defaultFormula);
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id) {
    const d = data.find(x => x.id === id);
    if (!d) return;
    editingId = id;
    $('#modalTitle').text('Edit Template');
    $('#formId').val(id);
    $('#product_id').val(d.product_id);
    $('#product_name').val(d.name);
    $('#fa').val(d.fa);
    $('#rev').val(d.rev);
    $('#date').val(d.date);
    $('#user').val(d.user);
    $('#status').val(d.status);
    $('#notes').val(d.notes);
    renderFormula(defaultFormula);
    new bootstrap.Modal('#formModal').show();
}

function saveForm() {
    const pid = $('#product_id').val().trim();
    const pname = $('#product_name').val().trim();
    if (!pid) { alert('Product ID wajib diisi'); return; }
    if (!pname) { alert('Name wajib diisi'); return; }

    const payload = {
        product_id: pid,
        name: pname,
        fa: $('#fa').val().trim(),
        rev: parseInt($('#rev').val()) || 0,
        date: $('#date').val(),
        user: $('#user').val().trim(),
        status: $('#status').val(),
        notes: $('#notes').val().trim(),
        formula: getFormulaFromTable(),
    };

    if (editingId) {
        const d = data.find(x => x.id === editingId);
        if (d) { Object.assign(d, payload); }
    } else {
        const newId = 'TPL-' + String(data.length + 1).padStart(3, '0');
        data.push({ id: newId, ...payload });
    }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(id) {
    if (!confirm('Hapus template ini?')) return;
    data = data.filter(x => x.id !== id);
    renderTable(data);
}

$(function() {
    renderTable(data);
    $('#filterSearch').on('keyup', filterData);
});
</script>
@endpush
