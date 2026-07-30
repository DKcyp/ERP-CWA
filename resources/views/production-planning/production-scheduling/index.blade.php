@extends('layouts.layout')
@section('title','Production Scheduling')
@push('after-style')
<style>
    .gantt-wrapper{overflow-x:auto;background:#fff;border-radius:8px;}
    .gantt-table{width:100%;border-collapse:collapse;font-size:12px;}
    .gantt-table th,.gantt-table td{border:1px solid #e9ecef;padding:6px 8px;white-space:nowrap;vertical-align:middle;}
    .gantt-table th{background:#f8f9fa;font-weight:600;position:sticky;top:0;z-index:2;}
    .gantt-table .machine-col{min-width:120px;font-weight:600;background:#f8f9fa;position:sticky;left:0;z-index:3;}
    .gantt-table .day-col{min-width:48px;text-align:center;}
    .gantt-cell{position:relative;height:32px;}
    .gantt-bar{position:absolute;top:4px;bottom:4px;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;color:#fff;cursor:grab;overflow:hidden;text-overflow:ellipsis;padding:0 4px;transition:box-shadow .15s;}
    .gantt-bar:hover{box-shadow:0 2px 8px rgba(0,0,0,.25);z-index:5;}
    .gantt-bar.water{background:linear-gradient(135deg,#0d6efd,#0b5ed7);}
    .gantt-bar.solvent{background:linear-gradient(135deg,#fd7e14,#e8590c);}
    .gantt-bar.completed{background:linear-gradient(135deg,#198754,#146c43);}
    .gantt-bar.planned{background:linear-gradient(135deg,#0dcaf0,#0aa2c0);}
    .gantt-legend{display:flex;gap:16px;margin-bottom:12px;flex-wrap:wrap;}
    .gantt-legend span{display:flex;align-items:center;gap:6px;font-size:12px;}
    .gantt-legend .dot{width:14px;height:14px;border-radius:3px;}
</style>
@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari Doc ID, SPK, Line...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>From Date</label>
                <input type="date" class="form-control" id="filter-date-from" value="2026-07-01">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>To Date</label>
                <input type="date" class="form-control" id="filter-date-to" value="2026-07-31">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-droplet me-1"></i>Tipe</label>
                <select class="form-select" id="filter-tipe"><option value="all">Semua</option><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-gear me-1"></i>Line</label>
                <select class="form-select" id="filter-line"><option value="all">Semua Line</option><option value="LINE-A1">LINE-A1</option><option value="LINE-A2">LINE-A2</option><option value="LINE-B1">LINE-B1</option><option value="LINE-B2">LINE-B2</option></select>
            </div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
            </div>
        </div>
    </div></div>

    {{-- Gantt Chart --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-steps me-2"></i>Gantt Chart Jadwal Produksi</h6></div><div class="card-body p-0">
        <div class="gantt-wrapper" id="gantt-wrapper"></div>
    </div></div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Doc. ID</th><th>Tipe</th><th>FK</th><th>Date</th><th>SPK</th><th>From</th><th>To</th><th>Line</th><th>User ID</th><th>Notes</th><th class="text-center">Status</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label fw-semibold">Doc. ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_doc_id" maxlength="50"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label><select class="form-select" id="f_tipe"><option value="Water Based">Water Based</option><option value="Solvent Based">Solvent Based</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">FK</label><input type="text" class="form-control" id="f_fk" maxlength="50"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">SPK No <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_spk_no" maxlength="50"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Line Machine <span class="text-danger">*</span></label><select class="form-select" id="f_line_machine_id"><option value="LINE-A1">LINE-A1</option><option value="LINE-A2">LINE-A2</option><option value="LINE-B1">LINE-B1</option><option value="LINE-B2">LINE-B2</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">SPK From <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_spk_from_date"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">SPK To <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_spk_to_date"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">User ID</label><input type="text" class="form-control" id="f_user_id" maxlength="50"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label><select class="form-select" id="f_status"><option value="DRAFT">Draft</option><option value="PLANNED">Planned</option><option value="IN_PROGRESS">In Progress</option><option value="COMPLETED">Completed</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Notes</label><textarea class="form-control" id="f_notes" rows="2" maxlength="500"></textarea></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('production-scheduling.table')}}",storeUrl="{{route('production-scheduling.store')}}",showUrl="{{route('production-scheduling.show','__ID__')}}",updateUrl="{{route('production-scheduling.update','__ID__')}}",deleteUrl="{{route('production-scheduling.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

let allData=[];

function getGanttParams(){
    return{filter_search:$('#filter-search').val(),filter_tipe:$('#filter-tipe').val(),filter_line:$('#filter-line').val(),filter_date_from:$('#filter-date-from').val(),filter_date_to:$('#filter-date-to').val()};
}

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){$.extend(d,getGanttParams())},dataSrc:function(json){allData=json.data||[];renderGantt(allData);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'doc_id',name:'doc_id'},{data:'tipe',name:'tipe'},{data:'fk',name:'fk',render:function(d){return d||'-'}},
{data:'date_fmt',name:'date',className:'text-center'},
{data:'spk_no',name:'spk_no'},{data:'spk_from_fmt',name:'spk_from_date',className:'text-center'},{data:'spk_to_fmt',name:'spk_to_date',className:'text-center'},
{data:'line_machine_id',name:'line_machine_id'},
{data:'user_id',name:'user_id'},{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-tipe').on('change',function(){tbl.ajax.reload()});
$('#filter-line').on('change',function(){tbl.ajax.reload()});
$('#filter-date-from').on('change',function(){tbl.ajax.reload()});
$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-tipe').val('all');$('#filter-line').val('all');$('#filter-date-from').val('2026-07-01');$('#filter-date-to').val('2026-07-31');tbl.ajax.reload()});

function renderGantt(data){
    if(!data||!data.length){$('#gantt-wrapper').html('<div class="text-center text-muted py-4">Tidak ada data jadwal.</div>');return;}
    const from=new Date($('#filter-date-from').val()||'2026-07-01');
    const to=new Date($('#filter-date-to').val()||'2026-07-31');
    const days=[];const d=new Date(from);
    while(d<=to){days.push(new Date(d));d.setDate(d.getDate()+1)}

    const machines={};data.forEach(function(r){
        const m=r.line_machine_id||'Unknown';
        if(!machines[m])machines[m]=[];
        machines[m].push(r);
    });

    let html='<table class="gantt-table"><thead><tr><th class="machine-col">Line / Mesin</th>';
    days.forEach(function(dd){const cls=dd.getDay()===0||dd.getDay()===6?'text-danger':'';html+='<th class="day-col '+cls+'">'+(dd.getDate())+'</th>'});
    html+='</tr></thead><tbody>';

    Object.keys(machines).sort().forEach(function(m){
        html+='<tr><td class="machine-col">'+m+'</td>';
        days.forEach(function(dd){
            const dateStr=dd.toISOString().slice(0,10);
            const items=machines[m].filter(function(r){return dateStr>=r.spk_from_date&&dateStr<=r.spk_to_date});
            html+='<td class="gantt-cell">';
            items.forEach(function(r){
                const isFirst=dateStr===r.spk_from_date;
                const tipe=(r.tipe||'').toLowerCase().includes('solvent')?'solvent':'water';
                const cls=r.status==='COMPLETED'?'completed':r.status==='PLANNED'?'planned':tipe;
                if(isFirst){
                    const fromD=new Date(r.spk_from_date);const toD=new Date(r.spk_to_date);
                    const span=Math.round((toD-fromD)/(1000*60*60*24))+1;
                    html+='<div class="gantt-bar '+cls+'" style="width:'+(span*48-4)+'px" title="'+r.spk_no+' - '+r.doc_id+'">'+r.spk_no+'</div>';
                }
            });
            html+='</td>';
        });
        html+='</tr>';
    });
    html+='</tbody></table>';
    html='<div class="p-3"><div class="gantt-legend"><span><span class="dot" style="background:linear-gradient(135deg,#0d6efd,#0b5ed7)"></span>Water Based</span><span><span class="dot" style="background:linear-gradient(135deg,#fd7e14,#e8590c)"></span>Solvent Based</span><span><span class="dot" style="background:linear-gradient(135deg,#0dcaf0,#0aa2c0)"></span>Planned</span><span><span class="dot" style="background:linear-gradient(135deg,#198754,#146c43)"></span>Completed</span></div></div>'+html;
    $('#gantt-wrapper').html(html);
}

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Jadwal')}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){
    const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData();fd.append('_token',csrf);
    ['doc_id','tipe','fk','date','spk_no','spk_from_date','spk_to_date','line_machine_id','user_id','notes','status'].forEach(function(k){fd.append(k,document.getElementById('f_'+k).value)});
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Swal.fire({icon:'error',title:'Validasi Gagal',text:Object.values(r.errors).flat().join('\n')})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);['doc_id','tipe','fk','date','spk_no','spk_from_date','spk_to_date','line_machine_id','user_id','notes','status'].forEach(function(k){const el=document.getElementById('f_'+k);if(el)el.value=d[k]??''});modal.find('.modal-title').text('Edit Jadwal');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});

$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus jadwal ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){Swal.fire({icon:'error',title:'Gagal',text:(x.responseJSON||{}).message||'Gagal menghapus.'})}})})});
</script>
@endpush