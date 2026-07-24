@extends('layouts.layout')
@section('title','Shipment Preparation')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari prep no, gudang, rute, atau armada...">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label>
                <select id="filter-status" class="form-select"><option value="all">Semua</option><option value="PLANNING">Planning</option><option value="LOADING">Loading</option><option value="DEPARTED">Departed</option><option value="ARRIVED">Arrived</option><option value="CANCELED">Canceled</option></select>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Prep</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr><th class="text-center">No</th><th>Prep No</th><th class="text-center">Date</th><th>Warehouse ID</th><th>DO List</th><th class="text-end">Total Weight</th><th class="text-end">Total Volume</th><th>Fleet/Vehicle Type</th><th>Route Area</th><th class="text-center">Status</th><th class="text-end">Aksi</th></tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Prep</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Prep No <span class="text-danger">*</span></label><input type="text" class="form-control" name="prep_no" id="f_prep_no" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" name="date" id="f_date"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Warehouse ID</label><input type="text" class="form-control" name="warehouse_id" id="f_wh" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total Weight (kg)</label><input type="number" class="form-control" name="total_weight" id="f_weight" min="0" step="0.1"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total Volume (m³)</label><input type="number" class="form-control" name="total_volume" id="f_volume" min="0" step="0.1"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Fleet/Vehicle Type</label><input type="text" class="form-control" name="fleet_type" id="f_fleet" maxlength="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Route Area</label><input type="text" class="form-control" name="route_area" id="f_route" maxlength="100"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" id="f_status"><option value="PLANNING">Planning</option><option value="LOADING">Loading</option><option value="DEPARTED">Departed</option><option value="ARRIVED">Arrived</option><option value="CANCELED">Canceled</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">DO List (JSON)</label><textarea class="form-control" name="do_list" id="f_do_list" rows="2" placeholder='["DO-001","DO-002"]'></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('shipment-preparation.table')}}",storeUrl="{{route('shipment-preparation.store')}}",showUrl="{{route('shipment-preparation.show','__ID__')}}",updateUrl="{{route('shipment-preparation.update','__ID__')}}",deleteUrl="{{route('shipment-preparation.destroy',['id'=>'__ID__'])}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'prep_no',name:'prep_no'},{data:'date_fmt',name:'date',className:'text-center'},{data:'warehouse_id',name:'warehouse_id'},{data:'do_list',name:'do_list',className:'text-truncate',render:function(d){return d||'-'}},{data:'total_weight_fmt',name:'total_weight',className:'text-end'},{data:'total_volume_fmt',name:'total_volume',className:'text-end'},{data:'fleet_type',name:'fleet_type'},{data:'route_area',name:'route_area'},{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Prep');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-6,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_prep_no').val(d.prep_no??'');$('#f_date').val(d.date??'');$('#f_wh').val(d.warehouse_id??'');$('#f_do_list').val(d.do_list??'');$('#f_weight').val(d.total_weight??'');$('#f_volume').val(d.total_volume??'');$('#f_fleet').val(d.fleet_type??'');$('#f_route').val(d.route_area??'');$('#f_status').val(d.status??'PLANNING');modal.find('.modal-title').text('Edit Prep');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus prep ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush