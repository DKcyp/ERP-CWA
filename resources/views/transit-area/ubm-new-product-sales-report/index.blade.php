@extends('layouts.layout')
@section('title','UBM New Product Sales Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari TA</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Transit Area...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-outline-info" onclick="tbl.ajax.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Transit Area</th><th class="text-end">NOO</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('ubm-new-product-sales-report.table')}}";
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'transit_area',name:'transit_area'},{data:'noo',name:'noo',className:'text-end'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
</script>
@endpush