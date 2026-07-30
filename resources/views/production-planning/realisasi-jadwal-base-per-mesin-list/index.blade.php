@extends('layouts.layout')
@section('title','Realisasi Jadwal Base per Mesin List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, Mesin..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">S1</option><option value="Shift 2">S2</option><option value="Shift 3">S3</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Mesin</label><select class="form-select form-select-sm" id="filter-machine"><option value="all">Semua</option><option value="M-01">M-01</option><option value="M-02">M-02</option><option value="M-03">M-03</option><option value="M-04">M-04</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water</option><option value="Solvent Based">Solvent</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Realisasi</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Date</th><th>User ID</th><th>Prod Date</th><th>Shift</th><th>Mesin</th><th>Type</th><th class="text-center">Total Produk</th><th class="text-end">Total Realisasi (KG)</th><th>Status</th><th>Notes</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold" id="modal-title">Tambah Realisasi Base per Mesin</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="form-id">
        <div class="card bg-primary bg-opacity-10 border-0 mb-3"><div class="card-body py-2">
            <div class="row g-3 align-items-end">
                <div class="col-md-3"><label class="form-label fw-semibold small">Doc ID</label><input type="text" class="form-control" id="form-doc-id" value="(Auto-generated)" readonly></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-date"></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">User ID</label><input type="text" class="form-control" id="form-user-id" value="oper-001" readonly></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Status</label><select class="form-select" id="form-status"><option value="Draft">Draft</option><option value="Submitted">Submitted</option><option value="Approved">Approved</option><option value="Rejected">Rejected</option></select></div>
            </div>
        </div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-2"><label class="form-label fw-semibold small">Prod. Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-prod-date"></div>
                <div class="col-md-2"><label class="form-label fw-semibold small">Shift <span class="text-danger">*</span></label><select class="form-select" id="form-shift"><option value="Shift 1">Shift 1</option><option value="Shift 2">Shift 2</option><option value="Shift 3">Shift 3</option></select></div>
                <div class="col-md-2"><label class="form-label fw-semibold small">Mesin <span class="text-danger">*</span></label><select class="form-select" id="form-machine"><option value="M-01">M-01</option><option value="M-02">M-02</option><option value="M-03">M-03</option><option value="M-04">M-04</option></select></div>
                <div class="col-md-2"><label class="form-label fw-semibold small">Type <span class="text-danger">*</span></label><select class="form-select" id="form-type"><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select></div>
                <div class="col-md-2"><button type="button" class="btn btn-outline-primary" id="btn-load-jadwal"><i class="bi bi-arrow-clockwise me-1"></i>Load Jadwal</button></div>
            </div>
        </div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-gear-wide-connected me-1"></i>Data Grid Detail</h6>
            <button type="button" class="btn btn-outline-success btn-sm" id="btn-add-row"><i class="bi bi-plus-lg me-1"></i>Add Row</button>
        </div><div class="card-body p-0">
            <div class="table-responsive" style="max-height:400px;overflow-y:auto;"><table class="table table-bordered table-sm mb-0" id="detail-table"><thead class="table-light" style="position:sticky;top:0;z-index:1;">
                <tr>
                    <th class="text-center" style="width:30px">#</th>
                    <th>Nama Product <span class="text-danger">*</span></th><th>Batch No</th>
                    <th class="text-end">Basis (KG)</th><th class="text-end">Realisasi (KG) <span class="text-danger">*</span></th>
                    <th class="text-center">Air Start</th><th class="text-center">Air Finish</th>
                    <th class="text-center">Proses Start</th>
                    <th class="text-center">Cek Halus Start</th><th class="text-center">Cek Halus Finish</th>
                    <th class="text-center">Cek Akhir Start</th><th class="text-center">Cek Akhir Finish</th>
                    <th class="text-center">Penurunan</th>
                    <th>Operator</th><th>Keterangan</th>
                    <th class="text-center" style="width:35px"></th>
                </tr>
            </thead><tbody id="detail-body"></tbody></table>
        </div></div></div>
        <div class="mb-3"><label class="form-label fw-semibold small">Notes</label><textarea class="form-control" id="form-notes" rows="2" placeholder="Catatan..."></textarea></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Batal</button><button type="button" class="btn btn-primary" id="btn-save"><i class="bi bi-check-lg me-1"></i>Simpan</button></div>
</div></div></div>

<div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header bg-danger text-white"><h5 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i>Konfirmasi Hapus</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><p class="mb-0">Yakin ingin menghapus data ini?</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-danger" id="btn-confirm-delete"><i class="bi bi-trash me-1"></i>Hapus</button></div>
</div></div></div>
@endsection
@push('after-script')
<script>
const csrf=$('meta[name="csrf-token"]').attr('content');$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});
const products=['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L'];
const operators=['Budi Santoso','Andi Kurniawan','Citra Dewi','Dedi Kuswanto','Eka Putri','Fajar Nugroho','Gilang Ramadhan','Hendra Wijaya'];
let deleteId=null;

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-base-per-mesin-list.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_machine=$('#filter-machine').val();d.filter_type=$('#filter-type').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'date_fmt',name:'date',className:'text-center'},{data:'user_id',name:'user_id'},
{data:'prod_date_fmt',name:'prod_date',className:'text-center'},{data:'shift',name:'shift',className:'text-center'},
{data:'machine',name:'machine',className:'text-center'},{data:'type',name:'type'},
{data:'total_product_count',name:'total_product_count',className:'text-center'},
{data:'realisasi_fmt',name:'total_realisasi_kg',orderable:false,searchable:false,className:'text-end'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'action',orderable:false,searchable:false,className:'text-center'}
]});

function productSelect(selected){let h='<select class="form-select form-select-sm d-product"><option value="">- Pilih -</option>';products.forEach(p=>{h+='<option value="'+p+'"'+(p===selected?' selected':'')+'>'+p+'</option>'});return h+'</select>';}
function opSelect(selected){let h='<select class="form-select form-select-sm d-operator"><option value="">-</option>';operators.forEach(o=>{h+='<option value="'+o+'"'+(o===selected?' selected':'')+'>'+o+'</option>'});return h+'</select>';}

function addRow(data){
    data=data||{};
    const i=$('#detail-body tr').length+1;
    const tr=$('<tr>');
    tr.append('<td class="text-center align-middle">'+i+'</td>');
    tr.append('<td>'+productSelect(data.product_name)+'</td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-batch" value="'+(data.batch_no||'')+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-basis text-end" value="'+(data.total_basis_kg||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-realisasi text-end" value="'+(data.realisasi_kg||0)+'"></td>');
    ['air_start','air_finish','proses_start','cek_halus_start','cek_halus_finish','cek_akhir_start','cek_akhir_finish','penurunan'].forEach(f=>{
        tr.append('<td><input type="time" class="form-control form-control-sm d-'+f+'" value="'+(data[f]||'')+'"></td>');
    });
    tr.append('<td>'+opSelect(data.operator)+'</td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-ket" value="'+(data.keterangan||'')+'"></td>');
    tr.append('<td class="text-center align-middle"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-x"></i></button></td>');
    $('#detail-body').append(tr);
}

$('#btn-add-row').on('click',function(){addRow()});
$(document).on('click','.remove-row',function(){$(this).closest('tr').remove();$('#detail-body tr').each(function(i){$(this).find('td:first').text(i+1)})});

function getItems(){
    const items=[];
    $('#detail-body tr').each(function(){
        items.push({
            product_name:$(this).find('.d-product').val(),
            batch_no:$(this).find('.d-batch').val(),
            total_basis_kg:parseFloat($(this).find('.d-basis').val())||0,
            realisasi_kg:parseFloat($(this).find('.d-realisasi').val())||0,
            air_start:$(this).find('.d-air_start').val(),air_finish:$(this).find('.d-air_finish').val(),
            proses_start:$(this).find('.d-proses_start').val(),
            cek_halus_start:$(this).find('.d-cek_halus_start').val(),cek_halus_finish:$(this).find('.d-cek_halus_finish').val(),
            cek_akhir_start:$(this).find('.d-cek_akhir_start').val(),cek_akhir_finish:$(this).find('.d-cek_akhir_finish').val(),
            penurunan:$(this).find('.d-penurunan').val(),
            operator:$(this).find('.d-operator').val(),
            keterangan:$(this).find('.d-ket').val(),
        });
    });
    return items;
}

$('#btn-add').on('click',function(){
    $('#modal-title').text('Tambah Realisasi Base per Mesin');
    $('#form-id').val('');$('#form-doc-id').val('(Auto-generated)');
    $('#form-date').val(new Date().toISOString().slice(0,10));
    $('#form-prod-date').val(new Date().toISOString().slice(0,10));
    $('#form-status').val('Draft');$('#form-notes').val('');
    $('#detail-body').html('');addRow();
    new bootstrap.Modal('#formModal').show();
});

$('#btn-load-jadwal').on('click',function(){
    $('#detail-body').html('');
    for(let i=0;i<Math.floor(Math.random()*3)+2;i++){
        const s=6+i*4;
        addRow({product_name:products[Math.floor(Math.random()*products.length)],batch_no:'BPM-ADN-'+String(Math.floor(Math.random()*900)+100),total_basis_kg:Math.floor(Math.random()*400)+200,realisasi_kg:Math.floor(Math.random()*400)+200,air_start:String(s).padStart(2,'0')+':00',air_finish:String(s).padStart(2,'0')+':30',proses_start:String(s+1).padStart(2,'0')+':00',cek_halus_start:String(s+2).padStart(2,'0')+':00',cek_halus_finish:String(s+2).padStart(2,'0')+':30',cek_akhir_start:String(s+3).padStart(2,'0')+':00',cek_akhir_finish:String(s+3).padStart(2,'0')+':20',penurunan:String(s+3).padStart(2,'0')+':59',operator:operators[Math.floor(Math.random()*operators.length)]});
    }
});

function editRecord(id){$.get('/realisasi-jadwal-base-per-mesin-list/'+id,function(d){
    $('#modal-title').text('Edit Realisasi Base per Mesin');
    $('#form-id').val(d.id);$('#form-doc-id').val(d.doc_id);$('#form-date').val(d.date);$('#form-user-id').val(d.user_id);
    $('#form-prod-date').val(d.prod_date);$('#form-shift').val(d.shift);$('#form-machine').val(d.machine);
    $('#form-type').val(d.type);$('#form-status').val(d.status);$('#form-notes').val(d.notes||'');
    $('#detail-body').html('');if(d.items&&d.items.length)d.items.forEach(item=>addRow(item));else addRow();
    new bootstrap.Modal('#formModal').show();
});}

$('#btn-save').on('click',function(){
    const id=$('#form-id').val();
    const payload={date:$('#form-date').val(),user_id:$('#form-user-id').val(),prod_date:$('#form-prod-date').val(),shift:$('#form-shift').val(),machine:$('#form-machine').val(),type:$('#form-type').val(),status:$('#form-status').val(),notes:$('#form-notes').val(),items:getItems()};
    if(!payload.date||!payload.prod_date||!payload.shift||!payload.machine||!payload.type){alert('Lengkapi field wajib.');return;}
    if(!payload.items.length){alert('Minimal add 1 item.');return;}
    const url=id?'/realisasi-jadwal-base-per-mesin-list/'+id:'{{route("realisasi-jadwal-base-per-mesin-list.store")}}';
    $.ajax({url:url,type:'POST',data:{...payload,_method:id?'PUT':'POST'},success:function(r){bootstrap.Modal.getInstance('#formModal').hide();tbl.ajax.reload(null,false);alert(r.message)},error:function(e){alert(e.responseJSON?.error||'Terjadi kesalahan.')}});
});

function deleteRecord(id){deleteId=id;new bootstrap.Modal('#deleteModal').show();}
$('#btn-confirm-delete').on('click',function(){$.ajax({url:'/realisasi-jadwal-base-per-mesin-list/'+deleteId,type:'POST',data:{_method:'DELETE'},success:function(r){bootstrap.Modal.getInstance('#deleteModal').hide();tbl.ajax.reload(null,false);alert(r.message)}});});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-machine').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-machine').val('all');$('#filter-type').val('all');tbl.ajax.reload()});
</script>
@endpush