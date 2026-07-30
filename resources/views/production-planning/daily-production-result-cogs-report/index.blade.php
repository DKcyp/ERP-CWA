@extends('layouts.layout')
@section('title','Daily Production Result COGS Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Production ID, Produk, Batch..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-result-cogs-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-danger bg-opacity-10 text-danger rounded-circle"><i class="bi bi-cash-stack fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total COGS</p><h4 class="fw-bold mb-0 text-danger" id="sum-cogs">Rp 0</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-box-seam fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Rata-rata COGS/Kg</p><h4 class="fw-bold mb-0" id="sum-cogs-kg">Rp 0</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-layers fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Rata-rata COGS/Pcs</p><h4 class="fw-bold mb-0" id="sum-cogs-pcs">Rp 0</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-graph-up-arrow fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Komponen Biaya</p><h6 class="mb-0 mt-1"><span class="text-primary" id="sum-mat">0%</span> / <span class="text-warning" id="sum-overhead">0%</span> / <span class="text-success" id="sum-labor">0%</span></h6><small class="text-muted">Material / Overhead / Labor</small></div></div>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>Production ID</th><th>Product Name</th><th>Batch No</th><th class="text-center">Output (Pcs)</th><th class="text-end">Output (Kg)</th><th class="text-end">Material Cost</th><th class="text-end">Overhead</th><th class="text-end">Labor</th><th class="text-end">Total COGS</th><th class="text-end">COGS/Kg</th><th class="text-end">COGS/Pcs</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const fmtRp=v=>'Rp '+Number(v).toLocaleString('id-ID');
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-result-cogs-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-cogs').text(fmtRp(s.total_cogs||0));$('#sum-cogs-kg').text(fmtRp(s.avg_cogs_kg||0));$('#sum-cogs-pcs').text(fmtRp(s.avg_cogs_pcs||0));const t=s.total_cogs||1;$('#sum-mat').text(Math.round((s.total_mat||0)/t*100)+'%');$('#sum-overhead').text(Math.round((s.total_overhead||0)/t*100)+'%');$('#sum-labor').text(Math.round((s.total_labor||0)/t*100)+'%');return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'product_name',name:'product_name'},
{data:'batch_no',name:'batch_no'},
{data:'total_output_pcs',name:'total_output_pcs',className:'text-center'},
{data:'total_output_kg',name:'total_output_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'material_fmt',name:'total_material_cost',orderable:false,searchable:false,className:'text-end'},
{data:'overhead_fmt',name:'overhead_cost',orderable:false,searchable:false,className:'text-end'},
{data:'labor_fmt',name:'labor_cost',orderable:false,searchable:false,className:'text-end'},
{data:'cogs_fmt',name:'total_cogs',orderable:false,searchable:false,className:'text-end fw-bold text-danger'},
{data:'cogs_kg_fmt',name:'cogs_per_kg',orderable:false,searchable:false,className:'text-end'},
{data:'cogs_pcs_fmt',name:'cogs_per_pcs',orderable:false,searchable:false,className:'text-end'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');tbl.ajax.reload()});
</script>
@endpush