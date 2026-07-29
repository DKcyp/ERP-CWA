@extends('layouts.layout')
@section('title','Supplier Product')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari supplier atau product...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Supplier Product</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Supplier Product ID</th><th>Supplier</th><th>Product</th><th>Supplier Item Code</th><th>Supplier Item Name</th><th>Lead Time (Days)</th><th>Active</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Supplier Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label fw-semibold">Supplier <span class="text-danger">*</span></label>
                        <select class="form-select" name="supplier" id="f_supplier"><option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)<option value="{{$s['name']}}">{{$s['supplier_code']}} - {{$s['name']}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-6"><label class="form-label fw-semibold">Product <span class="text-danger">*</span></label>
                        <select class="form-select" name="product" id="f_product"><option value="">-- Pilih Product --</option>
                            @foreach($products as $p)<option value="{{$p['name']}}">{{$p['product_id']}} - {{$p['name']}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-6"><label class="form-label fw-semibold">Supplier Item Code</label><input type="text" class="form-control" name="supplier_item_code" id="f_item_code" maxlength="50"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Supplier Item Name</label><input type="text" class="form-control" name="supplier_item_name" id="f_item_name" maxlength="200"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Lead Time (Days)</label><input type="number" class="form-control" name="lead_time_days" id="f_lead_time" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Active <span class="text-danger">*</span></label>
                        <select class="form-select" name="active" id="f_active"><option value="Y">Y - Active</option><option value="N">N - Inactive</option></select>
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
const tableUrl="{{route('supplier-product.table')}}",storeUrl="{{route('supplier-product.store')}}",showUrl="{{route('supplier-product.show','__ID__')}}",updateUrl="{{route('supplier-product.update','__ID__')}}",deleteUrl="{{route('supplier-product.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'supplier_product_id',name:'supplier_product_id'},{data:'supplier',name:'supplier'},{data:'product',name:'product'},
{data:'supplier_item_code',name:'supplier_item_code',render:function(d){return d||'-'}},
{data:'supplier_item_name',name:'supplier_item_name',render:function(d){return d||'-'}},
{data:'lead_time_days',name:'lead_time_days',render:function(d){return d||'-'}},
{data:'active_badge',name:'active'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Supplier Product');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_supplier').val(d.supplier??'');$('#f_product').val(d.product??'');$('#f_item_code').val(d.supplier_item_code??'');$('#f_item_name').val(d.supplier_item_name??'');$('#f_lead_time').val(d.lead_time_days??'');$('#f_active').val(d.active??'Y');modal.find('.modal-title').text('Edit Supplier Product');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus supplier product ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush