@extends('layouts.layout')
@section('title','Daily Production Material Cost Recap Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Grup produk..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-tag me-1"></i>Grup Produk</label><select class="form-select" id="filter-group"><option value="all">Semua</option><option value="Wall Paint">Wall Paint</option><option value="Primer">Primer</option><option value="Top Coat">Top Coat</option><option value="Ekonomis">Ekonomis</option></select></div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-material-cost-recap-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Biaya</p><h5 class="fw-bold mb-0 text-primary" id="sum-cost">Rp 0</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Produksi</p><h5 class="fw-bold mb-0" id="sum-prod">0</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Kg</p><h5 class="fw-bold mb-0" id="sum-kg">0</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Avg Cost/Kg</p><h5 class="fw-bold mb-0" id="sum-avg">Rp 0</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Avg Variance</p><h5 class="fw-bold mb-0" id="sum-var">0%</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Data Points</p><h5 class="fw-bold mb-0" id="sum-count">0</h5>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <h6 class="card-title fw-bold"><i class="bi bi-graph-up me-1"></i>Tren Rata-rata Biaya Material per Kg</h6>
        <div style="height:280px;position:relative;"><canvas id="chartTrend"></canvas></div>
    </div></div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Period</th><th>Product Group</th><th class="text-center">Prod Count</th><th class="text-end">Total Kg</th><th class="text-end">Total Material Cost</th><th class="text-end">Avg Cost/Kg</th><th class="text-end">Std Cost/Kg</th><th class="text-center">Variance</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const fmtRp=v=>'Rp '+Number(v).toLocaleString('id-ID');
let chart=null;

function loadChart(){
    $.get('{{route("daily-production-material-cost-recap-report.chart")}}',{filter_date_from:$('#filter-date-from').val(),filter_date_to:$('#filter-date-to').val(),filter_group:$('#filter-group').val()},function(res){
        if(chart)chart.destroy();
        chart=new Chart($('#chartTrend')[0],{type:'line',data:{labels:res.labels,datasets:res.datasets},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{usePointStyle:true,boxWidth:6}}},scales:{y:{beginAtZero:false,ticks:{callback:v=>'Rp '+v.toLocaleString('id-ID')}}}}});
    });
}

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-material-cost-recap-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_group=$('#filter-group').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-cost').text(fmtRp(s.total_cost||0));$('#sum-prod').text((s.total_prod||0).toLocaleString('id-ID'));$('#sum-kg').text((s.total_kg||0).toLocaleString('id-ID'));$('#sum-avg').text(fmtRp(s.avg_cost||0));$('#sum-var').text((s.avg_variance||0)+'%');$('#sum-count').text(json.recordsTotal||0);loadChart();return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'period_fmt',name:'period',className:'text-center'},
{data:'product_group',name:'product_group'},
{data:'total_production_count',name:'total_production_count',className:'text-center'},
{data:'total_kg',name:'total_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'total_cost_fmt',name:'total_material_cost_accumulated',orderable:false,searchable:false,className:'text-end'},
{data:'avg_cost_fmt',name:'average_cost_per_kg',orderable:false,searchable:false,className:'text-end'},
{data:'std_cost_fmt',name:'standard_cost_per_kg',orderable:false,searchable:false,className:'text-end'},
{data:'variance_badge',name:'variance_to_standard',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-group').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-group').val('all');tbl.ajax.reload()});
</script>
@endpush