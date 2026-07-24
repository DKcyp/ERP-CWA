@extends('layouts.layout')
@section('title','Daily Sales Return Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Tanggal Awal</label>
                <input type="date" class="form-control" id="filter-date-start">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Tanggal Akhir</label>
                <input type="date" class="form-control" id="filter-date-end">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Return no, customer, atau produk...">
            </div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th class="text-center">Date</th><th>Return No</th><th>Customer Name</th><th>Product ID</th><th class="text-center">Qty Returned</th><th class="text-end">Total Amount</th><th>Reason</th><th>Warehouse ID</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Return</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="d-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Return No</small><span id="d-return" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer Name</small><span id="d-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Product ID</small><span id="d-product" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Qty Returned</small><span id="d-qty" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Amount</small><span id="d-total" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Reason</small><span id="d-reason" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Warehouse ID</small><span id="d-wh" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-sales-return-report.table')}}",showUrl="{{route('daily-sales-return-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_date_start=$('#filter-date-start').val();d.filter_date_end=$('#filter-date-end').val();d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'return_no',name:'return_no'},{data:'customer_name',name:'customer_name'},{data:'product_id',name:'product_id'},{data:'qty_returned',name:'qty_returned',className:'text-center'},{data:'total_amount_fmt',name:'total_amount',className:'text-end'},{data:'reason',name:'reason',className:'text-truncate'},{data:'warehouse_id',name:'warehouse_id'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-date-start,#filter-date-end,#filter-search').on('keyup change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-date-start').val('');$('#filter-date-end').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-date').text(d.date??'-');$('#d-return').text(d.return_no??'-');$('#d-customer').text(d.customer_name??'-');$('#d-product').text(d.product_id??'-');$('#d-qty').text(d.qty_returned??'0');$('#d-total').text('Rp '+Number(d.total_amount||0).toLocaleString('id-ID'));$('#d-reason').text(d.reason??'-');$('#d-wh').text(d.warehouse_id??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush