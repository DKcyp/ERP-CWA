@extends('layouts.layout')
@section('title','Customer AR Position Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari cust ID, nama, area, warehouse...">
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
                        <th class="text-center" rowspan="2">No</th>
                        <th rowspan="2">Warehouse</th><th rowspan="2">Area</th><th rowspan="2">Cust ID</th><th rowspan="2">Name</th>
                        <th rowspan="2" class="text-end">Sales</th><th rowspan="2" class="text-end">Saldo Piutang</th>
                        <th colspan="12" class="text-center">Bulan</th>
                        <th rowspan="2" class="text-end">Saldo Piutang</th><th rowspan="2" class="text-end">Total Piutang</th>
                    </tr>
                    <tr>
                        <th class="text-end">Jan</th><th class="text-end">Feb</th><th class="text-end">Mar</th><th class="text-end">Apr</th><th class="text-end">Mei</th><th class="text-end">Jun</th>
                        <th class="text-end">Jul</th><th class="text-end">Agu</th><th class="text-end">Sep</th><th class="text-end">Okt</th><th class="text-end">Nov</th><th class="text-end">Des</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('customer-ar-position-report.table')}}";
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'warehouse',name:'warehouse'},{data:'area',name:'area'},{data:'cust_id',name:'cust_id'},{data:'name',name:'name'},
{data:'sales_fmt',name:'sales',className:'text-end'},{data:'saldo_piutang_fmt',name:'saldo_piutang',className:'text-end'},
{data:'jan_fmt',name:'jan',className:'text-end'},{data:'feb_fmt',name:'feb',className:'text-end'},{data:'mar_fmt',name:'mar',className:'text-end'},{data:'apr_fmt',name:'apr',className:'text-end'},{data:'mei_fmt',name:'mei',className:'text-end'},{data:'jun_fmt',name:'jun',className:'text-end'},{data:'jul_fmt',name:'jul',className:'text-end'},{data:'agt_fmt',name:'agt',className:'text-end'},{data:'sep_fmt',name:'sep',className:'text-end'},{data:'okt_fmt',name:'okt',className:'text-end'},{data:'nov_fmt',name:'nov',className:'text-end'},{data:'des_fmt',name:'des',className:'text-end'},
{data:'saldo_piutang_end_fmt',name:'saldo_piutang_end',className:'text-end'},{data:'total_piutang_fmt',name:'total_piutang',className:'text-end'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
</script>
@endpush