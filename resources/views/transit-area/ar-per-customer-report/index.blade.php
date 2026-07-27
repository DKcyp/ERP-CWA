@extends('layouts.layout')
@section('title','AR per Customer Report')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari cust ID, nama, area, warehouse, salesman...">
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
                        <th>Warehouse</th><th>Area</th><th>Cust ID</th><th>Name</th>
                        <th class="text-end">Saldo Awal</th><th class="text-end">Penjualan</th><th class="text-end">PO Closing</th>
                        <th class="text-end">Bank</th><th class="text-end">Cash</th><th class="text-end">Discount</th>
                        <th class="text-end">Lain-Lain</th><th class="text-end">Retur</th><th class="text-end">Saldo Akhir</th>
                        <th class="text-end">Sisa Piutang</th><th class="text-end">Selisih</th><th>Salesman</th>
                        <th class="text-end">&lt; 45</th><th class="text-end">&gt; 45</th><th class="text-end">&gt; 90</th><th class="text-end">&gt; 120</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Detail AR per Customer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="detail-body"></div>
</div></div></div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('ar-per-customer-report.table')}}",showUrl="{{route('ar-per-customer-report.show','__ID__')}}";
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'warehouse',name:'warehouse'},{data:'area',name:'area'},{data:'cust_id',name:'cust_id'},{data:'name',name:'name'},
{data:'saldo_awal_fmt',name:'saldo_awal',className:'text-end'},{data:'penjualan_fmt',name:'penjualan',className:'text-end'},{data:'po_closing_fmt',name:'po_closing',className:'text-end'},
{data:'bank_fmt',name:'bank',className:'text-end'},{data:'cash_fmt',name:'cash',className:'text-end'},{data:'discount_fmt',name:'discount',className:'text-end'},
{data:'lain_lain_fmt',name:'lain_lain',className:'text-end'},{data:'retur_fmt',name:'retur',className:'text-end'},{data:'saldo_akhir_fmt',name:'saldo_akhir',className:'text-end'},
{data:'sisa_piutang_fmt',name:'sisa_piutang',className:'text-end'},{data:'selisih_fmt',name:'selisih',className:'text-end'},{data:'salesman',name:'salesman'},
{data:'lt45_fmt',name:'lt45',className:'text-end'},{data:'gt45_fmt',name:'gt45',className:'text-end'},{data:'gt90_fmt',name:'gt90',className:'text-end'},{data:'gt120_fmt',name:'gt120',className:'text-end'}
]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
</script>
@endpush