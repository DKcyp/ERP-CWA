@extends('layouts.layout')
@section('title','Product Stock Summary')
@section('content')
<div class="page-content">
    @php
        $totalValuationFmt = number_format($totalValuation, 0, ',', '.');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-primary bg-opacity-10 p-3"><i class="bi bi-box text-primary fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Unique Products</p><h4 class="mb-0 fw-bold">{{ number_format($totalItems) }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="bi bi-stack text-warning fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Quantity</p><h4 class="mb-0 fw-bold">{{ number_format($totalStock, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-success bg-opacity-10 p-3"><i class="bi bi-cash-stack text-success fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Stock Valuation (IDR)</p><h4 class="mb-0 fw-bold">Rp {{ $totalValuationFmt }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-info bg-opacity-10 p-3"><i class="bi bi-tags text-info fs-4"></i></div></div><div><p class="text-muted small mb-0">Categories</p><h4 class="mb-0 fw-bold">4</h4></div></div>
            </div></div>
        </div>
    </div>
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted">Kategori</label><select class="form-select form-select-sm" id="filter-category"><option value="all">Semua Kategori</option><option value="Bahan Baku">Bahan Baku</option><option value="Penolong">Penolong</option><option value="WIP">WIP</option><option value="Finished Goods">Finished Goods</option></select></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Product ID</th><th>Product Name</th><th>Kategori</th><th class="text-center">Warehouse</th><th class="text-center">Total Qty</th><th class="text-center">Reserved</th><th class="text-end">Unit Price (IDR)</th><th class="text-end">Total Valuation (IDR)</th><th class="text-center">UOM</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-stock-summary.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_category=$('#filter-category').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'category_badge',name:'category',orderable:false,searchable:false,className:'text-center'},
{data:'total_warehouses',name:'total_warehouses',orderable:false,searchable:false,className:'text-center',render:function(d){return d+' Gudang'}},
{data:'total_qty_fmt',name:'total_qty',orderable:false,searchable:false,className:'text-center'},
{data:'total_reserved_fmt',name:'total_reserved',orderable:false,searchable:false,className:'text-center'},
{data:'unit_price_fmt',name:'unit_price',orderable:false,searchable:false,className:'text-end'},
{data:'valuation_fmt',name:'valuation',orderable:false,searchable:false,className:'text-end'},
{data:'uom',name:'uom',className:'text-center'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-category').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-category').val('all');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-stock-summary.table')}}",data:{filter_search:$('#filter-search').val(),filter_category:$('#filter-category').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Product ID,Name,Category,Total Warehouses,Total Qty,Reserved,Unit Price IDR,Valuation IDR,UOM\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.product_id}","${row.name}","${(row.category_badge||'').replace(/<[^>]*>/g,'')}","${row.total_warehouses}","${row.total_qty_fmt}","${row.total_reserved_fmt}","${row.unit_price_fmt}","${row.valuation_fmt}","${row.uom}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-stock-summary.csv';a.click()}})}
</script>
@endpush