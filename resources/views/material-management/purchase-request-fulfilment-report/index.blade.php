@extends('layouts.layout')
@section('title','Purchase Request Fulfilment Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari PR No, Department, atau Material...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status Pemenuhan</label>
                <select class="form-select" id="filter-status">
                    <option value="all">Semua Status</option>
                    <option value="Unfulfilled">Unfulfilled</option>
                    <option value="Partial">Partial</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>PR No</th><th>PR Date</th><th>Department</th><th>Material ID</th><th>Material Name</th><th class="text-center">Qty Requested</th><th class="text-center">Qty Ordered Total</th><th class="text-center">Qty Outstanding</th><th>Linked PO Numbers</th><th class="text-center">Status</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('purchase-request-fulfilment.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'pr_no',name:'pr_no'},
{data:'pr_date',name:'pr_date',className:'text-center'},
{data:'department',name:'department'},
{data:'material_id',name:'material_id'},
{data:'material_name',name:'material_name'},
{data:'qty_requested',name:'qty_requested',className:'text-center'},
{data:'qty_ordered_total',name:'qty_ordered_total',className:'text-center'},
{data:'qty_outstanding',name:'qty_outstanding',className:'text-center',render:function(d){return '<span class="'+(d>0?'text-danger fw-bold':'text-success')+'">'+d+'</span>'}},
{data:'linked_po_numbers',name:'linked_po_numbers',render:function(d){return d||'-'}},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
</script>
@endpush