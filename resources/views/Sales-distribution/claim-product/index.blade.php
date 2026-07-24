@extends('layouts.layout')
@section('title','Claim Product')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama, doc id, atau customer...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Claim</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Doc ID</th><th>Customer</th><th>Nama</th><th>Member ID</th><th class="text-center">Tgl</th><th class="text-end">Total Point</th><th>Warehouse</th><th>User</th><th>Type</th><th style="width:100px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Claim Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label for="f_doc_id" class="form-label fw-semibold">Doc ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_doc_id" name="doc_id" maxlength="50"></div>
                    <div class="col-4"><label for="f_date" class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date" name="date"></div>
                    <div class="col-4"><label for="f_customer_id" class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_id" name="customer_id" maxlength="50"></div>
                    <div class="col-4"><label for="f_name" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_name" name="name" maxlength="200"></div>
                    <div class="col-4"><label for="f_member_id" class="form-label fw-semibold">Member ID</label><input type="text" class="form-control" id="f_member_id" name="member_id" maxlength="50"></div>
                    <div class="col-4"><label for="f_type_name_id" class="form-label fw-semibold">Type Name ID</label><input type="text" class="form-control" id="f_type_name_id" name="type_name_id" maxlength="50"></div>
                    <div class="col-4"><label for="f_point_reguler" class="form-label fw-semibold">Point Reguler</label><input type="number" class="form-control" id="f_point_reguler" name="point_reguler" min="0"></div>
                    <div class="col-4"><label for="f_point_promo" class="form-label fw-semibold">Point Promo</label><input type="number" class="form-control" id="f_point_promo" name="point_promo" min="0"></div>
                    <div class="col-4"><label for="f_point_type" class="form-label fw-semibold">Point Type</label><input type="text" class="form-control" id="f_point_type" name="point_type" maxlength="50"></div>
                    <div class="col-4"><label for="f_warehouse_id" class="form-label fw-semibold">Warehouse ID</label><input type="text" class="form-control" id="f_warehouse_id" name="warehouse_id" maxlength="50"></div>
                    <div class="col-4"><label for="f_user" class="form-label fw-semibold">User</label><input type="text" class="form-control" id="f_user" name="user" maxlength="100"></div>
                    <div class="col-4"><label for="f_total_point" class="form-label fw-semibold">Total Point Claim <span class="text-danger">*</span></label><input type="number" class="form-control" id="f_total_point" name="total_point_claim" min="0"></div>
                    <div class="col-12"><label for="f_note" class="form-label fw-semibold">Note</label><textarea class="form-control" id="f_note" name="note" rows="2"></textarea></div>
                    <div class="col-12"><hr><h6 class="fw-semibold mb-3"><i class="bi bi-box-seam me-1"></i>Item Produk</h6>
                        <div class="table-responsive"><table class="table table-sm table-bordered" id="items-table"><thead class="table-light"><tr><th style="width:120px;">Product ID</th><th>Nama</th><th>Deskripsi</th><th style="width:70px;" class="text-center">Qty</th><th style="width:90px;">UOM</th><th style="width:70px;" class="text-end">Point</th><th style="width:90px;" class="text-end">Total Point</th><th style="width:40px;" class="text-center"><button type="button" class="btn btn-sm btn-outline-success" id="btn-add-item"><i class="bi bi-plus"></i></button></th></tr></thead>
                            <tbody id="items-tbody"></tbody>
                        </table></div>
                        <p class="text-muted small mb-0">Klik <strong>+</strong> untuk menambah item.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('claim-product.table')}}",storeUrl="{{route('claim-product.store')}}",showUrl="{{route('claim-product.show','__ID__')}}",updateUrl="{{route('claim-product.update','__ID__')}}",deleteUrl="{{route('claim-product.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'doc_id',name:'doc_id'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'member_id',name:'member_id'},{data:'date_fmt',name:'date',className:'text-center'},{data:'total_point_fmt',name:'total_point_claim',className:'text-end'},{data:'warehouse_id',name:'warehouse_id'},{data:'user',name:'user'},{data:'type_name_id',name:'type_name_id'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();$('#items-tbody').empty();modal.find('.modal-title').text('Tambah Claim Product');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
function renderItemRow(i,data){return '<tr data-index="'+i+'"><td><input type="text" class="form-control form-control-sm item-pid" name="items['+i+'][product_id]" value="'+(data.product_id||'')+'"></td><td><input type="text" class="form-control form-control-sm item-name" name="items['+i+'][name]" value="'+(data.name||'')+'"></td><td><input type="text" class="form-control form-control-sm item-desc" name="items['+i+'][description]" value="'+(data.description||'')+'"></td><td><input type="number" class="form-control form-control-sm item-qty text-center" name="items['+i+'][qty]" value="'+(data.qty||0)+'" min="0"></td><td><input type="text" class="form-control form-control-sm item-uom" name="items['+i+'][uom_id]" value="'+(data.uom_id||'')+'"></td><td><input type="number" class="form-control form-control-sm item-point text-end" name="items['+i+'][point]" value="'+(data.point||0)+'" min="0"></td><td><input type="number" class="form-control form-control-sm item-total text-end" name="items['+i+'][total_point]" value="'+(data.total_point||0)+'" min="0" readonly></td><td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x"></i></button></td></tr>';}
$('#btn-add-item').on('click',function(){const i=$('#items-tbody tr').length;$('#items-tbody').append(renderItemRow(i,{}))});
$('#items-tbody').on('click','.btn-remove-item',function(){$(this).closest('tr').remove()});
$('#items-tbody').on('input','.item-qty,.item-point',function(){const tr=$(this).closest('tr'),qty=parseInt(tr.find('.item-qty').val())||0,point=parseInt(tr.find('.item-point').val())||0;tr.find('.item-total').val(qty*point)});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});const items=[];$('#items-tbody tr').each(function(){items.push({product_id:$(this).find('.item-pid').val(),name:$(this).find('.item-name').val(),description:$(this).find('.item-desc').val(),qty:parseInt($(this).find('.item-qty').val())||0,uom_id:$(this).find('.item-uom').val(),point:parseInt($(this).find('.item-point').val())||0,total_point:parseInt($(this).find('.item-total').val())||0})});fd.push({name:'items',value:JSON.stringify(items)});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_doc_id').val(d.doc_id??'');$('#f_date').val(d.date??'');$('#f_customer_id').val(d.customer_id??'');$('#f_name').val(d.name??'');$('#f_member_id').val(d.member_id??'');$('#f_type_name_id').val(d.type_name_id??'');$('#f_point_reguler').val(d.point_reguler??'');$('#f_point_promo').val(d.point_promo??'');$('#f_point_type').val(d.point_type??'');$('#f_warehouse_id').val(d.warehouse_id??'');$('#f_user').val(d.user??'');$('#f_total_point').val(d.total_point_claim??'');$('#f_note').val(d.note??'');if(d.items&&d.items.length){d.items.forEach(function(it,i){$('#items-tbody').append(renderItemRow(i,it))})}modal.find('.modal-title').text('Edit Claim Product');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus claim ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush
