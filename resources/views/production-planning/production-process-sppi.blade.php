@extends('layouts.layout')
@section('title','SPPI - Surat Perintah Penggunaan Insektisida / Bahan Penolong Khusus')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="SPPI No, Batch, Material..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Status</label><select class="form-select form-select-sm" id="filter-status"><option value="all">Semua</option><option value="Completed">Completed</option><option value="Pending QC">Pending QC</option><option value="Draft">Draft</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah SPPI</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>SPPI No</th><th class="text-center">Date</th><th>Batch No</th><th>Product Name</th><th>Material Name</th><th class="text-end">Target Dose</th><th class="text-end">Actual Dose</th><th class="text-center">UOM</th><th>Operator</th><th class="text-center">Status</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold" id="modal-title">Tambah SPPI</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="form-id">
        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-1"></i>Header Info</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">SPPI No</label><input type="text" class="form-control" id="form-sppi-no" value="(Auto-generated)" readonly></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-date"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Created By</label><input type="text" class="form-control" id="form-created-by" placeholder="Nama"></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">Ref Batch No</label><input type="text" class="form-control" id="form-batch" placeholder="BN-XXXX"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Product Name <span class="text-danger">*</span></label><select class="form-select" id="form-product"><option value="Wall Paint White 20L">Wall Paint White 20L</option><option value="Wall Paint Cream 10L">Wall Paint Cream 10L</option><option value="Primer Grey 5L">Primer Grey 5L</option><option value="Top Coat Clear 15L">Top Coat Clear 15L</option><option value="Cat Ekonomis 5L">Cat Ekonomis 5L</option></select></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Machine <span class="text-danger">*</span></label><select class="form-select" id="form-machine"><option>Mixer A-1</option><option>Mixer A-2</option><option>Mixer B-1</option><option>Mixer B-2</option><option>Mixer C-1</option></select></div>
        </div>

        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-droplet me-1"></i>Detail Dosis & Bahan</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">Material ID</label><input type="text" class="form-control" id="form-mat-id" placeholder="MAT-AD-XXX"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Material Name <span class="text-danger">*</span></label><select class="form-select" id="form-mat-name"><option value="Anti Jamur AG-200">Anti Jamur AG-200</option><option value="Biosida BACT-50">Biosida BACT-50</option><option value="Insektisida INSECT-10">Insektisida INSECT-10</option><option value="Anti Busuk AB-100">Anti Busuk AB-100</option><option value="Pengawet Khusus PK-30">Pengawet Khusus PK-30</option><option value="Defoamer Industrial DI-20">Defoamer Industrial DI-20</option><option value="Retarder Special RS-15">Retarder Special RS-15</option></select></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">UOM</label><select class="form-select" id="form-uom"><option value="Kg">Kg</option><option value="L">L</option></select></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">Target Dose Qty <span class="text-danger">*</span></label><input type="number" class="form-control" id="form-target" step="0.01" min="0"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Actual Dose Qty</label><input type="number" class="form-control" id="form-actual" step="0.01" min="0"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Mixing Time</label><input type="text" class="form-control" id="form-mixing" placeholder="15 menit"></div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><label class="form-label fw-semibold small">Operator Pelaksana</label><input type="text" class="form-control" id="form-operator" placeholder="Nama operator"></div>
            <div class="col-md-6"><label class="form-label fw-semibold small">Status</label><select class="form-select" id="form-status"><option value="Draft">Draft</option><option value="Pending QC">Pending QC</option><option value="Completed">Completed</option></select></div>
        </div>

        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-chat-left-text me-1"></i>Catatan</h6>
        <textarea class="form-control" id="form-notes" rows="2" placeholder="Catatan penggunaan bahan..."></textarea>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="button" class="btn btn-primary" id="btn-save"><i class="bi bi-check-lg me-1"></i>Simpan SPPI</button></div>
</div></div></div>

<div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold"><i class="bi bi-eye me-1"></i>Detail SPPI</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4"><small class="text-muted">SPPI No</small><p class="fw-semibold mb-0" id="dt-sppi-no">-</p></div>
            <div class="col-md-4"><small class="text-muted">Date</small><p class="fw-semibold mb-0" id="dt-date">-</p></div>
            <div class="col-md-4"><small class="text-muted">Created By</small><p class="fw-semibold mb-0" id="dt-created-by">-</p></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><small class="text-muted">Batch No</small><p class="fw-semibold mb-0" id="dt-batch">-</p></div>
            <div class="col-md-4"><small class="text-muted">Product Name</small><p class="fw-semibold mb-0" id="dt-product">-</p></div>
            <div class="col-md-4"><small class="text-muted">Machine</small><p class="fw-semibold mb-0" id="dt-machine">-</p></div>
        </div>
        <hr>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><small class="text-muted">Material ID</small><p class="fw-semibold mb-0" id="dt-mat-id">-</p></div>
            <div class="col-md-4"><small class="text-muted">Material Name</small><p class="fw-semibold mb-0" id="dt-mat-name">-</p></div>
            <div class="col-md-4"><small class="text-muted">UOM</small><p class="fw-semibold mb-0" id="dt-uom">-</p></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><small class="text-muted">Target Dose Qty</small><p class="fw-semibold mb-0" id="dt-target">-</p></div>
            <div class="col-md-4"><small class="text-muted">Actual Dose Qty</small><p class="fw-semibold mb-0" id="dt-actual">-</p></div>
            <div class="col-md-4"><small class="text-muted">Mixing Time</small><p class="fw-semibold mb-0" id="dt-mixing">-</p></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><small class="text-muted">Operator</small><p class="fw-semibold mb-0" id="dt-operator">-</p></div>
            <div class="col-md-4"><small class="text-muted">Status</small><p class="mb-0" id="dt-status">-</p></div>
        </div>
        <hr>
        <small class="text-muted">Notes</small><p class="fw-semibold mb-0" id="dt-notes">-</p>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
</div></div></div>

<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-danger text-white"><h5 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i>Konfirmasi Hapus</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><p class="mb-0">Yakin ingin menghapus SPPI <strong id="delete-name"></strong>?</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-danger" id="btn-confirm-delete"><i class="bi bi-trash me-1"></i>Hapus</button></div>
</div></div></div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
let deleteId=null;

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('production-process-sppi.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'sppi_no',name:'sppi_no'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'batch_no',name:'batch_no'},
{data:'product_name',name:'product_name'},
{data:'material_name',name:'material_name'},
{data:'target_fmt',name:'target_dose_qty',orderable:false,searchable:false,className:'text-end'},
{data:'actual_fmt',name:'actual_dose_qty',orderable:false,searchable:false,className:'text-end'},
{data:'uom',name:'uom',className:'text-center'},
{data:'operator',name:'operator'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'action',orderable:false,searchable:false,className:'text-center'}
]});

$('#btn-add').on('click',function(){
    $('#modal-title').text('Tambah SPPI');$('#form-id').val('');
    $('#form-sppi-no').val('(Auto-generated)');$('#form-date').val(new Date().toISOString().slice(0,10));
    $('#form-created-by').val('');$('#form-batch').val('');
    $('#form-product').val('Wall Paint White 20L');$('#form-machine').val('Mixer A-1');
    $('#form-mat-id').val('');$('#form-mat-name').val('Anti Jamur AG-200');
    $('#form-uom').val('Kg');$('#form-target').val('');$('#form-actual').val('');
    $('#form-mixing').val('');$('#form-operator').val('');$('#form-status').val('Draft');
    $('#form-notes').val('');new bootstrap.Modal('#formModal').show();
});

function editRecord(id){$.get('/production-process-sppi/'+id,function(d){
    $('#modal-title').text('Edit SPPI');$('#form-id').val(d.id);
    $('#form-sppi-no').val(d.sppi_no);$('#form-date').val(d.date);
    $('#form-created-by').val(d.created_by);$('#form-batch').val(d.batch_no);
    $('#form-product').val(d.product_name);$('#form-machine').val(d.machine);
    $('#form-mat-id').val(d.material_id);$('#form-mat-name').val(d.material_name);
    $('#form-uom').val(d.uom);$('#form-target').val(d.target_dose_qty);
    $('#form-actual').val(d.actual_dose_qty);$('#form-mixing').val(d.mixing_time);
    $('#form-operator').val(d.operator);$('#form-status').val(d.status);
    $('#form-notes').val(d.notes||'');new bootstrap.Modal('#formModal').show();
});}

function detailRecord(id){$.get('/production-process-sppi/'+id,function(d){
    $('#dt-sppi-no').text(d.sppi_no||'-');$('#dt-date').text(d.date||'-');
    $('#dt-created-by').text(d.created_by||'-');$('#dt-batch').text(d.batch_no||'-');
    $('#dt-product').text(d.product_name||'-');$('#dt-machine').text(d.machine||'-');
    $('#dt-mat-id').text(d.material_id||'-');$('#dt-mat-name').text(d.material_name||'-');
    $('#dt-uom').text(d.uom||'-');$('#dt-target').text(d.target_dose_qty||'-');
    $('#dt-actual').text(d.actual_dose_qty||'-');$('#dt-mixing').text(d.mixing_time||'-');
    $('#dt-operator').text(d.operator||'-');
    const st=d.status||'';
    const stCls=st==='Completed'?'bg-success':(st==='Pending QC'?'bg-warning text-dark':'bg-secondary');
    $('#dt-status').html('<span class="badge '+stCls+'">'+st+'</span>');
    $('#dt-notes').text(d.notes||'-');new bootstrap.Modal('#detailModal').show();
});}

$('#btn-save').on('click',function(){
    const id=$('#form-id').val();
    const payload={production_id:'',sppi_no:$('#form-sppi-no').val(),date:$('#form-date').val(),created_by:$('#form-created-by').val(),batch_no:$('#form-batch').val(),product_name:$('#form-product').val(),machine:$('#form-machine').val(),material_id:$('#form-mat-id').val(),material_name:$('#form-mat-name').val(),target_dose_qty:parseFloat($('#form-target').val())||0,actual_dose_qty:parseFloat($('#form-actual').val())||0,uom:$('#form-uom').val(),mixing_time:$('#form-mixing').val(),operator:$('#form-operator').val(),status:$('#form-status').val(),notes:$('#form-notes').val()};
    if(!payload.date||!payload.product_name||!payload.machine||!payload.material_name||!payload.target_dose_qty){alert('Lengkapi field wajib.');return;}
    const url=id?'/production-process-sppi/'+id:'{{route("production-process-sppi.store")}}';
    $.ajax({url:url,type:'POST',data:{...payload,_method:id?'PUT':'POST'},success:function(r){bootstrap.Modal.getInstance('#formModal').hide();tbl.ajax.reload(null,false);alert(r.message)},error:function(e){alert(e.responseJSON?.error||'Terjadi kesalahan.')}});
});

function deleteRecord(id){deleteId=id;$('#delete-name').text('');new bootstrap.Modal('#deleteModal').show();}
$('#btn-confirm-delete').on('click',function(){$.ajax({url:'/production-process-sppi/'+deleteId,type:'POST',data:{_method:'DELETE'},success:function(r){bootstrap.Modal.getInstance('#deleteModal').hide();tbl.ajax.reload(null,false);alert(r.message)}});});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-status').val('all');tbl.ajax.reload()});
</script>
@endpush