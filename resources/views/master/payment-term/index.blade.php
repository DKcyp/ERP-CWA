@extends('layouts.layout')
@section('title','Payment Term Master')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Term ID atau Sales Discount...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Payment Term</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Term ID</th><th>Disc %</th><th>If Paid Within (Days)</th><th>Net Due In (Days)</th><th>COD</th><th>Default Non-COD</th><th>Sales Discount</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Payment Term</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label fw-semibold">Payment Discount (%)</label><input type="number" class="form-control" name="payment_discount_percent" id="f_disc_pct" step="0.01" min="0" max="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">If Paid Within (Days)</label><input type="number" class="form-control" name="if_paid_within_days" id="f_paid_within" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Net Due In (Days) <span class="text-danger">*</span></label><input type="number" class="form-control" name="net_due_in_days" id="f_net_due" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Sales Discount</label>
                        <select class="form-select" name="sales_discount" id="f_sales_discount"><option value="">-- Pilih --</option>
                            @foreach($salesDiscounts as $sd)<option value="{{$sd['name']}}">{{$sd['sales_discount_id']}} - {{$sd['name']}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-12"><hr></div>
                    <div class="col-6">
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="cash_on_delivery" id="f_cod" value="Y"><label class="form-check-label fw-semibold">Cash On Delivery</label></div>
                    </div>
                    <div class="col-6">
                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="default_term_for_not_cod" id="f_default" value="Y"><label class="form-check-label fw-semibold">Default Term for Not COD</label></div>
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
const tableUrl="{{route('payment-term.table')}}",storeUrl="{{route('payment-term.store')}}",showUrl="{{route('payment-term.show','__ID__')}}",updateUrl="{{route('payment-term.update','__ID__')}}",deleteUrl="{{route('payment-term.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

function ynBadge(d){return d==='Y'?'<span class="badge bg-success">Yes</span>':'<span class="badge bg-secondary">No</span>';}

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'term_id',name:'term_id'},
{data:'payment_discount_percent',name:'payment_discount_percent',render:function(d){return d?d+'%':'-'}},
{data:'if_paid_within_days',name:'if_paid_within_days',render:function(d){return d||'-'}},
{data:'net_due_in_days',name:'net_due_in_days'},
{data:'cash_on_delivery',name:'cash_on_delivery',render:ynBadge},
{data:'default_term_for_not_cod',name:'default_term_for_not_cod',render:ynBadge},
{data:'sales_discount',name:'sales_discount',render:function(d){return d||'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');$('#f_cod,#f_default').prop('checked',false);form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Payment Term');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData(form[0]);
    if(!fd.has('cash_on_delivery'))fd.append('cash_on_delivery','N');
    if(!fd.has('default_term_for_not_cod'))fd.append('default_term_for_not_cod','N');
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',
        success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},
        error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_disc_pct').val(d.payment_discount_percent??'');$('#f_paid_within').val(d.if_paid_within_days??'');$('#f_net_due').val(d.net_due_in_days??'');$('#f_sales_discount').val(d.sales_discount??'');$('#f_cod').prop('checked',(d.cash_on_delivery??'N')==='Y');$('#f_default').prop('checked',(d.default_term_for_not_cod??'N')==='Y');modal.find('.modal-title').text('Edit Payment Term');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus payment term ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush