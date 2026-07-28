@extends('layouts.layout')
@section('title','Product Master')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama, product ID, barcode...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted">Brand</label>
                <select class="form-select" id="filter-brand">
                    <option value="">Semua Brand</option>
                    <option value="Nippon Paint">Nippon Paint</option>
                    <option value="Semen Indonesia">Semen Indonesia</option>
                    <option value="Toto">Toto</option>
                    <option value="Multiplus">Multiplus</option>
                    <option value="Royal">Royal</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted">Group</label>
                <select class="form-select" id="filter-group">
                    <option value="">Semua Group</option>
                    <option value="Material Bahan">Material Bahan</option>
                    <option value="Alat Finishing">Alat Finishing</option>
                    <option value="Sanitair">Sanitair</option>
                </select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Product</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th><th>Product ID</th><th>Name</th><th class="text-end">Stock</th><th>UOM</th>
                        <th class="text-end">Tonase</th><th class="text-end">Kg</th><th class="text-end">Def. Price</th>
                        <th>Supplier</th><th>Barcode</th><th>Location</th><th>Type</th><th>Brand</th>
                        <th>Group</th><th>Category</th><th>Series</th><th>Quality</th><th>Active</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-xl"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Product ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="product_id" id="f_pid" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Barcode</label><input type="text" class="form-control" name="barcode" id="f_barcode" maxlength="50"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Stock <span class="text-danger">*</span></label><input type="number" class="form-control" name="stock" id="f_stock" min="0"></div>
                    <div class="col-3"><label class="form-label fw-semibold">UOM</label><input type="text" class="form-control" name="uom" id="f_uom" maxlength="20" placeholder="Pcs, Kg, Sak"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Tonase</label><input type="number" class="form-control" name="tonase" id="f_tonase" min="0" step="0.01"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Kg</label><input type="number" class="form-control" name="kg" id="f_kg" min="0" step="0.01"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Def. Sales Price <span class="text-danger">*</span></label><input type="number" class="form-control" name="def_sales_price" id="f_price" min="0"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Supplier</label><input type="text" class="form-control" name="supplier" id="f_supplier" maxlength="200"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Location</label><input type="text" class="form-control" name="location" id="f_loc" maxlength="100"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Type</label><input type="text" class="form-control" name="type" id="f_type" maxlength="50"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Brand</label><input type="text" class="form-control" name="brand" id="f_brand" maxlength="100"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Group</label><input type="text" class="form-control" name="group" id="f_group" maxlength="100"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Category</label><input type="text" class="form-control" name="category" id="f_cat" maxlength="100"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Series</label><input type="text" class="form-control" name="series" id="f_series" maxlength="100"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Quality</label>
                        <select class="form-select" name="quality" id="f_quality">
                            <option value="">-- Pilih --</option>
                            <option value="A">A</option><option value="B">B</option><option value="C">C</option>
                        </select>
                    </div>
                    <div class="col-3"><label class="form-label fw-semibold">Active <span class="text-danger">*</span></label>
                        <select class="form-select" name="active" id="f_active">
                            <option value="Y">Y - Active</option>
                            <option value="N">N - Inactive</option>
                        </select>
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
const tableUrl="{{route('product.table')}}",storeUrl="{{route('product.store')}}",showUrl="{{route('product.show','__ID__')}}",updateUrl="{{route('product.update','__ID__')}}",deleteUrl="{{route('product.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_brand=$('#filter-brand').val();d.filter_group=$('#filter-group').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'product_id',name:'product_id'},{data:'name',name:'name'},{data:'stock_fmt',name:'stock',className:'text-end'},{data:'uom',name:'uom'},
{data:'tonase_fmt',name:'tonase',className:'text-end'},{data:'kg_fmt',name:'kg',className:'text-end'},{data:'price_fmt',name:'def_sales_price',className:'text-end'},
{data:'supplier',name:'supplier'},{data:'barcode',name:'barcode'},{data:'location',name:'location'},{data:'type',name:'type'},{data:'brand',name:'brand'},
{data:'group',name:'group'},{data:'category',name:'category'},{data:'series',name:'series'},{data:'quality',name:'quality'},{data:'active_badge',name:'active'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-brand,#filter-group').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-brand').val('');$('#filter-group').val('');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Product');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-3,.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_pid').val(d.product_id??'');$('#f_name').val(d.name??'');$('#f_stock').val(d.stock??'');$('#f_uom').val(d.uom??'');$('#f_tonase').val(d.tonase??'');$('#f_kg').val(d.kg??'');$('#f_price').val(d.def_sales_price??'');$('#f_supplier').val(d.supplier??'');$('#f_barcode').val(d.barcode??'');$('#f_loc').val(d.location??'');$('#f_type').val(d.type??'');$('#f_brand').val(d.brand??'');$('#f_group').val(d.group??'');$('#f_cat').val(d.category??'');$('#f_series').val(d.series??'');$('#f_quality').val(d.quality??'');$('#f_active').val(d.active??'Y');modal.find('.modal-title').text('Edit Product');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus product ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush