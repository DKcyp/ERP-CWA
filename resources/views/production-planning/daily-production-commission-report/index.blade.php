@extends('layouts.layout')
@section('title','Daily Production Commission Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Cari Employee ID, Nama, Mesin..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><a href="{{route('daily-production-commission-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a></div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle"><i class="bi bi-people fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Batch</p><h4 class="fw-bold mb-0" id="sum-batch">-</h4></div></div>
        </div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-info bg-opacity-10 text-info rounded-circle"><i class="bi bi-box-seam fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Qty (Pcs)</p><h4 class="fw-bold mb-0" id="sum-qty">-</h4></div></div>
        </div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm hz-card h-100"><div class="card-body">
            <div class="d-flex align-items-center"><div class="flex-shrink-0 me-3"><div class="avatar bg-success bg-opacity-10 text-success rounded-circle"><i class="bi bi-cash-stack fs-4"></i></div></div>
            <div><p class="text-muted mb-0 small">Total Komisi</p><h4 class="fw-bold mb-0 text-success" id="sum-commission">-</h4></div></div>
        </div></div></div>
    </div>

    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Date</th><th>Employee ID</th><th>Employee Name</th><th>Machine ID</th><th class="text-center">Total Batch</th><th class="text-center">Total Qty (Pcs)</th><th class="text-end">Commission</th><th>Notes</th></tr>
        </thead></table></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const fmtRp=v=>'Rp '+v.toLocaleString('id-ID');
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,order:[[1,'asc']],ajax:{url:"{{route('daily-production-commission-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()},dataSrc:function(json){const s=json.summary||{};$('#sum-batch').text((s.total_batch||0).toLocaleString('id-ID'));$('#sum-qty').text((s.total_qty||0).toLocaleString('id-ID'));$('#sum-commission').text(fmtRp(s.total_commission||0));return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'employee_id',name:'employee_id'},
{data:'employee_name',name:'employee_name'},
{data:'machine_id',name:'machine_id'},
{data:'total_batch_handled',name:'total_batch_handled',className:'text-center'},
{data:'total_qty_produced',name:'total_qty_produced',className:'text-center'},
{data:'commission_fmt',name:'total_commission_amount',orderable:false,searchable:false,className:'text-end'},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');tbl.ajax.reload()});
</script>
@endpush