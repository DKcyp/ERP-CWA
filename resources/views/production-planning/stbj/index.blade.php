@extends('layouts.layout')
@section('title','STBJ - Surat Tanda Barang Jadi')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-3"><div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="STBJ No, Prod ID, Batch..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Status</label><select class="form-select form-select-sm" id="filter-status"><option value="all">Semua</option><option value="Draft">Draft</option><option value="Issued">Issued</option><option value="Received">Received</option><option value="Verified">Verified</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah STBJ</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>STBJ No</th><th>Date</th><th>Production ID</th><th>Batch No</th><th>Product</th><th>From Line</th><th>To Warehouse</th><th class="text-center">Qty (Pcs)</th><th class="text-end">Weight (Kg)</th><th>Received By</th><th>Status</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold" id="modal-title">Tambah STBJ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="form-id">
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">STBJ No</label><input type="text" class="form-control" id="form-stbj-no" value="(Auto-generated)" readonly></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-date"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Status</label><select class="form-select" id="form-status"><option value="Draft">Draft</option><option value="Issued">Issued</option><option value="Received">Received</option><option value="Verified">Verified</option></select></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">Production ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="form-prod-id" placeholder="PRD-LST-XXXX"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Batch No <span class="text-danger">*</span></label><input type="text" class="form-control" id="form-batch" placeholder="BN-XXXX"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Product Name</label><input type="text" class="form-control" id="form-product-name" placeholder="Nama produk"></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">From Line <span class="text-danger">*</span></label><select class="form-select" id="form-line"><option value="LINE-A1">LINE-A1</option><option value="LINE-A2">LINE-A2</option><option value="LINE-B1">LINE-B1</option><option value="LINE-B2">LINE-B2</option><option value="LINE-C1">LINE-C1</option></select></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">To Warehouse <span class="text-danger">*</span></label><select class="form-select" id="form-warehouse"><option value="Gudang Jadi Bandung">Gudang Jadi Bandung</option><option value="Gudang Jadi Jakarta">Gudang Jadi Jakarta</option><option value="Gudang Jadi Surabaya">Gudang Jadi Surabaya</option></select></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Received By</label><input type="text" class="form-control" id="form-received-by" placeholder="Nama penerima"></div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4"><label class="form-label fw-semibold small">Total Qty (Pcs) <span class="text-danger">*</span></label><input type="number" class="form-control" id="form-pcs" min="1"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Total Weight (Kg) <span class="text-danger">*</span></label><input type="number" class="form-control" id="form-kg" min="0.1" step="0.1"></div>
            <div class="col-md-4"><label class="form-label fw-semibold small">Notes</label><input type="text" class="form-control" id="form-notes" placeholder="Catatan..."></div>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="button" class="btn btn-primary" id="btn-save"><i class="bi bi-check-lg me-1"></i>Simpan</button></div>
</div></div></div>

<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-danger text-white"><h5 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i>Konfirmasi Hapus</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><p class="mb-0">Yakin ingin menghapus STBJ <strong id="delete-name"></strong>?</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-danger" id="btn-confirm-delete"><i class="bi bi-trash me-1"></i>Hapus</button></div>
</div></div></div>

<div class="modal fade" id="printModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold"><i class="bi bi-printer me-1"></i>Cetak STBJ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body text-center">
        <div class="border rounded p-4 mb-3 bg-white">
            <h5 class="fw-bold mb-1">SURAT TANDA BARANG JADI</h5>
            <p class="text-muted mb-3" id="print-stbj-no">-</p>
            <div class="row text-start mb-3" id="print-details">
                <div class="col-6"><small class="text-muted">Date:</small><br><span id="print-date">-</span></div>
                <div class="col-6"><small class="text-muted">Production ID:</small><br><span id="print-prod-id">-</span></div>
                <div class="col-6"><small class="text-muted">Batch No:</small><br><span id="print-batch">-</span></div>
                <div class="col-6"><small class="text-muted">Product:</small><br><span id="print-product">-</span></div>
                <div class="col-6"><small class="text-muted">From Line:</small><br><span id="print-line">-</span></div>
                <div class="col-6"><small class="text-muted">To Warehouse:</small><br><span id="print-warehouse">-</span></div>
                <div class="col-6"><small class="text-muted">Qty:</small><br><span id="print-qty">-</span></div>
                <div class="col-6"><small class="text-muted">Weight:</small><br><span id="print-weight">-</span></div>
            </div>
            <div class="border-top pt-3 mt-3">
                <div class="row">
                    <div class="col-4"><small class="text-muted">Dikeluarkan Oleh</small><br><br><small>_________________</small></div>
                    <div class="col-4"><small class="text-muted">Diterima Oleh</small><br><br><small>_________________</small></div>
                    <div class="col-4"><small class="text-muted">Disetujui Oleh</small><br><br><small>_________________</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button><button type="button" class="btn btn-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button></div>
</div></div></div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
let deleteId=null;

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('stbj.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_status=$('#filter-status').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'stbj_no',name:'stbj_no'},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'production_id',name:'production_id'},
{data:'batch_no',name:'batch_no'},
{data:'product_name',name:'product_name'},
{data:'from_line',name:'from_line'},
{data:'to_warehouse_id',name:'to_warehouse_id'},
{data:'pcs_fmt',name:'total_qty_pcs',orderable:false,searchable:false,className:'text-center'},
{data:'kg_fmt',name:'total_weight_kg',orderable:false,searchable:false,className:'text-end'},
{data:'received_by',name:'received_by'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'action',orderable:false,searchable:false,className:'text-center'}
]});

$('#btn-add').on('click',function(){
    $('#modal-title').text('Tambah STBJ');
    $('#form-id').val('');$('#form-stbj-no').val('(Auto-generated)');
    $('#form-date').val(new Date().toISOString().slice(0,10));$('#form-status').val('Draft');
    $('#form-prod-id').val('');$('#form-batch').val('');$('#form-product-name').val('');
    $('#form-line').val('LINE-A1');$('#form-warehouse').val('Gudang Jadi Bandung');
    $('#form-received-by').val('');$('#form-pcs').val('');$('#form-kg').val('');$('#form-notes').val('');
    new bootstrap.Modal('#formModal').show();
});

function editRecord(id){$.get('/stbj/'+id,function(d){
    $('#modal-title').text('Edit STBJ');
    $('#form-id').val(d.id);$('#form-stbj-no').val(d.stbj_no);
    $('#form-date').val(d.date);$('#form-status').val(d.status);
    $('#form-prod-id').val(d.production_id);$('#form-batch').val(d.batch_no);
    $('#form-product-name').val(d.product_name);$('#form-line').val(d.from_line);
    $('#form-warehouse').val(d.to_warehouse_id);$('#form-received-by').val(d.received_by);
    $('#form-pcs').val(d.total_qty_pcs);$('#form-kg').val(d.total_weight_kg);$('#form-notes').val(d.notes||'');
    new bootstrap.Modal('#formModal').show();
});}

$('#btn-save').on('click',function(){
    const id=$('#form-id').val();
    const payload={date:$('#form-date').val(),production_id:$('#form-prod-id').val(),batch_no:$('#form-batch').val(),product_name:$('#form-product-name').val(),from_line:$('#form-line').val(),to_warehouse_id:$('#form-warehouse').val(),received_by:$('#form-received-by').val(),total_qty_pcs:parseInt($('#form-pcs').val())||0,total_weight_kg:parseFloat($('#form-kg').val())||0,status:$('#form-status').val(),notes:$('#form-notes').val()};
    if(!payload.date||!payload.production_id||!payload.batch_no||!payload.from_line||!payload.to_warehouse_id||!payload.total_qty_pcs||!payload.total_weight_kg){alert('Lengkapi field wajib.');return;}
    const url=id?'/stbj/'+id:'{{route("stbj.store")}}';
    $.ajax({url:url,type:'POST',data:{...payload,_method:id?'PUT':'POST'},success:function(r){bootstrap.Modal.getInstance('#formModal').hide();tbl.ajax.reload(null,false);alert(r.message)},error:function(e){alert(e.responseJSON?.error||'Terjadi kesalahan.')}});
});

function issueSTBJ(id){if(!confirm('Issue STBJ ini?'))return;$.ajax({url:'/stbj/'+id+'/issue',type:'POST',data:{_token:csrf},success:function(r){tbl.ajax.reload(null,false);alert(r.message)}});}
function verifySTBJ(id){if(!confirm('Verifikasi STBJ ini?'))return;$.ajax({url:'/stbj/'+id+'/verify',type:'POST',data:{_token:csrf},success:function(r){tbl.ajax.reload(null,false);alert(r.message)}});}

function printSTBJ(id){$.get('/stbj/'+id,function(d){
    $('#print-stbj-no').text(d.stbj_no);$('#print-date').text(d.date);
    $('#print-prod-id').text(d.production_id);$('#print-batch').text(d.batch_no);
    $('#print-product').text(d.product_name);$('#print-line').text(d.from_line);
    $('#print-warehouse').text(d.to_warehouse_id);$('#print-qty').text(d.total_qty_pcs+' Pcs');
    $('#print-weight').text(d.total_weight_kg+' Kg');
    new bootstrap.Modal('#printModal').show();
});}

function deleteRecord(id){deleteId=id;$('#delete-name').text('');new bootstrap.Modal('#deleteModal').show();}
$('#btn-confirm-delete').on('click',function(){$.ajax({url:'/stbj/'+deleteId,type:'POST',data:{_method:'DELETE'},success:function(r){bootstrap.Modal.getInstance('#deleteModal').hide();tbl.ajax.reload(null,false);alert(r.message)}});});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-status').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-status').val('all');tbl.ajax.reload()});
</script>
@endpush