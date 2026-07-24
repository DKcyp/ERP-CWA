@extends('layouts.layout')
@section('title','Sales Return List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari return no, customer, atau SI...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="SUBMITTED">Submitted</option><option value="APPROVED">Approved</option><option value="REJECTED">Rejected</option><option value="PROCESSED">Processed</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Return</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Return No</th><th class="text-center">Date</th><th>Warehouse</th><th>Customer ID</th><th>Name</th><th>Area</th><th>WA</th><th class="text-center">Disc %</th><th class="text-end">Disc Amt</th><th class="text-end">Total</th><th>Currency</th><th class="text-center">Status</th><th>Note</th><th>Term</th><th>Sales</th><th>SI Returned</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Return</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Return No <span class="text-danger">*</span></label><input type="text" class="form-control" name="no" id="f_no" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Warehouse</label><input type="text" class="form-control" name="warehouse" id="f_warehouse" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="customer_id" id="f_customer_id" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Area</label><input type="text" class="form-control" name="area" id="f_area" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">WA</label><input type="text" class="form-control" name="wa" id="f_wa" maxlength="30"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Disc %</label><input type="number" class="form-control" name="disc_pct" id="f_disc_pct" min="0" max="100" step="0.01"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Disc Amt</label><input type="number" class="form-control" name="disc_amt" id="f_disc_amt" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total <span class="text-danger">*</span></label><input type="number" class="form-control" name="total" id="f_total" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Currency</label><input type="text" class="form-control" name="currency" id="f_currency" maxlength="10"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="DRAFT">Draft</option><option value="SUBMITTED">Submitted</option><option value="APPROVED">Approved</option><option value="REJECTED">Rejected</option><option value="PROCESSED">Processed</option></select></div>
                    <div class="col-4"><label class="form-label fw-semibold">Term</label><input type="text" class="form-control" name="term" id="f_term" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Sales</label><input type="text" class="form-control" name="sales" id="f_sales" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">SI Returned</label><input type="text" class="form-control" name="si_returned" id="f_si" maxlength="50"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Note</label><textarea class="form-control" name="note" id="f_note" maxlength="500" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-return-list.table')}}",storeUrl="{{route('sales-return-list.store')}}",showUrl="{{route('sales-return-list.show','__ID__')}}",updateUrl="{{route('sales-return-list.update','__ID__')}}",deleteUrl="{{route('sales-return-list.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'no',name:'no'},{data:'date_fmt',name:'date',className:'text-center'},{data:'warehouse',name:'warehouse'},{data:'customer_id',name:'customer_id'},{data:'name',name:'name'},{data:'area',name:'area'},{data:'wa',name:'wa'},{data:'disc_pct_fmt',name:'disc_pct',className:'text-center'},{data:'disc_amt_fmt',name:'disc_amt',className:'text-end'},{data:'total_fmt',name:'total',className:'text-end'},{data:'currency',name:'currency'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'note',name:'note',className:'text-truncate',render:function(d){return d||'-'}},{data:'term',name:'term'},{data:'sales',name:'sales'},{data:'si_returned',name:'si_returned'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Return');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_no').val(d.no??'');$('#f_date').val(d.date??'');$('#f_warehouse').val(d.warehouse??'');$('#f_customer_id').val(d.customer_id??'');$('#f_name').val(d.name??'');$('#f_area').val(d.area??'');$('#f_wa').val(d.wa??'');$('#f_disc_pct').val(d.disc_pct??'');$('#f_disc_amt').val(d.disc_amt??'');$('#f_total').val(d.total??'');$('#f_currency').val(d.currency??'');$('#f_status').val(d.status??'DRAFT');$('#f_term').val(d.term??'');$('#f_sales').val(d.sales??'');$('#f_si').val(d.si_returned??'');$('#f_note').val(d.note??'');modal.find('.modal-title').text('Edit Return');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus return ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush