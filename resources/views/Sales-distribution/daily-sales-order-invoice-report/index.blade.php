@extends('layouts.layout')
@section('title','Daily Sales Order Invoice Report')
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
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari SO, SI, atau customer...">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="button" class="btn btn-primary flex-fill" id="btn-filter"><i class="bi bi-search me-1"></i>Cari</button>
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
    </div></div>

    <div class="row g-3 mb-4" id="summary-cards">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total Records</div>
                <div class="fs-4 fw-bold text-primary" id="summary-total-records">0</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total SO Amount</div>
                <div class="fs-4 fw-bold text-info" id="summary-total-so">0</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total Inv Amount</div>
                <div class="fs-4 fw-bold text-warning" id="summary-total-inv">0</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Avg Fulfilment</div>
                <div class="fs-4 fw-bold text-success" id="summary-avg-fulfilment">0%</div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th class="text-center">Date</th><th>SO No</th><th>SI No</th><th>Customer Name</th><th class="text-end">SO Amount</th><th class="text-end">Invoiced Amount</th><th class="text-center">Fulfilment Rate</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail SO Invoice</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="detail-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">SO No</small><span id="detail-so" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">SI No</small><span id="detail-si" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer</small><span id="detail-customer" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">SO Amount</small><span id="detail-so-amt" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Invoiced Amount</small><span id="detail-inv-amt" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Fulfilment Rate</small><span id="detail-fulfilment" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-so-invoice-report.table')}}",summaryUrl="{{route('daily-so-invoice-report.summary')}}",showUrl="{{route('daily-so-invoice-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
function getFilterData(){return{start_date:$('#filter-start-date').val(),end_date:$('#filter-end-date').val(),filter_search:$('#filter-search').val()}}
function loadSummary(){$.get(summaryUrl,getFilterData()).done(function(r){if(!r.success)return;$('#summary-total-records').text(r.total_records||0);$('#summary-total-so').text('Rp '+Number(r.total_so_amount||0).toLocaleString('id-ID'));$('#summary-total-inv').text('Rp '+Number(r.total_inv_amount||0).toLocaleString('id-ID'));$('#summary-avg-fulfilment').text((r.avg_fulfilment||0)+'%')})}
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){$.extend(d,getFilterData())}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'so_no',name:'so_no'},{data:'si_no',name:'si_no'},{data:'customer_name',name:'customer_name'},{data:'so_amt_fmt',name:'so_amount',className:'text-end'},{data:'inv_amt_fmt',name:'invoiced_amount',className:'text-end'},{data:'fulfilment_pct',name:'fulfilment_pct',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
tbl.on('draw',function(){loadSummary()});
$('#btn-filter').on('click',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-start-date').val('');$('#filter-end-date').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-date').text(d.date??'-');$('#detail-so').text(d.so_no??'-');$('#detail-si').text(d.si_no??'-');$('#detail-customer').text(d.customer_name??'-');$('#detail-so-amt').text('Rp '+Number(d.so_amount||0).toLocaleString('id-ID'));$('#detail-inv-amt').text('Rp '+Number(d.invoiced_amount||0).toLocaleString('id-ID'));const pct=d.so_amount>0?((d.invoiced_amount/d.so_amount)*100).toFixed(1):0;$('#detail-fulfilment').html(pct>=100?'<span class="badge bg-success">'+pct+'%</span>':pct>=50?'<span class="badge bg-warning text-dark">'+pct+'%</span>':'<span class="badge bg-danger">'+pct+'%</span>');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
loadSummary();
</script>
@endpush
