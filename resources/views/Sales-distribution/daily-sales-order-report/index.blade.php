@extends('layouts.layout')
@section('title','Daily Sales Order Report')
@push('after-style')
<style>
    #table-data thead th { font-weight: 600; }
    .badge { font-size: .75rem; }
    #summary-cards .card { border-radius: .5rem; }
    #summary-cards .card-body { padding: 1rem .75rem; }
</style>
@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari Tanggal</label>
                <input type="date" class="form-control" id="filter-start-date">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai Tanggal</label>
                <input type="date" class="form-control" id="filter-end-date">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari SO, customer, atau sales...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="APPROVED">Approved</option><option value="COMPLETED">Completed</option></select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="button" class="btn btn-primary flex-fill" id="btn-filter"><i class="bi bi-search me-1"></i>Cari</button>
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
    </div></div>

    <div class="row g-3 mb-4" id="summary-cards">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total SO</div>
                <div class="fs-4 fw-bold text-primary" id="summary-total-so">0</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total Amount</div>
                <div class="fs-4 fw-bold text-info" id="summary-total-amount">0</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Status</div>
                <div class="d-flex justify-content-center gap-2 small flex-wrap" id="summary-status"><span>-</span></div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th class="text-center">Date</th><th>SO No</th><th>Customer Name</th><th>Salesman</th><th class="text-end">Total Amount</th><th class="text-center">Status</th><th>Warehouse</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail SO</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="detail-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">SO No</small><span id="detail-so" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="detail-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Salesman</small><span id="detail-salesman" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Amount</small><span id="detail-amount" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Warehouse</small><span id="detail-wh" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Status</small><span id="detail-status">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-sales-order-report.table')}}",summaryUrl="{{route('daily-sales-order-report.summary')}}",showUrl="{{route('daily-sales-order-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
function getFilterData(){return{start_date:$('#filter-start-date').val(),end_date:$('#filter-end-date').val(),filter_search:$('#filter-search').val(),filter_status:$('#filter-status').val()}}
function loadSummary(){$.get(summaryUrl,getFilterData()).done(function(r){if(!r.success)return;$('#summary-total-so').text(r.total_so||0);$('#summary-total-amount').text('Rp '+Number(r.total_amount||0).toLocaleString('id-ID'));const lb={DRAFT:'<span class="badge bg-secondary">Draft: 0</span>',APPROVED:'<span class="badge bg-info text-dark">Approved: 0</span>',COMPLETED:'<span class="badge bg-success">Completed: 0</span>'};const c=r.status_counts||{};let h='';Object.keys(lb).forEach(function(s){h+=lb[s].replace(': 0',': '+(c[s]||0))+' '});$('#summary-status').html(h||'-')})}
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){$.extend(d,getFilterData())}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'so_no',name:'so_no'},{data:'customer_name',name:'customer_name'},{data:'salesman',name:'salesman'},{data:'total_fmt',name:'total_amount',className:'text-end'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
tbl.on('draw',function(){loadSummary()});
$('#btn-filter').on('click',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-start-date').val('');$('#filter-end-date').val('');$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-date').text(d.date??'-');$('#detail-so').text(d.so_no??'-');$('#detail-customer').text(d.customer_name??'-');$('#detail-salesman').text(d.salesman??'-');$('#detail-amount').text('Rp '+Number(d.total_amount||0).toLocaleString('id-ID'));$('#detail-wh').text(d.warehouse??'-');const sm={DRAFT:'<span class="badge bg-secondary">Draft</span>',APPROVED:'<span class="badge bg-info text-dark">Approved</span>',COMPLETED:'<span class="badge bg-success">Completed</span>'};$('#detail-status').html(sm[d.status]??d.status);$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
loadSummary();
</script>
@endpush
