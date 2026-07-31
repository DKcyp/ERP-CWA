@extends('layouts.layout')
@section('title','Product Stock Minus Report')
@section('content')
<div class="page-content">
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm hz-card border-start border-danger border-4"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-danger bg-opacity-10 p-3"><i class="bi bi-exclamation-triangle text-danger fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Items Stok Minus</p><h4 class="mb-0 fw-bold text-danger">{{ number_format($minusCount) }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="bi bi-shield-exclamation text-warning fs-4"></i></div></div><div><p class="text-muted small mb-0">Status</p><h5 class="mb-0 fw-bold text-danger">Perlu Perhatian Segera</h5></div></div>
            </div></div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-info bg-opacity-10 p-3"><i class="bi bi-info-circle text-info fs-4"></i></div></div><div><p class="text-muted small mb-0">Keterangan</p><p class="mb-0 small"><span class="badge bg-danger">Critical</span> &lt;-100 &nbsp; <span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i>Severe</span> &lt;-50 &nbsp; <span class="badge bg-warning text-dark">Minor</span> &lt;0</p></div></div>
            </div></div>
        </div>
    </div>
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted">Gudang</label><select class="form-select form-select-sm" id="filter-warehouse"><option value="all">Semua Gudang</option><option value="Gudang Bahan Bandung">Gudang Bahan Bandung</option><option value="Gudang Bahan Jakarta">Gudang Bahan Jakarta</option><option value="Gudang WIP Bandung">Gudang WIP Bandung</option><option value="Gudang Jadi Bandung">Gudang Jadi Bandung</option><option value="Gudang Jadi Jakarta">Gudang Jadi Jakarta</option></select></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Product ID</th><th>Name</th><th>Warehouse</th><th class="text-center">Current Stock</th><th class="text-center">Reserved</th><th class="text-center">Available Stock</th><th class="text-center">UOM</th><th class="text-center">Status</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-stock-minus-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_warehouse=$('#filter-warehouse').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'warehouse',name:'warehouse'},
{data:'current_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-center'},
{data:'reserved_fmt',name:'reserved_stock',orderable:false,searchable:false,className:'text-center'},
{data:'available_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-center'},
{data:'uom',name:'uom',className:'text-center'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-warehouse').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-warehouse').val('all');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-stock-minus-report.table')}}",data:{filter_search:$('#filter-search').val(),filter_warehouse:$('#filter-warehouse').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Product ID,Name,Warehouse,Current Stock,Reserved,Available Stock,UOM,Status\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.product_id}","${row.name}","${row.warehouse}","${row.current_fmt.replace(/\./g,'')}","${row.reserved_fmt.replace(/\./g,'')}","${(row.available_fmt||'').replace(/<[^>]*>/g,'').replace(/\./g,'')}","${row.uom}","${(row.status_badge||'').replace(/<[^>]*>/g,'')}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-stock-minus-report.csv';a.click()}})}
</script>
@endpush