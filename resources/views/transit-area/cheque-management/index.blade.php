@extends('layouts.layout')
@section('title','Cheque Management')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari no BG, customer, atau bank...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Valid</label>
                <select id="filter-valid" class="form-select"><option value="all">Semua</option><option value="YES">Yes</option><option value="NO">No</option><option value="PENDING">Pending</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah BG</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th class="text-center">Date</th><th>Cust ID</th><th>Name</th><th>No BG</th><th>Bank</th><th class="text-center">Valid Date</th><th class="text-end">Amount</th><th class="text-center">Valid</th><th>Note</th><th>Payment</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah BG</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Cust ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="customer_id" id="f_cust_id" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">No BG <span class="text-danger">*</span></label><input type="text" class="form-control" name="no_bg" id="f_no_bg" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Bank <span class="text-danger">*</span></label><input type="text" class="form-control" name="bank" id="f_bank" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Valid Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="valid_date" id="f_valid_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="amount" id="f_amount" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Valid</label><select class="form-select" name="valid" id="f_valid"><option value="PENDING">Pending</option><option value="YES">Yes</option><option value="NO">No</option></select></div>
                    <div class="col-4"><label class="form-label fw-semibold">Payment</label><input type="text" class="form-control" name="payment" id="f_payment" maxlength="100"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Note</label><textarea class="form-control" name="note" id="f_note" rows="2" maxlength="500"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('cheque-management.table')}}",storeUrl="{{route('cheque-management.store')}}",showUrl="{{route('cheque-management.show','__ID__')}}",updateUrl="{{route('cheque-management.update','__ID__')}}",deleteUrl="{{route('cheque-management.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_valid=$('#filter-valid').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'no_bg',name:'no_bg'},{data:'bank',name:'bank'},{data:'valid_date_fmt',name:'valid_date',className:'text-center'},{data:'amount_fmt',name:'amount',className:'text-end'},{data:'valid_badge',name:'valid',orderable:false,searchable:false,className:'text-center'},{data:'note',name:'note',className:'text-truncate',render:function(d){return d||'-'}},{data:'payment',name:'payment'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-valid').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-valid').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah BG');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_date').val(d.date??'');$('#f_cust_id').val(d.customer_id??'');$('#f_name').val(d.name??'');$('#f_no_bg').val(d.no_bg??'');$('#f_bank').val(d.bank??'');$('#f_valid_date').val(d.valid_date??'');$('#f_amount').val(d.amount??'');$('#f_valid').val(d.valid??'PENDING');$('#f_note').val(d.note??'');$('#f_payment').val(d.payment??'');modal.find('.modal-title').text('Edit BG');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus BG ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush