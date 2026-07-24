@extends('layouts.layout')
@section('title','Daily Customer Payment Report')
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
                <input type="text" class="form-control" id="filter-search" placeholder="Payment, customer, atau metode...">
            </div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th class="text-center">Date</th><th>Payment No</th><th>Customer Name</th><th>Payment Method</th><th class="text-end">Total Paid</th><th>Account Name</th><th>User</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Pembayaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="d-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Payment No</small><span id="d-payment" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer Name</small><span id="d-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Payment Method</small><span id="d-method" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Paid</small><span id="d-total" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Account Name</small><span id="d-account" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">User</small><span id="d-user" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-customer-payment-report.table')}}",showUrl="{{route('daily-customer-payment-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_date_start=$('#filter-date-start').val();d.filter_date_end=$('#filter-date-end').val();d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'payment_no',name:'payment_no'},{data:'customer_name',name:'customer_name'},{data:'payment_method',name:'payment_method'},{data:'total_paid_fmt',name:'total_paid',className:'text-end'},{data:'account_name',name:'account_name'},{data:'user',name:'user'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-date-start,#filter-date-end,#filter-search').on('keyup change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-date-start').val('');$('#filter-date-end').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-date').text(d.date??'-');$('#d-payment').text(d.payment_no??'-');$('#d-customer').text(d.customer_name??'-');$('#d-method').text(d.payment_method??'-');$('#d-total').text('Rp '+Number(d.total_paid||0).toLocaleString('id-ID'));$('#d-account').text(d.account_name??'-');$('#d-user').text(d.user??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush