@extends('layouts.layout')
@section('title', 'WA Name')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama atau nomor WA...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah WA</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Customer ID</th><th>Nama</th><th>No. WA</th><th>Role</th><th class="text-center">Primary</th><th style="width:100px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah WA Contact</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-12"><label for="f_customer_id" class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_id" name="customer_id" placeholder="Customer ID" maxlength="50"></div>
                    <div class="col-md-6"><label for="f_name" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_name" name="name" placeholder="Nama kontak" maxlength="200"></div>
                    <div class="col-md-6"><label for="f_phone" class="form-label fw-semibold">No. WA <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_phone" name="phone_number" placeholder="08XXXXXXXXXX" maxlength="30"></div>
                    <div class="col-md-6"><label for="f_role" class="form-label fw-semibold">Role / Position</label><input type="text" class="form-control" id="f_role" name="role_position" placeholder="Cth: Direktur" maxlength="100"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold d-block">Primary</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" id="f_is_primary" name="is_primary" value="1"><label class="form-check-label" for="f_is_primary">Kontak utama</label></div></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('wa-name.table')}}",storeUrl="{{route('wa-name.store')}}",showUrl="{{route('wa-name.show','__ID__')}}",updateUrl="{{route('wa-name.update','__ID__')}}",deleteUrl="{{route('wa-name.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'phone_number',name:'phone_number'},{data:'role_position',name:'role_position'},{data:'is_primary_badge',name:'is_primary',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah WA Contact');$('#f_is_primary').prop('checked',false);}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;if(!$('#f_is_primary').is(':checked'))$('#f_is_primary').val('');const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-12,.col-md-6').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_customer_id').val(d.customer_id??'');$('#f_name').val(d.name??'');$('#f_phone').val(d.phone_number??'');$('#f_role').val(d.role_position??'');$('#f_is_primary').prop('checked',!!d.is_primary);modal.find('.modal-title').text('Edit WA Contact');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus kontak ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush
