@extends('layouts.layout')
@section('title','Promo Buy N Get M')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama promo atau product...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Promo</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Promo ID</th><th>Name</th><th>Date From</th><th>Date To</th><th>Buy Product</th><th>Buy Qty</th><th>Get Product</th><th>Get Qty</th><th>Get Disc %</th><th>Invoice Disc %</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Promo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3 mb-3">
                    <div class="col-12"><h6 class="fw-bold text-primary"><i class="bi bi-info-circle me-1"></i>Header Info</h6><hr class="mt-1"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="200"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Date From <span class="text-danger">*</span></label><input type="date" class="form-control" name="date_from" id="f_date_from"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Date To</label><input type="date" class="form-control" name="date_to" id="f_date_to"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12"><h6 class="fw-bold text-success"><i class="bi bi-cart-plus me-1"></i>Seksi Buy (Beli)</h6><hr class="mt-1"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Product <span class="text-danger">*</span></label>
                        <select class="form-select" name="buy_product_id" id="f_buy_product_id"><option value="">-- Pilih Product --</option>
                            @foreach($products as $p)<option value="{{$p['product_id']}}" data-name="{{$p['name']}}">{{$p['product_id']}} - {{$p['name']}}</option>@endforeach
                        </select>
                    </div>
                    <input type="hidden" name="buy_product_name" id="f_buy_product_name">
                    <div class="col-md-4"><label class="form-label fw-semibold">Buy Qty <span class="text-danger">*</span></label><input type="number" class="form-control" name="buy_qty" id="f_buy_qty" min="1"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12"><h6 class="fw-bold text-warning"><i class="bi bi-gift me-1"></i>Seksi Get (Bonus)</h6><hr class="mt-1"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Product <span class="text-danger">*</span></label>
                        <select class="form-select" name="get_product_id" id="f_get_product_id"><option value="">-- Pilih Product --</option>
                            @foreach($products as $p)<option value="{{$p['product_id']}}" data-name="{{$p['name']}}">{{$p['product_id']}} - {{$p['name']}}</option>@endforeach
                        </select>
                    </div>
                    <input type="hidden" name="get_product_name" id="f_get_product_name">
                    <div class="col-md-4"><label class="form-label fw-semibold">Get Qty <span class="text-danger">*</span></label><input type="number" class="form-control" name="get_qty" id="f_get_qty" min="1"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Get Discount Amount</label><input type="number" class="form-control" name="get_discount_amount" id="f_get_disc_amt" step="0.01" min="0"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Get Discount %</label><input type="number" class="form-control" name="get_discount_percentage" id="f_get_disc_pct" step="0.01" min="0" max="100"></div>
                </div>
                <div class="row g-3">
                    <div class="col-12"><h6 class="fw-bold text-danger"><i class="bi bi-receipt me-1"></i>Sales Invoice Discount</h6><hr class="mt-1"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Discount Amount</label><input type="number" class="form-control" name="sales_invoice_discount_amount" id="f_inv_disc_amt" step="0.01" min="0"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Discount %</label><input type="number" class="form-control" name="sales_invoice_discount_percentage" id="f_inv_disc_pct" step="0.01" min="0" max="100"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('promo-buy-n-get-m.table')}}",storeUrl="{{route('promo-buy-n-get-m.store')}}",showUrl="{{route('promo-buy-n-get-m.show','__ID__')}}",updateUrl="{{route('promo-buy-n-get-m.update','__ID__')}}",deleteUrl="{{route('promo-buy-n-get-m.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'promo_id',name:'promo_id'},{data:'name',name:'name'},{data:'date_from',name:'date_from'},{data:'date_to',name:'date_to',render:function(d){return d||'-'}},
{data:'buy_product_name',name:'buy_product_name'},{data:'buy_qty',name:'buy_qty'},
{data:'get_product_name',name:'get_product_name'},{data:'get_qty',name:'get_qty'},
{data:'get_discount_percentage',name:'get_discount_percentage',render:function(d){return d?d+'%':'-'}},
{data:'sales_invoice_discount_percentage',name:'sales_invoice_discount_percentage',render:function(d){return d?d+'%':'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});

$('#f_buy_product_id').on('change',function(){$('#f_buy_product_name').val($(this).find('option:selected').data('name')||'');});
$('#f_get_product_id').on('change',function(){$('#f_get_product_name').val($(this).find('option:selected').data('name')||'');});

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');$('#f_buy_product_name,#f_get_product_name').val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Promo');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    $('#f_buy_product_name').val($('#f_buy_product_id').find('option:selected').data('name')||'');
    $('#f_get_product_name').val($('#f_get_product_id').find('option:selected').data('name')||'');
    const fd=new FormData(form[0]);if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',
        success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},
        error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-md-4,.col-md-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_name').val(d.name??'');$('#f_date_from').val(d.date_from??'');$('#f_date_to').val(d.date_to??'');$('#f_buy_product_id').val(d.buy_product_id??'');$('#f_buy_product_name').val(d.buy_product_name??'');$('#f_buy_qty').val(d.buy_qty??'');$('#f_get_product_id').val(d.get_product_id??'');$('#f_get_product_name').val(d.get_product_name??'');$('#f_get_qty').val(d.get_qty??'');$('#f_get_disc_amt').val(d.get_discount_amount??'');$('#f_get_disc_pct').val(d.get_discount_percentage??'');$('#f_inv_disc_amt').val(d.sales_invoice_discount_amount??'');$('#f_inv_disc_pct').val(d.sales_invoice_discount_percentage??'');modal.find('.modal-title').text('Edit Promo');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus promo ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush