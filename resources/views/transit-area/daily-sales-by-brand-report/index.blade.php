@extends('layouts.layout')
@section('title','Daily Sales by Brand Report')
@push('after-style')<style>#table-data thead th{font-weight:600;white-space:nowrap}.badge{font-size:.75rem}</style>@endpush
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
                <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Brand, warehouse, area...">
            </div>
            <div class="col-md-3 d-flex gap-1 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:30px;">No</th><th class="text-center">Date</th><th>Warehouse</th><th>Area</th><th>Brand ID</th><th>Brand Name</th><th class="text-center">Total Qty</th><th class="text-end">Gross Amount</th><th class="text-end">Discount</th><th class="text-end">Net Sales</th><th class="text-center">Contribution</th><th class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Brand</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-2 small">
                <div class="col-md-4"><small class="text-muted d-block">Date</small><span id="d-date" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Warehouse</small><span id="d-wh" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Area</small><span id="d-area" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Brand ID</small><span id="d-brand-id" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Brand Name</small><span id="d-brand-name" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Qty</small><span id="d-qty" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Gross Amount</small><span id="d-gross" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Discount</small><span id="d-discount" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Net Sales</small><span id="d-net" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Contribution</small><span id="d-pct" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('daily-sales-by-brand-report.table')}}",showUrl="{{route('daily-sales-by-brand-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.start_date=$('#start-date').val();d.end_date=$('#end-date').val();d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'area',name:'area'},{data:'brand_id',name:'brand_id'},{data:'brand_name',name:'brand_name'},{data:'total_qty_fmt',name:'total_qty_sold',className:'text-center'},{data:'gross_fmt',name:'gross_amount',className:'text-end'},{data:'discount_fmt',name:'discount_amount',className:'text-end'},{data:'net_fmt',name:'net_sales_amount',className:'text-end'},{data:'pct_fmt',name:'contribution',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#start-date,#end-date,#filter-search').on('keyup change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#start-date').val('');$('#end-date').val('');$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#d-date').text(d.date??'-');$('#d-wh').text(d.warehouse??'-');$('#d-area').text(d.area??'-');$('#d-brand-id').text(d.brand_id??'-');$('#d-brand-name').text(d.brand_name??'-');$('#d-qty').text(Number(d.total_qty_sold||0).toLocaleString('id-ID'));$('#d-gross').text('Rp '+Number(d.gross_amount||0).toLocaleString('id-ID'));$('#d-discount').text('Rp '+Number(d.discount_amount||0).toLocaleString('id-ID'));$('#d-net').text('Rp '+Number(d.net_sales_amount||0).toLocaleString('id-ID'));$('#d-pct').text(Number(d.net_sales_amount||0)>0?'...%':'-');$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush