@extends('layouts.layout')
@section('title','Warehouse Master')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama atau code warehouse...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Warehouse</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Warehouse ID</th><th>Code</th><th>Name</th><th>Address</th><th>PIC Name</th><th>Phone</th><th>Note</th><th>Use VAT</th><th>Active</th><th>Allow Neg Stock</th><th>View Sales Return</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Warehouse</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label fw-semibold">Code <span class="text-danger">*</span></label><input type="text" class="form-control" name="code" id="f_code" maxlength="50"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="200"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">PIC Name</label><input type="text" class="form-control" name="pic_name" id="f_pic_name" maxlength="200"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Address</label><textarea class="form-control" name="address" id="f_address" rows="2" maxlength="500"></textarea></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Phone</label><input type="text" class="form-control" name="phone" id="f_phone" maxlength="50"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Note</label><input type="text" class="form-control" name="note" id="f_note" maxlength="500"></div>
                    <div class="col-12"><hr><h6 class="fw-bold">Pengaturan</h6></div>
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="use_vat" id="f_use_vat" value="Y" checked><label class="form-check-label fw-semibold">Use VAT</label></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="active" id="f_active" value="Y" checked><label class="form-check-label fw-semibold">Active</label></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="allow_negative_stock" id="f_allow_neg" value="Y"><label class="form-check-label fw-semibold">Allow Negative Stock</label></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="view_in_sales_return" id="f_view_return" value="Y"><label class="form-check-label fw-semibold">View In Sales Return</label></div>
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
const tableUrl="{{route('warehouse.table')}}",storeUrl="{{route('warehouse.store')}}",showUrl="{{route('warehouse.show','__ID__')}}",updateUrl="{{route('warehouse.update','__ID__')}}",deleteUrl="{{route('warehouse.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

function ynBadge(d){return d==='Y'?'<span class="badge bg-success">Yes</span>':'<span class="badge bg-secondary">No</span>';}

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'warehouse_id',name:'warehouse_id'},{data:'code',name:'code'},{data:'name',name:'name'},
{data:'address',name:'address',render:function(d){return d||'-'}},{data:'pic_name',name:'pic_name',render:function(d){return d||'-'}},
{data:'phone',name:'phone',render:function(d){return d||'-'}},{data:'note',name:'note',render:function(d){return d||'-'}},
{data:'use_vat',name:'use_vat',render:ynBadge},{data:'active_badge',name:'active'},
{data:'allow_negative_stock',name:'allow_negative_stock',render:ynBadge},
{data:'view_in_sales_return',name:'view_in_sales_return',render:ynBadge},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');$('#f_use_vat,#f_active').prop('checked',true);$('#f_allow_neg,#f_view_return').prop('checked',false);form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Warehouse');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData(form[0]);
    if(!fd.has('use_vat'))fd.append('use_vat','N');
    if(!fd.has('active'))fd.append('active','N');
    if(!fd.has('allow_negative_stock'))fd.append('allow_negative_stock','N');
    if(!fd.has('view_in_sales_return'))fd.append('view_in_sales_return','N');
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',
        success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},
        error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-md-3,.col-md-4,.col-md-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_code').val(d.code??'');$('#f_name').val(d.name??'');$('#f_address').val(d.address??'');$('#f_pic_name').val(d.pic_name??'');$('#f_phone').val(d.phone??'');$('#f_note').val(d.note??'');$('#f_use_vat').prop('checked',(d.use_vat??'Y')==='Y');$('#f_active').prop('checked',(d.active??'Y')==='Y');$('#f_allow_neg').prop('checked',(d.allow_negative_stock??'N')==='Y');$('#f_view_return').prop('checked',(d.view_in_sales_return??'N')==='Y');modal.find('.modal-title').text('Edit Warehouse');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus warehouse ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush