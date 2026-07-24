@extends('layouts.layout')
@section('title','Invoice Payment Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari invoice atau customer...">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-funnel me-1"></i>Status</label>
                <select class="form-select" id="filter-status"><option value="">Semua Status</option><option value="LUNAS">Lunas</option><option value="BELUM LUNAS">Belum Lunas</option><option value="OVERDUE">Overdue</option></select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Invoice No</th><th>Invoice Date</th><th>Customer Name</th><th class="text-end">Total Invoice</th><th class="text-end">Total Paid</th><th class="text-end">Balance Due</th><th>Last Payment Date</th><th class="text-center">Status</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Pembayaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Invoice No</small><span id="detail-invoice" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Invoice Date</small><span id="detail-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="detail-customer" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Total Invoice</small><span id="detail-total" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Total Paid</small><span id="detail-paid" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Balance Due</small><span id="detail-balance" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Last Payment Date</small><span id="detail-last-pay" class="fw-semibold">-</span></div>
                <div class="col-md-12"><small class="text-muted d-block">Status</small><span id="detail-status" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('invoice-payment-report.table')}}",showUrl="{{route('invoice-payment-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'invoice_no',name:'invoice_no'},{data:'invoice_date',name:'invoice_date'},{data:'customer_name',name:'customer_name'},{data:'total_invoice_fmt',name:'total_invoice',className:'text-end'},{data:'total_paid_fmt',name:'total_paid',className:'text-end'},{data:'balance_due_fmt',name:'balance_due',className:'text-end'},{data:'last_payment_date',name:'last_payment_date'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-invoice').text(d.invoice_no??'-');$('#detail-date').text(d.invoice_date??'-');$('#detail-customer').text(d.customer_name??'-');$('#detail-total').text('Rp '+Number(d.total_invoice||0).toLocaleString('id-ID'));$('#detail-paid').text('Rp '+Number(d.total_paid||0).toLocaleString('id-ID'));$('#detail-balance').text('Rp '+Number(d.balance_due||0).toLocaleString('id-ID'));$('#detail-last-pay').text(d.last_payment_date??'-');$('#detail-status').text(d.status??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush