@extends('layouts.layout')
@section('title','Price List Master')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama, code, atau currency...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Price List</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Price List ID</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Currency</th>
                        <th>Effective Date</th>
                        <th>Expiry Date</th>
                        <th>Active</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

{{-- Modal Form (Add / Edit / Duplicate) --}}
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Price List</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id"><input type="hidden" id="form_mode" value="add">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Code <span class="text-danger">*</span></label><input type="text" class="form-control" name="code" id="f_code" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                        <select class="form-select" name="currency" id="f_currency"><option value="">-- Pilih --</option><option value="IDR">IDR</option><option value="USD">USD</option></select>
                    </div>
                    <div class="col-4"><label class="form-label fw-semibold">Effective Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="effective_date" id="f_eff"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Expiry Date</label><input type="date" class="form-control" name="expiry_date" id="f_exp"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Active <span class="text-danger">*</span></label>
                        <select class="form-select" name="active" id="f_active"><option value="Y">Y - Active</option><option value="N">N - Inactive</option></select>
                    </div>
                </div>
                <hr><h6 class="fw-bold mb-3">Detail Produk</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="items-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Product ID <span class="text-danger">*</span></th>
                                <th>Product Name <span class="text-danger">*</span></th>
                                <th>UOM <span class="text-danger">*</span></th>
                                <th>Price <span class="text-danger">*</span></th>
                                <th>Min Qty</th>
                                <th>Disc %</th>
                                <th style="width:40px"><button type="button" class="btn btn-sm btn-outline-success add-item"><i class="bi bi-plus-lg"></i></button></th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button>
            </div>
        </form>
    </div></div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Detail Price List</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" id="detail-body"></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('price-list.table')}}",storeUrl="{{route('price-list.store')}}",showUrl="{{route('price-list.show','__ID__')}}",updateUrl="{{route('price-list.update','__ID__')}}",duplicateUrl="{{route('price-list.duplicate')}}",detailUrl="{{route('price-list.detail','__ID__')}}",deleteUrl="{{route('price-list.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'price_list_id',name:'price_list_id'},{data:'code',name:'code'},{data:'name',name:'name'},{data:'currency',name:'currency'},
{data:'effective_date',name:'effective_date'},{data:'expiry_date',name:'expiry_date',render:function(d){return d||'-'}},
{data:'active_badge',name:'active'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id'),modeI=$('#form_mode');
function itemRow(i,data){data=data||{};return '<tr><td class="text-center idx">'+(i+1)+'</td>'+
'<td><input type="text" class="form-control form-control-sm" name="items['+i+'][product_id]" value="'+(data.product_id||'')+'"></td>'+
'<td><input type="text" class="form-control form-control-sm" name="items['+i+'][product_name]" value="'+(data.product_name||'')+'"></td>'+
'<td><select class="form-select form-select-sm" name="items['+i+'][uom]"><option value="">--</option><option value="PCS" '+(data.uom==='PCS'?'selected':'')+'>PCS</option><option value="KG" '+(data.uom==='KG'?'selected':'')+'>KG</option><option value="BOX" '+(data.uom==='BOX'?'selected':'')+'>BOX</option><option value="DUS" '+(data.uom==='DUS'?'selected':'')+'>DUS</option><option value="LTR" '+(data.uom==='LTR'?'selected':'')+'>LTR</option></select></td>'+
'<td><input type="number" class="form-control form-control-sm" name="items['+i+'][price]" value="'+(data.price||'')+'" step="0.01" min="0"></td>'+
'<td><input type="number" class="form-control form-control-sm" name="items['+i+'][min_qty]" value="'+(data.min_qty||'')+'" step="0.01" min="0"></td>'+
'<td><input type="number" class="form-control form-control-sm" name="items['+i+'][discount_percent]" value="'+(data.discount_percent||'')+'" step="0.01" min="0" max="100"></td>'+
'<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-item"><i class="bi bi-x-lg"></i></button></td></tr>';}
function refreshIndex(){ $('#items-body tr').each(function(i){ $(this).find('.idx').text(i+1); }); }
function loadItems(items){ if(!items||!items.length) return; items.forEach(function(d,i){ $('#items-body').append(itemRow(i,d)); }); }

$(document).on('click','.add-item',function(){ const i=$('#items-body tr').length; $('#items-body').append(itemRow(i,{})); });
$(document).on('click','.remove-item',function(){ $(this).closest('tr').remove(); refreshIndex(); });

function resetForm(){ form[0].reset(); idI.val(''); modeI.val('add'); form.find('.is-invalid').removeClass('is-invalid'); form.find('.invalid-feedback').remove(); $('#items-body').empty(); modal.find('.modal-title').text('Tambah Price List'); }
$('#btn-add').on('click',function(){ resetForm(); modal.modal('show'); });
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){
    const mode=modeI.val(),id=idI.val();
    let url,method='POST';
    if(mode==='edit'){ url=updateUrl.replace('__ID__',id); method='POST'; } else if(mode==='duplicate'){ url=duplicateUrl; method='POST'; } else { url=storeUrl; method='POST'; }
    const fd=new FormData(form[0]);
    if(mode==='edit') fd.append('_method','PUT');
    $.ajax({url,type:method,data:fd,processData:false,contentType:false,dataType:'json',
        success:function(d){
            Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){ resetForm(); modal.modal('hide'); tbl.ajax.reload(null,false); });
        },
        error:function(x){
            const r=x.responseJSON||{};
            if(x.status===422&&r.errors){
                Object.entries(r.errors).forEach(function([k,m]){
                    const i=form.find('[name="'+k+'"]').first();
                    if(i.length){ i.addClass('is-invalid'); i.closest('.col-6,.col-12,.col-4').append('<div class="invalid-feedback">'+m[0]+'</div>'); }
                });
            } else { Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'}); }
        }
    });
};

$('#table-data').on('click','.btn-detail',function(){
    const id=$(this).data('id');
    $.get(detailUrl.replace('__ID__',id)).done(function(r){
        const d=r.data||{},items=d.items||[];
        let html='<div class="row mb-3"><div class="col-4"><strong>Price List ID:</strong> '+(d.price_list_id||'')+'</div>'+
            '<div class="col-4"><strong>Code:</strong> '+(d.code||'')+'</div><div class="col-4"><strong>Name:</strong> '+(d.name||'')+'</div>'+
            '<div class="col-3"><strong>Currency:</strong> '+(d.currency||'')+'</div><div class="col-3"><strong>Effective:</strong> '+(d.effective_date||'')+'</div>'+
            '<div class="col-3"><strong>Expiry:</strong> '+(d.expiry_date||'-')+'</div><div class="col-3"><strong>Active:</strong> '+((d.active||'Y')==='Y'?'Active':'Inactive')+'</div></div>'+
            '<hr><h6 class="fw-bold">Daftar Produk</h6><div class="table-responsive"><table class="table table-bordered table-sm"><thead class="table-light"><tr><th>#</th><th>Product ID</th><th>Product Name</th><th>UOM</th><th>Price</th><th>Min Qty</th><th>Disc %</th></tr></thead><tbody>';
        items.forEach(function(it,i){ html+='<tr><td>'+(i+1)+'</td><td>'+(it.product_id||'')+'</td><td>'+(it.product_name||'')+'</td><td>'+(it.uom||'')+'</td><td>'+(parseFloat(it.price||0).toLocaleString('id-ID',{minimumFractionDigits:2}))+'</td><td>'+(it.min_qty||'-')+'</td><td>'+(it.discount_percent||'-')+'</td></tr>'; });
        html+='</tbody></table></div>';
        $('#detail-body').html(html); $('#modal-detail').modal('show');
    }).fail(function(){ Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil detail.'}); });
});

$('#table-data').on('click','.btn-edit',function(){
    const id=$(this).data('id'); resetForm();
    $.get(showUrl.replace('__ID__',id)).done(function(r){
        const d=r.data||{}; idI.val(d.id); modeI.val('edit');
        $('#f_code').val(d.code??''); $('#f_name').val(d.name??''); $('#f_currency').val(d.currency??''); $('#f_eff').val(d.effective_date??'');
        $('#f_exp').val(d.expiry_date??''); $('#f_active').val(d.active??'Y');
        if(d.items) loadItems(d.items);
        modal.find('.modal-title').text('Edit Price List'); modal.modal('show');
    }).fail(function(){ Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'}); });
});

$('#table-data').on('click','.btn-duplicate',function(){
    const id=$(this).data('id'); resetForm();
    $.get(showUrl.replace('__ID__',id)).done(function(r){
        const d=r.data||{}; modeI.val('duplicate');
        $('#f_code').val(d.code??''); $('#f_name').val(d.name??''); $('#f_currency').val(d.currency??''); $('#f_eff').val(d.effective_date??'');
        $('#f_exp').val(d.expiry_date??''); $('#f_active').val(d.active??'Y');
        if(d.items) loadItems(d.items);
        modal.find('.modal-title').text('Pembaruan Price List'); modal.modal('show');
    }).fail(function(){ Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'}); });
});

$('#table-data').on('click','.btn-delete',function(){
    const id=$(this).data('id');
    Swal.fire({title:'Hapus price list ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){
        if(!r.isConfirmed) return;
        $.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){ Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false}); tbl.ajax.reload(null,false); },error:function(x){ const r3=x.responseJSON||{}; Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'}); }});
    });
});
</script>
@endpush