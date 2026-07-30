@extends('layouts.layout')
@section('title','Production Stock Level')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Product ID, Nama..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Gudang</label><select class="form-select form-select-sm" id="filter-warehouse"><option value="all">Semua</option><option value="Gudang Bahan Bandung">Bahan Bandung</option><option value="Gudang Bahan Jakarta">Bahan Jakarta</option><option value="Gudang Bahan Surabaya">Bahan Surabaya</option><option value="Gudang WIP Bandung">WIP Bandung</option><option value="Gudang Jadi Bandung">Jadi Bandung</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Kategori</label><select class="form-select form-select-sm" id="filter-category"><option value="all">Semua</option><option value="Bahan Baku">Bahan Baku</option><option value="Bahan Penolong">Penolong</option><option value="WIP">WIP</option><option value="Finished Goods">Finished</option></select></div>
            <div class="col-md-5 d-flex gap-2 align-items-center justify-content-md-end">
                <small class="text-muted" id="last-refresh">Terakhir: -</small>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-refresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>

    <div class="row g-2 mb-3">
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Current Stock</p><h6 class="fw-bold mb-0 text-primary" id="sum-current" style="font-size:0.95rem">0</h6>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Reserved Stock</p><h6 class="fw-bold mb-0 text-warning" id="sum-reserved" style="font-size:0.95rem">0</h6>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Available Stock</p><h6 class="fw-bold mb-0 text-success" id="sum-available" style="font-size:0.95rem">0</h6>
        </div></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm hz-card py-2"><div class="card-body text-center py-1">
            <p class="text-muted mb-0 small" style="font-size:0.75rem">Total Items</p><h6 class="fw-bold mb-0" id="sum-count" style="font-size:0.95rem">0</h6>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Product ID</th><th>Name</th><th class="text-center">Kategori</th><th>Warehouse</th><th class="text-end">Current Stock</th><th class="text-end">Reserved Stock</th><th class="text-end">Available Stock</th><th class="text-center">UOM</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('production-stock-level.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_warehouse=$('#filter-warehouse').val();d.filter_category=$('#filter-category').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-current').text((s.total_current||0).toLocaleString('id-ID'));$('#sum-reserved').text((s.total_reserved||0).toLocaleString('id-ID'));$('#sum-available').text((s.total_available||0).toLocaleString('id-ID'));$('#sum-count').text(s.total_records||0);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'cat_badge',name:'category',orderable:false,searchable:false,className:'text-center'},
{data:'warehouse',name:'warehouse'},
{data:'current_fmt',name:'current_stock',orderable:false,searchable:false,className:'text-end'},
{data:'reserved_fmt',name:'reserved_stock',orderable:false,searchable:false,className:'text-end'},
{data:'available_fmt',name:'available_stock',orderable:false,searchable:false,className:'text-end'},
{data:'uom',name:'uom',className:'text-center'}
]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-warehouse').on('change',function(){tbl.ajax.reload()});$('#filter-category').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-warehouse').val('all');$('#filter-category').val('all');tbl.ajax.reload()});

$('#btn-refresh').on('click',function(){
    const btn=$(this);btn.prop('disabled',true).html('<i class="bi bi-arrow-clockwise me-1 spin"></i>Loading...');
    $.post('{{route("production-stock-level.refresh")}}',{_token:csrf},function(r){$('#last-refresh').text('Terakhir: '+r.timestamp);tbl.ajax.reload(null,false)}).always(function(){btn.prop('disabled',false).html('<i class="bi bi-arrow-clockwise me-1"></i>Refresh')});
});
</script>
<style>.spin{animation:spin 1s linear infinite}@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}</style>
@endpush