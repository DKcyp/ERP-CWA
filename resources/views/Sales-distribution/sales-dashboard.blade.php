@extends('layouts.layout')
@section('title', 'Dashboard Sales & Distribution')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard Sales & Distribution</h4>
        <small class="text-muted">Ringkasan performa penjualan, omset, piutang, dan pengiriman</small>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-secondary" id="btnRefresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label form-label-sm">Date From</label>
                <input type="date" class="form-control form-control-sm" id="filterDateFrom" value="{{ date('Y-m-01') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Date To</label>
                <input type="date" class="form-control form-control-sm" id="filterDateTo" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Wilayah / Area</label>
                <select class="form-select form-select-sm" id="filterArea">
                    <option value="all">Semua Wilayah</option>
                    <option>Jawa Barat</option><option>Jawa Tengah</option><option>Jawa Timur</option><option>Jakarta</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Gudang</label>
                <select class="form-select form-select-sm" id="filterWarehouse">
                    <option value="all">Semua Gudang</option>
                    <option>WH-UTAMA</option><option>WH-BANDUNG</option><option>WH-JAKARTA</option><option>WH-SEMARANG</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary" onclick="loadData()"><i class="bi bi-search me-1"></i>Tampilkan</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl col-md-4 col-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-cash-stack fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Omset Hari Ini</p><h5 class="fw-bold mb-0" id="statOmset">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-receipt fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">SO Aktif</p><h5 class="fw-bold mb-0" id="statSO">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-truck fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Shipment Pending</p><h5 class="fw-bold mb-0" id="statShipment">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-wallet2 fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">AR Outstanding</p><h5 class="fw-bold mb-0" id="statAR">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-exclamation-triangle fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">AR Overdue</p><h5 class="fw-bold mb-0" id="statOverdue">-</h5></div>
            </div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Tren Omset Harian (30 Hari)</h6></div><div class="card-body"><div style="height:300px;position:relative;"><canvas id="chartDailyTrend"></canvas></div></div></div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i>Penjualan per Kategori</h6></div><div class="card-body"><div style="height:300px;position:relative;"><canvas id="chartCategory"></canvas></div></div></div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2"></i>Top Salesman Performance</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem;" id="tableSalesman">
                <thead class="table-dark"><tr>
                    <th width="30">#</th><th>Salesman</th><th>Target</th><th>Achieved</th><th>%</th><th>SO Count</th>
                </tr></thead>
            </table>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2"></i>Recent Sales Orders</h6></div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem;" id="tableRecentSO">
                <thead class="table-dark"><tr>
                    <th width="30">#</th><th>SO No</th><th>Customer</th><th>Salesman</th><th>Date</th><th>Amount</th><th>Status</th>
                </tr></thead>
            </table>
        </div></div></div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold text-danger"><i class="bi bi-exclamation-diamond me-2"></i>Credit Limit Exceeded</h6></div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem;" id="tableCreditAlert">
                <thead class="table-dark"><tr>
                    <th width="30">#</th><th>Customer</th><th>Outstanding</th><th>Limit</th><th>Exceeded</th><th>Days</th>
                </tr></thead>
            </table>
        </div></div></div>
    </div>
</div>
@endsection

@push('after-style')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('after-script')
<script>
let chartTrend = null, chartCat = null;

function formatRp(v) {
    if (v >= 1000000000) return 'Rp ' + (v/1000000000).toFixed(1) + 'M';
    if (v >= 1000000) return 'Rp ' + (v/1000000).toFixed(1) + 'Jt';
    if (v >= 1000) return 'Rp ' + (v/1000).toFixed(0) + 'Rb';
    return 'Rp ' + v;
}

function loadData() {
    $.get('{{ route("sales-dashboard.data") }}', function(r) {
        $('#statOmset').text(formatRp(r.total_sales_omset_today));
        $('#statSO').text(r.total_active_so);
        $('#statShipment').text(r.total_pending_shipment);
        $('#statAR').text(formatRp(r.total_ar_outstanding));
        $('#statOverdue').text(r.total_overdue_ar_count + ' Customer');

        if (chartTrend) chartTrend.destroy();
        chartTrend = new Chart($('#chartDailyTrend')[0], {
            type: 'line',
            data: {
                labels: r.daily_sales_trend.map(d => d.date),
                datasets: [{
                    label: 'Omset (Rp)',
                    data: r.daily_sales_trend.map(d => d.omset),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28,200,138,0.08)',
                    fill: true, tension: 0.3, pointRadius: 1.5
                }]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false} },
                scales:{ y:{ beginAtZero:true, ticks:{ callback: v => formatRp(v) } }, x:{ ticks:{ maxRotation:45, font:{size:10} } } } }
        });

        const catColors = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#858796','#5a5c69'];
        if (chartCat) chartCat.destroy();
        chartCat = new Chart($('#chartCategory')[0], {
            type: 'doughnut',
            data: {
                labels: r.sales_by_category.map(c => c.name),
                datasets: [{ data: r.sales_by_category.map(c => c.value), backgroundColor: catColors, borderWidth: 2 }]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'right',labels:{font:{size:11}}} } }
        });

        let tblSM = $('#tableSalesman').DataTable();
        tblSM.clear();
        r.top_salesman_performance.forEach(function(s, i) {
            const pctBadge = s.percent >= 100 ? 'text-success fw-bold' : s.percent >= 80 ? 'text-warning fw-bold' : 'text-danger fw-bold';
            tblSM.row.add([i+1, s.name, formatRp(s.target), formatRp(s.achieved),
                '<span class="'+pctBadge+'">'+s.percent+'%</span>', s.so_count]).draw(false);
        });
        tblSM.draw();

        let tblSO = $('#tableRecentSO').DataTable();
        tblSO.clear();
        r.recent_sales_orders.forEach(function(s) {
            tblSO.row.add([s.no, '<strong>'+s.so_no+'</strong>', s.customer, s.salesman, s.date,
                formatRp(s.amount), '<span class="badge bg-'+s.status_color+'">'+s.status+'</span>']).draw(false);
        });
        tblSO.draw();

        let tblCR = $('#tableCreditAlert').DataTable();
        tblCR.clear();
        r.credit_limit_exceeded_alert.forEach(function(c) {
            tblCR.row.add([c.no, c.customer, formatRp(c.outstanding), formatRp(c.credit_limit),
                '<span class="text-danger fw-bold">-'+formatRp(c.exceeded)+'</span>', c.days_overdue+'d']).draw(false);
        });
        tblCR.draw();
    });
}

$(function() {
    $('#tableSalesman, #tableRecentSO, #tableCreditAlert').DataTable({ paging:false, searching:false, info:false, ordering:false });
    loadData();
    $('#btnRefresh').on('click', loadData);
});
</script>
@endpush
