@extends('layouts.layout')
@section('title','Production Commission')
@section('content')
<div class="page-content">
    {{-- Filter --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-person me-1"></i>Employee</label><select class="form-select" id="filter-employee"><option value="all">Semua Employee</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><div class="form-check mt-4"><input class="form-check-input" type="checkbox" id="filter-complete"><label class="form-check-label fw-semibold small" for="filter-complete">Show COMPLETE only</label></div></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button type="button" class="btn btn-primary" id="btn-refresh"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button></div>
        </div>
    </div></div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-commission" type="button"><i class="bi bi-cash-coin me-1"></i>Commission</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-payment" type="button"><i class="bi bi-wallet2 me-1"></i>Payment</button></li>
    </ul>
    <div class="tab-content">
        {{-- Tab Commission --}}
        <div class="tab-pane fade show active" id="tab-commission">
            <div class="card border-0 shadow-sm hz-card"><div class="card-body">
                <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-commission"><thead class="table-light">
                    <tr><th class="text-center" style="width:40px;"><input type="checkbox" class="form-check-input" id="check-all"></th><th class="text-center">No</th><th>Date</th><th>Production</th><th class="text-center">Paid</th><th>Payment Date</th><th class="text-center">Status</th><th>Machine</th><th class="text-end">Commission</th><th class="text-center">Qty</th><th class="text-end">Amount</th><th class="text-end">Total</th><th>Notes</th></tr>
                </thead></table></div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">Pilih komisi yang belum dibayar untuk diproses pembayarannya.</small>
                    <button type="button" class="btn btn-success" id="btn-pay-selected"><i class="bi bi-wallet2 me-1"></i>Pay Selected</button>
                </div>
            </div></div>
        </div>

        {{-- Tab Payment --}}
        <div class="tab-pane fade" id="tab-payment">
            <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3"><h6 class="mb-0 fw-bold">Riwayat Pembayaran</h6><button type="button" class="btn btn-outline-success btn-sm" id="btn-print-payment"><i class="bi bi-printer me-1"></i>Print Payment Doc.</button></div>
                <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-payment"><thead class="table-light">
                    <tr><th class="text-center">No</th><th>Id</th><th>Date</th><th>Account</th><th class="text-end">Total</th><th>Notes</th><th class="text-center">Detail</th></tr>
                </thead></table></div>
            </div></div>
            <div class="card border-0 shadow-sm hz-card"><div class="card-body">
                <h6 class="mb-3 fw-bold">Payment Detail <span id="detail-payment-id" class="text-muted"></span></h6>
                <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-payment-detail"><thead class="table-secondary">
                    <tr><th class="text-center">No</th><th>Production Id</th><th class="text-end">Commission</th><th class="text-center">Qty</th><th class="text-end">Amount</th><th class="text-end">Total</th><th class="text-end">Total Detail</th></tr>
                </thead><tbody id="detail-tbody"><tr><td colspan="7" class="text-center text-muted py-3">Pilih payment untuk melihat detail.</td></tr></tbody></table></div>
            </div></div>
        </div>
    </div>
</div>

{{-- Pay Modal --}}
<div class="modal fade" id="modal-pay" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Proses Pembayaran Komisi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="mb-3"><label class="form-label fw-semibold">Total Dipilih</label><div id="pay-total" class="fs-4 fw-bold text-success">Rp 0</div></div>
            <div class="mb-3"><label class="form-label fw-semibold">Rekening <span class="text-danger">*</span></label><select class="form-select" id="pay-account"><option value="BCA 1234567890">BCA 1234567890</option><option value="BRI 0987654321">BRI 0987654321</option><option value="MANDIRI 1122334455">MANDIRI 1122334455</option></select></div>
            <div class="mb-3"><label class="form-label fw-semibold">Notes</label><textarea class="form-control" id="pay-notes" rows="2"></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-success" id="btn-confirm-pay"><i class="bi bi-check-lg me-1"></i>Konfirmasi Bayar</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const commTableUrl="{{route('production-commission.commission-table')}}",payTableUrl="{{route('production-commission.payment-table')}}",payShowUrl="{{route('production-commission.payment-show','__ID__')}}",payUrl="{{route('production-commission.pay')}}",empUrl="{{route('production-commission.employees')}}";

$.get(empUrl,function(emap){Object.entries(emap).forEach(function([k,v]){$('#filter-employee').append('<option value="'+k+'">'+v+' ('+k+')</option>')})});

function getCommParams(){return{filter_employee:$('#filter-employee').val(),filter_date_from:$('#filter-date-from').val(),filter_date_to:$('#filter-date-to').val(),filter_complete_only:$('#filter-complete').is(':checked')?'1':'0'}}

const tblComm=$('#table-commission').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:commTableUrl,data:function(d){$.extend(d,getCommParams())}},columns:[
{data:'checkbox',orderable:false,searchable:false,className:'text-center'},
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'paid_badge',name:'paid',orderable:false,searchable:false,className:'text-center'},
{data:'payment_date_fmt',name:'payment_date',className:'text-center'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'machine',name:'machine'},
{data:'commission_rate',name:'commission_rate',className:'text-end',render:function(d){return 'Rp '+d.toLocaleString('id-ID')}},
{data:'qty',name:'qty',className:'text-center'},
{data:'amount',name:'amount',className:'text-end',render:function(d){return 'Rp '+d.toLocaleString('id-ID')}},
{data:'amount',name:'amount',className:'text-end',render:function(d){return 'Rp '+d.toLocaleString('id-ID')}},
{data:'notes',name:'notes',render:function(d){return d||'-'}}
]});

const tblPay=$('#table-payment').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:payTableUrl},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'id',name:'id'},{data:'date_fmt',name:'date',className:'text-center'},{data:'account',name:'account'},
{data:'total',name:'total',className:'text-end',render:function(d){return 'Rp '+d.toLocaleString('id-ID')}},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:null,orderable:false,searchable:false,className:'text-center',render:function(){return '<button type="button" class="btn btn-sm btn-outline-info btn-view-detail"><i class="bi bi-eye"></i></button>'}}
]});

$('#filter-employee').on('change',function(){tblComm.ajax.reload()});$('#filter-date-from').on('change',function(){tblComm.ajax.reload()});$('#filter-date-to').on('change',function(){tblComm.ajax.reload()});$('#filter-complete').on('change',function(){tblComm.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-employee').val('all');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-complete').prop('checked',false);tblComm.ajax.reload()});
$('#btn-refresh').on('click',function(){tblComm.ajax.reload()});

$('#check-all').on('change',function(){$('.row-check').prop('checked',this.checked)});

function getSelectedIds(){const ids=[];$('.row-check:checked').each(function(){ids.push($(this).data('id'))});return ids}

$('#btn-pay-selected').on('click',function(){
    const ids=getSelectedIds();if(!ids.length){Swal.fire({icon:'warning',text:'Pilih minimal satu komisi yang belum dibayar.'});return}
    const allData=tblComm.data().toArray();let total=0;
    allData.forEach(function(r){if(ids.includes(r.id))total+=r.amount});
    $('#pay-total').text('Rp '+total.toLocaleString('id-ID'));$('#modal-pay').modal('show');
});

$('#btn-confirm-pay').on('click',function(){
    const ids=getSelectedIds();
    $.ajax({url:payUrl,method:'POST',data:{_token:csrf,ids:JSON.stringify(ids),account:$('#pay-account').val(),notes:$('#pay-notes').val()},dataType:'json',success:function(d){Swal.fire({icon:'success',title:d.message,confirmButtonText:'OK'}).then(function(){$('#modal-pay').modal('hide');tblComm.ajax.reload();tblPay.ajax.reload()})},error:function(x){Swal.fire({icon:'error',text:(x.responseJSON||{}).message||'Gagal memproses.'})}});
});

let paymentDetailTable=null;
$('#table-payment').on('click','.btn-view-detail',function(){
    const rowData=tblPay.row($(this).closest('tr')).data();
    $.get(payShowUrl.replace('__ID__',rowData.id)).done(function(r){
        const d=r.data||{};const details=d.details||[];
        $('#detail-payment-id').text('('+d.id+')');
        if(paymentDetailTable)paymentDetailTable.clear().draw();
        else paymentDetailTable=$('#table-payment-detail').DataTable({paging:false,searching:false,info:false,ordering:false,autoWidth:false});
        details.forEach(function(dt,i){paymentDetailTable.row.add([i+1,dt.production_id,'Rp '+dt.commission.toLocaleString('id-ID'),dt.qty,'Rp '+dt.amount.toLocaleString('id-ID'),'Rp '+dt.total.toLocaleString('id-ID'),'Rp '+dt.total_detail.toLocaleString('id-ID')]).draw(false)});
        paymentDetailTable.draw();
    });
});

$('#btn-print-payment').on('click',function(){Swal.fire({icon:'info',title:'Print',text:'Fitur print payment document segera hadir.'})});
</script>
@endpush