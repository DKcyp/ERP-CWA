@extends('layouts.layout')
@section('title','Pre SPK List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Doc ID atau Customer...">
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
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select class="form-select" id="filter-status">
                    <option value="all">Semua</option>
                    <option value="DRAFT">Draft</option>
                    <option value="PENDING">Pending</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Pre SPK</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Doc. ID</th><th>Date</th><th>Customer ID</th><th>Customer Name</th><th class="text-center">Total Qty</th><th class="text-center">Total Tonase</th><th class="text-center">Status</th><th>Notes</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Pre SPK</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3 mb-4">
                    <div class="col-md-3"><label class="form-label fw-semibold">Doc. ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_doc_id" maxlength="50"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_id" maxlength="50"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_name" maxlength="200"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">User ID</label><input type="text" class="form-control" id="f_user_id" maxlength="50"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="f_status"><option value="DRAFT">Draft</option><option value="PENDING">Pending</option><option value="APPROVED">Approved</option><option value="REJECTED">Rejected</option></select>
                    </div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Notes</label><textarea class="form-control" id="f_notes" rows="2" maxlength="500"></textarea></div>
                </div>
                <div class="card shadow-sm border-0 mb-3"><div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><label class="form-label fw-semibold mb-1">Detail Items</label><div class="small text-muted">Tambahkan produk, target qty, dan target tonase.</div></div>
                        <button type="button" class="btn btn-sm btn-success" id="btn-add-item"><i class="bi bi-plus-lg me-1"></i>Tambah Item</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table-items">
                            <thead class="table-secondary"><tr>
                                <th style="width:40px;" class="text-center">No</th>
                                <th>Product ID <span class="text-danger">*</span></th>
                                <th>Product Name</th>
                                <th style="width:130px;" class="text-center">Target Qty <span class="text-danger">*</span></th>
                                <th style="width:130px;" class="text-center">Target Tonase <span class="text-danger">*</span></th>
                                <th style="width:60px;" class="text-center">Aksi</th>
                            </tr></thead>
                            <tbody id="items-tbody"><tr id="row-empty"><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i><span class="small">Belum ada item.</span></td></tr></tbody>
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
const tableUrl="{{route('pre-spk-list.table')}}",storeUrl="{{route('pre-spk-list.store')}}",showUrl="{{route('pre-spk-list.show','__ID__')}}",updateUrl="{{route('pre-spk-list.update','__ID__')}}",deleteUrl="{{route('pre-spk-list.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'date_fmt',name:'date',className:'text-center'},
{data:'customer_id',name:'customer_id'},{data:'customer_name',name:'customer_name'},
{data:'total_qty',name:'total_qty',className:'text-center'},
{data:'total_tonase',name:'total_tonase',className:'text-center'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#filter-date-from').on('change',function(){tbl.ajax.reload()});
$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');$('#filter-date-from').val('');$('#filter-date-to').val('');tbl.ajax.reload()});

let itemIndex=0;
function addItemRow(data){
    const tbody=$('#items-tbody');$('#row-empty').hide();const i=itemIndex++;
    const pid=data?.product_id??'';const pname=data?.product_name??'';const qty=data?.qty??'';const tonase=data?.tonase??'';
    tbody.append(`<tr><td class="text-center item-no">${tbody.find('tr:visible').length+1}</td>
        <td><input type="text" class="form-control form-control-sm item-product-id" value="${pid}" placeholder="PRD-000" maxlength="50"></td>
        <td><input type="text" class="form-control form-control-sm item-product-name" value="${pname}" placeholder="Nama produk" maxlength="200"></td>
        <td><input type="number" class="form-control form-control-sm item-qty" value="${qty}" placeholder="0" min="1"></td>
        <td><input type="number" class="form-control form-control-sm item-tonase" value="${tonase}" placeholder="0" min="0" step="0.01"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x-lg"></i></button></td></tr>`);
    renumberItems();
}
function renumberItems(){$('#items-tbody tr:visible').each(function(i){$(this).find('.item-no').text(i+1)})}
$('#btn-add-item').on('click',function(){addItemRow()});
$('#items-tbody').on('click','.btn-remove-item',function(){$(this).closest('tr').remove();if(!$('#items-tbody tr:visible').not('#row-empty').length)$('#row-empty').show();renumberItems()});
function resetItems(){itemIndex=0;$('#items-tbody tr:not(#row-empty)').remove();$('#row-empty').show()}
function populateItems(items){resetItems();if(items&&items.length)items.forEach(function(i){addItemRow(i)})}
function collectItems(){const items=[];$('#items-tbody tr:visible').not('#row-empty').each(function(){const pid=$(this).find('.item-product-id').val()||'';const pname=$(this).find('.item-product-name').val()||'';const q=parseInt($(this).find('.item-qty').val())||0;const t=parseFloat($(this).find('.item-tonase').val())||0;if(pid)items.push({product_id:pid,product_name:pname,qty:q,tonase:t})});return items}

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Pre SPK');resetItems()}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){
    const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData();fd.append('_token',csrf);fd.append('doc_id',document.getElementById('f_doc_id').value);fd.append('date',document.getElementById('f_date').value);fd.append('customer_id',document.getElementById('f_customer_id').value);fd.append('customer_name',document.getElementById('f_customer_name').value);fd.append('user_id',document.getElementById('f_user_id').value);fd.append('notes',document.getElementById('f_notes').value);fd.append('status',document.getElementById('f_status').value);fd.append('items',JSON.stringify(collectItems()));
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Swal.fire({icon:'error',title:'Validasi Gagal',text:Object.values(r.errors).flat().join('\n')})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_doc_id').val(d.doc_id??'');$('#f_date').val(d.date??'');$('#f_customer_id').val(d.customer_id??'');$('#f_customer_name').val(d.customer_name??'');$('#f_user_id').val(d.user_id??'');$('#f_notes').val(d.notes??'');$('#f_status').val(d.status??'DRAFT');populateItems(d.items??[]);modal.find('.modal-title').text('Edit Pre SPK');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});

$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus Pre SPK ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){Swal.fire({icon:'error',title:'Gagal',text:(x.responseJSON||{}).message||'Gagal menghapus.'})}})})});
</script>
@endpush