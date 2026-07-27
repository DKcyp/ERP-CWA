@extends('layouts.layout')
@section('title','Stock Conversion')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari conversion no, material, atau warehouse...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label>
                <input type="date" class="form-control" id="filter-date-from">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label>
                <input type="date" class="form-control" id="filter-date-to">
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Conversion</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Conversion No</th><th class="text-center">Date</th><th>Warehouse</th>
                        <th>Template</th><th>Output Material</th><th class="text-end">Qty Produced</th>
                        <th>Raw Material</th><th class="text-end">Qty Consumed</th><th>Notes</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-lg"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Conversion</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Conversion No <span class="text-danger">*</span></label><input type="text" class="form-control" name="conversion_no" id="f_no" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Warehouse ID</label><input type="text" class="form-control" name="warehouse_id" id="f_wh" maxlength="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Material Template</label><input type="text" class="form-control" name="material_template" id="f_tpl" maxlength="200"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Output Material</label><input type="text" class="form-control" name="output_material" id="f_output" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Qty Produced <span class="text-danger">*</span></label><input type="number" class="form-control" name="qty_produced" id="f_qty_prod" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Raw Material</label><input type="text" class="form-control" name="raw_material" id="f_raw" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Qty Consumed <span class="text-danger">*</span></label><input type="number" class="form-control" name="qty_consumed" id="f_qty_cons" min="0"></div>
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
const tableUrl="{{route('stock-convertion.table')}}",storeUrl="{{route('stock-convertion.store')}}",showUrl="{{route('stock-convertion.show','__ID__')}}",updateUrl="{{route('stock-convertion.update','__ID__')}}",deleteUrl="{{route('stock-convertion.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'conversion_no',name:'conversion_no'},{data:'date_fmt',name:'date',className:'text-center'},{data:'warehouse_id',name:'warehouse_id'},
{data:'material_template',name:'material_template'},{data:'output_material',name:'output_material'},{data:'qty_produced_fmt',name:'qty_produced',className:'text-end'},
{data:'raw_material',name:'raw_material'},{data:'qty_consumed_fmt',name:'qty_consumed',className:'text-end'},{data:'notes',name:'notes',render:function(d){return d||'-'}},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-date-from,#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Conversion');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_no').val(d.conversion_no??'');$('#f_date').val(d.date??'');$('#f_wh').val(d.warehouse_id??'');$('#f_tpl').val(d.material_template??'');$('#f_output').val(d.output_material??'');$('#f_qty_prod').val(d.qty_produced??'');$('#f_raw').val(d.raw_material??'');$('#f_qty_cons').val(d.qty_consumed??'');$('#f_notes').val(d.notes??'');modal.find('.modal-title').text('Edit Conversion');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus conversion ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush