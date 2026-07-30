@extends('layouts.layout')
@section('title','Product Price Info')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Kategori</label><select class="form-select form-select-sm" id="filter-category"><option value="all">Semua</option><option value="Bahan Baku">Bahan Baku</option><option value="Penolong">Penolong</option><option value="Finished Goods">Finished Goods</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Margin Min (%)</label><input type="number" class="form-control" id="filter-margin-min" min="0" max="200" placeholder="0"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Margin Max (%)</label><input type="number" class="form-control" id="filter-margin-max" min="0" max="200" placeholder="200"></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Product ID</th><th>Name</th><th>Kategori</th><th class="text-end">Selling Price</th><th class="text-end">Base Cost (COGS)</th><th class="text-center">Margin (%)</th><th class="text-center">Currency</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-price-info.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_category=$('#filter-category').val();d.filter_margin_min=$('#filter-margin-min').val();d.filter_margin_max=$('#filter-margin-max').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'category_badge',name:'category',orderable:false,searchable:false,className:'text-center'},
{data:'selling_fmt',name:'selling_price',orderable:false,searchable:false,className:'text-end'},
{data:'cost_fmt',name:'base_cost',orderable:false,searchable:false,className:'text-end'},
{data:'margin_badge',name:'margin',orderable:false,searchable:false,className:'text-center'},
{data:'currency',name:'currency',className:'text-center'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-category').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-margin-min').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-margin-max').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-category').val('all');$('#filter-margin-min').val('');$('#filter-margin-max').val('');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-price-info.table')}}",data:{filter_search:$('#filter-search').val(),filter_category:$('#filter-category').val(),filter_margin_min:$('#filter-margin-min').val(),filter_margin_max:$('#filter-margin-max').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Product ID,Name,Category,Selling Price,Base Cost,Margin,Currency\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.product_id}","${row.name}","${(row.category_badge||'').replace(/<[^>]*>/g,'')}","${row.selling_fmt.replace(/[^0-9]/g,'')}","${row.cost_fmt.replace(/[^0-9]/g,'')}","${(row.margin_badge||'').replace(/<[^>]*>/g,'')}","${row.currency}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-price-info.csv';a.click()}})}
</script>
@endpush