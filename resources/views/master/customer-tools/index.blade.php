@extends('layouts.layout')
@section('title', 'Customer Tools')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari tool atau serial number...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="Dipinjam">Dipinjam</option><option value="Dikembalikan">Dikembalikan</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Tool</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center" style="width:40px;">No</th><th>Customer ID</th><th>Tool</th><th>Serial Number</th><th class="text-center">Qty</th><th>Kondisi</th><th class="text-center">Tgl Pinjam</th><th class="text-center">Status</th><th style="width:100px;" class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Customer Tool</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label for="f_customer_id" class="form-label fw-semibold">Customer ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_customer_id" name="customer_id" placeholder="Customer ID" maxlength="50"></div>
                    <div class="col-6"><label for="f_tool_name" class="form-label fw-semibold">Tool Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_tool_name" name="tool_name" placeholder="Nama alat" maxlength="200"></div>
                    <div class="col-4"><label for="f_serial" class="form-label fw-semibold">Serial Number</label><input type="text" class="form-control" id="f_serial" name="serial_number" placeholder="Serial number" maxlength="100"></div>
                    <div class="col-4"><label for="f_qty" class="form-label fw-semibold">Qty</label><input type="number" class="form-control" id="f_qty" name="qty" placeholder="0" min="0"></div>
                    <div class="col-4"><label for="f_condition" class="form-label fw-semibold">Kondisi</label><input type="text" class="form-control" id="f_condition" name="condition" placeholder="Baik/Rusak" maxlength="50"></div>
                    <div class="col-4"><label for="f_loan_date" class="form-label fw-semibold">Tgl Pinjam</label><input type="date" class="form-control" id="f_loan_date" name="loan_date"></div>
                    <div class="col-4"><label for="f_status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label><select class="form-select" id="f_status" name="status"><option value="Dipinjam">Dipinjam</option><option value="Dikembalikan">Dikembalikan</option></select></div>
                    <div class="col-12"><label for="f_note" class="form-label fw-semibold">Note</label><textarea class="form-control" id="f_note" name="note" rows="2" placeholder="Catatan tambahan"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('customer-tools.table')}}",storeUrl="{{route('customer-tools.store')}}",showUrl="{{route('customer-tools.show','__ID__')}}",updateUrl="{{route('customer-tools.update','__ID__')}}",deleteUrl="{{route('customer-tools.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'customer_id',name:'customer_id'},{data:'tool_name',name:'tool_name'},{data:'serial_number',name:'serial_number'},{data:'qty',name:'qty',className:'text-center'},{data:'condition',name:'condition'},{data:'loan_date_fmt',name:'loan_date',className:'text-center'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search,#filter-status').on('keyup change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Customer Tool');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_customer_id').val(d.customer_id??'');$('#f_tool_name').val(d.tool_name??'');$('#f_serial').val(d.serial_number??'');$('#f_qty').val(d.qty??0);$('#f_condition').val(d.condition??'');$('#f_loan_date').val(d.loan_date??'');$('#f_status').val(d.status??'Dipinjam');$('#f_note').val(d.note??'');modal.find('.modal-title').text('Edit Customer Tool');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus tool ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush
