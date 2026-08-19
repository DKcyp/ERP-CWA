@extends('layouts.layout')
@section('title', 'SPKP ADU - Surat Perintah Kerja Produksi Adu / Adjustment Base')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>SPKP ADU</h4>
        <small class="text-muted">Surat Perintah Kerja Produksi Adu / Adjustment Base</small>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label form-label-sm">Tanggal Awal</label>
                <input type="date" class="form-control form-control-sm" id="filterDateFrom">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Tanggal Akhir</label>
                <input type="date" class="form-control form-control-sm" id="filterDateTo">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm">Tipe Produk</label>
                <select class="form-select form-select-sm" id="filterTipeProduk">
                    <option value="all">Semua Tipe</option>
                    <option value="Water Based">Water Based</option>
                    <option value="Solvent Based">Solvent Based</option>
                    <option value="Lain-Lain">Lain-Lain</option>
                    <option value="Kemasan">Kemasan</option>
                    <option value="TM">TM</option>
                    <option value="MP">MP</option>
                    <option value="Labeling">Labeling</option>
                    <option value="Pasta Printing">Pasta Printing</option>
                    <option value="WB ADU">WB ADU</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="No. SPKP ADU / Batch / Product...">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()"><i class="bi bi-x-circle me-1"></i>Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="spkpaduTable" class="table table-hover table-sm mb-0" style="font-size:0.82rem;">
            <thead class="table-dark">
                <tr>
                    <th width="20">#</th>
                    <th>Production ID</th>
                    <th>Jadwal</th>
                    <th>No. SPKP</th>
                    <th>No. Batch</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <th>Product Name</th>
                    <th>Proses BASE</th>
                    <th>Selesai BASE</th>
                    <th>Machine</th>
                    <th>Tipe Produk</th>
                    <th>Formulasi</th>
                    <th>FK</th>
                    <th>Basis</th>
                    <th>Required</th>
                    <th>Recanning</th>
                    <th width="80">Aksi</th>
                </tr>
            </thead>
            <tbody id="spkpaduBody"></tbody>
        </table>
    </div>
</div>
@endsection

@push('after-script')
<script>
const dummyData = [
    {id:1, production_id:'PRD-2026-201', jadwal:'JAD-2026-0819-201', no_spkp:'SPKP-ADU-260819-001', no_batch:'BATCH-ADU01', date:'2026-08-19', created_by:'Supervisor Andi', product_name:'Cat Tembok Putih 25kg (Rework)', proses_base:'Adu Base', selesai_base:'2026-08-19', machine:'ADU-01', tipe_produk:'WB ADU', formulasi:'ADU-WB-001', fk:'FK-4001', basis:'5.00', required:'5.25', recanning:'0.25'},
    {id:2, production_id:'PRD-2026-202', jadwal:'JAD-2026-0819-202', no_spkp:'SPKP-ADU-260819-002', no_batch:'BATCH-ADU02', date:'2026-08-19', created_by:'Supervisor Budi', product_name:'Cat Tembok Kuning 25kg (Rework)', proses_base:'Adu Base', selesai_base:'2026-08-19', machine:'ADU-02', tipe_produk:'WB ADU', formulasi:'ADU-WB-002', fk:'FK-4002', basis:'3.00', required:'3.10', recanning:'0.10'},
    {id:3, production_id:'PRD-2026-203', jadwal:'JAD-2026-0818-203', no_spkp:'SPKP-ADU-260818-003', no_batch:'BATCH-ADU03', date:'2026-08-18', created_by:'Supervisor Dika', product_name:'Cat Solvent Merah 20kg (Rework)', proses_base:'Adu Base', selesai_base:'2026-08-18', machine:'ADU-01', tipe_produk:'WB ADU', formulasi:'ADU-SB-001', fk:'FK-4003', basis:'4.00', required:'4.20', recanning:'0.20'},
];

function renderTable(data) {
    const tbody = document.getElementById('spkpaduBody');
    tbody.innerHTML = data.map((d, i) => `
        <tr style="cursor:pointer" onclick="openDetail(${d.id})">
            <td>${i + 1}</td>
            <td>${d.production_id}</td>
            <td>${d.jadwal}</td>
            <td><strong>${d.no_spkp}</strong></td>
            <td>${d.no_batch}</td>
            <td>${d.date}</td>
            <td>${d.created_by}</td>
            <td>${d.product_name}</td>
            <td>${d.proses_base}</td>
            <td>${d.selesai_base}</td>
            <td>${d.machine}</td>
            <td><span class="badge bg-warning text-dark">${d.tipe_produk}</span></td>
            <td>${d.formulasi}</td>
            <td>${d.fk}</td>
            <td class="text-end">${parseFloat(d.basis).toFixed(2)}</td>
            <td class="text-end">${parseFloat(d.required).toFixed(2)}</td>
            <td class="text-end">${parseFloat(d.recanning).toFixed(2)}</td>
            <td><button class="btn btn-sm btn-primary" onclick="event.stopPropagation();openDetail(${d.id})"><i class="bi bi-play-circle me-1"></i>Proses</button></td>
        </tr>
    `).join('');
}

function filterData() {
    const search = document.getElementById('filterSearch').value.toLowerCase();
    const dateFrom = document.getElementById('filterDateFrom').value;
    const dateTo = document.getElementById('filterDateTo').value;
    const tipe = document.getElementById('filterTipeProduk').value;

    const filtered = dummyData.filter(d => {
        const matchSearch = !search || d.no_spkp.toLowerCase().includes(search) || d.no_batch.toLowerCase().includes(search) || d.product_name.toLowerCase().includes(search);
        const matchDateFrom = !dateFrom || d.date >= dateFrom;
        const matchDateTo = !dateTo || d.date <= dateTo;
        const matchTipe = tipe === 'all' || d.tipe_produk === tipe;
        return matchSearch && matchDateFrom && matchDateTo && matchTipe;
    });
    renderTable(filtered);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    document.getElementById('filterDateFrom').value = '';
    document.getElementById('filterDateTo').value = '';
    document.getElementById('filterTipeProduk').value = 'all';
    renderTable(dummyData);
}

function openDetail(id) {
    window.open('{{ url("/production-process-spkpadu") }}/' + id + '/detail', '_blank');
}

$(function() {
    renderTable(dummyData);
    $('#filterSearch, #filterDateFrom, #filterDateTo, #filterTipeProduk').on('change keyup', filterData);
});
</script>
@endpush
