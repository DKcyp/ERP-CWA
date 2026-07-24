@extends('layouts.layout')
@section('title', 'Customer Balance Summary')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari Customer</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama atau ID customer...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:40px;">No</th>
                        <th>Customer</th><th>Curr</th>
                        <th class="text-end">Beginning</th><th class="text-end">Invoice</th><th class="text-end">Payment</th><th class="text-end">Return</th>
                        <th class="text-end">Ending</th><th class="text-end">Limit</th><th class="text-end">Available</th>
                        <th style="width:80px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('customer-balance.table')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'name',name:'name'},{data:'currency',name:'currency'},
{data:'beginning_balance_fmt',name:'beginning_balance',className:'text-end'},
{data:'total_invoice_fmt',name:'total_invoice',className:'text-end'},
{data:'total_payment_fmt',name:'total_payment',className:'text-end'},
{data:'total_return_fmt',name:'total_return',className:'text-end'},
{data:'ending_balance_fmt',name:'ending_balance',className:'text-end'},
{data:'credit_limit_fmt',name:'credit_limit',className:'text-end'},
{data:'available_credit_fmt',name:'available_credit',className:'text-end'},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
</script>
@endpush
