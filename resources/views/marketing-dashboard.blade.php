@extends('layouts.layout')
@section('title','Dashboard Marketing')

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
                <label class="form-label form-label-sm">Area / Rayon</label>
                <select class="form-select form-select-sm" id="filterArea">
                    <option value="all">Semua Area</option>
                    <option>Area Bandung</option><option>Area Jakarta</option><option>Area Semarang</option>
                    <option>Area Surabaya</option><option>Area Bogor</option><option>Area Tangerang</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Sales / Marketing</label>
                <select class="form-select form-select-sm" id="filterSales">
                    <option value="all">Semua Sales</option>
                    <option>Ahmad Hidayat</option><option>Dewi Lestari</option><option>Rudi Hermawan</option>
                    <option>Siti Nurhaliza</option><option>Bambang Sutrisno</option><option>Lina Maulida</option>
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
                <div class="flex-shrink-0 me-3"><div class="bg-purple bg-opacity-10 text-purple rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:rgba(111,66,193,0.1);color:#6f42c1;"><i class="bi bi-person-plus fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Prospect (Non-Customer)</p><h5 class="fw-bold mb-0" id="statProspect">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-geo-alt fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Kunjungan Hari Ini</p><h5 class="fw-bold mb-0" id="statVisits">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-shop fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">NOO Bulan Ini</p><h5 class="fw-bold mb-0" id="statNOO">-</h5></div>
            </div>
        </div></div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 me-3"><div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;"><i class="bi bi-cash-coin fs-4"></i></div></div>
                <div class="flex-grow-1"><p class="text-muted mb-0 small">Incentive Paid</p><h5 class="fw-bold mb-0" id="statIncentive">-</h5></div>
            </div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Tren Kunjungan Lapangan (30 Hari)</h6></div><div class="card-body"><div style="height:300px;position:relative;"><canvas id="chartVisitTrend"></canvas></div></div></div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2"></i>Komisi per Sales</h6></div><div class="card-body"><div style="height:300px;position:relative;"><canvas id="chartCommission"></canvas></div></div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2"></i>Pertumbuhan NOO per Area</h6></div><div class="card-body"><div style="height:280px;position:relative;"><canvas id="chartNOO"></canvas></div></div></div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100"><div class="card-header bg-transparent border-0 py-2"><h6 class="mb-0 fw-bold"><i class="bi bi-trophy me-2"></i>Top Performers Sales</h6></div>
        <div class="card-body p-0"><div class="table-responsive">
            <table class="table table-sm table-hover mb-0" style="font-size:0.82rem;" id="tablePerformers">
                <thead class="table-dark"><tr>
                    <th width="40">Rank</th><th>Name</th><th>Area</th><th>Visits</th><th>NOO Target</th><th>NOO Achv</th><th>%</th><th class="text-end">Commission</th>
                </tr></thead>
            </table>
        </div></div></div>
    </div>
</div>
@endsection

@push('after-style')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>.text-purple{color:#6f42c1!important;}</style>
@endpush

@push('after-script')
<script>
let chartVisit = null, chartComm = null, chartNOO = null;

function formatRp(v) {
    if (Math.abs(v) >= 1000000000) return 'Rp ' + (v/1000000000).toFixed(1) + 'M';
    if (Math.abs(v) >= 1000000) return 'Rp ' + (v/1000000).toFixed(1) + 'Jt';
    if (Math.abs(v) >= 1000) return 'Rp ' + (v/1000).toFixed(0) + 'Rb';
    return 'Rp ' + v;
}

function loadData() {
    $.get('{{ route("marketing-dashboard.data") }}', function(r) {
        $('#statProspect').text(r.total_prospect_non_customer);
        $('#statVisits').text(r.total_marketing_visits_today);
        $('#statNOO').text(r.total_noo_this_month + ' Outlet');
        $('#statIncentive').text(formatRp(r.total_incentive_paid));

        if (chartVisit) chartVisit.destroy();
        chartVisit = new Chart($('#chartVisitTrend')[0], {
            type: 'line',
            data: {
                labels: r.visit_trend.map(d => d.date),
                datasets: [
                    { label:'Kunjungan', data: r.visit_trend.map(d => d.visits), borderColor:'#4e73df', backgroundColor:'rgba(78,115,223,0.08)', fill:true, tension:0.3, pointRadius:1.5, borderWidth:2 },
                    { label:'New Leads', data: r.visit_trend.map(d => d.new_leads), borderColor:'#1cc88a', backgroundColor:'transparent', tension:0.3, pointRadius:1.5, borderWidth:2 }
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'top',labels:{font:{size:10}}} },
                scales:{ y:{ beginAtZero:true }, x:{ ticks:{ maxRotation:45, font:{size:9} } } } }
        });

        const commColors = ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b','#858796'];
        if (chartComm) chartComm.destroy();
        chartComm = new Chart($('#chartCommission')[0], {
            type: 'doughnut',
            data: {
                labels: r.commission_distribution.map(c => c.name),
                datasets: [{ data: r.commission_distribution.map(c => c.amount), backgroundColor: commColors, borderWidth:2 }]
            },
            options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'right',labels:{font:{size:10}}} } }
        });

        if (chartNOO) chartNOO.destroy();
        chartNOO = new Chart($('#chartNOO')[0], {
            type: 'bar',
            data: {
                labels: r.noo_growth_by_area.map(a => a.area),
                datasets: [
                    { label:'Target', data: r.noo_growth_by_area.map(a => a.target), backgroundColor:'rgba(78,115,223,0.3)', borderColor:'#4e73df', borderWidth:1 },
                    { label:'Achieved', data: r.noo_growth_by_area.map(a => a.achieved), backgroundColor:'rgba(28,200,138,0.3)', borderColor:'#1cc88a', borderWidth:1 }
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, indexAxis:'y', plugins:{ legend:{position:'top'} }, scales:{ x:{ beginAtZero:true } } }
        });

        let tbl = $('#tablePerformers').DataTable();
        tbl.clear();
        r.top_performers.forEach(function(p) {
            const rankIcon = p.rank===1?'<i class="bi bi-trophy-fill text-warning"></i>':p.rank===2?'<i class="bi bi-trophy-fill text-secondary"></i>':p.rank===3?'<i class="bi bi-trophy-fill text-danger"></i>':p.rank;
            const pctBadge = p.achievement_pct>=100?'text-success fw-bold':p.achievement_pct>=80?'text-warning fw-bold':'text-danger fw-bold';
            tbl.row.add([rankIcon,'<strong>'+p.name+'</strong>','<small>'+p.area+'</small>',p.total_visits,p.noo_target,p.noo_achieved,
                '<span class="'+pctBadge+'">'+p.achievement_pct+'%</span>',formatRp(p.commission_earned)]).draw(false);
        });
        tbl.draw();
    });
}

$(function() {
    $('#tablePerformers').DataTable({ paging:false, searching:false, info:false, ordering:false });
    loadData();
    $('#btnRefresh').on('click', loadData);
});
</script>
@endpush
