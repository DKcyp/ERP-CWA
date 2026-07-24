@extends('layouts.layout')
@section('title','Sales Void Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari doc no, customer, alasan, atau user...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th class="text-center">Void Date</th><th>Doc No</th><th>Customer Name</th><th class="text-end">Original Amount</th><th>Void Reason</th><th>Authorized User</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Void</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Void Date</small><span id="detail-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Doc No</small><span id="detail-doc" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="detail-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Original Amount</small><span id="detail-amount" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Void Reason</small><span id="detail-reason" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Authorized User</small><span id="detail-user" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-void-report.table')}}",showUrl="{{route('sales-void-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'void_date_fmt',name:'void_date',className:'text-center'},{data:'doc_no',name:'doc_no'},{data:'customer_name',name:'customer_name'},{data:'amount_fmt',name:'original_amount',className:'text-end'},{data:'void_reason',name:'void_reason'},{data:'authorized_user',name:'authorized_user'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-date').text(d.void_date??'-');$('#detail-doc').text(d.doc_no??'-');$('#detail-customer').text(d.customer_name??'-');$('#detail-amount').text('Rp '+Number(d.original_amount||0).toLocaleString('id-ID'));$('#detail-reason').text(d.void_reason??'-');$('#detail-user').text(d.authorized_user??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush