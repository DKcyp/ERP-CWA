@extends('layouts.layout')
@section('title','PMB - Penetapan & Monitoring Bonus')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah PMB</button>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="table-data">
                <thead class="table-light"><tr>
                    <th class="text-center">No</th><th>Period</th><th>Transit Area</th><th>Salesman ID</th>
                    <th class="text-end">Target Collection</th><th class="text-end">Achieved</th><th class="text-end">Incentive Rate</th>
                    <th class="text-end">Penalty</th><th class="text-end">Total Bonus</th><th>Status</th><th class="text-end">Aksi</th>
                </tr></thead>
            </table>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah PMB</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-4"><label class="form-label fw-semibold">Period <span class="text-danger">*</span></label><input type="text" class="form-control" name="period" id="f_period" maxlength="20" placeholder="MM/YYYY"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Transit Area</label><input type="text" class="form-control" name="transit_area" id="f_ta" maxlength="100"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Salesman ID</label><input type="text" class="form-control" name="salesman_id" id="f_sales" maxlength="50"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Target Collection <span class="text-danger">*</span></label><input type="number" class="form-control" name="target_collection" id="f_target" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Achieved Collection <span class="text-danger">*</span></label><input type="number" class="form-control" name="achieved_collection" id="f_achieved" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Incentive Rate (%) <span class="text-danger">*</span></label><input type="number" step="0.01" class="form-control" name="incentive_rate" id="f_rate" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Penalty Amount <span class="text-danger">*</span></label><input type="number" class="form-control" name="penalty_amount" id="f_penalty" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Total PMB Bonus <span class="text-danger">*</span></label><input type="number" class="form-control" name="total_pmb_bonus" id="f_bonus" min="0"></div>
                    <div class="col-4"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="f_status">
                            <option value="">-- Pilih --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                            <option value="Pending">Pending</option>
                        </select>
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
const tableUrl="{{route('pmb.table')}}",storeUrl="{{route('pmb.store')}}",showUrl="{{route('pmb.show','__ID__')}}",updateUrl="{{route('pmb.update','__ID__')}}",deleteUrl="{{route('pmb.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:tableUrl,columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'period',name:'period'},{data:'transit_area',name:'transit_area'},{data:'salesman_id',name:'salesman_id'},
{data:'target_collection_fmt',name:'target_collection',className:'text-end'},{data:'achieved_collection_fmt',name:'achieved_collection',className:'text-end'},{data:'incentive_rate_fmt',name:'incentive_rate',className:'text-end'},
{data:'penalty_amount_fmt',name:'penalty_amount',className:'text-end'},{data:'total_pmb_bonus_fmt',name:'total_pmb_bonus',className:'text-end'},{data:'status_badge',name:'status'},{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah PMB');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',function(){resetForm()});
window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;const fd=form.serializeArray();if(id)fd.push({name:'_method',value:'PUT'});
$.ajax({url,type:'POST',data:fd,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-4,.col-12').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};
$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_period').val(d.period??'');$('#f_ta').val(d.transit_area??'');$('#f_sales').val(d.salesman_id??'');$('#f_target').val(d.target_collection??'');$('#f_achieved').val(d.achieved_collection??'');$('#f_rate').val(d.incentive_rate??'');$('#f_penalty').val(d.penalty_amount??'');$('#f_bonus').val(d.total_pmb_bonus??'');$('#f_status').val(d.status??'');modal.find('.modal-title').text('Edit PMB');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus PMB ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush