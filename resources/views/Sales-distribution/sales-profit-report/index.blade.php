@extends('layouts.layout')
@section('title','Sales Profit Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari invoice, customer, atau produk...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th style="width:40px;" class="text-center">No</th><th>Invoice No</th><th class="text-center">Date</th><th>Customer Name</th><th>Product ID</th><th class="text-center">Qty</th><th class="text-end">Selling Price</th><th class="text-end">HPP/Cost</th><th class="text-end">Gross Profit</th><th class="text-center">Margin</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Profit</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Invoice No</small><span id="detail-invoice" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="detail-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="detail-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Product</small><span id="detail-product" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Qty</small><span id="detail-qty" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Selling Price</small><span id="detail-price" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">HPP/Cost</small><span id="detail-hpp" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Gross Profit</small><span id="detail-profit" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Profit Margin</small><span id="detail-margin" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-profit-report.table')}}",showUrl="{{route('sales-profit-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'invoice_no',name:'invoice_no'},{data:'date_fmt',name:'date',className:'text-center'},{data:'customer_name',name:'customer_name'},{data:'product_id',name:'product_id'},{data:'qty',name:'qty',className:'text-center'},{data:'selling_price_fmt',name:'selling_price',className:'text-end'},{data:'hpp_cost_fmt',name:'hpp_cost',className:'text-end'},{data:'gross_profit_fmt',name:'gross_profit',className:'text-end'},{data:'profit_margin_fmt',name:'profit_margin',className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-invoice').text(d.invoice_no??'-');$('#detail-date').text(d.date??'-');$('#detail-customer').text(d.customer_name??'-');$('#detail-product').text(d.product_id??'-');$('#detail-qty').text(d.qty??'0');$('#detail-price').text('Rp '+Number(d.selling_price||0).toLocaleString('id-ID'));$('#detail-hpp').text('Rp '+Number(d.hpp_cost||0).toLocaleString('id-ID'));$('#detail-profit').text('Rp '+Number(d.gross_profit||0).toLocaleString('id-ID'));$('#detail-margin').text((d.profit_margin||'0')+'%');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush