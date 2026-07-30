@extends('layouts.layout')
@section('title','Realisasi Jadwal Pasta List')
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label><input type="text" class="form-control" id="filter-search" placeholder="Doc ID, User..."></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label><input type="date" class="form-control" id="filter-date-from"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label><input type="date" class="form-control" id="filter-date-to"></div>
            <div class="col-md-2"><label class="form-label fw-semibold mb-1 small text-muted">Type</label><select class="form-select form-select-sm" id="filter-type"><option value="all">Semua</option><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select></div>
            <div class="col-md-3 d-flex gap-2 justify-content-md-end"><button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button><button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah Realisasi Pasta</button></div>
        </div>
    </div></div>
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive"><table class="table table-hover align-middle w-100 table-sm" id="table-data"><thead class="table-light">
            <tr><th class="text-center" style="width:30px">No</th><th>Doc ID</th><th>Date</th><th>User ID</th><th>Type</th><th class="text-center">Total Pasta</th><th class="text-end">Total Realisasi (KG)</th><th>Status</th><th>Notes</th><th class="text-center">Aksi</th></tr>
        </thead></table></div>
    </div></div>
</div>

<div class="modal fade" id="formModal" tabindex="-1"><div class="modal-dialog modal-fullscreen-lg-down modal-xl modal-dialog-scrollable"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold" id="modal-title">Tambah Realisasi Pasta</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" id="form-id">
        <div class="card bg-primary bg-opacity-10 border-0 mb-3"><div class="card-body py-2">
            <div class="row g-3 align-items-end">
                <div class="col-md-3"><label class="form-label fw-semibold small">Doc ID</label><input type="text" class="form-control" id="form-doc-id" value="(Auto-generated)" readonly></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="form-date"></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">User ID</label><input type="text" class="form-control" id="form-user-id" value="pgs-001" readonly></div>
                <div class="col-md-3"><label class="form-label fw-semibold small">Status</label><select class="form-select" id="form-status"><option value="Draft">Draft</option><option value="Submitted">Submitted</option><option value="Approved">Approved</option><option value="Rejected">Rejected</option></select></div>
            </div>
        </div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-3"><label class="form-label fw-semibold small">Type <span class="text-danger">*</span></label><select class="form-select" id="form-type"><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select></div>
            </div>
        </div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-palette me-1"></i>Data Grid Detail</h6>
            <button type="button" class="btn btn-outline-success btn-sm" id="btn-add-row"><i class="bi bi-plus-lg me-1"></i>Add Row</button>
        </div><div class="card-body p-0">
            <div class="table-responsive" style="max-height:400px;overflow-y:auto;"><table class="table table-bordered table-sm mb-0" id="detail-table"><thead class="table-light" style="position:sticky;top:0;z-index:1;">
                <tr>
                    <th class="text-center" style="width:30px">#</th>
                    <th>Date</th><th>Shift</th><th>Kode Pasta <span class="text-danger">*</span></th><th>Name</th><th>Batch No</th><th>Mesin</th>
                    <th>Tgl Jadwal</th><th>Lead Time</th><th>Dateline</th><th>Status</th>
                    <th class="text-end">Basis (KG)</th><th class="text-end">Realisasi (KG) <span class="text-danger">*</span></th><th class="text-end">Selisih</th><th class="text-center">%</th>
                    <th>Mulai</th><th>Selesai</th><th class="text-center">Wkt Tunggu</th>
                    <th>Operator</th>
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
const pastaList=[{kode:'PGS-RED-001',name:'Pigment Red Oxide'},{kode:'PGS-YEL-002',name:'Pigment Yellow Ochre'},{kode:'PGS-BLU-003',name:'Pigment Blue Ultramarine'},{kode:'PGS-GRN-004',name:'Pigment Green Chrome'},{kode:'PGS-WHT-005',name:'Pigment White Titanium'},{kode:'PGS-BLK-006',name:'Pigment Black Carbon'},{kode:'PGS-CRM-007',name:'Pigment Cream'},{kode:'PGS-ORG-008',name:'Pigment Orange'}];
const machines=['P-01','P-02','P-03'];
const operators=['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo','Maya Putri','Andi Lesmana'];
const shifts=['Shift 1','Shift 2','Shift 3'];
const pStatus=['Selesai','Proses','Tertunda'];
let deleteId=null;

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:"{{route('realisasi-jadwal-pasta-list.table')}}",data:function(d){d.filter_search=$('#filter-search').val();d.filter_date_from=$('#filter-date-from').val();d.filter_date_to=$('#filter-date-to').val();d.filter_type=$('#filter-type').val()}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'date_fmt',name:'date',className:'text-center'},{data:'user_id',name:'user_id'},
{data:'type',name:'type'},{data:'total_pasta_count',name:'total_pasta_count',className:'text-center'},
{data:'realisasi_fmt',name:'total_realisasi_kg',orderable:false,searchable:false,className:'text-end'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'action',orderable:false,searchable:false,className:'text-center'}
]});

function pastaSelect(selected){let h='<select class="form-select form-select-sm d-kode-pasta"><option value="">- Pilih -</option>';pastaList.forEach(p=>{h+='<option value="'+p.kode+'"'+(p.kode===selected?' selected':'')+'>'+p.kode+'</option>'});return h+'</select>';}
function shiftSelect(selected){let h='<select class="form-select form-select-sm d-shift"><option value="">-</option>';shifts.forEach(s=>{h+='<option value="'+s+'"'+(s===selected?' selected':'')+'>'+s+'</option>'});return h+'</select>';}
function machineSelect(selected){let h='<select class="form-select form-select-sm d-machine"><option value="">-</option>';machines.forEach(m=>{h+='<option value="'+m+'"'+(m===selected?' selected':'')+'>'+m+'</option>'});return h+'</select>';}
function opSelect(selected){let h='<select class="form-select form-select-sm d-operator"><option value="">-</option>';operators.forEach(o=>{h+='<option value="'+o+'"'+(o===selected?' selected':'')+'>'+o+'</option>'});return h+'</select>';}
function pStatusSelect(selected){let h='<select class="form-select form-select-sm d-pstatus"><option value="">-</option>';pStatus.forEach(s=>{h+='<option value="'+s+'"'+(s===selected?' selected':'')+'>'+s+'</option>'});return h+'</select>';}

function addRow(data){
    data=data||{};
    const i=$('#detail-body tr').length+1;
    const p=pastaList.find(x=>x.kode===data.kode_pasta)||pastaList[0];
    const tr=$('<tr>');
    tr.append('<td class="text-center align-middle">'+i+'</td>');
    tr.append('<td><input type="date" class="form-control form-control-sm d-date" value="'+(data.date||$('#form-date').val()||'')+'"></td>');
    tr.append('<td>'+shiftSelect(data.shift)+'</td>');
    tr.append('<td>'+pastaSelect(data.kode_pasta)+'</td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-name" value="'+(data.name_pasta||p.name)+'" readonly></td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-batch" value="'+(data.batch_no||'')+'"></td>');
    tr.append('<td>'+machineSelect(data.machine)+'</td>');
    tr.append('<td><input type="date" class="form-control form-control-sm d-tgl-jadwal" value="'+(data.tgl_jadwal||'')+'"></td>');
    tr.append('<td><input type="text" class="form-control form-control-sm d-leadtime" value="'+(data.lead_time||'')+'"></td>');
    tr.append('<td><input type="date" class="form-control form-control-sm d-deadline" value="'+(data.dateline||'')+'"></td>');
    tr.append('<td>'+pStatusSelect(data.pasta_status)+'</td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-basis text-end" value="'+(data.total_basis_kg||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-realisasi text-end" value="'+(data.realisasi_kg||0)+'"></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-selisih text-end" value="'+(data.selisih_kg||0)+'" readonly></td>');
    tr.append('<td><input type="number" class="form-control form-control-sm d-pct text-center" value="'+(data.percentage||0)+'" readonly step="0.1"></td>');
    tr.append('<td><input type="time" class="form-control form-control-sm d-mulai" value="'+(data.mulai||'')+'"></td>');
    tr.append('<td><input type="time" class="form-control form-control-sm d-selesai" value="'+(data.selesai||'')+'"></td>');
    tr.append('<td class="text-center align-middle"><small class="text-muted">'+(data.waktu_tunggu_jam||0)+'j '+(data.waktu_tunggu_menit||0)+'m</small></td>');
    tr.append('<td>'+opSelect(data.operator)+'</td>');
    tr.append('<td class="text-center align-middle"><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-x"></i></button></td>');
    $('#detail-body').append(tr);
    tr.find('.d-kode-pasta').on('change',function(){const x=pastaList.find(p=>p.kode===this.value);if(x)tr.find('.d-name').val(x.name)});
    tr.find('.d-basis, .d-realisasi').on('input',function(){
        const b=parseFloat(tr.find('.d-basis').val())||0;const r=parseFloat(tr.find('.d-realisasi').val())||0;
        tr.find('.d-selisih').val(r-b);tr.find('.d-pct').val(b>0?Math.round(r/b*1000)/10:0);
    });
}

$('#btn-add-row').on('click',function(){addRow()});
$(document).on('click','.remove-row',function(){$(this).closest('tr').remove();$('#detail-body tr').each(function(i){$(this).find('td:first').text(i+1)})});

function getItems(){
    const items=[];
    $('#detail-body tr').each(function(){
        items.push({
            date:$(this).find('.d-date').val(),shift:$(this).find('.d-shift').val(),
            kode_pasta:$(this).find('.d-kode-pasta').val(),name_pasta:$(this).find('.d-name').val(),
            batch_no:$(this).find('.d-batch').val(),machine:$(this).find('.d-machine').val(),
            tgl_jadwal:$(this).find('.d-tgl-jadwal').val(),lead_time:$(this).find('.d-leadtime').val(),
            dateline:$(this).find('.d-deadline').val(),pasta_status:$(this).find('.d-pstatus').val(),
            total_basis_kg:parseFloat($(this).find('.d-basis').val())||0,
            realisasi_kg:parseFloat($(this).find('.d-realisasi').val())||0,
            selisih_kg:parseFloat($(this).find('.d-selisih').val())||0,
            percentage:parseFloat($(this).find('.d-pct').val())||0,
            mulai:$(this).find('.d-mulai').val(),selesai:$(this).find('.d-selesai').val(),
            operator:$(this).find('.d-operator').val(),
        });
    });
    return items;
}

$('#btn-add').on('click',function(){
    $('#modal-title').text('Tambah Realisasi Pasta');
    $('#form-id').val('');$('#form-doc-id').val('(Auto-generated)');
    $('#form-date').val(new Date().toISOString().slice(0,10));
    $('#form-status').val('Draft');$('#form-notes').val('');
    $('#detail-body').html('');addRow();
    new bootstrap.Modal('#formModal').show();
});

function editRecord(id){$.get('/realisasi-jadwal-pasta-list/'+id,function(d){
    $('#modal-title').text('Edit Realisasi Pasta');
    $('#form-id').val(d.id);$('#form-doc-id').val(d.doc_id);$('#form-date').val(d.date);$('#form-user-id').val(d.user_id);
    $('#form-type').val(d.type);$('#form-status').val(d.status);$('#form-notes').val(d.notes||'');
    $('#detail-body').html('');if(d.items&&d.items.length)d.items.forEach(item=>addRow(item));else addRow();
    new bootstrap.Modal('#formModal').show();
});}

$('#btn-save').on('click',function(){
    const id=$('#form-id').val();
    const payload={date:$('#form-date').val(),user_id:$('#form-user-id').val(),type:$('#form-type').val(),status:$('#form-status').val(),notes:$('#form-notes').val(),items:getItems()};
    if(!payload.date||!payload.type){alert('Lengkapi field wajib.');return;}
    if(!payload.items.length){alert('Minimal add 1 item.');return;}
    const url=id?'/realisasi-jadwal-pasta-list/'+id:'{{route("realisasi-jadwal-pasta-list.store")}}';
    $.ajax({url:url,type:'POST',data:{...payload,_method:id?'PUT':'POST'},success:function(r){bootstrap.Modal.getInstance('#formModal').hide();tbl.ajax.reload(null,false);alert(r.message)},error:function(e){alert(e.responseJSON?.error||'Terjadi kesalahan.')}});
});

function deleteRecord(id){deleteId=id;new bootstrap.Modal('#deleteModal').show();}
$('#btn-confirm-delete').on('click',function(){$.ajax({url:'/realisasi-jadwal-pasta-list/'+deleteId,type:'POST',data:{_method:'DELETE'},success:function(r){bootstrap.Modal.getInstance('#deleteModal').hide();tbl.ajax.reload(null,false);alert(r.message)}});});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});$('#filter-date-from').on('change',function(){tbl.ajax.reload()});$('#filter-date-to').on('change',function(){tbl.ajax.reload()});$('#filter-type').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-date-from').val('');$('#filter-date-to').val('');$('#filter-type').val('all');tbl.ajax.reload()});
</script>
@endpush