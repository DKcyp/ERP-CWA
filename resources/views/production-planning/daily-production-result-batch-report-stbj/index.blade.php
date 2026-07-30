@extends('layouts.layout')
@section('title','Daily Production Result Batch Report (STBJ)')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="STBJ No, Batch..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-building me-1"></i>Gudang Tujuan</label><select class="form-select" id="filter-warehouse"><option value="all">Semua</option><option value="Gudang Jadi Bandung">Gudang Jadi Bandung</option><option value="Gudang Jadi Jakarta">Gudang Jadi Jakarta</option><option value="Gudang Bahan Bandung">Gudang Bahan Bandung</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-result-batch-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>STBJ No</th><th>Production ID</th><th>Batch No</th><th>Product Name</th><th>Gudang Tujuan</th><th class="text-center">Received (Pcs)</th><th class="text-end">Weight (Kg)</th><th>User ID</th><th>Status</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-production-result-batch-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_warehouse=$('#filter-warehouse').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'stbj_no',name:'stbj_no'},
{data:'production_id',name:'production_id'},
{data:'batch_no',name:'batch_no'},
{data:'product_name',name:'product_name'},
{data:'warehouse_target',name:'warehouse_target'},
{data:'total_qty_received_pcs',name:'total_qty_received_pcs',className:'text-center'},
{data:'total_weight_kg',name:'total_weight_kg',className:'text-end',render:function(d){return d.toLocaleString('id-ID')}},
{data:'user_id',name:'user_id'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-warehouse').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-warehouse').val('all');tbl.ajax.reload()});
</script>
@endpush