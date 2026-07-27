@extends('layouts.layout')
@section('title','UBM Daily Control Progress Sales Report')
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
                        <th class="text-end">Target Bulanan</th>
                        <th class="text-end">Toleransi</th>
                        <th class="text-end">Belum Tercapai</th>
                        <th class="text-end">Tahun Lalu</th>
                        <th class="text-end">Bulan Lalu</th>
                        <th class="text-end">Pencapaian TA</th>
                        <th class="text-end">Target Hari Ini</th>
                        <th class="text-end">Akumulasi</th>
                        <th class="text-end">% Target</th>
                        <th class="text-end">% Target TLR</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('ubm-daily-control-progress-sales-report.table')}}";
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'transit_area',name:'transit_area'},
{data:'target_bulanan_fmt',name:'target_bulanan',className:'text-end'},{data:'toleransi_fmt',name:'toleransi',className:'text-end'},{data:'belum_tercapai_fmt',name:'belum_tercapai',className:'text-end'},
{data:'tahun_lalu_fmt',name:'tahun_lalu',className:'text-end'},{data:'bulan_lalu_fmt',name:'bulan_lalu',className:'text-end'},{data:'pencapaian_ta_fmt',name:'pencapaian_ta',className:'text-end'},
{data:'target_hari_ini_fmt',name:'target_hari_ini',className:'text-end'},{data:'akumulasi_fmt',name:'akumulasi',className:'text-end'},{data:'persen_target_fmt',name:'persen_target',className:'text-end'},{data:'persen_target_tlr_fmt',name:'persen_target_tlr',className:'text-end'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
</script>
@endpush