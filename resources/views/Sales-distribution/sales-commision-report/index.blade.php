@extends('layouts.layout')
@section('title','Sales Commission Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari salesman ID, nama, atau periode...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Salesman ID</th><th>Salesman Name</th><th>Period</th><th class="text-end">Total Omset</th><th class="text-end">Target</th><th class="text-center">Commission Rate</th><th class="text-end">Total Commission</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Komisi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-4"><small class="text-muted d-block">Salesman ID</small><span id="detail-salesman-id" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Salesman Name</small><span id="detail-salesman-name" class="fw-semibold">-</span></div>
                <div class="col-md-4"><small class="text-muted d-block">Period</small><span id="detail-period" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Total Omset</small><span id="detail-omset" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Target</small><span id="detail-target" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Commission Rate</small><span id="detail-rate" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Total Commission</small><span id="detail-commission" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-commision-report.table')}}",showUrl="{{route('sales-commision-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'salesman_id',name:'salesman_id'},{data:'salesman_name',name:'salesman_name'},{data:'period',name:'period'},{data:'total_omset_fmt',name:'total_omset',className:'text-end'},{data:'target_fmt',name:'target',className:'text-end'},{data:'commission_rate_fmt',name:'commission_rate',className:'text-center'},{data:'total_commission_fmt',name:'total_commission',className:'text-end'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-salesman-id').text(d.salesman_id??'-');$('#detail-salesman-name').text(d.salesman_name??'-');$('#detail-period').text(d.period??'-');$('#detail-omset').text('Rp '+Number(d.total_omset||0).toLocaleString('id-ID'));$('#detail-target').text('Rp '+Number(d.target||0).toLocaleString('id-ID'));$('#detail-rate').text((d.commission_rate||0)+'%');$('#detail-commission').text('Rp '+Number(d.total_commission||0).toLocaleString('id-ID'));$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush