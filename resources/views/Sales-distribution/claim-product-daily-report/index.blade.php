@extends('layouts.layout')
@section('title','Claim Product Daily Report')
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
                <input type="text" class="form-control" id="filter-search" placeholder="Cari doc no, customer, produk, atau user...">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="button" class="btn btn-primary flex-fill" id="btn-filter"><i class="bi bi-search me-1"></i>Cari</button>
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
    </div></div>

    <div class="row g-3 mb-4" id="summary-cards">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total Claims</div>
                <div class="fs-4 fw-bold text-primary" id="summary-total-claims">0</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total Qty Claimed</div>
                <div class="fs-4 fw-bold text-info" id="summary-total-qty">0</div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
                <div class="text-muted small mb-1">Total Points Deducted</div>
                <div class="fs-4 fw-bold text-warning" id="summary-total-points">0</div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th class="text-center">Date</th><th>Claim Doc No</th><th>Customer ID</th><th>Customer Name</th><th>Product ID</th><th class="text-center">Qty Claimed</th><th class="text-end">Total Points Deducted</th><th>User</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Claim - <span id="detail-doc-no"></span></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4"><small class="text-muted d-block">Tanggal</small><span id="detail-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer ID</small><span id="detail-customer-id" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer Name</small><span id="detail-customer-name" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Product ID</small><span id="detail-product-id" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Qty Claimed</small><span id="detail-qty" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Points Deducted</small><span id="detail-points" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">User</small><span id="detail-user" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('claim-product-daily-report.table')}}",summaryUrl="{{route('claim-product-daily-report.summary')}}",showUrl="{{route('claim-product-daily-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
function getFilterData(){return{start_date:$('#filter-start-date').val(),end_date:$('#filter-end-date').val(),filter_search:$('#filter-search').val()}}
function loadSummary(){$.get(summaryUrl,getFilterData()).done(function(r){if(!r.success)return;$('#summary-total-claims').text(r.total_claims||0);$('#summary-total-qty').text(r.total_qty||0);$('#summary-total-points').text(r.total_points||0)})}
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){$.extend(d,getFilterData())}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'claim_doc_no',name:'claim_doc_no'},{data:'customer_id',name:'customer_id'},{data:'customer_name',name:'customer_name'},{data:'product_id',name:'product_id'},{data:'qty_fmt',name:'qty_claimed',className:'text-center'},{data:'points_fmt',name:'total_points_deducted',className:'text-end'},{data:'user',name:'user'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
tbl.on('draw',function(){loadSummary()});
$('#btn-filter').on('click',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-start-date').val('');$('#filter-end-date').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-doc-no').text(d.claim_doc_no??'-');$('#detail-date').text(d.date??'-');$('#detail-customer-id').text(d.customer_id??'-');$('#detail-customer-name').text(d.customer_name??'-');$('#detail-product-id').text(d.product_id??'-');$('#detail-qty').text(d.qty_claimed??'0');$('#detail-points').text(d.total_points_deducted??'0');$('#detail-user').text(d.user??'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
loadSummary();
</script>
@endpush
