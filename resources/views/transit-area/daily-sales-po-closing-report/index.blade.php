@extends('layouts.layout')
@section('title','Daily Sales PO Closing Report')
@push('after-style')<style>#table-data thead th{font-weight:600;white-space:nowrap;font-size:.75rem}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Tanggal Awal</label>
                <input type="date" class="form-control form-control-sm" id="start-date">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Tanggal Akhir</label>
                <input type="date" class="form-control form-control-sm" id="end-date">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Invoice, DO, customer, produk...">
            </div>
            <div class="col-md-3 d-flex gap-1 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:30px;">No</th><th class="text-center">Date</th><th>Warehouse</th><th>Cust ID</th><th>Name</th><th>Area</th><th>SI No</th><th>DO No</th><th>Prod ID</th><th>Prod Name</th><th>UOM</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-center">Disc %</th><th class="text-end">Disc Amt</th><th class="text-end">Total Pot</th><th class="text-end">Total</th><th class="text-end">DPP</th><th class="text-end">PPN</th><th class="text-end">Grand Total</th><th class="text-center">Due Date</th><th class="text-end">Tonase</th><th>Note</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail PO Closing</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-2 small">
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="d-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Warehouse</small><span id="d-wh" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="d-cust" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Area</small><span id="d-area" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">SI No</small><span id="d-si" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">DO No</small><span id="d-do" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Product</small><span id="d-prod" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">UOM</small><span id="d-uom" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Qty</small><span id="d-qty" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Price</small><span id="d-price" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Disc %</small><span id="d-disc-pct" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Disc Amt</small><span id="d-disc-amt" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Potongan</small><span id="d-pot" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total</small><span id="d-total" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">DPP</small><span id="d-dpp" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">PPN</small><span id="d-ppn" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Grand Total</small><span id="d-grand" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Due Date</small><span id="d-due" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Tonase</small><span id="d-tonase" class="fw-semibold">-</span></div>
                <div class="col-md-12"><small class="text-muted d-block">Note</small><span id="d-note" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-sales-po-closing-report.table')}}",showUrl="{{route('daily-sales-po-closing-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,scrollCollapse:true,ajax:{url:tableUrl,data:function(d){d.start_date=$('#start-date').val();d.end_date=$('#end-date').val();d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'area',name:'area'},{data:'sales_invoice',name:'sales_invoice'},{data:'delivery_order',name:'delivery_order'},{data:'product_id',name:'product_id'},{data:'product_name',name:'product_name'},{data:'uom',name:'uom'},{data:'qty_fmt',name:'qty',className:'text-center'},{data:'price_fmt',name:'price',className:'text-end'},{data:'disc_pct_fmt',name:'disc_pct',className:'text-center'},{data:'disc_amt_fmt',name:'disc_amount',className:'text-end'},{data:'total_potongan_fmt',name:'total_potongan',className:'text-end'},{data:'total_fmt',name:'total',className:'text-end'},{data:'dpp_fmt',name:'dpp',className:'text-end'},{data:'ppn_fmt',name:'ppn',className:'text-end'},{data:'grand_total_fmt',name:'grand_total',className:'text-end'},{data:'due_date_fmt',name:'due_date',className:'text-center'},{data:'tonase_fmt',name:'tonase',className:'text-end'},{data:'note',name:'note',className:'text-truncate',render:function(d){return d||'-'}},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#start-date,#end-date,#filter-search').on('keyup change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#start-date').val('');$('#end-date').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-date').text(d.date??'-');$('#d-wh').text(d.warehouse??'-');$('#d-cust').text((d.customer_id??'')+' - '+(d.name??''));$('#d-area').text(d.area??'-');$('#d-si').text(d.sales_invoice??'-');$('#d-do').text(d.delivery_order??'-');$('#d-prod').text((d.product_id??'')+' - '+(d.product_name??''));$('#d-uom').text(d.uom??'-');$('#d-qty').text(d.qty??'0');$('#d-price').text('Rp '+Number(d.price||0).toLocaleString('id-ID'));$('#d-disc-pct').text((d.disc_pct||0)+'%');$('#d-disc-amt').text('Rp '+Number(d.disc_amount||0).toLocaleString('id-ID'));$('#d-pot').text('Rp '+Number(d.total_potongan||0).toLocaleString('id-ID'));$('#d-total').text('Rp '+Number(d.total||0).toLocaleString('id-ID'));$('#d-dpp').text('Rp '+Number(d.dpp||0).toLocaleString('id-ID'));$('#d-ppn').text('Rp '+Number(d.ppn||0).toLocaleString('id-ID'));$('#d-grand').text('Rp '+Number(d.grand_total||0).toLocaleString('id-ID'));$('#d-due').text(d.due_date??'-');$('#d-tonase').text(Number(d.tonase||0).toLocaleString('id-ID')+' kg');$('#d-note').text(d.note??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush