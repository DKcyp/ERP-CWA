@extends('layouts.layout')
@section('title', 'Customer Centre')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama centre, kode, atau PIC...">
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Centre</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Customer ID</th><th>Kode Centre</th><th>Nama Centre</th><th>Alamat</th><th>PIC</th><th>Telepon</th><th>Email</th><th>WH ID</th><th style="width:100px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Customer Centre</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label for="f_customer_id" class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_id" name="customer_id" placeholder="Customer ID" maxlength="50"></div>
                    <div class="col-6"><label for="f_centre_code" class="form-label fw-semibold">Kode Centre <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_centre_code" name="centre_code" placeholder="Cth: CAB-BDG-01" maxlength="50"></div>
                    <div class="col-6"><label for="f_centre_name" class="form-label fw-semibold">Nama Centre <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_centre_name" name="centre_name" placeholder="Nama centre" maxlength="200"></div>
                    <div class="col-6"><label for="f_warehouse_id" class="form-label fw-semibold">Warehouse ID</label><input type="text" class="form-control" id="f_warehouse_id" name="warehouse_id" placeholder="WH ID" maxlength="50"></div>
                    <div class="col-12"><label for="f_address" class="form-label fw-semibold">Alamat</label><textarea class="form-control" id="f_address" name="address" rows="2" placeholder="Alamat lengkap"></textarea></div>
                    <div class="col-4"><label for="f_pic" class="form-label fw-semibold">PIC Name</label><input type="text" class="form-control" id="f_pic" name="pic_name" placeholder="Nama PIC" maxlength="100"></div>
                    <div class="col-4"><label for="f_phone" class="form-label fw-semibold">Telepon</label><input type="text" class="form-control" id="f_phone" name="phone" placeholder="021-XXXXXXXX" maxlength="30"></div>
                    <div class="col-4"><label for="f_email" class="form-label fw-semibold">Email</label><input type="email" class="form-control" id="f_email" name="email" placeholder="email@domain.com" maxlength="100"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('customer-centre.table')}}",storeUrl="{{route('customer-centre.store')}}",showUrl="{{route('customer-centre.show','__ID__')}}",updateUrl="{{route('customer-centre.update','__ID__')}}",deleteUrl="{{route('customer-centre.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'centre_code',name:'centre_code'},{data:'centre_name',name:'centre_name'},{data:'address',name:'address'},{data:'pic_name',name:'pic_name'},{data:'phone',name:'phone'},{data:'email',name:'email'},{data:'warehouse_id',name:'warehouse_id'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Customer Centre');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_customer_id').val(d.customer_id??'');$('#f_centre_code').val(d.centre_code??'');$('#f_centre_name').val(d.centre_name??'');$('#f_address').val(d.address??'');$('#f_pic').val(d.pic_name??'');$('#f_phone').val(d.phone??'');$('#f_email').val(d.email??'');$('#f_warehouse_id').val(d.warehouse_id??'');modal.find('.modal-title').text('Edit Customer Centre');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus centre ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush
