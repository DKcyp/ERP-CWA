@extends('layouts.layout')
@section('title','Dashboard Transit Area')

@section('content')
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
                <label class="form-label form-label-sm">Transit Area / Depo</label>
                <select class="form-select form-select-sm" id="filterDepo">
                    <option value="all">Semua Depo</option>
                    <option>Depo Bandung</option><option>Depo Jakarta</option><option>Depo Semarang</option><option>Depo Surabaya</option>
                    <option>Depo Bogor</option><option>Depo Tangerang</option><option>Depo Bekasi</option><option>Depo Cirebon</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-sm btn-primary me-1" onclick="loadData()"><i class="bi bi-search me-1"></i>Tampilkan</button>
                <button class="btn btn-sm btn-outline-secondary" id="btnRefresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-cash-stack fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Transit Sales Hari Ini</p><h5 class="fw-bold mb-0" id="statSales">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-building fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Depo Aktif</p><h5 class="fw-bold mb-0" id="statDepo">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-wallet2 fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">AR Depo Outstanding</p><h5 class="fw-bold mb-0" id="statAR">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-bullseye fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Target Achievement</p><h5 class="fw-bold mb-0" id="statTarget">-</h5></div>
            </div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Tren Penjualan Harian per Depo (30 Hari)</h6></div><div class="card-body"><div style="height:300px;position:relative;"><canvas id="chartDailyTrend"></canvas></div></div></div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2"></i>Collection vs Target per Depo</h6></div><div class="card-body"><div style="height:300px;position:relative;"><canvas id="chartCollection"></canvas></div></div></div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-trophy me-2"></i>Top Depo Performance Ranking</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem;" id="tableRanking">
                <thead class="table-dark"><tr>
                    <th width="40">Rank</th><th>Depo</th><th class="text-end">Target</th><th class="text-end">Realized</th><th>%</th><th class="text-end">AR Outstanding</th><th>Collection Rate</th>
                </tr></thead>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold text-danger"><i class="bi bi-exclamation-diamond me-2"></i>Overdue Depo AR Alert (>90 Hari)</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem;" id="tableOverdue">
                <thead class="table-dark"><tr>
                    <th width="30">#</th><th>Customer</th><th>Depo</th><th>Invoice No</th><th class="text-end">Amount</th><th>Days Overdue</th><th>Aging</th>
                </tr></thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('after-style')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('after-script')
<script>
let chartTrend = null, chartCollection = null;

function formatRp(v) {
    if (Math.abs(v) >= 1000000000) return 'Rp ' + (v/1000000000).toFixed(1) + 'M';
    if (Math.abs(v) >= 1000000) return 'Rp ' + (v/1000000).toFixed(1) + 'Jt';
    return 'Rp ' + v;
}

function loadData() {
    $.get('{{ route("transit-area-dashboard-data") }}', function(r) {
        $('#statSales').text(formatRp(r.total_transit_sales_today));
        $('#statDepo').text(r.total_active_depo_count);
        $('#statAR').text(formatRp(r.total_ar_depo_outstanding));
        const tColor = r.target_achievement_rate >= 100 ? 'text-success' : (r.target_achievement_rate >= 80 ? 'text-warning' : 'text-danger');
        $('#statTarget').text(r.target_achievement_rate + '%').removeClass('text-success text-warning text-danger').addClass(tColor);

        const depoColors = ['#4e73df','#1cc88a','#36b9cc','#f6c23e'];
        if (chartTrend) chartTrend.destroy();
        const datasets = Object.keys(r.daily_depo_sales_trend[0] || {}).filter(k => k !== 'date').map((k, i) => ({
            label: k.replace(/_/g, ' '),
            data: r.daily_depo_sales_trend.map(d => d[k]),
            borderColor: depoColors[i % depoColors.length],
            backgroundColor: 'transparent',
            tension: 0.3, pointRadius: 1, borderWidth: 2
        }));
        chartTrend = new Chart($('#chartDailyTrend')[0], {
            type: 'line',
            data: { labels: r.daily_depo_sales_trend.map(d => d.date), datasets },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'top',labels:{font:{size:10}}} },
                scales:{ y:{ beginAtZero:true, ticks:{ callback: v => formatRp(v) } }, x:{ ticks:{ maxRotation:45, font:{size:9} } } } }
        });

        if (chartCollection) chartCollection.destroy();
        chartCollection = new Chart($('#chartCollection')[0], {
            type: 'bar',
            data: {
                labels: r.collection_vs_target.map(c => c.name),
                datasets: [
                    { label:'Target', data: r.collection_vs_target.map(c => c.target), backgroundColor:'rgba(78,115,223,0.3)', borderColor:'#4e73df', borderWidth:1 },
                    { label:'Collected', data: r.collection_vs_target.map(c => c.collected), backgroundColor:'rgba(28,200,138,0.3)', borderColor:'#1cc88a', borderWidth:1 }
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'top'} },
                scales:{ y:{ beginAtZero:true, ticks:{ callback: v => formatRp(v) } } } }
        });

        let tblRank = $('#tableRanking').DataTable();
        tblRank.clear();
        r.depo_performance_ranking.forEach(function(d, i) {
            const pctBadge = d.percent >= 100 ? 'text-success fw-bold' : d.percent >= 80 ? 'text-warning fw-bold' : 'text-danger fw-bold';
            const rankIcon = i === 0 ? '<i class="bi bi-trophy-fill text-warning"></i>' : i === 1 ? '<i class="bi bi-trophy-fill text-secondary"></i>' : i === 2 ? '<i class="bi bi-trophy-fill text-danger"></i>' : (i+1);
            tblRank.row.add([rankIcon, '<strong>'+d.name+'</strong>', formatRp(d.target), formatRp(d.realized),
                '<span class="'+pctBadge+'">'+d.percent+'%</span>', formatRp(d.ar_outstanding),
                d.collection_rate+'%']).draw(false);
        });
        tblRank.draw();

        let tblOver = $('#tableOverdue').DataTable();
        tblOver.clear();
        r.overdue_depo_ar_alert.forEach(function(a) {
            const agingBadge = a.aging === 'CRITICAL' ? 'bg-danger' : a.aging === 'SEVERE' ? 'bg-warning text-dark' : 'bg-info';
            tblOver.row.add([a.no, a.customer, a.depo, '<strong>'+a.invoice_no+'</strong>', formatRp(a.amount),
                a.days_overdue+'d', '<span class="badge '+agingBadge+'">'+a.aging+'</span>']).draw(false);
        });
        tblOver.draw();
    });
}

$(function() {
    $('#tableRanking, #tableOverdue').DataTable({ paging:false, searching:false, info:false, ordering:false });
    loadData();
    $('#btnRefresh').on('click', loadData);
});
</script>
@endpush
