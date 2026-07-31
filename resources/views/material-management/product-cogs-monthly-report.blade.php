@extends('layouts.layout')
@section('title','Product COGS Monthly Report')
@section('content')
<div class="page-content">
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-primary bg-opacity-10 p-3"><i class="bi bi-cash-stack text-primary fs-4"></i></div></div><div><p class="text-muted small mb-0">Total COGS Valuation</p><h4 class="mb-0 fw-bold">Rp {{ number_format($totalVal, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-success bg-opacity-10 p-3"><i class="bi bi-stack text-success fs-4"></i></div></div><div><p class="text-muted small mb-0">Total Manufactured Qty</p><h4 class="mb-0 fw-bold">{{ number_format($totalQty, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-warning bg-opacity-10 p-3"><i class="bi bi-graph-up text-warning fs-4"></i></div></div><div><p class="text-muted small mb-0">Avg COGS/Unit</p><h4 class="mb-0 fw-bold">Rp {{ number_format($avgCogs, 0, ',', '.') }}</h4></div></div>
            </div></div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body py-2">
                <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="rounded-3 bg-info bg-opacity-10 p-3"><i class="bi bi-calendar-range text-info fs-4"></i></div></div><div><p class="text-muted small mb-0">Periods</p><h4 class="mb-0 fw-bold">{{ count($periods) }} <small class="text-muted fs-6">bulan</small></h4></div></div>
            </div></div>
        </div>
    </div>
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted">Periode</label><select class="form-select form-select-sm" id="filter-month"><option value="all">Semua Periode</option>@foreach($periods as $m)<option value="{{ $m }}">{{ \Carbon\Carbon::parse($m.'-01')->format('F Y') }}</option>@endforeach</select></div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="bi bi-download me-1"></i>Export</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th class="text-center">Periode</th><th>Product ID</th><th>Name</th><th class="text-end">Avg COGS/Unit</th><th class="text-center">Total Manufactured Qty</th><th class="text-end">Total COGS Valuation</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('product-cogs-monthly-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_month=$('#filter-month').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'period_fmt',name:'period',className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'avg_cogs_fmt',name:'avg_cogs_unit',orderable:false,searchable:false,className:'text-end'},
{data:'qty_fmt',name:'total_manufactured_qty',orderable:false,searchable:false,className:'text-center'},
{data:'valuation_fmt',name:'total_cogs_valuation',orderable:false,searchable:false,className:'text-end'}
]});
$('#filter-search').on('keyup',function(){$('#table-data').DataTable().ajax.reload()});
$('#filter-month').on('change',function(){$('#table-data').DataTable().ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-month').val('all');$('#table-data').DataTable().ajax.reload()});
function exportCSV(){$.ajax({url:"{{route('product-cogs-monthly-report.table')}}",data:{filter_search:$('#filter-search').val(),filter_month:$('#filter-month').val(),start:0,length:10000,draw:1},success:function(r){let csv='No,Period,Product ID,Name,Avg COGS Unit,Total Qty,Total COGS Valuation\n';r.data.forEach(function(row){csv+=`"${row.DT_RowIndex}","${row.period_fmt}","${row.product_id}","${row.name}","${row.avg_cogs_fmt.replace(/[^0-9]/g,'')}","${row.qty_fmt.replace(/\./g,'')}","${row.valuation_fmt.replace(/[^0-9]/g,'')}"\n`});const blob=new Blob([csv],{type:'text/csv'});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='product-cogs-monthly-report.csv';a.click()}})}
</script>
@endpush