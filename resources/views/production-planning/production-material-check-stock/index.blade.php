@extends('layouts.layout')
@section('title','Production Material Check Stock')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Schedule ID, Material..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Schedule</label><select class="form-select form-select-sm" id="filter-schedule"><option value="all">Semua</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Gudang</label><select class="form-select form-select-sm" id="filter-warehouse"><option value="all">Semua</option><option value="Gudang Bahan Bandung">Bandung</option><option value="Gudang Bahan Jakarta">Jakarta</option><option value="Gudang Bahan Surabaya">Surabaya</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Status</label><select class="form-select form-select-sm" id="filter-status"><option value="all">Semua</option><option value="Sufficient">Sufficient</option><option value="Shortage">Shortage</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('production-material-check-stock.export')}}" class="btn btn-outline-success btn-sm" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-2 mb-3">
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Kebutuhan</p><h6 class="fw-bold mb-0 text-primary" id="sum-need" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">KG/Liter</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Stok</p><h6 class="fw-bold mb-0 text-info" id="sum-stock" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">KG/Liter</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Kekurangan</p><h6 class="fw-bold mb-0 text-danger" id="sum-shortage" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">KG/Liter</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Sufficient</p><h6 class="fw-bold mb-0 text-success" id="sum-suff" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">item</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Shortage</p><h6 class="fw-bold mb-0 text-danger" id="sum-short" style="font-size:0.95rem">0</h6><small class="text-muted" style="font-size:0.7rem">item</small>
        </div></div></div>
        <div class="col-md-2"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Item</p><h6 class="fw-bold mb-0" id="sum-count" style="font-size:0.95rem">0</h6>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Schedule ID</th><th>Product ID</th><th>Product Name</th><th class="text-end">Total Qty Needed</th><th class="text-end">Current Stock</th><th class="text-center">UOM</th><th>Warehouse</th><th class="text-end">Shortage Qty</th><th class="text-center">Status</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

$.get('{{route("production-material-check-stock.table")}}',{filter_search:'',filter_schedule:'all',filter_warehouse:'all',filter_status:'all',draw:1,start:0,length:100},function(init){
    const sids=[...new Set((init.data||[]).map(r=>r.schedule_id))].sort();
    const sel=$('#filter-schedule');sids.forEach(s=>sel.append('<option value="'+s+'">'+s+'</option>'));
});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('production-material-check-stock.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_schedule=$('#filter-schedule').val();d.filter_warehouse=$('#filter-warehouse').val();d.filter_status=$('#filter-status').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-need').text((s.total_need||0).toLocaleString('id-ID'));$('#sum-stock').text((s.total_stock||0).toLocaleString('id-ID'));$('#sum-shortage').text((s.total_shortage||0).toLocaleString('id-ID'));$('#sum-suff').text(s.sufficient||0);$('#sum-short').text(s.shortage_count||0);$('#sum-count').text(s.total_records||0);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'schedule_id',name:'schedule_id'},
{data:'product_id',name:'product_id'},
{data:'product_name',name:'product_name'},
{data:'need_fmt',name:'total_qty',orderable:false,searchable:false,className:'text-end'},
{data:'stock_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-end'},
{data:'uom',name:'uom',className:'text-center'},
{data:'warehouse',name:'warehouse'},
{data:'shortage_fmt',name:'shortage_qty',orderable:false,searchable:false,className:'text-end'},
{data:'status_badge',name:'stock_status',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-schedule').on('change',function(){tbl.ajax.reload()});$('#filter-warehouse').on('change',function(){tbl.ajax.reload()});$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-schedule').val('all');$('#filter-warehouse').val('all');$('#filter-status').val('all');tbl.ajax.reload()});
</script>
@endpush