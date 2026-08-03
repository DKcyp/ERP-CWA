@extends('layouts.layout')
@section('title','Production Process Dashboard')
@section('content')
<div class="page-content">
    <div class="row g-2 mb-3 align-items-center">
        <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control form-control-sm" id="filter-date-from"></div>
        <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control form-control-sm" id="filter-date-to"></div>
        <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua Shift</option><option>Shift 1 (06-14)</option><option>Shift 2 (14-22)</option><option>Shift 3 (22-06)</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Lini</label><select class="form-select form-select-sm" id="filter-line"><option value="all">Semua Lini</option><option>LINE-A1</option><option>LINE-A2</option><option>LINE-B1</option><option>LINE-B2</option><option>LINE-C1</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Tipe Adonan</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option>Base Putih</option><option>Base Krem</option><option>CM Putih</option><option>CM Krem</option><option>CM Special</option></select></div>
        <div class="col-md-2"><button class="btn btn-sm btn-outline-secondary w-100" onclick="loadData()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button></div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2 text-center">
                <div class="rounded-3 bg-primary bg-opacity-10 p-2 mx-auto mb-2" style="width:fit-content;"><i class="bi bi-hourglass-split text-primary fs-4"></i></div>
                <h4 class="fw-bold mb-0" id="stat-active">-</h4><small class="text-muted">Active Batches</small>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2 text-center">
                <div class="rounded-3 bg-success bg-opacity-10 p-2 mx-auto mb-2" style="width:fit-content;"><i class="bi bi-check2-circle text-success fs-4"></i></div>
                <h4 class="fw-bold mb-0" id="stat-base">-</h4><small class="text-muted">Base Done</small>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2 text-center">
                <div class="rounded-3 bg-info bg-opacity-10 p-2 mx-auto mb-2" style="width:fit-content;"><i class="bi bi-gear text-info fs-4"></i></div>
                <h4 class="fw-bold mb-0" id="stat-cm">-</h4><small class="text-muted">CM Done</small>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2 text-center">
                <div class="rounded-3 bg-warning bg-opacity-10 p-2 mx-auto mb-2" style="width:fit-content;"><i class="bi bi-clipboard-check text-warning fs-4"></i></div>
                <h4 class="fw-bold mb-0" id="stat-qc">-</h4><small class="text-muted">QC Pass %</small>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2 text-center">
                <div class="rounded-3 bg-danger bg-opacity-10 p-2 mx-auto mb-2" style="width:fit-content;"><i class="bi bi-arrow-repeat text-danger fs-4"></i></div>
                <h4 class="fw-bold mb-0 text-danger" id="stat-rework">-</h4><small class="text-muted">Rework/ADU</small>
            </div></div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2 text-center">
                <div class="rounded-3 bg-secondary bg-opacity-10 p-2 mx-auto mb-2" style="width:fit-content;"><i class="bi bi-box-seam text-secondary fs-4"></i></div>
                <h4 class="fw-bold mb-0" id="stat-pack">-</h4><active class="text-muted">Packing Lines</small>
            </div></div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-5">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart me-1"></i>Batch Status Distribution</h6>
                <div style="height:280px;position:relative;"><canvas id="chart-status"></canvas></div>
            </div></div>
        </div>
        <div class="col-xl-7">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-1"></i>Hourly Process Yield</h6>
                <div style="height:280px;position:relative;"><canvas id="chart-yield"></canvas></div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle text-danger me-1"></i>Active Rework / ADU Batches</h6>
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="rework-table">
            <thead class="table-light"><tr><th class="text-center" style="width:30px">No</th><th>Batch No</th><th>Product ID</th><th>Tipe Adonan</th><th>Lini</th><th>Shift</th><th>Tipe Rework</th><th>Alasan</th><th class="text-center">Mulai</th><th class="text-center">Status</th></tr></thead>
            <tbody id="rework-body"></tbody>
        </table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
let statusChart=null, yieldChart=null;

function loadData(){
    $.get('{{route("production-process-dashboard.data")}}',function(d){
        $('#stat-active').text(d.active_batch_in_progress_count);
        $('#stat-base').text(d.total_base_completed_today);
        $('#stat-cm').text(d.total_cm_completed_today);
        $('#stat-qc').text(d.qc_pass_rate_percent+'%');
        $('#stat-rework').text(d.rework_adu_count);
        $('#stat-pack').text(d.active_packaging_lines_count);

        if(statusChart) statusChart.destroy();
        statusChart=new Chart($('#chart-status'),{type:'doughnut',data:{labels:d.batch_status_distribution.map(i=>i.label),datasets:[{data:d.batch_status_distribution.map(i=>i.count),backgroundColor:d.batch_status_distribution.map(i=>i.color),borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{boxWidth:12,padding:10,font:{size:11}}}}}});

        if(yieldChart) yieldChart.destroy();
        yieldChart=new Chart($('#chart-yield'),{type:'line',data:{labels:d.hourly_process_yield.map(i=>i.hour),datasets:[{label:'Base',data:d.hourly_process_yield.map(i=>i.base),borderColor:'#4e73df',backgroundColor:'rgba(78,115,223,0.1)',fill:true,tension:0.3},{label:'CM',data:d.hourly_process_yield.map(i=>i.cm),borderColor:'#1cc88a',backgroundColor:'rgba(28,200,138,0.1)',fill:true,tension:0.3},{label:'Packing',data:d.hourly_process_yield.map(i=>i.packing),borderColor:'#f6c23e',backgroundColor:'rgba(246,194,62,0.1)',fill:true,tension:0.3}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top',labels:{boxWidth:12,padding:8,font:{size:11}}}},scales:{y:{beginAtZero:true,grid:{color:'rgba(0,0,0,0.05)'}},x:{grid:{display:false}}}}});

        const $body=$('#rework-body');$body.empty();
        d.active_rework_batches_notification.forEach(function(b,i){
            const stCls=b.status==='In Progress'?'bg-danger':(b.status==='Pending QC'?'bg-warning text-dark':'bg-success');
            $body.append(`<tr><td class="text-center text-muted">${i+1}</td><td><code>${b.batch_no}</code></td><td>${b.product_id}</td><td>${b.type}</td><td>${b.line}</td><td>${b.shift}</td><td><span class="badge bg-danger">${b.rework_type}</span></td><td>${b.reason}</td><td class="text-center">${b.started_at}</td><td class="text-center"><span class="badge ${stCls}">${b.status}</span></td></tr>`);
        });
    });
}

loadData();
</script>
@endpush