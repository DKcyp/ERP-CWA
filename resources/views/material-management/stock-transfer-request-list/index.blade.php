@extends('layouts.layout')
@section('title','Stock Transfer Request List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Request No, Warehouse...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select class="form-select" id="filter-status">
                    <option value="all">Semua Status</option>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                    <option value="FULFILLED">Fulfilled</option>
                </select>
            </div>
            <div class="col-md-7 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah STR</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Request No</th><th>Date</th><th>Requester Warehouse</th><th>Source Warehouse</th><th class="text-center">Total Items</th><th class="text-center">Status</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Stock Transfer Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><label class="form-label fw-semibold">Request No <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_request_no" maxlength="50"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Requester Warehouse <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_requester_warehouse" maxlength="100"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Source Warehouse <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_source_warehouse" maxlength="100"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Reason</label><textarea class="form-control" id="f_reason" rows="2" maxlength="500"></textarea></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="f_status"><option value="DRAFT">Draft</option><option value="PENDING">Pending</option><option value="APPROVED">Approved</option><option value="REJECTED">Rejected</option><option value="FULFILLED">Fulfilled</option></select>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><label class="form-label fw-semibold mb-1">Daftar Item</label><div class="small text-muted">Tambahkan material dan jumlah yang diminta.</div></div>
                        <button type="button" class="btn btn-sm btn-success" id="btn-add-item"><i class="bi bi-plus-lg me-1"></i>Tambah Item</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table-items">
                            <thead class="table-secondary"><tr>
                                <th style="width:40px;" class="text-center">No</th>
                                <th>Nama Material <span class="text-danger">*</span></th>
                                <th style="width:130px;" class="text-center">Qty Requested <span class="text-danger">*</span></th>
                                <th style="width:60px;" class="text-center">Aksi</th>
                            </tr></thead>
                            <tbody id="items-tbody"><tr id="row-empty"><td colspan="4" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i><span class="small">Belum ada item.</span></td></tr></tbody>
                        </table>
                    </div>
                </div></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('stock-transfer-request-list.table')}}",storeUrl="{{route('stock-transfer-request-list.store')}}",showUrl="{{route('stock-transfer-request-list.show','__ID__')}}",updateUrl="{{route('stock-transfer-request-list.update','__ID__')}}",deleteUrl="{{route('stock-transfer-request-list.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'request_no',name:'request_no'},{data:'date_fmt',name:'date',className:'text-center'},
{data:'requester_warehouse',name:'requester_warehouse'},{data:'source_warehouse',name:'source_warehouse'},
{data:'total_items',name:'total_items',className:'text-center'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});

let itemIndex=0;
function addItemRow(data){
    const tbody=$('#items-tbody');$('#row-empty').hide();const i=itemIndex++;
    const material=data?.material??'';const qty=data?.qty??'';
    tbody.append(`<tr><td class="text-center item-no">${tbody.find('tr:visible').length+1}</td>
        <td><input type="text" class="form-control form-control-sm item-material" value="${material}" placeholder="Nama material" maxlength="200"></td>
        <td><input type="number" class="form-control form-control-sm item-qty" value="${qty}" placeholder="0" min="1"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x-lg"></i></button></td></tr>`);
    renumberItems();
}
function renumberItems(){$('#items-tbody tr:visible').each(function(i){$(this).find('.item-no').text(i+1)})}
$('#btn-add-item').on('click',function(){addItemRow()});
$('#items-tbody').on('click','.btn-remove-item',function(){$(this).closest('tr').remove();if(!$('#items-tbody tr:visible').not('#row-empty').length)$('#row-empty').show();renumberItems()});
function resetItems(){itemIndex=0;$('#items-tbody tr:not(#row-empty)').remove();$('#row-empty').show()}
function populateItems(items){resetItems();if(items&&items.length)items.forEach(function(i){addItemRow(i)})}
function collectItems(){const items=[];$('#items-tbody tr:visible').not('#row-empty').each(function(){const m=$(this).find('.item-material').val()||'';const q=parseInt($(this).find('.item-qty').val())||0;if(m)items.push({material:m,qty:q})});return items}

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Stock Transfer Request');resetItems()}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){
    const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData(form[0]);fd.append('items',JSON.stringify(collectItems()));fd.append('request_no',document.getElementById('f_request_no').value);fd.append('date',document.getElementById('f_date').value);fd.append('requester_warehouse',document.getElementById('f_requester_warehouse').value);fd.append('source_warehouse',document.getElementById('f_source_warehouse').value);fd.append('reason',document.getElementById('f_reason').value);fd.append('status',document.getElementById('f_status').value);
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Swal.fire({icon:'error',title:'Validasi Gagal',text:Object.values(r.errors).flat().join('\n')})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_request_no').val(d.request_no??'');$('#f_date').val(d.date??'');$('#f_requester_warehouse').val(d.requester_warehouse??'');$('#f_source_warehouse').val(d.source_warehouse??'');$('#f_reason').val(d.reason??'');$('#f_status').val(d.status??'DRAFT');populateItems(d.items??[]);modal.find('.modal-title').text('Edit Stock Transfer Request');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});

$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus STR ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){Swal.fire({icon:'error',title:'Gagal',text:(x.responseJSON||{}).message||'Gagal menghapus.'})}})})});
</script>
@endpush