@extends('layouts.layout')
@section('title', 'Customer Balance Summary')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Customer ID / Name...">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Currency</label>
                <select class="form-select form-select-sm" id="filterCurrency">
                    <option value="all">All</option>
                    <option value="IDR">IDR</option>
                    <option value="USD">USD</option>
                </select>
            </div>
            <div class="col-md-5"></div>
            <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-outline-success me-1" onclick="exportExcel()"><i class="bi bi-filetype-xlsx me-1"></i>Excel</button>
                <button class="btn btn-sm btn-outline-danger" onclick="exportPDF()"><i class="bi bi-filetype-pdf me-1"></i>PDF</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Customers</small>
            <h4 class="fw-bold mb-0" id="statTotal">-</h4>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total AR Outstanding</small>
            <h4 class="fw-bold mb-0" id="statAR">-</h4>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-4 border-danger shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Credit Limit Exceeded</small>
            <h4 class="fw-bold mb-0" id="statExceeded">-</h4>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.8rem;" id="balanceTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Currency</th>
                        <th class="text-end">Beginning Balance</th>
                        <th class="text-end">Total Invoice</th>
                        <th class="text-end">Total Payment</th>
                        <th class="text-end">Total Return</th>
                        <th class="text-end">Ending Balance</th>
                        <th class="text-end">Credit Limit</th>
                        <th class="text-end">Available Credit</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let table;
function formatRp(v) {
    if (Math.abs(v) >= 1000000000) return 'Rp ' + (v/1000000000).toFixed(1) + 'M';
    if (Math.abs(v) >= 1000000) return 'Rp ' + (v/1000000).toFixed(1) + 'Jt';
    if (Math.abs(v) >= 1000) return 'Rp ' + (v/1000).toFixed(0) + 'Rb';
    return 'Rp ' + v;
}
function formatUSD(v) { return '$ ' + v.toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2}); }

$(function(){
    $.get('{{ route("sales-dashboard.data") }}', function(){});

    table = $('#balanceTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("customer-balance.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
            d.filter_currency = $('#filterCurrency').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'customer_id',name:'customer_id'},
            {data:'customer_name',name:'customer_name'},
            {data:'currency',name:'currency',render:function(d){return '<span class="badge bg-secondary">'+d+'</span>'}},
            {data:'beginning_balance_fmt',name:'beginning_balance_fmt',className:'text-end'},
            {data:'total_invoice_fmt',name:'total_invoice_fmt',className:'text-end'},
            {data:'total_payment_fmt',name:'total_payment_fmt',className:'text-end'},
            {data:'total_return_fmt',name:'total_return_fmt',className:'text-end'},
            {data:'ending_balance_fmt',name:'ending_balance_fmt',className:'text-end'},
            {data:'credit_limit_fmt',name:'credit_limit_fmt',className:'text-end'},
            {data:'available_credit_fmt',name:'available_credit_fmt',className:'text-end'},
        ],
        order:[[1,'asc']],
        language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });

    $('#filterSearch').on('keyup', debounce(function(){ table.ajax.reload(); }, 300));
    $('#filterCurrency').on('change', function(){ table.ajax.reload(); });

    $.get('{{ route("customer-balance-summary.table") }}', {
        draw:1, start:0, length:100, 'columns[0][data]':'DT_RowIndex', 'order[0][column]':1, 'order[0][dir]':'asc'
    }, function(r){
        const data = r.data || [];
        $('#statTotal').text(data.length);
        let totalAR = 0, exceeded = 0;
        data.forEach(function(d){
            totalAR += d.ending_balance || 0;
            if ((d.available_credit||0) < 0) exceeded++;
        });
        const isIDR = totalAR > 1000000;
        $('#statAR').text(isIDR ? formatRp(totalAR) : formatUSD(totalAR));
        $('#statExceeded').text(exceeded + ' Customer').removeClass(exceeded > 0 ? 'text-success' : 'text-danger').addClass(exceeded > 0 ? 'text-danger' : 'text-success');
    });
});

function exportExcel(){ table.button(0).trigger(); }
function exportPDF(){ table.button(1).trigger(); }
</script>
@endpush
