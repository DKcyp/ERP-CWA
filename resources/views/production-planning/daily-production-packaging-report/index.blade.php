@extends('layouts.layout')
@section('title','Daily Production Packaging Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Production ID..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-box me-1"></i>Jenis Kemasan</label><select class="form-select" id="filter-type"><option value="all">Semua</option><option value="Kaleng">Kaleng</option><option value="Galon">Galon</option><option value="Pail">Pail</option><option value="Box Kardus">Box Kardus</option><option value="Shrink Wrap">Shrink Wrap</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-packaging-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-box-seam fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Used (Pcs)</p><h4 class="fw-bold mb-0" id="sum-used">0</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle"><i class="bi bi-exclamation-triangle fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Damaged (Pcs)</p><h4 class="fw-bold mb-0 text-danger" id="sum-damaged">0</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle"><i class="bi bi-percent fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Avg Damage Rate</p><h4 class="fw-bold mb-0" id="sum-rate">0%</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-cash-stack fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Biaya Kemasan</p><h4 class="fw-bold mb-0 text-success" id="sum-cost">Rp 0</h4></div></div>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>Production ID</th><th>Package Type</th><th class="text-center">Used (Pcs)</th><th class="text-center">Damaged (Pcs)</th><th class="text-center">Damage Rate</th><th class="text-end">Unit Cost</th><th class="text-end">Total Cost</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const fmtRp=v=>'Rp '+Number(v).toLocaleString('id-ID');
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-packaging-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_type=$('#filter-type').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-used').text((s.total_used||0).toLocaleString('id-ID'));$('#sum-damaged').text((s.total_damaged||0).toLocaleString('id-ID'));$('#sum-rate').text((s.avg_damage_rate||0)+'%');$('#sum-cost').text(fmtRp(s.total_cost||0));return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'package_type',name:'package_type'},
{data:'qty_used_pcs',name:'qty_used_pcs',className:'text-center'},
{data:'qty_damaged_pcs',name:'qty_damaged_pcs',className:'text-center',render:function(d){return'<span class="text-danger fw-semibold">'+d+'</span>'}},
{data:'damage_rate',name:'qty_damaged_pcs',orderable:false,searchable:false,className:'text-center'},
{data:'unit_cost_fmt',name:'unit_packaging_cost',orderable:false,searchable:false,className:'text-end'},
{data:'total_cost_fmt',name:'total_packaging_cost',orderable:false,searchable:false,className:'text-end fw-bold'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-type').val('all');tbl.ajax.reload()});
</script>
@endpush