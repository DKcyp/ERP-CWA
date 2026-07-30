@extends('layouts.layout')
@section('title','Daily Schedule Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Product ID, Nama, Mesin...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label>
                <input type="date" class="form-control" id="filter-date-from">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label>
                <input type="date" class="form-control" id="filter-date-to">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-gear me-1"></i>Mesin</label>
                <select class="form-select" id="filter-mesin">
                    <option value="all">Semua Mesin</option>
                    <option value="LINE-A1">LINE-A1</option>
                    <option value="LINE-A2">LINE-A2</option>
                    <option value="LINE-B1">LINE-B1</option>
                    <option value="LINE-B2">LINE-B2</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Realisasi</label>
                <select class="form-select" id="filter-realisasi">
                    <option value="all">Semua</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Proses">Proses</option>
                    <option value="Belum">Belum</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <a href="{{route('daily-schedule-report.export')}}" class="btn btn-outline-success" target="_blank"><i class="bi bi-file-earmark-excel me-1"></i>Export</a>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Tanggal</th><th>Product ID</th><th>Name</th><th>Base</th><th>Formulasi</th><th>Batch NR</th><th>Basis</th><th class="text-end">Basis (Kg)</th><th class="text-end">Total Basis (Kg)</th><th class="text-end">Hasil CM</th><th>Kode Mesin</th><th class="text-center">Status</th><th class="text-center">Realisasi</th><th class="text-center">Lead Time</th><th class="text-center">Dateline</th><th class="text-center">On Time</th></tr>
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
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('daily-schedule-report.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_mesin=$('#filter-mesin').val();d.filter_realisasi=$('#filter-realisasi').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'tanggal_fmt',name:'tanggal',className:'text-center'},
{data:'product_id',name:'product_id'},
{data:'name',name:'name'},
{data:'base',name:'base'},
{data:'formulasi',name:'formulasi'},
{data:'batch_nr',name:'batch_nr'},
{data:'basis',name:'basis'},
{data:'basis_kg_fmt',name:'basis_kg',className:'text-end'},
{data:'total_basis_kg_fmt',name:'total_basis_kg',className:'text-end'},
{data:'hasil_cm_fmt',name:'hasil_cm',className:'text-end'},
{data:'kode_mesin',name:'kode_mesin'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'realisasi',name:'realisasi',className:'text-center'},
{data:'lead_time_fmt',name:'lead_time',className:'text-center'},
{data:'dateline',name:'dateline',className:'text-center'},
{data:'on_time_badge',name:'on_time',orderable:false,searchable:false,className:'text-center'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-date-from').on('change',function(){tbl.ajax.reload()});
$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#filter-mesin').on('change',function(){tbl.ajax.reload()});
$('#filter-realisasi').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-mesin').val('all');$('#filter-realisasi').val('all');tbl.ajax.reload()});
</script>
@endpush