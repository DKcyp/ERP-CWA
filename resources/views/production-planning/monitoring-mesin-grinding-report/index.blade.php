@extends('layouts.layout')
@section('title','Monitoring Mesin Grinding Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, Mesin, Produk..."></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">S1</option><option value="Shift 2">S2</option><option value="Shift 3">S3</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Mesin</label><select class="form-select form-select-sm" id="filter-machine"><option value="all">Semua</option><option value="GR-01">GR-01</option><option value="GR-02">GR-02</option><option value="GR-03">GR-03</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water</option><option value="Solvent Based">Solvent</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Operator</label><select class="form-select form-select-sm" id="filter-operator"><option value="all">Semua</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('monitoring-mesin-grinding-report.export')}}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-2 mb-4">
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Tonase</p><h6 class="fw-bold mb-0 text-primary" id="sum-ton" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">Ton</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Avg Kehalusan</p><h6 class="fw-bold mb-0 text-success" id="sum-micron" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">u (micron)</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Avg Gear Pump</p><h6 class="fw-bold mb-0 text-info" id="sum-gear" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">RPM</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Avg Blade</p><h6 class="fw-bold mb-0 text-warning" id="sum-blade" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">RPM</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Siklus</p><h6 class="fw-bold mb-0" id="sum-siklus" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">pass</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Batch</p><h6 class="fw-bold mb-0" id="sum-count" style="font-size:0.95rem">0</h6>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Prod Date</th><th>Shift</th><th>Mesin</th><th>Type</th><th>Product</th><th>Batch No</th><th class="text-end">Tonase</th><th>No Mesin</th><th class="text-center">Jam</th><th class="text-center">Siklus</th><th class="text-center">Jam Obs</th><th class="text-center">Gear (RPM)</th><th class="text-center">Blade (RPM)</th><th class="text-center">Micron</th><th>Operator</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

$.get('{{route("monitoring-mesin-grinding-report.table")}}',{filter_search:'',filter_date_from:'',filter_date_to:'',filter_shift:'all',filter_machine:'all',filter_type:'all',filter_operator:'all',draw:1,start:0,length:100},function(init){
    const ops=[...new Set((init.data||[]).map(r=>r.operator))].sort();
    ops.forEach(o=>$('#filter-operator').append('<option value="'+o+'">'+o+'</option>'));
});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('monitoring-mesin-grinding-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_machine=$('#filter-machine').val();d.filter_type=$('#filter-type').val();d.filter_operator=$('#filter-operator').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-ton').text((s.total_ton||0).toLocaleString('id-ID',{minimumFractionDigits:2}));$('#sum-micron').text(s.avg_micron||0);$('#sum-gear').text((s.avg_gear||0).toLocaleString('id-ID'));$('#sum-blade').text((s.avg_blade||0).toLocaleString('id-ID'));$('#sum-siklus').text((s.total_siklus||0).toLocaleString('id-ID'));$('#sum-count').text(s.total_records||0);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'prod_date_fmt',name:'prod_date',className:'text-center'},
{data:'shift',name:'shift',className:'text-center'},{data:'machine',name:'machine',className:'text-center'},
{data:'type',name:'type'},{data:'nama_product',name:'nama_product'},
{data:'batch_no',name:'batch_no'},
{data:'tonase_fmt',name:'tonase',orderable:false,searchable:false,className:'text-end'},
{data:'no_mesin',name:'no_mesin',className:'text-center'},
{data:'jam',name:'mulai',orderable:false,searchable:false,className:'text-center'},
{data:'siklus_ke',name:'siklus_ke',className:'text-center'},
{data:'jam_pengamatan',name:'jam_pengamatan',className:'text-center'},
{data:'speed_gear_pump',name:'speed_gear_pump',className:'text-center'},
{data:'speed_blade',name:'speed_blade',className:'text-center'},
{data:'micron_badge',name:'hasil_micron',orderable:false,searchable:false,className:'text-center'},
{data:'operator',name:'operator'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-machine').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});$('#filter-operator').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-machine').val('all');$('#filter-type').val('all');$('#filter-operator').val('all');tbl.ajax.reload()});
</script>
@endpush