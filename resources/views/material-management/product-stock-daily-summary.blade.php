@extends('layouts.layout')
@section('title','Product Stock Daily Summary')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th class="text-center">Date</th><th>Product ID</th><th>Name</th><th class="text-end">Stok Awal</th><th class="text-end">In Qty (+)</th><th class="text-end">Out Qty (-)</th><th class="text-end">Stok Akhir</th><th class="text-center">UOM</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-stock-daily-summary.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'initial_fmt',name:'initial_stock',orderable:false,searchable:false,className:'text-end'},
{data:'in_fmt',name:'in_qty',orderable:false,searchable:false,className:'text-end'},
{data:'out_fmt',name:'out_qty',orderable:false,searchable:false,className:'text-end'},
{data:'final_fmt',name:'final_stock',orderable:false,searchable:false,className:'text-end'},
{data:'uom',name:'uom',className:'text-center'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-date-from').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-date-to').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-stock-daily-summary.table')}}",data:{filter_search:$('#filter-search').val(),filter_date_from:$('#filter-date-from').val(),filter_date_to:$('#filter-date-to').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Date,Product ID,Name,Initial Stock,In Qty,Out Qty,Final Stock,UOM\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.date_fmt}","${row.product_id}","${row.name}","${row.initial_fmt.replace(/\./g,'')}","${row.in_fmt.replace(/[+.]/g,'')}","${row.out_fmt.replace(/[-.]/g,'')}","${row.final_fmt.replace(/\./g,'')}","${row.uom}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-stock-daily-summary.csv';a.click()}})}
</script>
@endpush