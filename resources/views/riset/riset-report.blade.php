@extends('layouts.layout')
@section('title', 'Riset Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-file-earmark-bar-graph me-2"></i>Riset Report</h4>
        <small class="text-muted">Riset - Laporan Analisis Biaya & Komposisi Finansial Formula</small>
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
                        <th>No LHR</th>
                        <th>Price Method</th>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Created Date</th>
                        <th>Posting Date</th>
                        <th>Status</th>
                        <th>FA</th>
                        <th>Rev</th>
                        <th class="text-end">Grand Total</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL FORM --}}
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-file-earmark-bar-graph me-1"></i><span id="modalTitle">Tambah Riset Report</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">

                    {{-- HEADER --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header LHR</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Doc ID</label>
                                    <input type="text" class="form-control form-control-sm" id="doc_id" placeholder="Auto-generated" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">No LHR</label>
                                    <input type="text" class="form-control form-control-sm" id="no_lhr" placeholder="LHR-XXXX">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Price Method</label>
                                    <select class="form-select form-select-sm" id="price_method">
                                        <option value="COGS">COGS</option>
                                        <option value="Standard">Standard</option>
                                        <option value="Actual">Actual</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Product ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="product_id" placeholder="PRD-XXXX" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="product_name" placeholder="Nama produk" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Created Date</label>
                                    <input type="date" class="form-control form-control-sm" id="created_date">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Posting Date</label>
                                    <input type="date" class="form-control form-control-sm" id="posting_date">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Status</label>
                                    <select class="form-select form-select-sm" id="status">
                                        <option value="Draft">Draft</option>
                                        <option value="Posted">Posted</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">FA</label>
                                    <input type="text" class="form-control form-control-sm" id="fa" placeholder="FA-XXX">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label form-label-sm">Rev</label>
                                    <input type="number" class="form-control form-control-sm" id="rev" min="0" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">User ID</label>
                                    <input type="text" class="form-control form-control-sm" id="user_id" placeholder="User ID">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm">Notes</label>
                                    <input type="text" class="form-control form-control-sm" id="notes" placeholder="Catatan...">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TABEL KALKULASI --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-calculator me-1"></i>Kalkulasi Bahan</h6></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0" style="font-size:0.82rem;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="20">#</th>
                                            <th>Kode Bahan</th>
                                            <th>Nama Bahan</th>
                                            <th class="text-end">Formula %</th>
                                            <th class="text-end">Harga COGS/kg</th>
                                            <th class="text-end">Formula (KG)</th>
                                            <th class="text-end">Faktor Konversi</th>
                                            <th class="text-end">Total Formula</th>
                                            <th class="text-end">Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calcBody"></tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end"><strong id="totPct">0.00%</strong></td>
                                            <td></td>
                                            <td class="text-end"><strong id="totKg">0.00</strong></td>
                                            <td></td>
                                            <td class="text-end"><strong id="totFormula">0.00</strong></td>
                                            <td class="text-end"><strong id="totHarga">Rp 0</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- GRAND TOTAL --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-2">
                            <div class="row justify-content-end">
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Grand Total</h5>
                                        <h4 class="mb-0 text-primary" id="grandTotal">Rp 0</h4>
                                    </div>
                                </div>
                            </div>
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
    {doc_id:'DOC-001', no_lhr:'LHR-001', price_method:'COGS', product_id:'PRD-1001', name:'Cat Tembok Putih Premium', created_date:'2026-08-10', posting_date:'2026-08-12', status:'Posted', fa:'FA-001', rev:2, grand_total:12500000, notes:'Biaya standar WB putih', user_id:'rina'},
    {doc_id:'DOC-002', no_lhr:'LHR-002', price_method:'Standard', product_id:'PRD-1002', name:'Cat Solvent Merah Export', created_date:'2026-08-14', posting_date:'2026-08-15', status:'Posted', fa:'FA-002', rev:1, grand_total:18750000, notes:'Formula export', user_id:'dika'},
    {doc_id:'DOC-003', no_lhr:'LHR-003', price_method:'COGS', product_id:'PRD-1003', name:'Primer Anti-Korosi', created_date:'2026-08-18', posting_date:'-', status:'Draft', fa:'FA-003', rev:0, grand_total:8200000, notes:'Prototipe', user_id:'andi'},
    {doc_id:'DOC-004', no_lhr:'LHR-004', price_method:'Actual', product_id:'PRD-1004', name:'Top Coat Clear Glossy', created_date:'2026-08-19', posting_date:'-', status:'Draft', fa:'FA-004', rev:3, grand_total:15600000, notes:'Revisi ke-3', user_id:'rina'},
];

const calcDummy = [
    {kode:'BB-001', nama:'Titanium Dioxide (TiO2)', pct:15, harga:45000, kg:3.75, faktor:1.0, totalFormula:3.75, totalHarga:168750},
    {kode:'BB-002', nama:'Resin Acrylic Emulsion', pct:35, harga:28000, kg:8.75, faktor:1.0, totalFormula:8.75, totalHarga:245000},
    {kode:'BB-003', nama:'Calcium Carbonate', pct:25, harga:3500, kg:6.25, faktor:1.0, totalFormula:6.25, totalHarga:21875},
    {kode:'BB-004', nama:'Water', pct:20, harga:500, kg:5.00, faktor:1.0, totalFormula:5.00, totalHarga:2500},
    {kode:'BB-005', nama:'Additive', pct:5, harga:85000, kg:1.25, faktor:1.0, totalFormula:1.25, totalHarga:106250},
];

let editingId = null;

function fmtRp(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); }

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => {
        const badge = d.status === 'Posted' ? 'bg-success' : d.status === 'Draft' ? 'bg-warning text-dark' : 'bg-secondary';
        return `
        <tr>
            <td>${i + 1}</td>
            <td><strong>${d.doc_id}</strong></td>
            <td>${d.no_lhr}</td>
            <td>${d.price_method}</td>
            <td>${d.product_id}</td>
            <td>${d.name}</td>
            <td>${d.created_date}</td>
            <td>${d.posting_date}</td>
            <td><span class="badge ${badge}">${d.status}</span></td>
            <td>${d.fa}</td>
            <td class="text-center">${d.rev}</td>
            <td class="text-end"><strong>${fmtRp(d.grand_total)}</strong></td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRecord('${d.doc_id}')"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord('${d.doc_id}')"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    }).join('');
}

function renderCalc() {
    const tbody = document.getElementById('calcBody');
    tbody.innerHTML = calcDummy.map((r, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${r.kode}</td>
            <td>${r.nama}</td>
            <td class="text-end">${r.pct.toFixed(2)}%</td>
            <td class="text-end">${fmtRp(r.harga)}</td>
            <td class="text-end">${r.kg.toFixed(2)}</td>
            <td class="text-end">${r.faktor.toFixed(2)}</td>
            <td class="text-end">${r.totalFormula.toFixed(2)}</td>
            <td class="text-end">${fmtRp(r.totalHarga)}</td>
        </tr>
    `).join('');

    const totPct = calcDummy.reduce((s, r) => s + r.pct, 0);
    const totKg = calcDummy.reduce((s, r) => s + r.kg, 0);
    const totF = calcDummy.reduce((s, r) => s + r.totalFormula, 0);
    const totH = calcDummy.reduce((s, r) => s + r.totalHarga, 0);

    document.getElementById('totPct').textContent = totPct.toFixed(2) + '%';
    document.getElementById('totKg').textContent = totKg.toFixed(2);
    document.getElementById('totFormula').textContent = totF.toFixed(2);
    document.getElementById('totHarga').textContent = fmtRp(totH);
    document.getElementById('grandTotal').textContent = fmtRp(totH);
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.doc_id.toLowerCase().includes(s) || d.no_lhr.toLowerCase().includes(s) || d.product_id.toLowerCase().includes(s) || d.name.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    renderTable(data);
}

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Riset Report');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    $('#doc_id').val('DOC-' + String(data.length + 1).padStart(3, '0'));
    $('#created_date').val(new Date().toISOString().split('T')[0]);
    renderCalc();
    new bootstrap.Modal('#formModal').show();
}

function editRecord(docId) {
    const d = data.find(x => x.doc_id === docId);
    if (!d) return;
    editingId = docId;
    $('#modalTitle').text('Edit Riset Report');
    $('#formId').val(docId);
    $('#doc_id').val(d.doc_id);
    $('#no_lhr').val(d.no_lhr);
    $('#price_method').val(d.price_method);
    $('#product_id').val(d.product_id);
    $('#product_name').val(d.name);
    $('#created_date').val(d.created_date);
    $('#posting_date').val(d.posting_date);
    $('#status').val(d.status);
    $('#fa').val(d.fa);
    $('#rev').val(d.rev);
    $('#user_id').val(d.user_id);
    $('#notes').val(d.notes);
    renderCalc();
    new bootstrap.Modal('#formModal').show();
}

function saveForm() {
    const pid = $('#product_id').val().trim();
    const pname = $('#product_name').val().trim();
    if (!pid) { alert('Product ID wajib diisi'); return; }
    if (!pname) { alert('Name wajib diisi'); return; }

    const totH = calcDummy.reduce((s, r) => s + r.totalHarga, 0);

    const payload = {
        doc_id: $('#doc_id').val(),
        no_lhr: $('#no_lhr').val().trim(),
        price_method: $('#price_method').val(),
        product_id: pid,
        name: pname,
        created_date: $('#created_date').val(),
        posting_date: $('#posting_date').val(),
        status: $('#status').val(),
        fa: $('#fa').val().trim(),
        rev: parseInt($('#rev').val()) || 0,
        grand_total: totH,
        notes: $('#notes').val().trim(),
        user_id: $('#user_id').val().trim(),
    };

    if (editingId) {
        const d = data.find(x => x.doc_id === editingId);
        if (d) Object.assign(d, payload);
    } else {
        data.push(payload);
    }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(docId) {
    if (!confirm('Hapus report ini?')) return;
    data = data.filter(x => x.doc_id !== docId);
    renderTable(data);
}

$(function() {
    renderTable(data);
    $('#filterSearch').on('keyup', filterData);
});
</script>
@endpush
