@extends('layouts.layout')
@section('title','SPPBJ - Surat Perintah Pembuatan Barang Jadi (CM)')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="SPKP Ref, Batch, Product..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Keputusan</label><select class="form-select form-select-sm" id="filter-keputusan"><option value="all">Semua</option><option value="Approve">Approve</option><option value="Reject">Reject</option><option value="Rework">Rework</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah SPPBJ</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>No. SPKP Ref</th><th class="text-center">Date</th><th>Batch No</th><th>Product Name</th><th class="text-center">Proses CM</th><th class="text-center">Selesai CM</th><th>Machine</th><th class="text-center">Status QC</th><th class="text-center">Keputusan</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold" id="modal-title">Tambah SPPBJ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="form-id">
        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-1"></i>Header Info</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-3"><label class="form-label fw-semibold small">Production ID</label><input type="text" class="form-control" id="form-prod-id" placeholder="PRD-LST-XXXX"></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">No. SPKP Ref <span class="text-danger">*</span></label><input type="text" class="form-control" id="form-jadwal" placeholder="SPKP-XXXXXX-XXX"></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-date"></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Created By</label><input type="text" class="form-control" id="form-created-by" placeholder="Nama pembuat"></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3"><label class="form-label fw-semibold small">No. Batch</label><input type="text" class="form-control" id="form-batch" placeholder="BN-XXXX"></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Product Name <span class="text-danger">*</span></label><select class="form-select" id="form-product"><option value="Wall Paint White 20L">Wall Paint White 20L</option><option value="Wall Paint Cream 10L">Wall Paint Cream 10L</option><option value="Primer Grey 5L">Primer Grey 5L</option><option value="Top Coat Clear 15L">Top Coat Clear 15L</option><option value="Cat Ekonomis 5L">Cat Ekonomis 5L</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Machine <span class="text-danger">*</span></label><select class="form-select" id="form-machine"><option>CM Machine 1</option><option>CM Machine 2</option><option>CM Machine 3</option><option>CM Machine 4</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Tipe Produk <span class="text-danger">*</span></label><select class="form-select" id="form-tipe"><option>Emulsi Acrylic</option><option>Primer</option><option>Top Coat</option><option>Economy</option></select></div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><label class="form-label fw-semibold small">Formulasi CM</label><input type="text" class="form-control" id="form-formulasi" placeholder="CM-XX-XXX"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Basis</label><select class="form-select" id="form-basis"><option>Water Based</option><option>Solvent Based</option></select></div>
        </div>

        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-palette me-1"></i>Data Grid Pigmen & Pasta Warna</h6>
        <div class="table-responsive mb-3"><table class="table table-bordered table-sm" id="items-table">
            <thead class="table-light"><tr><th>Bahan Baku (Pasta/Pigmen)</th><th class="text-end" style="width:100px">Required</th><th class="text-end" style="width:100px">Production</th><th class="text-end" style="width:100px">STBJ</th><th class="text-center" style="width:100px">QC Warna</th><th class="text-end" style="width:90px">Adj</th><th style="width:40px"></th></tr></thead>
            <tbody id="items-body"></tbody>
        </table></div>
        <button type="button" class="btn btn-outline-primary btn-sm mb-4" id="btn-add-item"><i class="bi bi-plus me-1"></i>Tambah Baris</button>

        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-clock me-1"></i>Waktu & Keputusan</h6>
        <div class="row g-3 mb-3">
            <div class="col-md-3"><label class="form-label fw-semibold small">Proses CM</label><input type="time" class="form-control" id="form-proses-cm"></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">Selesai CM</label><input type="time" class="form-control" id="form-selesai-cm"></div>
            <div class="col-md-6"><label class="form-label fw-semibold small">Notes</label><input type="text" class="form-control" id="form-notes" placeholder="Catatan..."></div>
        </div>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">Keputusan <span class="text-danger">*</span></label><div class="d-flex gap-3 mt-1"><div class="form-check"><input class="form-check-input" type="radio" name="keputusan" id="kpt-approve" value="Approve" checked><label class="form-check-label" for="kpt-approve"><span class="badge bg-success">Approve</span></label></div><div class="form-check"><input class="form-check-input" type="radio" name="keputusan" id="kpt-reject" value="Reject"><label class="form-check-label" for="kpt-reject"><span class="badge bg-danger">Reject</span></label></div><div class="form-check"><input class="form-check-input" type="radio" name="keputusan" id="kpt-rework" value="Rework"><label class="form-check-label" for="kpt-rework"><span class="badge bg-warning text-dark">Rework</span></label></div></div></div>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="button" class="btn btn-primary" id="btn-save"><i class="bi bi-check-lg me-1"></i>Simpan</button></div>
</div></div></div>

<div class="modal fade" id="detailModal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold"><i class="bi bi-eye me-1"></i>Detail SPPBJ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3"><small class="text-muted">Production ID</small><p class="fw-semibold mb-0" id="dt-prod-id">-</p></div>
            <div class="col-md-3"><small class="text-muted">No. SPKP Ref</small><p class="fw-semibold mb-0" id="dt-jadwal">-</p></div>
            <div class="col-md-3"><small class="text-muted">Date</small><p class="fw-semibold mb-0" id="dt-date">-</p></div>
            <div class="col-md-3"><small class="text-muted">Created By</small><p class="fw-semibold mb-0" id="dt-created-by">-</p></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3"><small class="text-muted">Batch No</small><p class="fw-semibold mb-0" id="dt-batch">-</p></div>
            <div class="col-md-3"><small class="text-muted">Product Name</small><p class="fw-semibold mb-0" id="dt-product">-</p></div>
            <div class="col-md-3"><small class="text-muted">Machine</small><p class="fw-semibold mb-0" id="dt-machine">-</p></div>
            <div class="col-md-3"><small class="text-muted">Tipe Produk</small><p class="fw-semibold mb-0" id="dt-tipe">-</p></div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3"><small class="text-muted">Formulasi CM</small><p class="fw-semibold mb-0" id="dt-formulasi">-</p></div>
            <div class="col-md-3"><small class="text-muted">Basis</small><p class="fw-semibold mb-0" id="dt-basis">-</p></div>
            <div class="col-md-3"><small class="text-muted">Proses CM</small><p class="fw-semibold mb-0" id="dt-proses">-</p></div>
            <div class="col-md-3"><small class="text-muted">Selesai CM</small><p class="fw-semibold mb-0" id="dt-selesai">-</p></div>
        </div>
        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-palette me-1"></i>Data Pigmen & Pasta Warna</h6>
        <div class="table-responsive mb-3"><table class="table table-bordered table-sm"><thead class="table-light"><tr><th>Bahan Baku</th><th class="text-end">Required</th><th class="text-end">Production</th><th class="text-end">STBJ</th><th class="text-center">QC Warna</th><th class="text-end">Adj</th></tr></thead><tbody id="dt-items-body"></tbody></table></div>
        <div class="row g-3">
            <div class="col-md-6"><small class="text-muted">Notes</small><p class="fw-semibold mb-0" id="dt-notes">-</p></div>
            <div class="col-md-3"><small class="text-muted">Status QC</small><p class="mb-0" id="dt-status">-</p></div>
            <div class="col-md-3"><small class="text-muted">Keputusan</small><p class="mb-0" id="dt-keputusan">-</p></div>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
</div></div></div>

<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-danger text-white"><h5 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i>Konfirmasi Hapus</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><p class="mb-0">Yakin ingin menghapus SPPBJ <strong id="delete-name"></strong>?</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-danger" id="btn-confirm-delete"><i class="bi bi-trash me-1"></i>Hapus</button></div>
</div></div></div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
let deleteId=null;

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('production-process-sppbj.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_keputusan=$('#filter-keputusan').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'jadwal_ref',name:'jadwal_ref'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'no_batch',name:'no_batch'},
{data:'product_name',name:'product_name'},
{data:'proses_cm',name:'proses_cm',className:'text-center'},
{data:'selesai_cm',name:'selesai_cm',className:'text-center'},
{data:'machine',name:'machine'},
{data:'status_qc',name:'status_qc',className:'text-center'},
{data:'keputusan_badge',name:'keputusan',orderable:false,searchable:false,className:'text-center'},
{data:'action',orderable:false,searchable:false,className:'text-center'}
]});

function renderItems(items){
    const $b=$('#items-body');$b.empty();
    const mats=items||[
        {bahan_baku:'Pasta Putih',required_qty:50,production_qty:48,stbj_realization:47,hasil_qc_warna:'96%',adjustment:-3},
        {bahan_baku:'Pasta Krem',required_qty:30,production_qty:30,stbj_realization:29,hasil_qc_warna:'98%',adjustment:-1},
        {bahan_baku:'Pigment Oxide Red',required_qty:15,production_qty:16,stbj_realization:15,hasil_qc_warna:'95%',adjustment:1},
    ];
    mats.forEach(function(m){
        $b.append(`<tr><td><input type="text" class="form-control form-control-sm item-bahan" value="${m.bahan_baku}"></td><td><input type="number" class="form-control form-control-sm item-req text-end" value="${m.required_qty}"></td><td><input type="number" class="form-control form-control-sm item-prod text-end" value="${m.production_qty}"></td><td><input type="number" class="form-control form-control-sm item-stbj text-end" value="${m.stbj_realization}"></td><td><input type="text" class="form-control form-control-sm item-qc text-center" value="${m.hasil_qc_warna}"></td><td><input type="number" class="form-control form-control-sm item-adj text-end" value="${m.adjustment}"></td><td><button type="button" class="btn btn-outline-danger btn-sm" onclick="$(this).closest(\'tr\').remove()"><i class="bi bi-x"></i></button></td></tr>`);
    });
}

function getItems(){
    const items=[];
    $('#items-body tr').each(function(){
        items.push({bahan_baku:$(this).find('.item-bahan').val(),required_qty:parseInt($(this).find('.item-req').val())||0,production_qty:parseInt($(this).find('.item-prod').val())||0,stbj_realization:parseInt($(this).find('.item-stbj').val())||0,hasil_qc_warna:$(this).find('.item-qc').val(),adjustment:parseInt($(this).find('.item-adj').val())||0});
    });
    return items;
}

$('#btn-add-item').on('click',function(){$('#items-body').append(`<tr><td><input type="text" class="form-control form-control-sm item-bahan" placeholder="Nama pasta/pigmen"></td><td><input type="number" class="form-control form-control-sm item-req text-end" value="0"></td><td><input type="number" class="form-control form-control-sm item-prod text-end" value="0"></td><td><input type="number" class="form-control form-control-sm item-stbj text-end" value="0"></td><td><input type="text" class="form-control form-control-sm item-qc text-center" value="0%"></td><td><input type="number" class="form-control form-control-sm item-adj text-end" value="0"></td><td><button type="button" class="btn btn-outline-danger btn-sm" onclick="$(this).closest(\'tr\').remove()"><i class="bi bi-x"></i></button></td></tr>`);});

$('#btn-add').on('click',function(){
    $('#modal-title').text('Tambah SPPBJ');$('#form-id').val('');
    $('#form-prod-id').val('');$('#form-jadwal').val('');$('#form-date').val(new Date().toISOString().slice(0,10));
    $('#form-created-by').val('');$('#form-batch').val('');
    $('#form-product').val('Wall Paint White 20L');$('#form-machine').val('CM Machine 1');
    $('#form-tipe').val('Emulsi Acrylic');$('#form-formulasi').val('');$('#form-basis').val('Water Based');
    $('#form-proses-cm').val('');$('#form-selesai-cm').val('');
    $('#form-notes').val('');$('#kpt-approve').prop('checked',true);
    renderItems(null);new bootstrap.Modal('#formModal').show();
});

function editRecord(id){$.get('/production-process-sppbj/'+id,function(d){
    $('#modal-title').text('Edit SPPBJ');$('#form-id').val(d.id);
    $('#form-prod-id').val(d.production_id);$('#form-jadwal').val(d.jadwal_ref);
    $('#form-date').val(d.date);$('#form-created-by').val(d.created_by);
    $('#form-batch').val(d.no_batch);$('#form-product').val(d.product_name);
    $('#form-machine').val(d.machine);$('#form-tipe').val(d.tipe_produk);
    $('#form-formulasi').val(d.formulasi);$('#form-basis').val(d.basis);
    $('#form-proses-cm').val(d.proses_cm);$('#form-selesai-cm').val(d.selesai_cm);
    $('#form-notes').val(d.notes||'');
    $('input[name=keputusan][value="'+d.keputusan+'"]').prop('checked',true);
    renderItems(d.items||[]);new bootstrap.Modal('#formModal').show();
});}

function detailRecord(id){$.get('/production-process-sppbj/'+id,function(d){
    $('#dt-prod-id').text(d.production_id||'-');$('#dt-jadwal').text(d.jadwal_ref||'-');
    $('#dt-date').text(d.date||'-');$('#dt-created-by').text(d.created_by||'-');
    $('#dt-batch').text(d.no_batch||'-');$('#dt-product').text(d.product_name||'-');
    $('#dt-machine').text(d.machine||'-');$('#dt-tipe').text(d.tipe_produk||'-');
    $('#dt-formulasi').text(d.formulasi||'-');$('#dt-basis').text(d.basis||'-');
    $('#dt-proses').text(d.proses_cm||'-');$('#dt-selesai').text(d.selesai_cm||'-');
    $('#dt-notes').text(d.notes||'-');
    const kpt=d.keputusan||'';
    const kptCls=kpt==='Approve'?'bg-success':(kpt==='Reject'?'bg-danger':(kpt==='Rework'?'bg-warning text-dark':'bg-secondary'));
    $('#dt-keputusan').html('<span class="badge '+kptCls+'">'+kpt+'</span>');
    const stCls=d.status_qc==='Completed'?'bg-success':(d.status_qc==='Rejected'?'bg-danger':'bg-warning text-dark');
    $('#dt-status').html('<span class="badge '+stCls+'">'+(d.status_qc||'-')+'</span>');
    const $b=$('#dt-items-body');$b.empty();
    (d.items||[]).forEach(function(m){
        $b.append('<tr><td>'+m.bahan_baku+'</td><td class="text-end">'+m.required_qty+'</td><td class="text-end">'+m.production_qty+'</td><td class="text-end">'+m.stbj_realization+'</td><td class="text-center">'+m.hasil_qc_warna+'</td><td class="text-end">'+m.adjustment+'</td></tr>');
    });
    new bootstrap.Modal('#detailModal').show();
});}

$('#btn-save').on('click',function(){
    const id=$('#form-id').val();
    const payload={production_id:$('#form-prod-id').val(),jadwal_ref:$('#form-jadwal').val(),no_batch:$('#form-batch').val(),date:$('#form-date').val(),created_by:$('#form-created-by').val(),product_name:$('#form-product').val(),proses_cm:$('#form-proses-cm').val(),selesai_cm:$('#form-selesai-cm').val(),machine:$('#form-machine').val(),tipe_produk:$('#form-tipe').val(),formulasi:$('#form-formulasi').val(),basis:$('#form-basis').val(),notes:$('#form-notes').val(),keputusan:$('input[name=keputusan]:checked').val(),items:JSON.stringify(getItems())};
    if(!payload.date||!payload.product_name||!payload.machine||!payload.tipe_produk){alert('Lengkapi field wajib.');return;}
    const url=id?'/production-process-sppbj/'+id:'{{route("production-process-sppbj.store")}}';
    $.ajax({url:url,type:'POST',data:{...payload,_method:id?'PUT':'POST'},success:function(r){bootstrap.Modal.getInstance('#formModal').hide();tbl.ajax.reload(null,false);alert(r.message)},error:function(e){alert(e.responseJSON?.error||'Terjadi kesalahan.')}});
});

function approveRecord(id){if(!confirm('Approve SPPBJ ini?'))return;$.ajax({url:'/production-process-sppbj/'+id+'/approve',type:'POST',data:{_token:csrf},success:function(r){tbl.ajax.reload(null,false);alert(r.message)}});}
function rejectRecord(id){if(!confirm('Reject SPPBJ ini?'))return;$.ajax({url:'/production-process-sppbj/'+id+'/reject',type:'POST',data:{_token:csrf},success:function(r){tbl.ajax.reload(null,false);alert(r.message)}});}
function deleteRecord(id){deleteId=id;$('#delete-name').text('');new bootstrap.Modal('#deleteModal').show();}
$('#btn-confirm-delete').on('click',function(){$.ajax({url:'/production-process-sppbj/'+deleteId,type:'POST',data:{_method:'DELETE'},success:function(r){bootstrap.Modal.getInstance('#deleteModal').hide();tbl.ajax.reload(null,false);alert(r.message)}});});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-keputusan').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-keputusan').val('all');tbl.ajax.reload()});
</script>
@endpush