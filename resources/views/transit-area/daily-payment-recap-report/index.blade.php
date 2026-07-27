@extends('layouts.layout')
@section('title','Daily Payment Recap Report')
@push('after-style')<style>#table-data thead th{font-weight:600;white-space:nowrap;font-size:.72rem}.badge{font-size:.75rem}</style>@endpush
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
                <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="TTP, customer, invoice, payment...">
            </div>
            <div class="col-md-3 d-flex gap-1 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:25px;">No</th><th>No TTP</th><th class="text-center">Date</th><th>Kode Area</th><th>Area</th><th>Cust ID</th><th>Name</th><th>SI No</th><th class="text-end">Bank</th><th class="text-end">Cash</th><th class="text-end">Discount</th><th class="text-end">Lain-Lain</th><th class="text-end">Retur</th><th class="text-end">Total Bank In</th><th class="text-end">Outstanding</th><th>Note</th><th class="text-center">Tgl TTP</th><th>Payment ID</th><th class="text-center">Due Date</th><th class="text-end">Invoice Total</th><th>Term</th><th class="text-center">Diskon Promo</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Payment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-2 small">
                <div class="col-md-3"><small class="text-muted d-block">No TTP</small><span id="d-ttp" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Date</small><span id="d-date" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Kode Area</small><span id="d-kode" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Area</small><span id="d-area" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Customer</small><span id="d-cust" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">SI No</small><span id="d-si" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Bank</small><span id="d-bank" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Cash</small><span id="d-cash" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Discount</small><span id="d-discount" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Lain-Lain</small><span id="d-lain" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Retur</small><span id="d-retur" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Total Bank In</small><span id="d-tbi" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Outstanding</small><span id="d-out" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Tgl TTP</small><span id="d-tgl" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Payment ID</small><span id="d-pay" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Due Date</small><span id="d-due" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Invoice Total</small><span id="d-inv-total" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Term</small><span id="d-term" class="fw-semibold">-</span></div>
                <div class="col-md-3"><small class="text-muted d-block">Diskon Promo</small><span id="d-promo" class="fw-semibold">-</span></div>
                <div class="col-md-12"><small class="text-muted d-block">Note</small><span id="d-note" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-payment-recap-report.table')}}",showUrl="{{route('daily-payment-recap-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,scrollCollapse:true,ajax:{url:tableUrl,data:function(d){d.start_date=$('#start-date').val();d.end_date=$('#end-date').val();d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'no_ttp',name:'no_ttp'},{data:'date_fmt',name:'date',className:'text-center'},{data:'kode_area',name:'kode_area'},{data:'area',name:'area'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'sales_invoice',name:'sales_invoice'},{data:'bank_fmt',name:'bank',className:'text-end'},{data:'cash_fmt',name:'cash',className:'text-end'},{data:'discount_fmt',name:'discount',className:'text-end'},{data:'lain_lain_fmt',name:'lain_lain',className:'text-end'},{data:'retur_fmt',name:'retur',className:'text-end'},{data:'total_bank_in_fmt',name:'total_bank_in',className:'text-end'},{data:'outstanding_fmt',name:'outstanding',className:'text-end'},{data:'note',name:'note',className:'text-truncate',render:function(d){return d||'-'}},{data:'tgl_ttp_fmt',name:'tgl_ttp',className:'text-center'},{data:'payment_id',name:'payment_id'},{data:'due_date_fmt',name:'due_date',className:'text-center'},{data:'invoice_total_fmt',name:'invoice_total',className:'text-end'},{data:'term',name:'term'},{data:'diskon_promo_fmt',name:'diskon_promo',className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#start-date,#end-date,#filter-search').on('keyup change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#start-date').val('');$('#end-date').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-ttp').text(d.no_ttp??'-');$('#d-date').text(d.date??'-');$('#d-kode').text(d.kode_area??'-');$('#d-area').text(d.area??'-');$('#d-cust').text((d.customer_id??'')+' - '+(d.name??''));$('#d-si').text(d.sales_invoice??'-');$('#d-bank').text('Rp '+Number(d.bank||0).toLocaleString('id-ID'));$('#d-cash').text('Rp '+Number(d.cash||0).toLocaleString('id-ID'));$('#d-discount').text('Rp '+Number(d.discount||0).toLocaleString('id-ID'));$('#d-lain').text('Rp '+Number(d.lain_lain||0).toLocaleString('id-ID'));$('#d-retur').text('Rp '+Number(d.retur||0).toLocaleString('id-ID'));$('#d-tbi').text('Rp '+Number(d.total_bank_in||0).toLocaleString('id-ID'));$('#d-out').text('Rp '+Number(d.outstanding||0).toLocaleString('id-ID'));$('#d-tgl').text(d.tgl_ttp??'-');$('#d-pay').text(d.payment_id??'-');$('#d-due').text(d.due_date??'-');$('#d-inv-total').text('Rp '+Number(d.invoice_total||0).toLocaleString('id-ID'));$('#d-term').text(d.term??'-');$('#d-promo').text((d.diskon_promo||0)+'%');$('#d-note').text(d.note??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush