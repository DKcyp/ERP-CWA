@extends('layouts.layout')
@section('title','Sales Invoice List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari SO, faktur, customer...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="SENT">Sent</option><option value="PAID">Paid</option><option value="OVERDUE">Overdue</option><option value="CANCELED">Canceled</option></select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Invoice</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:36px;">No</th><th class="text-center">Date</th><th class="text-center">Due</th><th>Doc Type</th><th class="text-center">Printed</th><th>WH</th><th>SO</th><th>Faktur</th><th>Cust ID</th><th>Name</th><th>Curr</th><th class="text-end">Total</th><th>Disc %</th><th class="text-end">Disc Amt</th><th class="text-center">Status</th><th>Term</th><th>User</th><th class="text-end">Outst.</th><th class="text-center">Delv</th><th style="width:90px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Invoice</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-3"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="due_date" id="f_due_date"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Doc Type</label><input type="text" class="form-control" name="doc_type" id="f_doc_type" maxlength="50"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Printed</label><select class="form-select" name="printed_status" id="f_printed_status"><option value="N">No</option><option value="Y">Yes</option></select></div>
                    <div class="col-4"><label class="form-label fw-semibold">Warehouse <span class="text-danger">*</span></label><input type="text" class="form-control" name="warehouse" id="f_warehouse" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Sales Order</label><input type="text" class="form-control" name="sales_order" id="f_sales_order" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">No. Faktur</label><input type="text" class="form-control" name="no_faktur" id="f_no_faktur" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="customer_id" id="f_customer_id" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="200"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Area</label><input type="text" class="form-control" name="area" id="f_area" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">WA</label><input type="text" class="form-control" name="wa" id="f_wa" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Currency</label><input type="text" class="form-control" name="curr" id="f_curr" maxlength="10" placeholder="IDR"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total <span class="text-danger">*</span></label><input type="number" class="form-control" name="total" id="f_total" min="0"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Disc. %</label><input type="number" class="form-control" name="disc_pct" id="f_disc_pct" min="0" max="100" step="0.01"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Disc. Amt</label><input type="number" class="form-control" name="disc_amt" id="f_disc_amt" min="0"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Outstanding</label><input type="number" class="form-control" name="outstanding" id="f_outstanding" min="0"></div>
                    <div class="col-3"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="DRAFT">Draft</option><option value="SENT">Sent</option><option value="PAID">Paid</option><option value="OVERDUE">Overdue</option><option value="CANCELED">Canceled</option></select></div>
                    <div class="col-4"><label class="form-label fw-semibold">Term</label><input type="text" class="form-control" name="term" id="f_term" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">User</label><input type="text" class="form-control" name="user" id="f_user" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Delivery Status</label><select class="form-select" name="delivery_status" id="f_delivery_status"><option value="PENDING">Pending</option><option value="PARTIAL">Partial</option><option value="FULL">Full</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Purchase Note</label><textarea class="form-control" name="purchase_note" id="f_purchase_note" rows="2"></textarea></div>
                    <div class="col-12"><label class="form-label fw-semibold">Note</label><textarea class="form-control" name="note" id="f_note" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-invoice.table')}}",storeUrl="{{route('sales-invoice.store')}}",showUrl="{{route('sales-invoice.show','__ID__')}}",updateUrl="{{route('sales-invoice.update','__ID__')}}",deleteUrl="{{route('sales-invoice.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'date_fmt',name:'date',className:'text-center'},{data:'due_date_fmt',name:'due_date',className:'text-center'},{data:'doc_type',name:'doc_type'},{data:'printed_badge',name:'printed_status',orderable:false,searchable:false,className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'sales_order',name:'sales_order'},{data:'no_faktur',name:'no_faktur'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'curr',name:'curr'},{data:'total_fmt',name:'total',className:'text-end'},{data:'disc_pct',name:'disc_pct'},{data:'disc_amt_fmt',name:'disc_amt',className:'text-end'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'term',name:'term'},{data:'user',name:'user'},{data:'outstanding_fmt',name:'outstanding',className:'text-end'},{data:'delivery_badge',name:'delivery_status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Invoice');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-3,.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_date').val(d.date??'');$('#f_due_date').val(d.due_date??'');$('#f_doc_type').val(d.doc_type??'');$('#f_printed_status').val(d.printed_status??'N');$('#f_purchase_note').val(d.purchase_note??'');$('#f_warehouse').val(d.warehouse??'');$('#f_sales_order').val(d.sales_order??'');$('#f_no_faktur').val(d.no_faktur??'');$('#f_customer_id').val(d.customer_id??'');$('#f_name').val(d.name??'');$('#f_area').val(d.area??'');$('#f_wa').val(d.wa??'');$('#f_note').val(d.note??'');$('#f_curr').val(d.curr??'');$('#f_total').val(d.total??'');$('#f_disc_pct').val(d.disc_pct??'');$('#f_disc_amt').val(d.disc_amt??'');$('#f_outstanding').val(d.outstanding??'');$('#f_status').val(d.status??'DRAFT');$('#f_term').val(d.term??'');$('#f_user').val(d.user??'');$('#f_delivery_status').val(d.delivery_status??'PENDING');modal.find('.modal-title').text('Edit Invoice');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus invoice ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush