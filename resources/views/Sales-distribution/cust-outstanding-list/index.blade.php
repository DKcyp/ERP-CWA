@extends('layouts.layout')
@section('title','Cust. Outstanding List')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari invoice, customer ID, atau nama...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Invoice No</th><th>Customer ID</th><th>Customer Name</th><th>City</th><th class="text-center">Date</th><th class="text-center">Due Date</th><th class="text-center">Age</th><th>Curr</th><th class="text-end">Total</th><th class="text-end">Outstanding</th><th>Term</th><th>Invoiced</th><th>Warehouse</th><th>Sales</th><th>Note</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Outstanding</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Invoice No</small><span id="d-invoice" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="d-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">City</small><span id="d-city" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="d-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Due Date</small><span id="d-due" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Age</small><span id="d-age" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Curr</small><span id="d-curr" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total</small><span id="d-total" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Outstanding</small><span id="d-out" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Term</small><span id="d-term" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Invoiced</small><span id="d-inv" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Warehouse</small><span id="d-wh" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Sales</small><span id="d-sales" class="fw-semibold">-</span></div>
                <div class="col-md-12"><small class="text-muted d-block">Note</small><span id="d-note" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('cust-outstanding-list.table')}}",showUrl="{{route('cust-outstanding-list.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'invoice_no',name:'invoice_no'},{data:'customer_id',name:'customer_id'},{data:'customer_name',name:'customer_name'},{data:'city',name:'city'},{data:'date_fmt',name:'date',className:'text-center'},{data:'due_date_fmt',name:'due_date',className:'text-center'},{data:'age_badge',name:'age_days',orderable:false,searchable:false,className:'text-center'},{data:'curr',name:'curr'},{data:'total_fmt',name:'total',className:'text-end'},{data:'outstanding_fmt',name:'outstanding',className:'text-end'},{data:'term',name:'term'},{data:'invoiced',name:'invoiced'},{data:'warehouse',name:'warehouse'},{data:'sales',name:'sales'},{data:'note',name:'note',className:'text-truncate',render:function(d){return d||'-'}},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-invoice').text(d.invoice_no??'-');$('#d-customer').text(d.customer_id+' - '+d.customer_name);$('#d-city').text(d.city??'-');$('#d-date').text(d.date??'-');$('#d-due').text(d.due_date??'-');$('#d-age').text((d.age_days??0)+' hari');$('#d-curr').text(d.curr??'-');$('#d-total').text('Rp '+Number(d.total||0).toLocaleString('id-ID'));$('#d-out').text('Rp '+Number(d.outstanding||0).toLocaleString('id-ID'));$('#d-term').text(d.term??'-');$('#d-inv').text(d.invoiced??'-');$('#d-wh').text(d.warehouse??'-');$('#d-sales').text(d.sales??'-');$('#d-note').text(d.note??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush