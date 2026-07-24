@extends('layouts.layout')
@section('title','Sales Order Fulfilment')
@push('after-style')
<style>
    #table-data thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
</style>
@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari customer, SO, atau produk...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="FULL">Full</option><option value="PARTIAL">Partial</option><option value="PENDING">Pending</option></select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Cust ID</th><th>Name</th><th>Area</th><th>SO No</th><th class="text-center">SO Date</th><th>Warehouse</th><th>Product ID</th><th>Product Name</th><th>Desc</th><th class="text-center">SO Qty</th><th>SO UOM</th><th class="text-center">SI Date</th><th class="text-center">SI Qty</th><th>SI UOM</th><th class="text-center">Qty Diff</th><th class="text-end">Tonase</th><th>Note</th><th class="text-center">Status</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Fulfilment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="detail-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Sales Order</small><span id="detail-so" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">SO Date</small><span id="detail-so-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Warehouse</small><span id="detail-wh" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Status</small><span id="detail-status">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Product</small><span id="detail-product" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">SO Qty</small><span id="detail-so-qty" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">SI Qty</small><span id="detail-si-qty" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Qty Diff</small><span id="detail-qty-diff" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Tonase</small><span id="detail-tonase" class="fw-semibold">-</span></div>
                <div class="col-12"><small class="text-muted d-block">Note</small><span id="detail-note" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-order-fulfilment.table')}}",showUrl="{{route('sales-order-fulfilment.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'area',name:'area'},{data:'sales_order',name:'sales_order'},{data:'so_date_fmt',name:'so_date',className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'product_id',name:'product_id'},{data:'product_name',name:'product_name'},{data:'description',name:'description'},{data:'so_qty',name:'so_qty',className:'text-center'},{data:'so_uom_id',name:'so_uom_id'},{data:'si_date_fmt',name:'si_date',className:'text-center'},{data:'si_qty',name:'si_qty',className:'text-center'},{data:'si_uom_id',name:'si_uom_id'},{data:'qty_diff',name:'qty_diff',orderable:false,searchable:false,className:'text-center'},{data:'tonase',name:'tonase',className:'text-end'},{data:'note',name:'note'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-customer').text((d.customer_id??'')+' - '+(d.name??''));$('#detail-so').text(d.sales_order??'-');$('#detail-so-date').text(d.so_date??'-');$('#detail-wh').text(d.warehouse??'-');const sm={FULL:'<span class=\"badge bg-success\">Full</span>',PARTIAL:'<span class=\"badge bg-warning text-dark\">Partial</span>',PENDING:'<span class=\"badge bg-secondary\">Pending</span>'};$('#detail-status').html(sm[d.status]??d.status);$('#detail-product').text((d.product_id??'')+' - '+(d.product_name??''));$('#detail-so-qty').text(d.so_qty??'0');$('#detail-si-qty').text(d.si_qty??'0');$('#detail-qty-diff').text((parseInt(d.so_qty||0)-parseInt(d.si_qty||0)));$('#detail-tonase').text(d.tonase??'0');$('#detail-note').text(d.note??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush
