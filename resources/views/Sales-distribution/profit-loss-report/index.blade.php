@extends('layouts.layout')
@section('title','Profit Loss Report')
@push('after-style')<style>#table-data thead th{font-weight:600}.badge{font-size:.75rem}</style>@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari periode...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Period</th><th class="text-end">Total Sales Revenue</th><th class="text-end">Sales Return</th><th class="text-end">COGS (HPP)</th><th class="text-end">Gross Margin</th><th class="text-end">Operating Expenses</th><th class="text-end">Net Sales Profit</th><th style="width:80px;" class="text-center">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title fw-semibold">Detail Laba Rugi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-12"><small class="text-muted d-block">Period</small><span id="detail-period" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Total Sales Revenue</small><span id="detail-revenue" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Sales Return</small><span id="detail-return" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">COGS (HPP)</small><span id="detail-hpp" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Gross Margin</small><span id="detail-gross" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Operating Expenses</small><span id="detail-expense" class="fw-semibold">-</span></div>
                <div class="col-md-6"><small class="text-muted d-block">Net Sales Profit</small><span id="detail-net" class="fw-semibold">-</span></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('profit-loss-report.table')}}",showUrl="{{route('profit-loss-report.show','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'period',name:'period'},{data:'revenue_fmt',name:'total_sales_revenue',className:'text-end'},{data:'return_fmt',name:'sales_return',className:'text-end'},{data:'hpp_fmt',name:'cogs',className:'text-end'},{data:'gross_fmt',name:'gross_margin',className:'text-end'},{data:'expense_fmt',name:'operating_expenses',className:'text-end'},{data:'net_fmt',name:'net_sales_profit',className:'text-end'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
$('#table-data').on('click','.btn-detail',function(){const id=$(this).data('id');$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};$('#detail-period').text(d.period??'-');$('#detail-revenue').text('Rp '+Number(d.total_sales_revenue||0).toLocaleString('id-ID'));$('#detail-return').text('Rp '+Number(d.sales_return||0).toLocaleString('id-ID'));$('#detail-hpp').text('Rp '+Number(d.cogs||0).toLocaleString('id-ID'));$('#detail-gross').text('Rp '+Number(d.gross_margin||0).toLocaleString('id-ID'));$('#detail-expense').text('Rp '+Number(d.operating_expenses||0).toLocaleString('id-ID'));$('#detail-net').text('Rp '+Number(d.net_sales_profit||0).toLocaleString('id-ID'));$('#modal-detail').modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
</script>
@endpush