@extends('layouts.layout')
@section('title','Dashboard Material Management')
@section('content')
<div class="page-content">
    {{-- Filter --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label>
                <input type="date" class="form-control" id="filter-date-from" value="{{ date('Y-m-01') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label>
                <input type="date" class="form-control" id="filter-date-to" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-house me-1"></i>Gudang</label>
                <select class="form-select" id="filter-warehouse">
                    <option value="all">Semua Gudang</option>
                    <option value="WH-MAIN">Gudang Utama</option>
                    <option value="WH-BRANCH">Gudang Cabang</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-refresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
            </div>
        </div>
    </div></div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-file-earmark-text fs-4"></i></div></div>
                    <div class="flex-grow-1"><p class="text-muted mb-0 small">PR Pending</p><h4 class="fw-bold mb-0" id="stat-pr-pending">-</h4></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-cart-check fs-4"></i></div></div>
                    <div class="flex-grow-1"><p class="text-muted mb-0 small">PO Active</p><h4 class="fw-bold mb-0" id="stat-po-active">-</h4></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3"><div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle"><i class="bi bi-truck fs-4"></i></div></div>
                    <div class="flex-grow-1"><p class="text-muted mb-0 small">STBJ Hari Ini</p><h4 class="fw-bold mb-0" id="stat-stbj-today">-</h4></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3"><div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle"><i class="bi bi-cash-stack fs-4"></i></div></div>
                    <div class="flex-grow-1"><p class="text-muted mb-0 small">AP Outstanding</p><h4 class="fw-bold mb-0" id="stat-ap-outstanding">-</h4></div>
                </div>
            </div></div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3"><div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle"><i class="bi bi-exclamation-triangle fs-4"></i></div></div>
                    <div class="flex-grow-1"><p class="text-muted mb-0 small">Stock Alert</p><h4 class="fw-bold mb-0" id="stat-stock-alert">-</h4></div>
                </div>
            </div></div>
        </div>
    </div>

    {{-- Pipeline --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2"></i>Monitoring Progres Purchasing</h6></div><div class="card-body" id="pipeline-container"></div></div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2"></i>PO vs STBJ</h6></div><div class="card-body"><canvas id="chart-po-stbj" height="280"></canvas></div></div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Monthly Purchase Trend</h6></div><div class="card-body"><canvas id="chart-monthly" height="280"></canvas></div></div>
        </div>
    </div>

    {{-- Pending Documents Table --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-bell me-2"></i>Dokumen Pending & Notifikasi</h6></div><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-pending">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Tipe</th><th>Nomor</th><th>Supplier</th><th>Tanggal</th><th>Status</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection

@push('after-style')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('after-script')
<script>
let chartPoStbj = null, chartMonthly = null;

function loadData() {
    $.get('{{ route("material-dashboard.data") }}', function(r) {
        // Stats
        $('#stat-pr-pending').text(r.stats.total_pr_pending);
        $('#stat-po-active').text(r.stats.total_po_active);
        $('#stat-stbj-today').text(r.stats.total_stbj_today);
        $('#stat-ap-outstanding').text(r.stats.total_ap_outstanding);
        $('#stat-stock-alert').text(r.stats.stock_alert_count);

        // Pipeline
        let pipelineHtml = '<div class="d-flex flex-column gap-3">';
        r.pipeline.forEach(function(p) {
            let pct = p.count > 0 ? Math.round((p.completed / p.count) * 100) : 0;
            pipelineHtml += `
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold small">${p.stage}</span>
                        <span class="text-muted small">${p.completed} / ${p.count} (${pct}%)</span>
                    </div>
                    <div class="progress" style="height:22px;">
                        <div class="progress-bar bg-${p.color}" style="width:${pct}%">${pct}%</div>
                    </div>
                </div>`;
        });
        pipelineHtml += '</div>';
        $('#pipeline-container').html(pipelineHtml);

        // Chart PO vs STBJ
        if (chartPoStbj) chartPoStbj.destroy();
        chartPoStbj = new Chart($('#chart-po-stbj')[0], {
            type: 'bar',
            data: {
                labels: r.chart_po_vs_stbj.labels,
                datasets: [
                    { label: 'PO', data: r.chart_po_vs_stbj.po_data, backgroundColor: 'rgba(0,180,216,0.7)' },
                    { label: 'STBJ', data: r.chart_po_vs_stbj.stbj_data, backgroundColor: 'rgba(255,193,7,0.7)' }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
        });

        // Chart Monthly
        if (chartMonthly) chartMonthly.destroy();
        chartMonthly = new Chart($('#chart-monthly')[0], {
            type: 'line',
            data: {
                labels: r.chart_monthly.labels,
                datasets: [{
                    label: 'Pembelian (Rp)',
                    data: r.chart_monthly.data,
                    borderColor: 'rgba(13,110,253,1)',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { callback: function(v) { return 'Rp ' + (v/1000000).toFixed(0) + 'M'; } } } } }
        });

        // Pending Table
        let tbl = $('#table-pending').DataTable();
        tbl.clear();
        r.pending_docs.forEach(function(d) {
            tbl.row.add([
                d.no,
                '<span class="badge bg-secondary">' + d.type + '</span>',
                '<strong>' + d.number + '</strong>',
                d.supplier,
                d.date,
                '<span class="badge bg-' + d.status_color + '">' + d.status + '</span>'
            ]).draw(false);
        });
        tbl.draw();
    });
}

$(function() {
    $('#table-pending').DataTable({
        paging: false, searching: false, info: false, ordering: false, autoWidth: false
    });
    loadData();

    $('#btn-refresh').on('click', loadData);
    $('#btn-reset-filter').on('click', function() {
        $('#filter-date-from').val('{{ date("Y-m-01") }}');
        $('#filter-date-to').val('{{ date("Y-m-d") }}');
        $('#filter-warehouse').val('all');
        loadData();
    });
});
</script>
@endpush