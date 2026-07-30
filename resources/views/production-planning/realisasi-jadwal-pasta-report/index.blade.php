@extends('layouts.layout')
@section('title','Realisasi Jadwal Pasta Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, Kode Pasta..."></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">S1</option><option value="Shift 2">S2</option><option value="Shift 3">S3</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water</option><option value="Solvent Based">Solvent</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Mesin</label><select class="form-select form-select-sm" id="filter-mesin"><option value="all">Semua</option><option value="P-01">P-01</option><option value="P-02">P-02</option><option value="P-03">P-03</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Operator</label><select class="form-select form-select-sm" id="filter-operator"><option value="all">Semua</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Status</label><select class="form-select form-select-sm" id="filter-status"><option value="all">Semua</option><option value="Tepat Waktu">Tepat</option><option value="Terlambat">Lambat</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('realisasi-jadwal-pasta-report.export')}}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Basis</p><h5 class="fw-bold mb-0 text-primary" id="sum-basis">0</h5><small class="text-muted">KG</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Realisasi</p><h5 class="fw-bold mb-0 text-info" id="sum-real">0</h5><small class="text-muted">KG</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Avg Lead Time</p><h5 class="fw-bold mb-0 text-warning" id="sum-lead">0</h5><small class="text-muted">hari</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Avg Wkt Tunggu</p><h5 class="fw-bold mb-0" id="sum-tunggu">0</h5><small class="text-muted">menit</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Tepat Waktu</p><h5 class="fw-bold mb-0 text-success" id="sum-ontime">0</h5><small class="text-muted" id="sum-total">/ 0</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Batch</p><h5 class="fw-bold mb-0" id="sum-count">0</h5>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Date</th><th>Shift</th><th>Type</th><th>Kode Pasta</th><th>Name</th><th>Batch</th><th>Mesin</th><th class="text-end">Basis</th><th class="text-end">Realisasi</th><th class="text-center">Selisih</th><th class="text-center">%</th><th class="text-center">Jam</th><th class="text-center">Tunggu</th><th>Operator</th><th>Tgl Jadwal</th><th>Lead Time</th><th>Dateline</th><th class="text-center">Status</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

$.get('{{route("realisasi-jadwal-pasta-report.table")}}',{filter_search:'',filter_date_from:'',filter_date_to:'',filter_shift:'all',filter_type:'all',filter_mesin:'all',filter_operator:'all',filter_status:'all',draw:1,start:0,length:100},function(init){
    const ops=[...new Set((init.data||[]).map(r=>r.operator))].sort();
    ops.forEach(o=>$('#filter-operator').append('<option value="'+o+'">'+o+'</option>'));
});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-pasta-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_type=$('#filter-type').val();d.filter_mesin=$('#filter-mesin').val();d.filter_operator=$('#filter-operator').val();d.filter_status=$('#filter-status').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-basis').text((s.total_basis||0).toLocaleString('id-ID'));$('#sum-real').text((s.total_realisasi||0).toLocaleString('id-ID'));$('#sum-lead').text(s.avg_lead_time||0);$('#sum-tunggu').text(s.avg_waktu_tunggu||0);$('#sum-ontime').text(s.tepattime||0);$('#sum-total').text('/ '+(s.total_records||0));$('#sum-count').text(s.total_records||0);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'date_fmt',name:'date',className:'text-center'},
{data:'shift',name:'shift',className:'text-center'},{data:'type',name:'type'},
{data:'kode_pasta',name:'kode_pasta'},{data:'name',name:'name'},
{data:'batch',name:'batch'},{data:'mesin',name:'mesin',className:'text-center'},
{data:'basis_fmt',name:'total_basis_kg',orderable:false,searchable:false,className:'text-end'},
{data:'realisasi_fmt',name:'realisasi_kg',orderable:false,searchable:false,className:'text-end'},
{data:'selisih_badge',name:'selisih_kg',orderable:false,searchable:false,className:'text-center'},
{data:'pct_badge',name:'percentage',orderable:false,searchable:false,className:'text-center'},
{data:'jam',name:'mulai',orderable:false,searchable:false,className:'text-center'},
{data:'tunggu_fmt',name:'waktu_tunggu_total',orderable:false,searchable:false,className:'text-center'},
{data:'operator',name:'operator'},
{data:'jadwal_fmt',name:'tgl_jadwal',className:'text-center'},
{data:'lead_time',name:'lead_time',className:'text-center'},
{data:'deadline_fmt',name:'dateline',className:'text-center'},
{data:'status_badge',name:'status_pencapaian',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});$('#filter-mesin').on('change',function(){tbl.ajax.reload()});$('#filter-operator').on('change',function(){tbl.ajax.reload()});$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-type').val('all');$('#filter-mesin').val('all');$('#filter-operator').val('all');$('#filter-status').val('all');tbl.ajax.reload()});
</script>
@endpush