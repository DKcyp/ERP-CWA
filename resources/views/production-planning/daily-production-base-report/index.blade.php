@extends('layouts.layout')
@section('title','Daily Production Base Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Cari ID, Base, Batch..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-gear me-1"></i>Mesin</label><select class="form-select" id="filter-machine"><option value="all">Semua</option><option value="LINE-A1">LINE-A1</option><option value="LINE-A2">LINE-A2</option><option value="LINE-B1">LINE-B1</option><option value="LINE-B2">LINE-B2</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-droplet me-1"></i>Base</label><select class="form-select" id="filter-base"><option value="all">Semua</option><option value="White Titanium Base">White Titanium</option><option value="Grey Primer Base">Grey Primer</option><option value="Cream Color Base">Cream Color</option><option value="Clear Solvent Base">Clear Solvent</option><option value="Blue Color Base">Blue Color</option></select></div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-base-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>Production ID</th><th>Base Name</th><th>Batch No</th><th>Machine</th><th class="text-end">Target (Kg)</th><th class="text-end">Actual (Kg)</th><th class="text-center">Variance</th><th>Operator</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-base-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_machine=$('#filter-machine').val();d.filter_base=$('#filter-base').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'base_name',name:'base_name'},
{data:'batch_no',name:'batch_no'},
{data:'machine_id',name:'machine_id'},
{data:'target_base_kg',name:'target_base_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'actual_base_kg',name:'actual_base_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'variance_badge',name:'variance_kg',orderable:false,searchable:false,className:'text-center'},
{data:'operator',name:'operator'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-machine').on('change',function(){tbl.ajax.reload()});$('#filter-base').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-machine').val('all');$('#filter-base').val('all');tbl.ajax.reload()});
</script>
@endpush