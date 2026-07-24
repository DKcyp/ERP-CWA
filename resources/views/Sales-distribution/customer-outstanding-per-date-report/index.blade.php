@extends('layouts.layout')
@section('title','Customer Outstanding per Date Report')
@push('after-style')<style>#table-data thead th{font-weight:600;white-space:nowrap}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>As of Date</label>
                <input type="date" class="form-control" id="filter-as-of" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari customer ID atau nama...">
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Customer ID</th><th>Customer Name</th><th class="text-end">Current</th><th class="text-end">1-30 Days</th><th class="text-end">31-60 Days</th><th class="text-end">61-90 Days</th><th class="text-end">&gt;90 Days</th><th class="text-end">Total Outstanding</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Aging AR</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">As of Date</small><span id="d-asof" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer ID</small><span id="d-cust-id" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer Name</small><span id="d-cust-name" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Current</small><span id="d-current" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">1-30 Days</small><span id="d-1-30" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">31-60 Days</small><span id="d-31-60" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">61-90 Days</small><span id="d-61-90" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">&gt;90 Days</small><span id="d-90-plus" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Outstanding</small><span id="d-total" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('customer-outstanding-per-date-report.table')}}",showUrl="{{route('customer-outstanding-per-date-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'customer_name',name:'customer_name'},{data:'current_fmt',name:'current',className:'text-end'},{data:'days_1_30_fmt',name:'days_1_30',className:'text-end'},{data:'days_31_60_fmt',name:'days_31_60',className:'text-end'},{data:'days_61_90_fmt',name:'days_61_90',className:'text-end'},{data:'days_90_plus_fmt',name:'days_90_plus',className:'text-end'},{data:'total_outstanding_fmt',name:'total_outstanding',className:'text-end'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-as-of').val(new Date().toISOString().split('T')[0]);tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-asof').text($('#filter-as-of').val()||'-');$('#d-cust-id').text(d.customer_id??'-');$('#d-cust-name').text(d.customer_name??'-');$('#d-current').text('Rp '+Number(d.current||0).toLocaleString('id-ID'));$('#d-1-30').text('Rp '+Number(d.days_1_30||0).toLocaleString('id-ID'));$('#d-31-60').text('Rp '+Number(d.days_31_60||0).toLocaleString('id-ID'));$('#d-61-90').text('Rp '+Number(d.days_61_90||0).toLocaleString('id-ID'));$('#d-90-plus').text('Rp '+Number(d.days_90_plus||0).toLocaleString('id-ID'));$('#d-total').text('Rp '+Number(d.total_outstanding||0).toLocaleString('id-ID'));$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush