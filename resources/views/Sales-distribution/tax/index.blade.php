@extends('layouts.layout')
@section('title','Tax')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari doc no, invoice, NPWP, atau faktur pajak...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Tax Code</label>
                <select id="filter-tax-code" class="form-select"><option value="all">Semua</option><option value="PPN">PPN</option><option value="PPh">PPh</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Tax</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Tax Doc No</th><th>Invoice No</th><th class="text-center">Tax Code</th><th>Customer NPWP</th><th class="text-end">DPP Amount</th><th class="text-end">Tax Amount</th><th>Tax Invoice No</th><th class="text-center">Status</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Tax</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Tax Doc No <span class="text-danger">*</span></label><input type="text" class="form-control" name="tax_doc_no" id="f_doc_no" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Invoice No <span class="text-danger">*</span></label><input type="text" class="form-control" name="invoice_no" id="f_invoice" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Tax Code <span class="text-danger">*</span></label><select class="form-select" name="tax_code" id="f_tax_code"><option value="PPN">PPN</option><option value="PPh">PPh</option></select></div>
                    <div class="col-6"><label class="form-label fw-semibold">Customer NPWP</label><input type="text" class="form-control" name="customer_npwp" id="f_npwp" maxlength="30" placeholder="XX.XXX.XXX.X-XXX.XXX"></div>
                    <div class="col-6"><label class="form-label fw-semibold">DPP Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="dpp_amount" id="f_dpp" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Tax Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="tax_amount" id="f_tax_amount" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Tax Invoice No</label><input type="text" class="form-control" name="tax_invoice_no" id="f_tax_inv" maxlength="50" placeholder="010.000-20.00000000"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="DRAFT">Draft</option><option value="PENDING">Pending</option><option value="EXPORTED">Exported</option><option value="FAILED">Failed</option></select></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('tax.table')}}",storeUrl="{{route('tax.store')}}",showUrl="{{route('tax.show','__ID__')}}",updateUrl="{{route('tax.update','__ID__')}}",deleteUrl="{{route('tax.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_tax_code=$('#filter-tax-code').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'tax_doc_no',name:'tax_doc_no'},{data:'invoice_no',name:'invoice_no'},{data:'tax_code_badge',name:'tax_code',orderable:false,searchable:false,className:'text-center'},{data:'customer_npwp',name:'customer_npwp'},{data:'dpp_fmt',name:'dpp_amount',className:'text-end'},{data:'tax_amount_fmt',name:'tax_amount',className:'text-end'},{data:'tax_invoice_no',name:'tax_invoice_no'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-tax-code').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-tax-code').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Tax');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_doc_no').val(d.tax_doc_no??'');$('#f_invoice').val(d.invoice_no??'');$('#f_tax_code').val(d.tax_code??'PPN');$('#f_npwp').val(d.customer_npwp??'');$('#f_dpp').val(d.dpp_amount??'');$('#f_tax_amount').val(d.tax_amount??'');$('#f_tax_inv').val(d.tax_invoice_no??'');$('#f_status').val(d.status??'DRAFT');modal.find('.modal-title').text('Edit Tax');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus tax ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush