@extends('layouts.layout')
@section('title','RLHP')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari doc ID, depo, tipe, atau user...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah RLHP</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Doc ID</th><th class="text-center">Doc Date</th><th class="text-center">Payment From</th><th class="text-center">Payment To</th><th>Depo</th><th>Tipe</th><th class="text-end">Total Cash</th><th class="text-end">Total Giro</th><th>Notes</th><th>User ID</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah RLHP</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Doc ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="doc_id" id="f_doc_id" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Doc Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="doc_date" id="f_doc_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">User ID</label><input type="text" class="form-control" name="user_id" id="f_user" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Payment From <span class="text-danger">*</span></label><input type="date" class="form-control" name="payment_from_date" id="f_from"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Payment To <span class="text-danger">*</span></label><input type="date" class="form-control" name="payment_to_date" id="f_to"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Depo</label><input type="text" class="form-control" name="depo" id="f_depo" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Tipe</label><input type="text" class="form-control" name="tipe" id="f_tipe" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total Cash <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_cash" id="f_cash" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total Giro <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_giro" id="f_giro" min="0"></div>
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
const tableUrl="{{route('rlhp.table')}}",storeUrl="{{route('rlhp.store')}}",showUrl="{{route('rlhp.show','__ID__')}}",updateUrl="{{route('rlhp.update','__ID__')}}",deleteUrl="{{route('rlhp.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'doc_id',name:'doc_id'},{data:'doc_date_fmt',name:'doc_date',className:'text-center'},{data:'payment_from_fmt',name:'payment_from_date',className:'text-center'},{data:'payment_to_fmt',name:'payment_to_date',className:'text-center'},{data:'depo',name:'depo'},{data:'tipe',name:'tipe'},{data:'total_cash_fmt',name:'total_cash',className:'text-end'},{data:'total_giro_fmt',name:'total_giro',className:'text-end'},{data:'notes',name:'notes',className:'text-truncate',render:function(d){return d||'-'}},{data:'user_id',name:'user_id'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah RLHP');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_doc_id').val(d.doc_id??'');$('#f_doc_date').val(d.doc_date??'');$('#f_from').val(d.payment_from_date??'');$('#f_to').val(d.payment_to_date??'');$('#f_depo').val(d.depo??'');$('#f_tipe').val(d.tipe??'');$('#f_cash').val(d.total_cash??'');$('#f_giro').val(d.total_giro??'');$('#f_notes').val(d.notes??'');$('#f_user').val(d.user_id??'');modal.find('.modal-title').text('Edit RLHP');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus RLHP ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush