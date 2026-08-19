@extends('layouts.layout')
@section('title', 'SPPBJ - Surat Perintah Pembuatan Barang Jadi / CM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-palette me-2"></i>SPPBJ</h4>
        <small class="text-muted">Surat Perintah Pembuatan Barang Jadi / Color Matching</small>
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
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="No. SPKP / Batch / Product...">
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
            <table id="sppbjTable" class="table table-hover table-sm mb-0" style="font-size:0.82rem;">
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
                        <th>Proses CM</th>
                        <th>Selesai CM</th>
                        <th>Machine</th>
                        <th>Tipe Produk</th>
                        <th>Formulasi</th>
                        <th>FK</th>
                        <th>Basis</th>
                        <th>Required</th>
                        <th>Recanning</th>
                        <th>Base</th>
                        <th>Keputusan</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody id="sppbjBody"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
const dummyData = [
    {id:1, production_id:'PRD-2026-101', jadwal:'JAD-2026-0819-101', no_spkp:'SPKP-260819-001', no_batch:'BATCH-CM01', date:'2026-08-19', created_by:'Supervisor Rina', product_name:'Cat Tembok Putih 25kg', proses_cm:'2026-08-19', selesai_cm:'-', machine:'CM-01', tipe_produk:'Water Based', formulasi:'CM-WB-001', fk:'FK-1001', basis:'25.00', required:'25.50', recanning:'0.50', base:'BATCH-A001', keputusan:'<span class="badge bg-warning text-dark">In Progress</span>'},
    {id:2, production_id:'PRD-2026-102', jadwal:'JAD-2026-0819-102', no_spkp:'SPKP-260819-002', no_batch:'BATCH-CM02', date:'2026-08-19', created_by:'Supervisor Rina', product_name:'Cat Tembok Kuning 25kg', proses_cm:'2026-08-19', selesai_cm:'-', machine:'CM-02', tipe_produk:'Water Based', formulasi:'CM-WB-002', fk:'FK-1002', basis:'30.00', required:'30.25', recanning:'0.25', base:'BATCH-A002', keputusan:'<span class="badge bg-warning text-dark">In Progress</span>'},
    {id:3, production_id:'PRD-2026-103', jadwal:'JAD-2026-0818-103', no_spkp:'SPKP-260818-003', no_batch:'BATCH-CM03', date:'2026-08-18', created_by:'Supervisor Dika', product_name:'Cat Solvent Merah 20kg', proses_cm:'2026-08-18', selesai_cm:'2026-08-18', machine:'CM-01', tipe_produk:'Solvent Based', formulasi:'CM-SB-001', fk:'FK-2001', basis:'20.00', required:'20.50', recanning:'0.50', base:'BATCH-B001', keputusan:'<span class="badge bg-success">Approved</span>'},
    {id:4, production_id:'PRD-2026-104', jadwal:'JAD-2026-0818-104', no_spkp:'SPKP-260818-004', no_batch:'BATCH-CM04', date:'2026-08-18', created_by:'Supervisor Rina', product_name:'Cat Tembok Hijau 25kg', proses_cm:'2026-08-18', selesai_cm:'2026-08-18', machine:'CM-03', tipe_produk:'Water Based', formulasi:'CM-WB-003', fk:'FK-1003', basis:'25.00', required:'25.00', recanning:'0.00', base:'BATCH-B002', keputusan:'<span class="badge bg-success">Approved</span>'},
    {id:5, production_id:'PRD-2026-105', jadwal:'JAD-2026-0817-105', no_spkp:'SPKP-260817-005', no_batch:'BATCH-CM05', date:'2026-08-17', created_by:'Supervisor Dika', product_name:'Cat Tembok Abu-abu 25kg', proses_cm:'2026-08-17', selesai_cm:'2026-08-17', machine:'CM-02', tipe_produk:'Water Based', formulasi:'CM-WB-004', fk:'FK-1004', basis:'25.00', required:'25.75', recanning:'0.75', base:'BATCH-C001', keputusan:'<span class="badge bg-danger">Reject</span>'},
    {id:6, production_id:'PRD-2026-106', jadwal:'JAD-2026-0817-106', no_spkp:'SPKP-260817-006', no_batch:'BATCH-CM06', date:'2026-08-17', created_by:'Supervisor Rina', product_name:'Pasta Printing Biru 15kg', proses_cm:'2026-08-17', selesai_cm:'2026-08-17', machine:'CM-01', tipe_produk:'Pasta Printing', formulasi:'CM-PP-001', fk:'FK-3001', basis:'15.00', required:'15.25', recanning:'0.25', base:'BATCH-C002', keputusan:'<span class="badge bg-success">Approved</span>'},
];

function renderTable(data) {
    const tbody = document.getElementById('sppbjBody');
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
            <td>${d.proses_cm}</td>
            <td>${d.selesai_cm}</td>
            <td>${d.machine}</td>
            <td><span class="badge bg-info">${d.tipe_produk}</span></td>
            <td>${d.formulasi}</td>
            <td>${d.fk}</td>
            <td class="text-end">${parseFloat(d.basis).toFixed(2)}</td>
            <td class="text-end">${parseFloat(d.required).toFixed(2)}</td>
            <td class="text-end">${parseFloat(d.recanning).toFixed(2)}</td>
            <td>${d.base}</td>
            <td>${d.keputusan}</td>
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
    window.open('{{ url("/production-process-sppbj") }}/' + id + '/detail', '_blank');
}

$(function() {
    renderTable(dummyData);
    $('#filterSearch, #filterDateFrom, #filterDateTo, #filterTipeProduk').on('change keyup', filterData);
});
</script>
@endpush
