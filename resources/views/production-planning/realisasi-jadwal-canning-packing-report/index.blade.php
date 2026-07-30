@extends('layouts.layout')
@section('title','Realisasi Jadwal Canning & Packing Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, Kode Warna..."></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">S1</option><option value="Shift 2">S2</option><option value="Shift 3">S3</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water</option><option value="Solvent Based">Solvent</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Jadwal</label><select class="form-select form-select-sm" id="filter-category"><option value="all">Semua</option><option value="Pusat">Pusat</option><option value="Cabang">Cabang</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Operator</label><select class="form-select form-select-sm" id="filter-operator"><option value="all">Semua</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('realisasi-jadwal-canning-packing-report.export')}}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Basis</p><h5 class="fw-bold mb-0 text-primary" id="sum-basis">0</h5><small class="text-muted">KG</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Canning</p><h5 class="fw-bold mb-0 text-info" id="sum-canning">0</h5><small class="text-muted">KG</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Kemasan</p><h5 class="fw-bold mb-0 text-warning" id="sum-pcs">0</h5><small class="text-muted">PCS</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Avg Yield</p><h5 class="fw-bold mb-0 text-success" id="sum-yield">0%</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Selisih</p><h5 class="fw-bold mb-0" id="sum-sel">0</h5><small class="text-muted">KG</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Batch</p><h5 class="fw-bold mb-0" id="sum-count">0</h5>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Prod Date</th><th>Shift</th><th>Type</th><th>Jadwal</th><th>Kode Warna</th><th>Warna</th><th>Batch No</th><th class="text-end">Basis</th><th class="text-end">CM</th><th class="text-center">Kemasan</th><th class="text-end">Canning</th><th class="text-center">Yield</th><th class="text-end">Berat Awal</th><th class="text-end">Berat Akhir</th><th class="text-center">Selisih</th><th>Op. Canning</th><th>Op. Packing</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

$.get('{{route("realisasi-jadwal-canning-packing-report.table")}}',{filter_search:'',filter_date_from:'',filter_date_to:'',filter_shift:'all',filter_type:'all',filter_category:'all',filter_operator:'all',draw:1,start:0,length:100},function(init){
    const ops=[...new Set((init.data||[]).flatMap(r=>[r.operator_canning,r.operator_packing]))].sort();
    ops.forEach(o=>$('#filter-operator').append('<option value="'+o+'">'+o+'</option>'));
});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-canning-packing-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_type=$('#filter-type').val();d.filter_category=$('#filter-category').val();d.filter_operator=$('#filter-operator').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-basis').text((s.total_basis||0).toLocaleString('id-ID'));$('#sum-canning').text((s.total_canning||0).toLocaleString('id-ID'));$('#sum-pcs').text((s.total_pcs||0).toLocaleString('id-ID'));$('#sum-yield').text((s.avg_yield||0)+'%');const v=s.total_selisih||0;$('#sum-sel').text((v>0?'+':'')+v.toLocaleString('id-ID')).removeClass('text-danger text-success').addClass(v>0?'text-danger':v<0?'text-success':'');$('#sum-count').text(json.recordsTotal||0);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'prod_date_fmt',name:'prod_date',className:'text-center'},
{data:'shift',name:'shift',className:'text-center'},{data:'type',name:'type'},
{data:'schedule_category',name:'schedule_category'},{data:'kode_warna',name:'kode_warna'},
{data:'warna',name:'warna'},{data:'batch_no',name:'batch_no'},
{data:'basis_fmt',name:'basis_kg',orderable:false,searchable:false,className:'text-end'},
{data:'cm_fmt',name:'realisasi_cm_kg',orderable:false,searchable:false,className:'text-end'},
{data:'detail_kemasan_summary',name:'detail_kemasan_summary',orderable:false,searchable:false,className:'text-center'},
{data:'canning_fmt',name:'realisasi_canning_kg',orderable:false,searchable:false,className:'text-end'},
{data:'yield_badge',name:'yield_percent',orderable:false,searchable:false,className:'text-center'},
{data:'ba_fmt',name:'berat_awal',orderable:false,searchable:false,className:'text-end'},
{data:'bi_fmt',name:'berat_akhir',orderable:false,searchable:false,className:'text-end'},
{data:'selisih_fmt',name:'selisih_kg',orderable:false,searchable:false,className:'text-center'},
{data:'operator_canning',name:'operator_canning'},
{data:'operator_packing',name:'operator_packing'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});$('#filter-category').on('change',function(){tbl.ajax.reload()});$('#filter-operator').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-type').val('all');$('#filter-category').val('all');$('#filter-operator').val('all');tbl.ajax.reload()});
</script>
@endpush