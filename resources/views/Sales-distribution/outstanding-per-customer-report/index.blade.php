@extends('layouts.layout')
@section('title','Outstanding per Customer Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari customer ID atau nama...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Customer ID</th><th>Customer Name</th><th class="text-center">Total Invoices</th><th class="text-end">Total Outstanding</th><th class="text-end">Credit Limit</th><th class="text-end">Exceeded Amount</th><th class="text-center">Status</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Outstanding Customer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Customer ID</small><span id="d-cust-id" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer Name</small><span id="d-cust-name" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Invoices</small><span id="d-inv" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Outstanding</small><span id="d-out" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Credit Limit</small><span id="d-limit" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Exceeded Amount</small><span id="d-exceed" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('outstanding-per-customer-report.table')}}",showUrl="{{route('outstanding-per-customer-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'customer_name',name:'customer_name'},{data:'total_invoices',name:'total_invoices',className:'text-center'},{data:'total_outstanding_fmt',name:'total_outstanding',className:'text-end'},{data:'credit_limit_fmt',name:'credit_limit',className:'text-end'},{data:'exceeded_fmt',name:'exceeded_amount',className:'text-end'},{data:'exceeded_badge',name:'exceeded_amount',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-cust-id').text(d.customer_id??'-');$('#d-cust-name').text(d.customer_name??'-');$('#d-inv').text(d.total_invoices??'0');$('#d-out').text('Rp '+Number(d.total_outstanding||0).toLocaleString('id-ID'));$('#d-limit').text('Rp '+Number(d.credit_limit||0).toLocaleString('id-ID'));$('#d-exceed').text('Rp '+Number(d.exceeded_amount||0).toLocaleString('id-ID'));$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush