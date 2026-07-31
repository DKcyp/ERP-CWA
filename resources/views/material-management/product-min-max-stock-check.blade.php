@extends('layouts.layout')
@section('title','Product Min Max Stock Check')
@section('content')
<div class="page-content">
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm hz-card border-start border-danger border-4"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-danger bg-opacity-10 p-3"><i class="bi bi-exclamation-triangle text-danger fs-4"></i></div></div><div><p class="text-muted small mb-0">Below Min Stock</p><h4 class="mb-0 fw-bold text-danger">{{ number_format($belowMin) }} <small class="text-muted fs-6">items</small></h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm hz-card border-start border-success border-4"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-success bg-opacity-10 p-3"><i class="bi bi-check-circle text-success fs-4"></i></div></div><div><p class="text-muted small mb-0">Normal</p><h4 class="mb-0 fw-bold text-success">{{ number_format($normal) }} <small class="text-muted fs-6">items</small></h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm hz-card border-start border-warning border-4"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="bi bi-arrow-up-circle text-warning fs-4"></i></div></div><div><p class="text-muted small mb-0">Over Max Stock</p><h4 class="mb-0 fw-bold text-warning">{{ number_format($overMax) }} <small class="text-muted fs-6">items</small></h4></div></div>
            </div></div>
        </div>
    </div>
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Gudang</label><select class="form-select form-select-sm" id="filter-warehouse"><option value="all">Semua</option><option value="Gudang Bahan Bandung">Gudang Bahan Bandung</option><option value="Gudang Bahan Jakarta">Gudang Bahan Jakarta</option><option value="Gudang WIP Bandung">Gudang WIP Bandung</option><option value="Gudang Jadi Bandung">Gudang Jadi Bandung</option><option value="Gudang Jadi Jakarta">Gudang Jadi Jakarta</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Status</label><select class="form-select form-select-sm" id="filter-status"><option value="all">Semua</option><option value="Below Min">Below Min</option><option value="Normal">Normal</option><option value="Over Max">Over Max</option></select></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Product ID</th><th>Name</th><th>Warehouse</th><th class="text-center">Current Stock</th><th class="text-center">Min Stock</th><th class="text-center">Max Stock</th><th class="text-center">Safety Stock</th><th class="text-center">Reorder Qty</th><th class="text-center">Status Alert</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-min-max-stock-check.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_warehouse=$('#filter-warehouse').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'warehouse',name:'warehouse'},
{data:'current_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-center'},
{data:'min_fmt',name:'min_stock',orderable:false,searchable:false,className:'text-center'},
{data:'max_fmt',name:'max_stock',orderable:false,searchable:false,className:'text-center'},
{data:'safety_fmt',name:'safety_stock',orderable:false,searchable:false,className:'text-center'},
{data:'reorder_fmt',name:'reorder_qty',orderable:false,searchable:false,className:'text-center'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-warehouse').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-status').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-warehouse').val('all');$('#filter-status').val('all');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-min-max-stock-check.table')}}",data:{filter_search:$('#filter-search').val(),filter_warehouse:$('#filter-warehouse').val(),filter_status:$('#filter-status').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Product ID,Name,Warehouse,Current Stock,Min Stock,Max Stock,Safety Stock,Reorder Qty,Status\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.product_id}","${row.name}","${row.warehouse}","${row.current_fmt.replace(/\./g,'')}","${row.min_fmt.replace(/\./g,'')}","${row.max_fmt.replace(/\./g,'')}","${row.safety_fmt.replace(/\./g,'')}","${row.reorder_fmt.replace(/\./g,'')}","${(row.status_badge||'').replace(/<[^>]*>/g,'')}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-min-max-stock-check.csv';a.click()}})}
</script>
@endpush