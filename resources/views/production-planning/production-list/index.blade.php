@extends('layouts.layout')
@section('title','Production List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Cari ID, Template, Batch..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-gear me-1"></i>Mesin</label><select class="form-select" id="filter-machine"><option value="all">Semua</option><option value="LINE-A1">LINE-A1</option><option value="LINE-A2">LINE-A2</option><option value="LINE-B1">LINE-B1</option><option value="LINE-B2">LINE-B2</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label><select class="form-select" id="filter-status"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="PLANNED">Planned</option><option value="IN_PROGRESS">In Progress</option><option value="QC_PENDING">QC Pending</option><option value="COMPLETED">Completed</option></select></div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Production Id</th><th>Template</th><th>Formulasi</th><th>Batch No</th><th>No. SPKP</th><th>Date</th><th>Mesin</th><th>Tipe</th><th>Group</th><th class="text-center">Qty Jadwal</th><th class="text-center">Status</th><th class="text-center">QC</th><th>Notes</th><th class="text-end">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Production List</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()"><div class="modal-body">@csrf<input type="hidden" id="data_id">
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-header" type="button"><i class="bi bi-info-circle me-1"></i>Header</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-formulasi" type="button"><i class="bi bi-flask me-1"></i>Formulasi & Material</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-hasil" type="button"><i class="bi bi-bar-chart me-1"></i>Hasil & Selisih</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-qc" type="button"><i class="bi bi-clipboard-check me-1"></i>QC & Keputusan</button></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-header">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label fw-semibold">Production Id <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_production_id" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_template_name" maxlength="100"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Formulasi <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_formulasi" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Basis</label><input type="text" class="form-control" id="f_basis" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Batch No</label><input type="text" class="form-control" id="f_batch_no" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">No. SPKP <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_no_spkp" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">No. Box Arsip</label><input type="text" class="form-control" id="f_no_box_arsip" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Tipe Product</label><select class="form-select" id="f_tipe_product"><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Product Group</label><input type="text" class="form-control" id="f_product_group" maxlength="100"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Reference</label><input type="text" class="form-control" id="f_reference" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Machine <span class="text-danger">*</span></label><select class="form-select" id="f_machine"><option value="LINE-A1">LINE-A1</option><option value="LINE-A2">LINE-A2</option><option value="LINE-B1">LINE-B1</option><option value="LINE-B2">LINE-B2</option></select></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Qty Jadwal <span class="text-danger">*</span></label><input type="number" class="form-control" id="f_qty_jadwal" min="0"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">FK</label><input type="text" class="form-control" id="f_fk" maxlength="50"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Jadwal</label><input type="date" class="form-control" id="f_jadwal"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Produksi</label><input type="date" class="form-control" id="f_produksi"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Recanning</label><input type="date" class="form-control" id="f_recanning"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label><select class="form-select" id="f_status"><option value="DRAFT">Draft</option><option value="PLANNED">Planned</option><option value="IN_PROGRESS">In Progress</option><option value="QC_PENDING">QC Pending</option><option value="COMPLETED">Completed</option></select></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">User Id</label><input type="text" class="form-control" id="f_user_id" maxlength="50"></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Notes</label><textarea class="form-control" id="f_notes" rows="2" maxlength="500"></textarea></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-formulasi">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-semibold">Stock Release (Kg)</label><input type="number" class="form-control" id="f_stock_release" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Stock Receive (Kg)</label><input type="number" class="form-control" id="f_stock_receive" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Total Material (Kg)</label><input type="number" class="form-control" id="f_total_material" min="0" step="0.01"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-hasil">
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label fw-semibold">Total Realisasi (Kg)</label><input type="number" class="form-control" id="f_total_realisasi" min="0" step="0.01"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Selisih (Kg)</label><input type="number" class="form-control" id="f_selisih" min="0" step="0.01"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Adj. Batch</label><input type="number" class="form-control" id="f_adj_batch" min="0" step="0.01"></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Adjustment (Kg)</label><input type="number" class="form-control" id="f_adjustment" min="0" step="0.01"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-qc">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label fw-semibold">QC Status</label><select class="form-select" id="f_qc"><option value="-">-</option><option value="PASS">PASS</option><option value="FAIL">FAIL</option><option value="PENDING">PENDING</option></select></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Kesimpulan</label><input type="text" class="form-control" id="f_kesimpulan" maxlength="500"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Keputusan</label><select class="form-select" id="f_keputusan"><option value="">-</option><option value="LULUS">LULUS</option><option value="TIDAK LULUS">TIDAK LULUS</option></select></div>
                    </div>
                </div>
            </div>
        </div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div></form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('production-list.table')}}",storeUrl="{{route('production-list.store')}}",showUrl="{{route('production-list.show','__ID__')}}",updateUrl="{{route('production-list.update','__ID__')}}",deleteUrl="{{route('production-list.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

const allFields=['production_id','template_name','formulasi','basis','qty_jadwal','fk','jadwal','produksi','recanning','batch_no','no_spkp','date','no_box_arsip','tipe_product','product_group','reference','machine','status','user_id','notes','stock_release','stock_receive','qc','adjustment','total_material','total_realisasi','selisih','adj_batch','kesimpulan','keputusan'];

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val();d.filter_machine=$('#filter-machine').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'production_id',name:'production_id'},{data:'template_name',name:'template_name'},{data:'formulasi',name:'formulasi'},
{data:'batch_no',name:'batch_no',render:function(d){return d||'-'}},{data:'no_spkp',name:'no_spkp'},
{data:'date_fmt',name:'date',className:'text-center'},{data:'machine',name:'machine'},
{data:'tipe_product',name:'tipe_product'},{data:'product_group',name:'product_group'},
{data:'qty_jadwal',name:'qty_jadwal',className:'text-center'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'qc_badge',name:'qc',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-status').on('change',function(){tbl.ajax.reload()});$('#filter-machine').on('change',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');$('#filter-machine').val('all');$('#filter-date-from').val('');$('#filter-date-to').val('');tbl.ajax.reload()});

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Production List');$('.nav-tabs .nav-link').removeClass('active');$('.nav-tabs .nav-link').first().addClass('active');$('.tab-pane').removeClass('show active');$('#tab-header').addClass('show active')}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){
    const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData();fd.append('_token',csrf);
    allFields.forEach(function(k){const el=document.getElementById('f_'+k);if(el)fd.append(k,el.value)});
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Swal.fire({icon:'error',title:'Validasi Gagal',text:Object.values(r.errors).flat().join('\n')})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);allFields.forEach(function(k){const el=document.getElementById('f_'+k);if(el)el.value=d[k]??''});modal.find('.modal-title').text('Edit Production List');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});

$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus data ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){Swal.fire({icon:'error',title:'Gagal',text:(x.responseJSON||{}).message||'Gagal menghapus.'})}})})});
</script>
@endpush