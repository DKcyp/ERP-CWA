@extends('layouts.layout')
@section('title','UBM Collection Progress Report')
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
                    <tr>
                        <th class="text-center">No</th>
                        <th>Transit Area</th>
                        <th class="text-end">Collection 53-90</th>
                        <th class="text-end">Collection &gt; 90</th>
                        <th class="text-end">Total Collection</th>
                        <th class="text-end">Uncollected</th>
                        <th class="text-end">Days Before</th>
                        <th class="text-end">Target</th>
                        <th class="text-end">Accumulation</th>
                        <th class="text-end">Collection Tertagih</th>
                        <th class="text-end">Ranking</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('ubm-collection-progress-report.table')}}";
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'transit_area',name:'transit_area'},
{data:'collection_53_90_fmt',name:'collection_53_90',className:'text-end'},{data:'collection_gt90_fmt',name:'collection_gt90',className:'text-end'},{data:'total_collection_fmt',name:'total_collection',className:'text-end'},
{data:'uncollected_fmt',name:'uncollected',className:'text-end'},{data:'days_before_fmt',name:'days_before',className:'text-end'},{data:'target_fmt',name:'target',className:'text-end'},
{data:'accumulation_fmt',name:'accumulation',className:'text-end'},{data:'collection_tertagih_fmt',name:'collection_tertagih',className:'text-end'},{data:'ranking',name:'ranking',className:'text-end'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
</script>
@endpush