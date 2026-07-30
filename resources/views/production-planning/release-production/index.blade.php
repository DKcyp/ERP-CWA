@extends('layouts.layout')
@section('title','Release Production')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Cari Production ID, Batch, User..."></div>
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-toggle-on me-1"></i>Status</label><select class="form-select" id="filter-status"><option value="all">Semua</option><option value="DRAFT">Draft</option><option value="QC_PENDING">QC Pending</option><option value="APPROVED">Approved</option><option value="HOLD">Hold</option><option value="REJECTED">Rejected</option><option value="RELEASED">Released</option></select></div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center">No</th><th>Production ID</th><th>User</th><th>Tanggal</th><th>Batch No</th><th>Warehouse Target</th><th class="text-center">Status</th><th>QC Notes</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

{{-- Detail Modal --}}
<div class="modal fade" id="modal-detail" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Detail QC & Release</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <div class="row g-3 mb-4">
                <div class="col-md-4"><label class="form-label fw-semibold text-muted small">Production ID</label><div id="d_production_id" class="fw-bold">-</div></div>
                <div class="col-md-4"><label class="form-label fw-semibold text-muted small">Batch No</label><div id="d_batch_no" class="fw-bold">-</div></div>
                <div class="col-md-4"><label class="form-label fw-semibold text-muted small">Status</label><div id="d_status">-</div></div>
                <div class="col-md-4"><label class="form-label fw-semibold text-muted small">User</label><div id="d_user">-</div></div>
                <div class="col-md-4"><label class="form-label fw-semibold text-muted small">Tanggal</label><div id="d_tanggal">-</div></div>
                <div class="col-md-4"><label class="form-label fw-semibold text-muted small">Warehouse Target</label><div id="d_warehouse_target">-</div></div>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold text-muted small">QC Notes</label><div id="d_qc_notes" class="p-3 bg-light rounded" style="min-height:60px;">-</div></div>
            <div class="d-flex gap-2 justify-content-end" id="action-buttons">
                <button type="button" class="btn btn-outline-warning" id="btn-hold"><i class="bi bi-pause-circle me-1"></i>Hold</button>
                <button type="button" class="btn btn-outline-danger" id="btn-reject"><i class="bi bi-x-circle me-1"></i>Reject</button>
                <button type="button" class="btn btn-success" id="btn-approve-release"><i class="bi bi-check-circle me-1"></i>Approve & Release</button>
            </div>
            <hr>
            <div id="release-form" style="display:none;">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Gudang Tujuan <span class="text-danger">*</span></label><select class="form-select" id="f_warehouse_target"><option value="Gudang Bahan Jadi">Gudang Bahan Jadi</option><option value="Gudang Utama">Gudang Utama</option><option value="Gudang Cabang">Gudang Cabang</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">QC Notes (Update)</label><textarea class="form-control" id="f_qc_notes_release" rows="2"></textarea></div>
                </div>
                <div class="d-flex gap-2 justify-content-end mt-3"><button type="button" class="btn btn-success" id="btn-confirm-release"><i class="bi bi-check-lg me-1"></i>Konfirmasi Release</button></div>
            </div>
            <div id="hold-form" style="display:none;">
                <div class="mb-3"><label class="form-label fw-semibold">Alasan Hold</label><textarea class="form-control" id="f_qc_notes_hold" rows="3" placeholder="Masukkan alasan hold..."></textarea></div>
                <div class="d-flex gap-2 justify-content-end"><button type="button" class="btn btn-warning" id="btn-confirm-hold"><i class="bi bi-pause-lg me-1"></i>Konfirmasi Hold</button></div>
            </div>
            <div id="reject-form" style="display:none;">
                <div class="mb-3"><label class="form-label fw-semibold">Alasan Reject</label><textarea class="form-control" id="f_qc_notes_reject" rows="3" placeholder="Masukkan alasan reject..."></textarea></div>
                <div class="d-flex gap-2 justify-content-end"><button type="button" class="btn btn-danger" id="btn-confirm-reject"><i class="bi bi-x-lg me-1"></i>Konfirmasi Reject</button></div>
            </div>
        </div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('release-production.table')}}",showUrl="{{route('release-production.show','__ID__')}}",statusUrl="{{route('release-production.status','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

let currentId=null,currentStatus='';

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){d.filter_search=$('#filter-search').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'production_id',name:'production_id'},{data:'user',name:'user'},{data:'tanggal_fmt',name:'tanggal',className:'text-center'},
{data:'batch_no',name:'batch_no',render:function(d){return d||'-'}},{data:'warehouse_target',name:'warehouse_target',render:function(d){return d||'-'}},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'qc_notes',name:'qc_notes',render:function(d){return d?'<span title="'+d.replace(/"/g,'&quot;')+'">'+d.substring(0,60)+(d.length>60?'...':'')+'</span>':'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-center'}]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-status').val('all');tbl.ajax.reload()});

function hideForms(){$('#release-form,#hold-form,#reject-form').hide();$('#action-buttons').show()}

function openDetail(id){
    currentId=id;hideForms();
    $.get(showUrl.replace('__ID__',id)).done(function(r){
        const d=r.data||{};currentStatus=d.status||'DRAFT';
        $('#d_production_id').text(d.production_id||'-');$('#d_batch_no').text(d.batch_no||'-');$('#d_user').text(d.user||'-');$('#d_tanggal').text(d.tanggal||'-');
        $('#d_warehouse_target').text(d.warehouse_target||'- (Belum ditentukan)');$('#d_qc_notes').text(d.qc_notes||'Belum ada catatan QC');
        const smap={DRAFT:'bg-secondary',QC_PENDING:'bg-warning text-dark',APPROVED:'bg-info text-dark',HOLD:'bg-danger',REJECTED:'bg-dark',RELEASED:'bg-success'};
        const slmap={DRAFT:'Draft',QC_PENDING:'QC Pending',APPROVED:'Approved',HOLD:'Hold',REJECTED:'Rejected',RELEASED:'Released'};
        $('#d_status').html('<span class="badge '+(smap[currentStatus]||'bg-secondary')+'">'+(slmap[currentStatus]||currentStatus)+'</span>');
        if(['QC_PENDING','APPROVED','DRAFT'].includes(currentStatus)){$('#action-buttons').show()}else{$('#action-buttons').hide()}
        $('#modal-detail').modal('show');
    }).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})});
}

$('#table-data').on('click','.btn-release,.btn-detail',function(){openDetail($(this).data('id'))});
$('#table-data').on('click','.btn-hold',function(){currentId=$(this).data('id');currentStatus='HOLD';hideForms();$.get(showUrl.replace('__ID__',currentId)).done(function(r){const d=r.data||{};$('#d_production_id').text(d.production_id||'-');$('#d_batch_no').text(d.batch_no||'-');$('#d_user').text(d.user||'-');$('#d_tanggal').text(d.tanggal||'-');$('#d_warehouse_target').text(d.warehouse_target||'-');$('#d_qc_notes').text(d.qc_notes||'');$('#d_status').html('<span class="badge bg-danger">Hold</span>');$('#action-buttons').hide();$('#hold-form').show();$('#modal-detail').modal('show')})});
$('#table-data').on('click','.btn-reject',function(){currentId=$(this).data('id');currentStatus='REJECTED';hideForms();$.get(showUrl.replace('__ID__',currentId)).done(function(r){const d=r.data||{};$('#d_production_id').text(d.production_id||'-');$('#d_batch_no').text(d.batch_no||'-');$('#d_user').text(d.user||'-');$('#d_tanggal').text(d.tanggal||'-');$('#d_warehouse_target').text(d.warehouse_target||'-');$('#d_qc_notes').text(d.qc_notes||'');$('#d_status').html('<span class="badge bg-dark">Rejected</span>');$('#action-buttons').hide();$('#reject-form').show();$('#modal-detail').modal('show')})});

$('#btn-approve-release').on('click',function(){hideForms();$('#release-form').show()});
$('#btn-hold').on('click',function(){hideForms();$('#hold-form').show()});
$('#btn-reject').on('click',function(){hideForms();$('#reject-form').show()});

function doUpdate(status,notes,warehouse){
    $.ajax({url:statusUrl.replace('__ID__',currentId),method:'POST',data:{_method:'PUT',status:status,qc_notes:notes||'',warehouse_target:warehouse||''},dataType:'json',success:function(d){Swal.fire({icon:'success',title:d.message,timer:1500,showConfirmButton:false});$('#modal-detail').modal('hide');tbl.ajax.reload(null,false)},error:function(x){Swal.fire({icon:'error',title:'Gagal',text:(x.responseJSON||{}).message||'Terjadi kesalahan.'})}});
}

$('#btn-confirm-release').on('click',function(){const wh=$('#f_warehouse_target').val();const notes=$('#f_qc_notes_release').val();if(!wh){Swal.fire({icon:'warning',text:'Pilih gudang tujuan!'});return}doUpdate('RELEASED',notes,wh)});
$('#btn-confirm-hold').on('click',function(){doUpdate('HOLD',$('#f_qc_notes_hold').val(),'')});
$('#btn-confirm-reject').on('click',function(){doUpdate('REJECTED',$('#f_qc_notes_reject').val(),'')});
</script>
@endpush