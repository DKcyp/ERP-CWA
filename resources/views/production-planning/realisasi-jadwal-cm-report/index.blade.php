@extends('layouts.layout')
@section('title','Realisasi Jadwal CM Report')
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
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Mesin</label><select class="form-select form-select-sm" id="filter-mesin"><option value="all">Semua</option><option value="CM-01">CM-01</option><option value="CM-02">CM-02</option><option value="CM-03">CM-03</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Operator</label><select class="form-select form-select-sm" id="filter-operator"><option value="all">Semua</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('realisasi-jadwal-cm-report.export')}}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
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
            <p class="text-muted mb-1 small">Total Variance</p><h5 class="fw-bold mb-0" id="sum-var">0</h5><small class="text-muted">KG</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Avg Efisiensi</p><h5 class="fw-bold mb-0 text-success" id="sum-eff">0%</h5>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Kode Warna</p><h5 class="fw-bold mb-0 text-warning" id="sum-colors">0</h5><small class="text-muted">jenis</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body text-center">
            <p class="text-muted mb-1 small">Total Batch</p><h5 class="fw-bold mb-0" id="sum-count">0</h5>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Prod Date</th><th>Shift</th><th>Type</th><th>Jadwal</th><th>Product</th><th>Kode Warna</th><th>Batch No</th><th>Mesin</th><th class="text-end">Basis (KG)</th><th class="text-end">Realisasi (KG)</th><th class="text-center">Variance</th><th class="text-center">Eff.</th><th class="text-center">Jam</th><th>Operator</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

$.get('{{route("realisasi-jadwal-cm-report.table")}}',{filter_search:'',filter_date_from:'',filter_date_to:'',filter_shift:'all',filter_type:'all',filter_category:'all',filter_mesin:'all',filter_operator:'all',draw:1,start:0,length:100},function(init){
    const ops=[...new Set((init.data||[]).map(r=>r.operator))].sort();
    const sel=$('#filter-operator');ops.forEach(o=>sel.append('<option value="'+o+'">'+o+'</option>'));
});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-cm-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_type=$('#filter-type').val();d.filter_category=$('#filter-category').val();d.filter_mesin=$('#filter-mesin').val();d.filter_operator=$('#filter-operator').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-basis').text((s.total_basis||0).toLocaleString('id-ID'));$('#sum-real').text((s.total_realisasi||0).toLocaleString('id-ID'));const v=s.total_variance||0;$('#sum-var').text((v>0?'+':'')+v.toLocaleString('id-ID')).removeClass('text-danger text-success').addClass(v>0?'text-success':v<0?'text-danger':'');$('#sum-eff').text((s.avg_eff||0)+'%');$('#sum-colors').text(s.unique_colors||0);$('#sum-count').text(json.recordsTotal||0);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},
{data:'prod_date_fmt',name:'prod_date',className:'text-center'},
{data:'shift',name:'shift',className:'text-center'},
{data:'type',name:'type'},
{data:'schedule_category',name:'schedule_category'},
{data:'nama_product',name:'nama_product'},
{data:'kode_warna',name:'kode_warna'},
{data:'batch_no',name:'batch_no'},
{data:'mesin',name:'mesin',className:'text-center'},
{data:'basis_fmt',name:'total_basis_kg',orderable:false,searchable:false,className:'text-end'},
{data:'realisasi_fmt',name:'realisasi_kg',orderable:false,searchable:false,className:'text-end'},
{data:'variance_badge',name:'variance_kg',orderable:false,searchable:false,className:'text-center'},
{data:'eff_badge',name:'efficiency_percent',orderable:false,searchable:false,className:'text-center'},
{data:'jam',name:'mulai',orderable:false,searchable:false,className:'text-center'},
{data:'operator',name:'operator'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});$('#filter-category').on('change',function(){tbl.ajax.reload()});$('#filter-mesin').on('change',function(){tbl.ajax.reload()});$('#filter-operator').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-type').val('all');$('#filter-category').val('all');$('#filter-mesin').val('all');$('#filter-operator').val('all');tbl.ajax.reload()});
</script>
@endpush