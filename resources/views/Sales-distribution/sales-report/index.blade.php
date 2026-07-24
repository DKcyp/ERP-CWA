@extends('layouts.layout')
@section('title','Sales Report')
@push('after-style')
<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}.nav-tabs .nav-link{font-size:.85rem}.filter-section .form-label{font-size:.8rem;margin-bottom:2px}.table-container{min-height:300px}.tab-pane{min-height:400px}</style>
@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4 filter-section"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted"><i class="bi bi-calendar me-1"></i>Tanggal Awal</label>
                <input type="date" class="form-control form-control-sm" id="filter-date-start">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted"><i class="bi bi-calendar me-1"></i>Tanggal Akhir</label>
                <input type="date" class="form-control form-control-sm" id="filter-date-end">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted"><i class="bi bi-list-ul me-1"></i>Series</label>
                <select class="form-select form-select-sm" id="filter-series">
                    <option value="">Semua Series</option>
                    @foreach($seriesList as $s)
                    <option value="{{$s}}">{{$s}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted"><i class="bi bi-tag me-1"></i>Brand</label>
                <select class="form-select form-select-sm" id="filter-brand">
                    <option value="">Semua Brand</option>
                    @foreach($brandList as $b)
                    <option value="{{$b}}">{{$b}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold text-muted"><i class="bi bi-receipt me-1"></i>VAT</label>
                <select class="form-select form-select-sm" id="filter-vat">
                    <option value="">Semua</option>
                    <option value="vat">VAT</option>
                    <option value="non-vat">Non VAT</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="button" class="btn btn-primary btn-sm flex-fill" id="btn-apply-filter"><i class="bi bi-filter me-1"></i>Filter</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
    </div></div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <ul class="nav nav-tabs nav-fill mb-3" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-group="customer" type="button"><i class="bi bi-people me-1"></i>By Customer</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-group="product" type="button"><i class="bi bi-box me-1"></i>By Product</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-group="supplier" type="button"><i class="bi bi-truck me-1"></i>By Supplier</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-group="salesman" type="button"><i class="bi bi-person-badge me-1"></i>By Salesman</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-group="category" type="button"><i class="bi bi-grid me-1"></i>By Category</button>
            </li>
        </ul>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="table-data">
                    <thead class="table-light"></thead>
                </table>
            </div>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-report.table')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
let currentGroup='customer';

const colMap={
    customer:[{data:'DT_RowIndex',className:'text-center',width:'40px'},{data:'id',title:'Customer ID'},{data:'name',title:'Customer Name'},{data:'total_qty_fmt',title:'Total Qty',className:'text-end'},{data:'total_amount_fmt',title:'Total Amount',className:'text-end'}],
    product:[{data:'DT_RowIndex',className:'text-center',width:'40px'},{data:'id',title:'Product ID'},{data:'name',title:'Product Name'},{data:'total_qty_fmt',title:'Total Qty',className:'text-end'},{data:'total_amount_fmt',title:'Total Amount',className:'text-end'}],
    supplier:[{data:'DT_RowIndex',className:'text-center',width:'40px'},{data:'id',title:'Supplier ID'},{data:'name',title:'Supplier Name'},{data:'total_qty_fmt',title:'Total Qty',className:'text-end'},{data:'total_amount_fmt',title:'Total Amount',className:'text-end'}],
    salesman:[{data:'DT_RowIndex',className:'text-center',width:'40px'},{data:'id',title:'Salesman ID'},{data:'name',title:'Salesman Name'},{data:'total_qty_fmt',title:'Total Qty',className:'text-end'},{data:'total_amount_fmt',title:'Total Amount',className:'text-end'}],
    category:[{data:'DT_RowIndex',className:'text-center',width:'40px'},{data:'name',title:'Category'},{data:'total_qty_fmt',title:'Total Qty',className:'text-end'},{data:'total_amount_fmt',title:'Total Amount',className:'text-end'}]
};

let tbl;

function initTable(group){
    if(tbl){tbl.destroy();tbl=null;}
    const cols=colMap[group]||colMap.customer;
    const thead=$('#table-data thead');thead.empty();
    const tr=$('<tr>');$.each(cols,function(i,c){tr.append($('<th>').text(c.title||'').addClass(c.className||''))});thead.append(tr);

    tbl=$('#table-data').DataTable({
        processing:true,serverSide:true,
        ajax:{url:tableUrl,data:function(d){
            d.group_by=group;
            d.filter_date_start=$('#filter-date-start').val();
            d.filter_date_end=$('#filter-date-end').val();
            d.filter_series=$('#filter-series').val();
            d.filter_brand=$('#filter-brand').val();
            d.filter_vat=$('#filter-vat').val();
        }},
        columns:cols,
        order:[[cols.length-1,'desc']]
    });
}

$('#reportTabs .nav-link').on('click',function(){
    $('#reportTabs .nav-link').removeClass('active');
    $(this).addClass('active');
    currentGroup=$(this).data('group');
    initTable(currentGroup);
});

$('#btn-apply-filter').on('click',function(){initTable(currentGroup)});
$('#btn-reset-filter').on('click',function(){
    $('#filter-date-start').val('');$('#filter-date-end').val('');
    $('#filter-series').val('');$('#filter-brand').val('');$('#filter-vat').val('');
    initTable(currentGroup);
});

initTable('customer');
</script>
@endpush