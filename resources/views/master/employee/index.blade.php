@extends('layouts.layout')
@section('title','Employee Master')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari nama, user id, atau transit area...">
            </div>
            <div class="col-md-8 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Employee</button>
            </div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Employee ID</th><th>Name</th><th>User ID</th><th>Commission ID</th><th>Active</th><th>Transit Area</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Employee</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label fw-semibold">Employee ID <span class="text-danger">*</span></label><input type="text" class="form-control" name="employee_id" id="f_employee_id" maxlength="50"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="name" id="f_name" maxlength="200"></div>
                    <div class="col-6"><label class="form-label fw-semibold">User ID <span class="text-danger">*</span></label>
                        <select class="form-select" name="user_id" id="f_user_id"><option value="">-- Pilih User --</option>
                            @foreach($users as $u)<option value="{{$u['name']}}">{{$u['name']}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-6"><label class="form-label fw-semibold">Commission ID</label>
                        <select class="form-select" name="commission_id" id="f_commission_id"><option value="">-- Pilih --</option>
                            @foreach($commissions as $c)<option value="{{$c['code']}}">{{$c['code']}} - {{$c['name']}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-6"><label class="form-label fw-semibold">Transit Area</label>
                        <select class="form-select" name="transit_area" id="f_transit_area"><option value="">-- Pilih --</option>
                            @foreach($warehouses as $w)<option value="{{$w['name']}}">{{$w['code']}} - {{$w['name']}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-6"><label class="form-label fw-semibold">Active <span class="text-danger">*</span></label>
                        <div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="active" id="f_active" value="Y" checked><label class="form-check-label fw-semibold">Active</label></div>
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
const tableUrl="{{route('employee.table')}}",storeUrl="{{route('employee.store')}}",showUrl="{{route('employee.show','__ID__')}}",updateUrl="{{route('employee.update','__ID__')}}",deleteUrl="{{route('employee.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'employee_id',name:'employee_id'},{data:'name',name:'name'},{data:'user_id',name:'user_id'},
{data:'commission_id',name:'commission_id',render:function(d){return d||'-'}},
{data:'active_badge',name:'active'},{data:'transit_area',name:'transit_area',render:function(d){return d||'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});
$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');tbl.ajax.reload()});

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');$('#f_active').prop('checked',true);form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Employee');}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData(form[0]);
    if(!fd.has('active'))fd.append('active','N');
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',
        success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},
        error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Object.entries(r.errors).forEach(function([k,m]){const i=form.find('[name="'+k+'"]').first();i&&(i.addClass('is-invalid'),i.closest('.col-6').append('<div class="invalid-feedback">'+m[0]+'</div>'))})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);$('#f_employee_id').val(d.employee_id??'');$('#f_name').val(d.name??'');$('#f_user_id').val(d.user_id??'');$('#f_commission_id').val(d.commission_id??'');$('#f_transit_area').val(d.transit_area??'');$('#f_active').prop('checked',(d.active??'Y')==='Y');modal.find('.modal-title').text('Edit Employee');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});
$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus employee ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){const r3=x.responseJSON||{};Swal.fire({icon:'error',title:'Gagal',text:r3.message||'Tidak dapat menghapus data.'})}})})});
</script>
@endpush