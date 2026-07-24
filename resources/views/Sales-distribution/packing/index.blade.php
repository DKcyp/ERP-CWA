@extends('layouts.layout')
@section('title','Packing')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari packing no, SO, customer, atau staff...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="PACKED">Packed</option><option value="SHIPPED">Shipped</option><option value="CANCEL">Cancel</option></select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Packing</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Packing No</th><th class="text-center">Date</th><th>SO No</th><th>Customer ID</th><th>Warehouse ID</th><th>Packing Staff</th><th class="text-center">Total Box</th><th class="text-end">Weight</th><th class="text-center">Status</th><th style="width:100px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Packing</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label for="f_packing_no" class="form-label fw-semibold">Packing No <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_packing_no" name="packing_no" maxlength="50"></div>
                    <div class="col-6"><label for="f_date" class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date" name="date"></div>
                    <div class="col-6"><label for="f_so_no" class="form-label fw-semibold">SO No <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_so_no" name="so_no" maxlength="50"></div>
                    <div class="col-6"><label for="f_customer_id" class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_id" name="customer_id" maxlength="50"></div>
                    <div class="col-6"><label for="f_warehouse_id" class="form-label fw-semibold">Warehouse ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_warehouse_id" name="warehouse_id" maxlength="50"></div>
                    <div class="col-6"><label for="f_packing_staff" class="form-label fw-semibold">Packing Staff <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_packing_staff" name="packing_staff" maxlength="100"></div>
                    <div class="col-4"><label for="f_total_box" class="form-label fw-semibold">Total Box/Package <span class="text-danger">*</span></label><input type="number" class="form-control" id="f_total_box" name="total_box" min="1"></div>
                    <div class="col-4"><label for="f_weight" class="form-label fw-semibold">Weight <span class="text-danger">*</span></label><input type="number" class="form-control" id="f_weight" name="weight" min="0" step="0.1"></div>
                    <div class="col-4"><label for="f_status" class="form-label fw-semibold">Status</label><select id="f_status" name="status" class="form-select"><option value="DRAFT">Draft</option><option value="PACKED">Packed</option><option value="SHIPPED">Shipped</option><option value="CANCEL">Cancel</option></select></div>
                    <div class="col-12"><label for="f_note" class="form-label fw-semibold">Note</label><textarea class="form-control" id="f_note" name="note" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('packing.table')}}",storeUrl="{{route('packing.store')}}",showUrl="{{route('packing.show','__ID__')}}",updateUrl="{{route('packing.update','__ID__')}}",deleteUrl="{{route('packing.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'packing_no',name:'packing_no'},{data:'date_fmt',name:'date',className:'text-center'},{data:'so_no',name:'so_no'},{data:'customer_id',name:'customer_id'},{data:'warehouse_id',name:'warehouse_id'},{data:'packing_staff',name:'packing_staff'},{data:'total_box',name:'total_box',className:'text-center'},{data:'weight_fmt',name:'weight',className:'text-end'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Packing');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_packing_no').val(d.packing_no??'');$('#f_date').val(d.date??'');$('#f_so_no').val(d.so_no??'');$('#f_customer_id').val(d.customer_id??'');$('#f_warehouse_id').val(d.warehouse_id??'');$('#f_packing_staff').val(d.packing_staff??'');$('#f_total_box').val(d.total_box??'');$('#f_weight').val(d.weight??'');$('#f_status').val(d.status??'DRAFT');$('#f_note').val(d.note??'');modal.find('.modal-title').text('Edit Packing');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus packing ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush
