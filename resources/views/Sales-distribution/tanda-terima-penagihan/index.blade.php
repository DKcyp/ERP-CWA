@extends('layouts.layout')
@section('title','Tanda Terima Penagihan')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari TTP, kolektor, atau customer...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="SENT">Sent</option><option value="PARTIAL">Partial</option><option value="COLLECTED">Collected</option><option value="DONE">Done</option><option value="CANCELED">Canceled</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah TTP</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>TTP No</th><th class="text-center">TTP Date</th><th>Collector Name</th><th>Customer ID</th><th class="text-center">Invoice Count</th><th class="text-end">Total Amount</th><th class="text-center">Due Date</th><th class="text-center">Status</th><th>Note</th><th style="width:100px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah TTP</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label fw-semibold">TTP No <span class="text-danger">*</span></label><input type="text" class="form-control" name="ttp_no" id="f_ttp_no" maxlength="50"></div>
                    <div class="col-6"><label class="form-label fw-semibold">TTP Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="ttp_date" id="f_ttp_date"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Collector Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="collector_name" id="f_collector" maxlength="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="customer_id" id="f_customer" maxlength="50"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Invoice Count <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_inv_count" id="f_inv_count" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Total Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_amount" id="f_amount" min="0"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="due_date" id="f_due_date"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="DRAFT">Draft</option><option value="SENT">Sent</option><option value="PARTIAL">Partial</option><option value="COLLECTED">Collected</option><option value="DONE">Done</option><option value="CANCELED">Canceled</option></select></div>
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
const tableUrl="{{route('tanda-terima-penagihan.table')}}",storeUrl="{{route('tanda-terima-penagihan.store')}}",showUrl="{{route('tanda-terima-penagihan.show','__ID__')}}",updateUrl="{{route('tanda-terima-penagihan.update','__ID__')}}",deleteUrl="{{route('tanda-terima-penagihan.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'ttp_no',name:'ttp_no'},{data:'ttp_date_fmt',name:'ttp_date',className:'text-center'},{data:'collector_name',name:'collector_name'},{data:'customer_id',name:'customer_id'},{data:'total_inv_count',name:'total_inv_count',className:'text-center'},{data:'total_amount_fmt',name:'total_amount',className:'text-end'},{data:'due_date_fmt',name:'due_date',className:'text-center'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'note',name:'note',className:'text-truncate',render:function(d){return d||'-'}},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah TTP');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_ttp_no').val(d.ttp_no??'');$('#f_ttp_date').val(d.ttp_date??'');$('#f_collector').val(d.collector_name??'');$('#f_customer').val(d.customer_id??'');$('#f_inv_count').val(d.total_inv_count??'');$('#f_amount').val(d.total_amount??'');$('#f_due_date').val(d.due_date??'');$('#f_status').val(d.status??'DRAFT');$('#f_note').val(d.note??'');modal.find('.modal-title').text('Edit TTP');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus TTP ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush