@extends('layouts.layout')
@section('title','Realisasi Jadwal Canning & Packing List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, User..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Shift</label><select class="form-select form-select-sm" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">S1</option><option value="Shift 2">S2</option><option value="Shift 3">S3</option></select></div>
            <div class="col-md-1"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water</option><option value="Solvent Based">Solvent</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Lokasi</label><select class="form-select form-select-sm" id="filter-category"><option value="all">Semua</option><option value="Pusat">Pusat</option><option value="Cabang">Cabang</option></select></div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Realisasi</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Date</th><th>User ID</th><th>Prod Date</th><th>Shift</th><th>Type</th><th>Jadwal</th><th class="text-center">Total Produk</th><th class="text-end">Total Canning (KG)</th><th>Status</th><th>Notes</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold" id="modal-title">Tambah Realisasi Canning & Packing</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="form-id">
        <div class="card bg-primary bg-opacity-10 border-0 mb-3"><div class="card-body py-2">
            <div class="row g-3 align-items-end">
                <div class="col-md-3"><label class="form-label fw-semibold small">Doc ID</label><input type="text" class="form-control" id="form-doc-id" value="(Auto-generated)" readonly></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-date"></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">User ID</label><input type="text" class="form-control" id="form-user-id" value="cp-001" readonly></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Status</label><select class="form-select" id="form-status"><option value="Draft">Draft</option><option value="Submitted">Submitted</option><option value="Approved">Approved</option><option value="Rejected">Rejected</option></select></div>
            </div>
        </div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-2"><label class="form-label fw-semibold small">Prod. Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-prod-date"></div>
                <div class="col-md-2"><label class="form-label fw-semibold small">Shift <span class="text-danger">*</span></label><select class="form-select" id="form-shift"><option value="Shift 1">Shift 1</option><option value="Shift 2">Shift 2</option><option value="Shift 3">Shift 3</option></select></div>
                <div class="col-md-2"><label class="form-label fw-semibold small">Type <span class="text-danger">*</span></label><select class="form-select" id="form-type"><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select></div>
                <div class="col-md-2"><label class="form-label fw-semibold small">Jadwal <span class="text-danger">*</span></label><select class="form-select" id="form-category"><option value="Pusat">Pusat</option><option value="Cabang">Cabang</option></select></div>
                <div class="col-md-2"><button type="button" class="btn btn-outline-primary" id="btn-load-jadwal"><i class="bi bi-arrow-clockwise me-1"></i>Load Jadwal</button></div>
            </div>
        </div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-1"></i>Data Grid Detail</h6>
            <button type="button" class="btn btn-outline-success btn-sm" id="btn-add-row"><i class="bi bi-plus-lg me-1"></i>Add Row</button>
        </div><div class="card-body p-0">
            <div class="table-responsive" style="max-height:400px;overflow-y:auto;"><table class="table table-bordered table-sm mb-0" id="detail-table"><thead class="table-light" style="position:sticky;top:0;z-index:1;">
                <tr>
                    <th class="text-center" style="width:30px">#</th>
                    <th>Kode Warna</th><th>Warna</th><th>Batch No</th>
                    <th class="text-end">Basis (KG)</th><th class="text-end">Realisasi CM (KG)</th>
                    <th class="text-center">Kaleng 0.1L</th><th class="text-center">Kaleng 0.2L</th><th class="text-center">Kaleng 0.4L</th><th class="text-center">Kaleng 0.45L</th><th class="text-center">Kaleng 0.9L</th><th class="text-center">Kaleng (PCS)</th><th class="text-center">Galon</th><th class="text-center">Pail</th><th class="text-center">Liter</th><th class="text-center">500ML</th><th class="text-center">1L</th>
                    <th class="text-end">Realisasi Canning (KG)</th>
                    <th>Tgl Kemas</th><th>Tgl Selesai</th><th class="text-center">Sisa Kemas</th>
                    <th class="text-end">Berat Awal</th><th class="text-end">Berat Akhir</th><th class="text-end">Selisih</th>
                    <th>Op. Canning</th><th>Op. Packing</th><th>Jadwal (Ref)</th><th>Keterangan</th>
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
const colors=[{kode:'CM-W-001',warna:'Putih'},{kode:'CM-W-002',warna:'Cream'},{kode:'CM-P-001',warna:'Abu-abu'},{kode:'CM-TC-001',warna:'Biru'},{kode:'CM-E-001',warna:'Merah'},{kode:'CM-W-003',warna:'Hijau'}];
const opCanning=['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo'];
const opPacking=['Maya Putri','Andi Lesmana','Rina Sari','Dedi Kuswanto'];
let deleteId=null;

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-canning-packing-list.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_shift=$('#filter-shift').val();d.filter_type=$('#filter-type').val();d.filter_category=$('#filter-category').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'date_fmt',name:'date',className:'text-center'},{data:'user_id',name:'user_id'},
{data:'prod_date_fmt',name:'prod_date',className:'text-center'},{data:'shift',name:'shift',className:'text-center'},
{data:'type',name:'type'},{data:'schedule_category',name:'schedule_category'},
{data:'total_product_count',name:'total_product_count',className:'text-center'},
{data:'realisasi_fmt',name:'total_realisasi_canning_kg',orderable:false,searchable:false,className:'text-end'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'action',orderable:false,searchable:false,className:'text-center'}
]});

function opSelect(list,selected){let h='<select class="form-select form-select-sm"><option value="">-</option>';list.forEach(o=>{h+='<option value="'+o+'"'+(o===selected?' selected':'')+'>'+o+'</option>'});return h+'</select>';}

function addRow(data){
    data=data||{};
    const i=$('#detail-body tr').length+1;
    const c=colors.find(x=>x.kode===data.kode_warna)||colors[0];
    const tr=$('<tr>');
    tr.append('<td class="text-center align-middle">'+i+'</td>');
    tr.append('<td><select class="form-select form-select-sm d-kode"><option value="">-</option>'+colors.map(x=>'<option value="'+x.kode+'"'+(x.kode===data.kode_warna?' selected':'')+'>'+x.kode+'</option>').join('')+'</select></td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-warna" value="'+(data.warna||c.warna)+'" readonly></td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-batch" value="'+(data.batch_no||'')+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-basis text-end" value="'+(data.basis_kg||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-cm text-end" value="'+(data.realisasi_cm_kg||0)+'"></td>');
    ['kaleng_01l','kaleng_02l','kaleng_04l','kaleng_045l','kaleng_09l','kaleng_pcs','galon_pcs','pail_pcs','liter_pcs','kaleng_500ml','kaleng_1l'].forEach(f=>{
        tr.append('<td><input type="number" class="form-control form-control-sm d-'+f+' text-center" value="'+(data[f]||0)+'"></td>');
    });
    tr.append('<td><input type="number" class="form-control form-control-sm d-canning text-end" value="'+(data.realisasi_canning_kg||0)+'"></td>');
    tr.append('<td><input type="date" class="form-control form-control-sm d-tgl-kemas" value="'+(data.tgl_kemas||'')+'"></td>');
    tr.append('<td><input type="date" class="form-control form-control-sm d-tgl-selesai" value="'+(data.tgl_selesai||'')+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-sisa text-center" value="'+(data.sisa_hasil_kemas||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-ba text-end" value="'+(data.berat_awal||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-bi text-end" value="'+(data.berat_akhir||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-selisih text-end" value="'+(data.selisih||0)+'" readonly></td>');
    tr.append('<td>'+opSelect(opCanning,data.operator_canning)+'</td>');
    tr.append('<td>'+opSelect(opPacking,data.operator_packing)+'</td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-jadwal" value="'+(data.jadwal_ref||'')+'" readonly></td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-ket" value="'+(data.keterangan||'')+'"></td>');
    tr.append('<td class="text-center align-middle"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-x"></i></button></td>');
    $('#detail-body').append(tr);
    tr.find('.d-kode').on('change',function(){const x=colors.find(c=>c.kode===this.value);if(x)tr.find('.d-warna').val(x.warna)});
    tr.find('.d-ba, .d-bi').on('input',function(){const ba=parseFloat(tr.find('.d-ba').val())||0;const bi=parseFloat(tr.find('.d-bi').val())||0;tr.find('.d-selisih').val(bi-ba)});
}

$('#btn-add-row').on('click',function(){addRow()});
$(document).on('click','.remove-row',function(){$(this).closest('tr').remove();$('#detail-body tr').each(function(i){$(this).find('td:first').text(i+1)})});

function getItems(){
    const items=[];
    $('#detail-body tr').each(function(){
        items.push({
            kode_warna:$(this).find('.d-kode').val(),warna:$(this).find('.d-warna').val(),
            batch_no:$(this).find('.d-batch').val(),
            basis_kg:parseFloat($(this).find('.d-basis').val())||0,
            realisasi_cm_kg:parseFloat($(this).find('.d-cm').val())||0,
            kaleng_01l:parseInt($(this).find('.d-kaleng_01l').val())||0,kaleng_02l:parseInt($(this).find('.d-kaleng_02l').val())||0,
            kaleng_04l:parseInt($(this).find('.d-kaleng_04l').val())||0,kaleng_045l:parseInt($(this).find('.d-kaleng_045l').val())||0,
            kaleng_09l:parseInt($(this).find('.d-kaleng_09l').val())||0,kaleng_pcs:parseInt($(this).find('.d-kaleng_pcs').val())||0,
            galon_pcs:parseInt($(this).find('.d-galon_pcs').val())||0,pail_pcs:parseInt($(this).find('.d-pail_pcs').val())||0,
            liter_pcs:parseInt($(this).find('.d-liter_pcs').val())||0,kaleng_500ml:parseInt($(this).find('.d-kaleng_500ml').val())||0,
            kaleng_1l:parseInt($(this).find('.d-kaleng_1l').val())||0,
            realisasi_canning_kg:parseFloat($(this).find('.d-canning').val())||0,
            tgl_kemas:$(this).find('.d-tgl-kemas').val(),tgl_selesai:$(this).find('.d-tgl-selesai').val(),
            sisa_hasil_kemas:parseInt($(this).find('.d-sisa').val())||0,
            berat_awal:parseFloat($(this).find('.d-ba').val())||0,berat_akhir:parseFloat($(this).find('.d-bi').val())||0,
            selisih:parseFloat($(this).find('.d-selisih').val())||0,
            operator_canning:$(this).find('select:eq(1)').val(),operator_packing:$(this).find('select:eq(2)').val(),
            jadwal_ref:$(this).find('.d-jadwal').val(),keterangan:$(this).find('.d-ket').val(),
        });
    });
    return items;
}

$('#btn-add').on('click',function(){
    $('#modal-title').text('Tambah Realisasi Canning & Packing');
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
        const c=colors[Math.floor(Math.random()*colors.length)];
        addRow({kode_warna:c.kode,warna:c.warna,batch_no:'CP-ADN-'+String(Math.floor(Math.random()*900)+100),basis_kg:Math.floor(Math.random()*250)+100,realisasi_cm_kg:Math.floor(Math.random()*250)+100,kaleng_04l:Math.floor(Math.random()*40)+10,galon_pcs:Math.floor(Math.random()*20)+5,realisasi_canning_kg:Math.floor(Math.random()*250)+100,tgl_kemas:$('#form-prod-date').val(),tgl_selesai:$('#form-prod-date').val(),berat_awal:Math.floor(Math.random()*3000)+2000,berat_akhir:Math.floor(Math.random()*3000)+2000,jadwal_ref:$('#form-category').val(),operator_canning:opCanning[Math.floor(Math.random()*opCanning.length)],operator_packing:opPacking[Math.floor(Math.random()*opPacking.length)]});
    }
});

function editRecord(id){$.get('/realisasi-jadwal-canning-packing-list/'+id,function(d){
    $('#modal-title').text('Edit Realisasi Canning & Packing');
    $('#form-id').val(d.id);$('#form-doc-id').val(d.doc_id);$('#form-date').val(d.date);$('#form-user-id').val(d.user_id);
    $('#form-prod-date').val(d.prod_date);$('#form-shift').val(d.shift);$('#form-type').val(d.type);
    $('#form-category').val(d.schedule_category);$('#form-status').val(d.status);$('#form-notes').val(d.notes||'');
    $('#detail-body').html('');if(d.items&&d.items.length)d.items.forEach(item=>addRow(item));else addRow();
    new bootstrap.Modal('#formModal').show();
});}

$('#btn-save').on('click',function(){
    const id=$('#form-id').val();
    const payload={date:$('#form-date').val(),user_id:$('#form-user-id').val(),prod_date:$('#form-prod-date').val(),shift:$('#form-shift').val(),type:$('#form-type').val(),schedule_category:$('#form-category').val(),status:$('#form-status').val(),notes:$('#form-notes').val(),items:getItems()};
    if(!payload.date||!payload.prod_date||!payload.shift||!payload.type||!payload.schedule_category){alert('Lengkapi field wajib.');return;}
    if(!payload.items.length){alert('Minimal add 1 item.');return;}
    const url=id?'/realisasi-jadwal-canning-packing-list/'+id:'{{route("realisasi-jadwal-canning-packing-list.store")}}';
    $.ajax({url:url,type:'POST',data:{...payload,_method:id?'PUT':'POST'},success:function(r){bootstrap.Modal.getInstance('#formModal').hide();tbl.ajax.reload(null,false);alert(r.message)},error:function(e){alert(e.responseJSON?.error||'Terjadi kesalahan.')}});
});

function deleteRecord(id){deleteId=id;new bootstrap.Modal('#deleteModal').show();}
$('#btn-confirm-delete').on('click',function(){$.ajax({url:'/realisasi-jadwal-canning-packing-list/'+deleteId,type:'POST',data:{_method:'DELETE'},success:function(r){bootstrap.Modal.getInstance('#deleteModal').hide();tbl.ajax.reload(null,false);alert(r.message)}});});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-shift').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});$('#filter-category').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-shift').val('all');$('#filter-type').val('all');$('#filter-category').val('all');tbl.ajax.reload()});
</script>
@endpush