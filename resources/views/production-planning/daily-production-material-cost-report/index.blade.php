@extends('layouts.layout')
@section('title','Daily Production Material Cost Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Production ID, Batch, Material..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-material-cost-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body py-3">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-cash-stack fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Biaya Bahan (Filtered)</p><h4 class="fw-bold mb-0 text-success" id="sum-total-cost">Rp 0</h4></div>
        </div>
    </div></div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>Production ID</th><th>Batch No</th><th>Material ID</th><th>Material Name</th><th class="text-end">Qty Used</th><th>UOM</th><th class="text-end">Unit Cost</th><th class="text-end">Total Cost</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const fmtRp=v=>'Rp '+Number(v).toLocaleString('id-ID');
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-material-cost-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-total-cost').text(fmtRp(s.total_cost||0));return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'batch_no',name:'batch_no'},
{data:'material_id',name:'material_id'},
{data:'material_name',name:'material_name'},
{data:'qty_used',name:'qty_used',className:'text-end',render:function(d){return d.toLocaleString('id-ID',{minimumFractionDigits:1})}},
{data:'uom',name:'uom',className:'text-center'},
{data:'unit_cost_fmt',name:'unit_cost',orderable:false,searchable:false,className:'text-end'},
{data:'total_cost_fmt',name:'total_material_cost',orderable:false,searchable:false,className:'text-end fw-bold'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');tbl.ajax.reload()});
</script>
@endpush