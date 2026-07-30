@extends('layouts.layout')
@section('title','Dashboard Production Planning')
@section('content')
<div class="page-content">
    {{-- Filter --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label>
                <input type="date" class="form-control" id="filter-date-from" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label>
                <input type="date" class="form-control" id="filter-date-to" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-clock-history me-1"></i>Shift</label>
                <select class="form-select" id="filter-shift">
                    <option value="all">Semua Shift</option>
                    <option value="1">Shift 1 (06:00-14:00)</option>
                    <option value="2">Shift 2 (14:00-22:00)</option>
                    <option value="3">Shift 3 (22:00-06:00)</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-droplet me-1"></i>Tipe Adonan</label>
                <select class="form-select" id="filter-type">
                    <option value="all">Semua Tipe</option>
                    <option value="water">Water Based</option>
                    <option value="solvent">Solvent Based</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-house me-1"></i>Lokasi</label>
                <select class="form-select" id="filter-location">
                    <option value="all">Semua Lokasi</option>
                    <option value="plant-a">Pabrik A</option>
                    <option value="plant-b">Pabrik B</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-refresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
            </div>
        </div>
    </div></div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-file-earmark-text fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">SPK Active</p><h4 class="fw-bold mb-0" id="stat-spk-active">-</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-bullseye fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">Target Tonase</p><h4 class="fw-bold mb-0" id="stat-target-tonase">-</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-check-circle fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">Realisasi Tonase</p><h4 class="fw-bold mb-0" id="stat-realisasi-tonase">-</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle"><i class="bi bi-calendar-check fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">Schedule Compliance</p><h4 class="fw-bold mb-0" id="stat-schedule-compliance">-</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-gear fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">Machine Grinding</p><h4 class="fw-bold mb-0" id="stat-machine-util">-</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-graph-up-arrow fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">Overall Yield</p><h4 class="fw-bold mb-0" id="stat-overall-yield">-</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle"><i class="bi bi-exclamation-triangle fs-4"></i></div></div>
                    <div><p class="text-muted mb-0 small">Material Shortage</p><h4 class="fw-bold mb-0" id="stat-shortage">-</h4></div></div>
            </div></div>
        </div>
    </div>

    {{-- Pipeline --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-diagram-3 me-2"></i>Schedule Status Pipeline</h6></div><div class="card-body" id="pipeline-container"></div></div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2"></i>Hourly Production Output (Ton)</h6></div><div class="card-body"><div style="height:280px;position:relative;"><canvas id="chart-hourly"></canvas></div></div></div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart me-2"></i>Base vs CM vs Packing</h6></div><div class="card-body"><div style="height:280px;position:relative;"><canvas id="chart-base-cm"></canvas></div></div></div>
        </div>
    </div>

    {{-- Urgent SPK Table --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-bell me-2"></i>Urgent SPK Pending & Material Shortage</h6></div><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-urgent"><thead class="table-light">
                <tr><th class="text-center">No</th><th>SPK No</th><th>Customer</th><th>Product</th><th class="text-center">Target Tonase</th><th>Due Date</th><th class="text-center">Status</th></tr>
            </thead></table>
        </div>
    </div></div>
</div>
@endsection

@push('after-style')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@push('after-script')
<script>
let chartHourly=null,chartBaseCm=null;

function loadData(){
    $.get('{{route("production-planning-dashboard.data")}}',function(r){
        $('#stat-spk-active').text(r.stats.spk_active);
        $('#stat-target-tonase').text(r.stats.target_tonase_today+' Ton');
        $('#stat-realisasi-tonase').text(r.stats.realisasi_tonase_today+' Ton');
        $('#stat-schedule-compliance').text(r.stats.schedule_compliance+'%');
        $('#stat-machine-util').text(r.stats.machine_grinding_util+'%');
        $('#stat-overall-yield').text(r.stats.overall_yield+'%');
        $('#stat-shortage').text(r.stats.material_shortage_count);

        let html='<div class="d-flex flex-column gap-3">';
        r.pipeline.forEach(function(p){
            let total=r.pipeline.reduce(function(a,b){return a+b.count},0);
            let pct=total>0?Math.round((p.count/total)*100):0;
            html+=`<div><div class="d-flex justify-content-between align-items-center mb-1"><span class="fw-semibold small">${p.stage}</span><span class="text-muted small">${p.count} (${pct}%)</span></div><div class="progress" style="height:22px;"><div class="progress-bar bg-${p.color}" style="width:${pct}%">${pct}%</div></div></div>`;
        });
        html+='</div>';
        $('#pipeline-container').html(html);

        if(chartHourly)chartHourly.destroy();
        chartHourly=new Chart($('#chart-hourly')[0],{type:'line',data:{labels:r.chart_hourly.labels,datasets:[{label:'Output (Ton)',data:r.chart_hourly.data,borderColor:'rgba(13,110,253,1)',backgroundColor:'rgba(13,110,253,0.1)',fill:true,tension:0.3}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true}}}});

        if(chartBaseCm)chartBaseCm.destroy();
        chartBaseCm=new Chart($('#chart-base-cm')[0],{type:'bar',data:{labels:r.chart_base_vs_cm_vs_packing.labels,datasets:[{label:'Plan',data:r.chart_base_vs_cm_vs_packing.plan,backgroundColor:'rgba(0,180,216,0.7)'},{label:'Realisasi',data:r.chart_base_vs_cm_vs_packing.realisasi,backgroundColor:'rgba(25,139,252,0.7)'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true}}}});

        let tbl=$('#table-urgent').DataTable();tbl.clear();
        r.urgent_spk.forEach(function(d){
            tbl.row.add([d.no,'<strong>'+d.spk_no+'</strong>',d.customer,d.product,d.target_tonase+' Ton',d.due_date,'<span class="badge bg-'+d.status_color+'">'+d.status+'</span>']).draw(false);
        });
        tbl.draw();
    });
}

$(function(){
    $('#table-urgent').DataTable({paging:false,searching:false,info:false,ordering:false,autoWidth:false});
    loadData();
    $('#btn-refresh').on('click',loadData);
    $('#btn-reset-filter').on('click',function(){$('#filter-date-from').val('{{date("Y-m-d")}}');$('#filter-date-to').val('{{date("Y-m-d")}}');$('#filter-shift').val('all');$('#filter-type').val('all');$('#filter-location').val('all');loadData()});
});
</script>
@endpush