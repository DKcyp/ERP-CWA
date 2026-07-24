@extends('layouts.layout')
@section('title','Sales Omset Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari salesman, area, atau group...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Period</th><th>Salesman</th><th>Area</th><th>Customer Group</th><th class="text-end">Gross Sales</th><th class="text-end">Total Discount</th><th class="text-end">Net Omset</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Omset</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Period</small><span id="detail-period" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Salesman</small><span id="detail-salesman" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Area</small><span id="detail-area" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Customer Group</small><span id="detail-group" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Gross Sales</small><span id="detail-gross" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Total Discount</small><span id="detail-discount" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Net Omset</small><span id="detail-net" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-omset-report.table')}}",showUrl="{{route('sales-omset-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'period',name:'period'},{data:'salesman',name:'salesman'},{data:'area',name:'area'},{data:'customer_group',name:'customer_group'},{data:'gross_fmt',name:'total_gross_sales',className:'text-end'},{data:'discount_fmt',name:'total_discount',className:'text-end'},{data:'net_fmt',name:'total_net_omset',className:'text-end'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-period').text(d.period??'-');$('#detail-salesman').text(d.salesman??'-');$('#detail-area').text(d.area??'-');$('#detail-group').text(d.customer_group??'-');$('#detail-gross').text('Rp '+Number(d.total_gross_sales||0).toLocaleString('id-ID'));$('#detail-discount').text('Rp '+Number(d.total_discount||0).toLocaleString('id-ID'));$('#detail-net').text('Rp '+Number(d.total_net_omset||0).toLocaleString('id-ID'));$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush