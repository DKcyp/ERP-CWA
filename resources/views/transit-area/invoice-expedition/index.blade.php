@extends('layouts.layout')
@section('title','Invoice Expedition')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Expedition</button>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Doc ID</th><th class="text-center">Date</th><th>Warehouse</th><th>Salesman</th><th>Notes</th><th>User ID</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Expedition</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label fw-semibold">Doc ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="doc_id" id="f_doc_id" maxlength="50"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Warehouse</label><input type="text" class="form-control" name="warehouse" id="f_wh" maxlength="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Salesman</label><input type="text" class="form-control" name="salesman" id="f_sales" maxlength="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">User ID</label><input type="text" class="form-control" name="user_id" id="f_user" maxlength="50"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Notes</label><textarea class="form-control" name="notes" id="f_notes" rows="2" maxlength="500"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('invoice-expedition.table')}}",storeUrl="{{route('invoice-expedition.store')}}",showUrl="{{route('invoice-expedition.show','__ID__')}}",updateUrl="{{route('invoice-expedition.update','__ID__')}}",deleteUrl="{{route('invoice-expedition.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:tableUrl,columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'doc_id',name:'doc_id'},{data:'date_fmt',name:'date',className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'salesman',name:'salesman'},{data:'notes',name:'notes',render:function(d){return d||'-'}},{data:'user_id',name:'user_id'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Expedition');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_doc_id').val(d.doc_id??'');$('#f_date').val(d.date??'');$('#f_wh').val(d.warehouse??'');$('#f_sales').val(d.salesman??'');$('#f_notes').val(d.notes??'');$('#f_user').val(d.user_id??'');modal.find('.modal-title').text('Edit Expedition');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus expedition ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush