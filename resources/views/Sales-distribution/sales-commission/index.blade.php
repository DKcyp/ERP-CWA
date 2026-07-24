@extends('layouts.layout')
@section('title','Sales Commission')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari comm no, salesman, atau periode...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="CALCULATED">Calculated</option><option value="APPROVED">Approved</option><option value="PAID">Paid</option><option value="REJECTED">Rejected</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Commission</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Comm No</th><th class="text-center">Date</th><th>Period</th><th>Salesman ID</th><th class="text-center">Base</th><th class="text-end">Target</th><th class="text-end">Achieved</th><th class="text-center">Rate</th><th class="text-end">Commission Paid</th><th class="text-center">Status</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Commission</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Comm No <span class="text-danger">*</span></label><input type="text" class="form-control" name="comm_no" id="f_comm_no" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Period <span class="text-danger">*</span></label><input type="text" class="form-control" name="period" id="f_period" maxlength="10" placeholder="2026-07"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Salesman ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="salesman_id" id="f_salesman" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Calculation Base</label><select class="form-select" name="calculation_base" id="f_base"><option value="Omset">Omset</option><option value="Pelunasan">Pelunasan</option></select></div>
                    <div class="col-4"><label class="form-label fw-semibold">Target Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="target_amount" id="f_target" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Achieved Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="achieved_amount" id="f_achieved" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Commission Rate % <span class="text-danger">*</span></label><input type="number" class="form-control" name="commission_rate" id="f_rate" min="0" max="100" step="0.01"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total Commission <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_commission_paid" id="f_total_comm" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="DRAFT">Draft</option><option value="CALCULATED">Calculated</option><option value="APPROVED">Approved</option><option value="PAID">Paid</option><option value="REJECTED">Rejected</option></select></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('sales-commission.table')}}",storeUrl="{{route('sales-commission.store')}}",showUrl="{{route('sales-commission.show','__ID__')}}",updateUrl="{{route('sales-commission.update','__ID__')}}",deleteUrl="{{route('sales-commission.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'comm_no',name:'comm_no'},{data:'date_fmt',name:'date',className:'text-center'},{data:'period',name:'period'},{data:'salesman_id',name:'salesman_id'},{data:'calculation_base_badge',name:'calculation_base',orderable:false,searchable:false,className:'text-center'},{data:'target_fmt',name:'target_amount',className:'text-end'},{data:'achieved_fmt',name:'achieved_amount',className:'text-end'},{data:'rate_fmt',name:'commission_rate',className:'text-center'},{data:'total_commission_fmt',name:'total_commission_paid',className:'text-end'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Commission');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_comm_no').val(d.comm_no??'');$('#f_date').val(d.date??'');$('#f_period').val(d.period??'');$('#f_salesman').val(d.salesman_id??'');$('#f_base').val(d.calculation_base??'Omset');$('#f_target').val(d.target_amount??'');$('#f_achieved').val(d.achieved_amount??'');$('#f_rate').val(d.commission_rate??'');$('#f_total_comm').val(d.total_commission_paid??'');$('#f_status').val(d.status??'DRAFT');modal.find('.modal-title').text('Edit Commission');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus commission ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush