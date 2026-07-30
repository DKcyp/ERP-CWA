@extends('layouts.layout')
@section('title','Daily Production Result Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Cari ID, Produk, Batch..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-tag me-1"></i>Kelompok Produk</label><select class="form-select" id="filter-group"><option value="all">Semua</option><option value="Wall Paint">Wall Paint</option><option value="Primer">Primer</option><option value="Top Coat">Top Coat</option><option value="Ekonomis">Ekonomis</option></select></div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-result-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-box-seam fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Output (Pcs)</p><h4 class="fw-bold mb-0" id="sum-pcs">-</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-speedometer fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Output (Kg)</p><h4 class="fw-bold mb-0" id="sum-kg">-</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle"><i class="bi bi-x-octagon fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Reject (Kg)</p><h4 class="fw-bold mb-0" id="sum-reject">-</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-graph-up-arrow fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Avg Yield / Reject %</p><h4 class="fw-bold mb-0"><span id="sum-yield">-</span> / <span id="sum-reject-pct" class="text-danger">-</span></h4></div></div>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>Production ID</th><th>Product Name</th><th>Batch No</th><th class="text-center">Output (Pcs)</th><th class="text-end">Output (Kg)</th><th class="text-end">Reject (Kg)</th><th class="text-center">Yield</th><th>Group</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-result-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_group=$('#filter-group').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-pcs').text((s.total_pcs||0).toLocaleString('id-ID'));$('#sum-kg').text((s.total_kg||0).toLocaleString('id-ID')+' Kg');$('#sum-reject').text((s.total_reject||0).toLocaleString('id-ID')+' Kg');$('#sum-yield').text((s.avg_yield||0)+'%');$('#sum-reject-pct').text((s.reject_pct||0)+'%');return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'product_name',name:'product_name'},
{data:'batch_no',name:'batch_no'},
{data:'total_output_pcs',name:'total_output_pcs',className:'text-center'},
{data:'total_output_kg',name:'total_output_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'reject_qty_kg',name:'reject_qty_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'yield_badge',name:'yield_percent',orderable:false,searchable:false,className:'text-center'},
{data:'group',name:'group'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-group').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-group').val('all');tbl.ajax.reload()});
</script>
@endpush