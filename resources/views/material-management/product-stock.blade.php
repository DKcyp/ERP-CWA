@extends('layouts.layout')
@section('title','Product Stock')
@section('content')
<div class="page-content">
    @php
        $totalAvailable = $totalCurrent - $totalReserved;
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-primary bg-opacity-10 p-3"><i class="bi bi-box text-primary fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Current Stock</p><h4 class="mb-0 fw-bold">{{ number_format($totalCurrent, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="bi bi-box-seam text-warning fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Reserved Stock</p><h4 class="mb-0 fw-bold">{{ number_format($totalReserved, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-success bg-opacity-10 p-3"><i class="bi bi-box-check text-success fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Available Stock</p><h4 class="mb-0 fw-bold">{{ number_format($totalAvailable, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-info bg-opacity-10 p-3"><i class="bi bi-hdd-stack text-info fs-4"></i></div></div><div><p class="text-muted small mb-0">Warehouse Locations</p><h4 class="mb-0 fw-bold">{{ count($warehouses) }}</h4></div></div>
            </div></div>
        </div>
    </div>
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Gudang</label><select class="form-select form-select-sm" id="filter-warehouse"><option value="all">Semua Gudang</option>@foreach($warehouses as $w)<option value="{{ $w }}">{{ $w }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Kategori</label><select class="form-select form-select-sm" id="filter-category"><option value="all">Semua Kategori</option>@foreach($categories as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach</select></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Product ID</th><th>Name</th><th>Kategori</th><th>Warehouse</th><th class="text-center">Current Stock</th><th class="text-center">Reserved</th><th class="text-center">Available Stock</th><th class="text-center">UOM</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('material-management.product-stock.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_warehouse=$('#filter-warehouse').val();d.filter_category=$('#filter-category').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'category_badge',name:'category',orderable:false,searchable:false,className:'text-center'},
{data:'warehouse',name:'warehouse'},
{data:'current_stock_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-center'},
{data:'reserved_stock_fmt',name:'reserved_stock',orderable:false,searchable:false,className:'text-center'},
{data:'available_stock_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-center'},
{data:'uom',name:'uom',className:'text-center'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});$('#filter-warehouse').on('change',function(){$('#table-data').DataTable().ajax.reload()});$('#filter-category').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-warehouse').val('all');$('#filter-category').val('all');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-stock.table')}}",data:{filter_search:$('#filter-search').val(),filter_warehouse:$('#filter-warehouse').val(),filter_category:$('#filter-category').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Product ID,Name,Category,Warehouse,Current Stock,Reserved Stock,Available Stock,UOM\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.product_id}","${row.name}","${(row.category_badge||'').replace(/<[^>]*>/g,'')}","${row.warehouse}","${row.current_stock_fmt}","${row.reserved_stock_fmt}","${row.available_stock_fmt}","${row.uom}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-stock-report.csv';a.click()}})};
</script>
@endpush