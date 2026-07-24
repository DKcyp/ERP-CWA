@extends('layouts.layout')
@section('title','AR Warehouse Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari customer, warehouse, atau invoice...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-building me-1"></i>Warehouse</label>
                <select id="filter-wh" class="form-select"><option value="all">Semua</option><option value="WH-001">Gudang Utama</option><option value="WH-002">Gudang Bahan Baku</option><option value="WH-003">Gudang Finished Goods</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>WH ID</th><th>WH Name</th><th>Customer ID</th><th>Customer</th><th>Invoice No</th><th class="text-center">Tgl Invoice</th><th class="text-center">Jatuh Tempo</th><th class="text-end">Outstanding</th><th class="text-center">Age</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('ar-warehouse.table')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_warehouse=$('#filter-wh').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'warehouse_id',name:'warehouse_id'},{data:'warehouse_name',name:'warehouse_name'},{data:'customer_id',name:'customer_id'},{data:'customer_name',name:'customer_name'},{data:'invoice_no',name:'invoice_no'},{data:'invoice_date_fmt',name:'invoice_date',className:'text-center'},{data:'due_date_fmt',name:'due_date',className:'text-center'},{data:'outstanding_fmt',name:'outstanding_amount',className:'text-end'},{data:'age_badge',name:'age_days',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-wh').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-wh').val('all');tbl.ajax.reload()});
</script>
@endpush
