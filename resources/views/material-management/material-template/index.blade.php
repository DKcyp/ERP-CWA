@extends('layouts.layout')
@section('title','Material Template')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari kode, nama, atau material...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Template</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th><th>Template Code</th><th>Template Name</th><th>Target Material</th>
                        <th class="text-end">Target Output Qty</th><th>Raw Material</th><th class="text-end">Qty Needed</th>
                        <th>UOM</th><th>Description</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-lg"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Template</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Template Code <span class="text-danger">*</span></label><input type="text" class="form-control" name="template_code" id="f_code" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="template_name" id="f_name" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">UOM ID</label><input type="text" class="form-control" name="uom_id" id="f_uom" maxlength="50" placeholder="Pcs, Kg, Liter"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Target Material</label><input type="text" class="form-control" name="target_material" id="f_target" maxlength="200"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Target Output Qty <span class="text-danger">*</span></label><input type="number" class="form-control" name="target_output_qty" id="f_qty_out" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Raw Material</label><input type="text" class="form-control" name="raw_material" id="f_raw" maxlength="200"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Qty Needed <span class="text-danger">*</span></label><input type="number" class="form-control" name="qty_needed" id="f_qty_need" min="0"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea class="form-control" name="description" id="f_desc" rows="2" maxlength="500"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('material-template.table')}}",storeUrl="{{route('material-template.store')}}",showUrl="{{route('material-template.show','__ID__')}}",updateUrl="{{route('material-template.update','__ID__')}}",deleteUrl="{{route('material-template.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'template_code',name:'template_code'},{data:'template_name',name:'template_name'},{data:'target_material',name:'target_material'},
{data:'qty_output_fmt',name:'target_output_qty',className:'text-end'},{data:'raw_material',name:'raw_material'},{data:'qty_needed_fmt',name:'qty_needed',className:'text-end'},
{data:'uom_id',name:'uom_id'},{data:'description',name:'description',render:function(d){return d||'-'}},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Template');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_code').val(d.template_code??'');$('#f_name').val(d.template_name??'');$('#f_target').val(d.target_material??'');$('#f_qty_out').val(d.target_output_qty??'');$('#f_raw').val(d.raw_material??'');$('#f_qty_need').val(d.qty_needed??'');$('#f_uom').val(d.uom_id??'');$('#f_desc').val(d.description??'');modal.find('.modal-title').text('Edit Template');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus template ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush