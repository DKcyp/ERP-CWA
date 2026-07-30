@extends('layouts.layout')
@section('title','Product Stock Track Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama, Ref Doc..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Tipe</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Purchase Receipt">Purchase Receipt</option><option value="Production Output">Production Output</option><option value="Production Usage">Production Usage</option><option value="Sales Delivery">Sales Delivery</option><option value="Stock Adjustment (+)">Stock Adj (+)</option><option value="Stock Adjustment (-)">Stock Adj (-)</option><option value="Transfer In">Transfer In</option><option value="Transfer Out">Transfer Out</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th class="text-center">Trans Date</th><th>Product ID</th><th>Name</th><th>Ref Doc No</th><th>Tipe Transaksi</th><th class="text-end">In Qty (+)</th><th class="text-end">Out Qty (-)</th><th class="text-end">Balance</th><th>User ID</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-stock-track-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_type=$('#filter-type').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'trans_date',className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'ref_doc_no',name:'ref_doc_no'},
{data:'type_badge',name:'transaction_type',orderable:false,searchable:false,className:'text-center'},
{data:'in_fmt',name:'in_qty',orderable:false,searchable:false,className:'text-end'},
{data:'out_fmt',name:'out_qty',orderable:false,searchable:false,className:'text-end'},
{data:'balance_fmt',name:'balance_qty',orderable:false,searchable:false,className:'text-end'},
{data:'user_id',name:'user_id'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-date-from').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-date-to').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-type').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-type').val('all');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-stock-track-report.table')}}",data:{filter_search:$('#filter-search').val(),filter_date_from:$('#filter-date-from').val(),filter_date_to:$('#filter-date-to').val(),filter_type:$('#filter-type').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Trans Date,Product ID,Name,Ref Doc No,Type,In Qty,Out Qty,Balance,User ID\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.date_fmt}","${row.product_id}","${row.name}","${row.ref_doc_no}","${(row.type_badge||'').replace(/<[^>]*>/g,'')}","${(row.in_fmt||'').replace(/[^0-9]/g,'')}","${(row.out_fmt||'').replace(/[^0-9]/g,'')}","${row.balance_fmt}","${row.user_id}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-stock-track-report.csv';a.click()}})}
</script>
@endpush