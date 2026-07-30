@extends('layouts.layout')
@section('title','Realisasi Jadwal Base per Mesin Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, Mesin, Produk..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">Shift 1</option><option value="Shift 2">Shift 2</option><option value="Shift 3">Shift 3</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Mesin</label><select class="form-select form-select-sm" id="filter-machine"><option value="all">Semua</option><option value="M-01">M-01</option><option value="M-02">M-02</option><option value="M-03">M-03</option><option value="M-04">M-04</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water</option><option value="Solvent Based">Solvent</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Operator</label><select class="form-select form-select-sm" id="filter-operator"><option value="all">Semua</option></select></div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('realisasi-jadwal-base-per-mesin-report.export')}}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-clock-history fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Waktu Proses</p><h4 class="fw-bold mb-0" id="sum-time">0j 0m</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-box-seam fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Realisasi (KG)</p><h4 class="fw-bold mb-0" id="sum-kg">0</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-graph-up-arrow fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Effisiensi Basis→Realisasi</p><h4 class="fw-bold mb-0" id="sum-eff">0%</h4></div></div>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-warning bg-opacity-10 text-warning rounded-circle"><i class="bi bi-gear-wide-connected fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Basis (KG)</p><h4 class="fw-bold mb-0" id="sum-basis">0</h4></div></div>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Prod Date</th><th>Shift</th><th>Mesin</th><th>Type</th><th>Product</th><th>Batch No</th><th class="text-end">Basis (KG)</th><th class="text-end">Realisasi (KG)</th><th class="text-center">Rincian Durasi (mnt)</th><th class="text-center">Total Durasi</th><th>Operator</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

$.get('{{route("realisasi-jadwal-base-per-mesin-report.table")}}',{filter_search:'',filter_date_from:'',filter_date_to:'',filter_shift:'all',filter_machine:'all',filter_type:'all',filter_operator:'all',draw:1,start:0,length:100},function(init){
    const ops=[...new Set((init.data||[]).map(r=>r.operator))].sort();
    const sel=$('#filter-operator');ops.forEach(o=>sel.append('<option value="'+o+'">'+o+'</option>'));
});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-base-per-mesin-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_machine=$('#filter-machine').val();d.filter_type=$('#filter-type').val();d.filter_operator=$('#filter-operator').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-time').text((s.total_jam||0)+'j '+(s.total_menit||0)+'m');$('#sum-kg').text((s.total_realisasi||0).toLocaleString('id-ID'));$('#sum-eff').text((s.effisiensi||0)+'%');$('#sum-basis').text((s.total_basis||0).toLocaleString('id-ID'));return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},
{data:'prod_date_fmt',name:'prod_date',className:'text-center'},
{data:'shift',name:'shift',className:'text-center'},
{data:'machine',name:'machine',className:'text-center'},
{data:'type',name:'type'},
{data:'nama_product',name:'nama_product'},
{data:'batch_no',name:'batch_no'},
{data:'basis_fmt',name:'total_basis_kg',orderable:false,searchable:false,className:'text-end'},
{data:'realisasi_fmt',name:'realisasi_kg',orderable:false,searchable:false,className:'text-end'},
{data:'durasi_detail',name:'duration_pengisian_air',orderable:false,searchable:false,className:'text-center'},
{data:'durasi_total_fmt',name:'duration_total_process',orderable:false,searchable:false,className:'text-center'},
{data:'operator',name:'operator'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-machine').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});$('#filter-operator').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-machine').val('all');$('#filter-type').val('all');$('#filter-operator').val('all');tbl.ajax.reload()});
</script>
@endpush