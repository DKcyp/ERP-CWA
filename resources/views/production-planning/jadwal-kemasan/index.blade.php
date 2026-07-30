@extends('layouts.layout')
@section('title','Jadwal Kemasan')
@push('after-style')
<style>
    .sched-grid{overflow-x:auto;}
    .sched-grid table{width:100%;border-collapse:collapse;font-size:12px;}
    .sched-grid th,.sched-grid td{border:1px solid #e9ecef;padding:6px 8px;vertical-align:middle;}
    .sched-grid th{background:#f8f9fa;font-weight:600;position:sticky;top:0;z-index:2;}
    .sched-grid .line-col{min-width:100px;font-weight:600;background:#f8f9fa;position:sticky;left:0;z-index:3;}
    .sched-grid .day-col{min-width:110px;text-align:center;}
    .sched-cell{min-height:60px;}
    .shift-badge{display:inline-block;padding:3px 8px;border-radius:4px;font-size:10px;font-weight:600;color:#fff;margin:2px;}
    .shift-1{background:#0d6efd;}
    .shift-2{background:#fd7e14;}
    .shift-3{background:#6f42c1;}
    .sched-card{background:#fff;border-radius:6px;padding:4px 8px;margin:3px 0;font-size:11px;border-left:3px solid #0d6efd;box-shadow:0 1px 3px rgba(0,0,0,.08);}
    .sched-card.completed{border-left-color:#198754;opacity:.8;}
    .sched-card.in-progress{border-left-color:#0d6efd;}
    .sched-card.planned{border-left-color:#0dcaf0;}
    .sched-card.draft{border-left-color:#6c757d;}
    .legend{display:flex;gap:14px;margin-bottom:10px;flex-wrap:wrap;}
    .legend span{display:flex;align-items:center;gap:5px;font-size:11px;}
    .legend .dot{width:12px;height:12px;border-radius:3px;}
</style>
@endpush
@section('content')
<div class="page-content">
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-search me-1"></i>Cari</label>
                <input type="text" class="form-control" id="filter-search" placeholder="Cari ID, SPK Ref, Produk...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Dari</label>
                <input type="date" class="form-control" id="filter-date-from" value="2026-07-28">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-calendar me-1"></i>Sampai</label>
                <input type="date" class="form-control" id="filter-date-to" value="2026-07-31">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-gear me-1"></i>Line</label>
                <select class="form-select" id="filter-line"><option value="all">Semua</option><option value="PACK-A">PACK-A</option><option value="PACK-B">PACK-B</option><option value="PACK-C">PACK-C</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold mb-1 small text-muted"><i class="bi bi-clock-history me-1"></i>Shift</label>
                <select class="form-select" id="filter-shift"><option value="all">Semua</option><option value="Shift 1">Shift 1</option><option value="Shift 2">Shift 2</option><option value="Shift 3">Shift 3</option></select>
            </div>
            <div class="col-md-2 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-outline-secondary" id="btn-reset-filter"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                <button type="button" class="btn btn-primary" id="btn-add"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
            </div>
        </div>
    </div></div>

    {{-- Calendar Grid --}}
    <div class="card border-0 shadow-sm hz-card mb-4"><div class="card-header bg-transparent border-0"><h6 class="mb-0 fw-bold"><i class="bi bi-calendar3 me-2"></i>Jadwal Harian per Packaging Line</h6></div><div class="card-body p-0">
        <div class="sched-grid" id="sched-grid"></div>
    </div></div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm hz-card"><div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100 table-sm" id="table-data">
                <thead class="table-light">
                    <tr><th class="text-center">No</th><th>Schedule ID</th><th>Date</th><th>SPK Kemasan Ref</th><th>Line</th><th>Product</th><th class="text-center">Target Pcs</th><th>Shift</th><th>Operator</th><th class="text-center">Status</th><th>Notes</th><th class="text-end">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div></div>
</div>

<div class="modal fade" id="modal-data" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tambah Jadwal Kemasan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="form-data" action="javascript:onSave()">
            <div class="modal-body">@csrf<input type="hidden" id="data_id">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label fw-semibold">Schedule ID <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_schedule_id" maxlength="50"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Date <span class="text-danger">*</span></label><input type="date" class="form-control" id="f_date"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">SPK Kemasan Ref <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_spk_kemasan_ref" maxlength="50" placeholder="SPKK-2026-XXXX"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Line Packaging <span class="text-danger">*</span></label><select class="form-select" id="f_line_packaging_id"><option value="PACK-A">PACK-A</option><option value="PACK-B">PACK-B</option><option value="PACK-C">PACK-C</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_product_name" maxlength="200"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Shift <span class="text-danger">*</span></label><select class="form-select" id="f_shift"><option value="Shift 1">Shift 1 (06:00-14:00)</option><option value="Shift 2">Shift 2 (14:00-22:00)</option><option value="Shift 3">Shift 3 (22:00-06:00)</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Operator <span class="text-danger">*</span></label><input type="text" class="form-control" id="f_operator" maxlength="100"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label><select class="form-select" id="f_status"><option value="DRAFT">Draft</option><option value="PLANNED">Planned</option><option value="IN_PROGRESS">In Progress</option><option value="COMPLETED">Completed</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Notes</label><textarea class="form-control" id="f_notes" rows="2" maxlength="500"></textarea></div>
                </div>
                <div class="card shadow-sm border-0 mt-3"><div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div><label class="form-label fw-semibold mb-1">Target Items</label><div class="small text-muted">Target pcs per batch.</div></div>
                        <button type="button" class="btn btn-sm btn-success" id="btn-add-item"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table-items"><thead class="table-secondary"><tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th style="width:130px;" class="text-center">Target Pcs <span class="text-danger">*</span></th>
                            <th style="width:130px;" class="text-center">Actual Pcs</th>
                            <th style="width:60px;" class="text-center">Aksi</th>
                        </tr></thead><tbody id="items-tbody"><tr id="row-empty"><td colspan="4" class="text-center py-3 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i><span class="small">Belum ada item.</span></td></tr></tbody></table>
                    </div>
                </div></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
const tableUrl="{{route('jadwal-kemasan.table')}}",storeUrl="{{route('jadwal-kemasan.store')}}",showUrl="{{route('jadwal-kemasan.show','__ID__')}}",updateUrl="{{route('jadwal-kemasan.update','__ID__')}}",deleteUrl="{{route('jadwal-kemasan.destroy','__ID__')}}",csrf=$('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({headers:{'X-CSRF-TOKEN':csrf}});

let allData=[];
function getParams(){return{filter_search:$('#filter-search').val(),filter_line:$('#filter-line').val(),filter_shift:$('#filter-shift').val(),filter_date_from:$('#filter-date-from').val(),filter_date_to:$('#filter-date-to').val()}}

const tbl=$('#table-data').DataTable({processing:true,serverSide:true,scrollX:true,ajax:{url:tableUrl,data:function(d){$.extend(d,getParams())},dataSrc:function(json){allData=json.data||[];renderGrid(allData);return json.data}},columns:[
{data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
{data:'schedule_id',name:'schedule_id'},{data:'date_fmt',name:'date',className:'text-center'},
{data:'spk_kemasan_ref',name:'spk_kemasan_ref'},{data:'line_packaging_id',name:'line_packaging_id'},
{data:'product_name',name:'product_name'},{data:'target_pcs',name:'target_pcs',className:'text-center'},
{data:'shift',name:'shift'},{data:'operator',name:'operator'},
{data:'status_badge',name:'status',orderable:false,searchable:false,className:'text-center'},
{data:'notes',name:'notes',render:function(d){return d||'-'}},
{data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}]});

$('#filter-search').on('keyup',function(){tbl.ajax.reload()});
$('#filter-line').on('change',function(){tbl.ajax.reload()});
$('#filter-shift').on('change',function(){tbl.ajax.reload()});
$('#filter-date-from').on('change',function(){tbl.ajax.reload()});
$('#filter-date-to').on('change',function(){tbl.ajax.reload()});
$('#btn-reset-filter').on('click',function(){$('#filter-search').val('');$('#filter-line').val('all');$('#filter-shift').val('all');$('#filter-date-from').val('2026-07-28');$('#filter-date-to').val('2026-07-31');tbl.ajax.reload()});

function renderGrid(data){
    if(!data||!data.length){$('#sched-grid').html('<div class="text-center text-muted py-4">Tidak ada jadwal.</div>');return;}
    const from=new Date($('#filter-date-from').val()||'2026-07-28');
    const to=new Date($('#filter-date-to').val()||'2026-07-31');
    const days=[];const d=new Date(from);while(d<=to){days.push(new Date(d));d.setDate(d.getDate()+1)}
    const lines={};data.forEach(function(r){const l=r.line_packaging_id||'Unknown';if(!lines[l])lines[l]=[];lines[l].push(r)});

    let html='<div class="p-2"><div class="legend"><span><span class="dot shift-1"></span>Shift 1</span><span><span class="dot shift-2"></span>Shift 2</span><span><span class="dot shift-3"></span>Shift 3</span></div></div>';
    html+='<table><thead><tr><th class="line-col">Line</th>';
    days.forEach(function(dd){html+='<th class="day-col">'+dd.toLocaleDateString('id-ID',{weekday:'short',day:'numeric',month:'short'})+'</th>'});
    html+='</tr></thead><tbody>';
    Object.keys(lines).sort().forEach(function(l){
        html+='<tr><td class="line-col">'+l+'</td>';
        days.forEach(function(dd){
            const dateStr=dd.toISOString().slice(0,10);
            const items=lines[l].filter(function(r){return r.date===dateStr});
            html+='<td class="sched-cell">';
            items.forEach(function(r){
                const sc=r.status==='COMPLETED'?'completed':r.status==='IN_PROGRESS'?'in-progress':r.status==='PLANNED'?'planned':'draft';
                const shiftCls=r.shift==='Shift 1'?'shift-1':r.shift==='Shift 2'?'shift-2':'shift-3';
                html+='<div class="sched-card '+sc+'">';
                html+='<span class="shift-badge '+shiftCls+'">'+r.shift.replace('Shift ','S')+'</span> ';
                html+='<strong>'+r.product_name+'</strong><br>';
                html+='<small class="text-muted">'+r.operator+'</small>';
                html+='</div>';
            });
            html+='</td>';
        });
        html+='</tr>';
    });
    html+='</tbody></table>';
    $('#sched-grid').html(html);
}

let itemIndex=0;
function addItemRow(data){const tbody=$('#items-tbody');$('#row-empty').hide();const i=itemIndex++;const tp=data?.target_pcs??'';const ap=data?.actual_pcs??'';tbody.append(`<tr><td class="text-center item-no">${tbody.find('tr:visible').length+1}</td><td><input type="number" class="form-control form-control-sm item-target-pcs" value="${tp}" placeholder="0" min="1"></td><td><input type="number" class="form-control form-control-sm item-actual-pcs" value="${ap}" placeholder="0" min="0"></td><td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-x-lg"></i></button></td></tr>`);renumberItems()}
function renumberItems(){$('#items-tbody tr:visible').each(function(i){$(this).find('.item-no').text(i+1)})}
$('#btn-add-item').on('click',function(){addItemRow()});
$('#items-tbody').on('click','.btn-remove-item',function(){$(this).closest('tr').remove();if(!$('#items-tbody tr:visible').not('#row-empty').length)$('#row-empty').show();renumberItems()});
function resetItems(){itemIndex=0;$('#items-tbody tr:not(#row-empty)').remove();$('#row-empty').show()}
function populateItems(items){resetItems();if(items&&items.length)items.forEach(function(i){addItemRow(i)})}
function collectItems(){const items=[];$('#items-tbody tr:visible').not('#row-empty').each(function(){const tp=parseInt($(this).find('.item-target-pcs').val())||0;const ap=parseInt($(this).find('.item-actual-pcs').val())||0;if(tp)items.push({target_pcs:tp,actual_pcs:ap})});return items}

const modal=$('#modal-data'),form=$('#form-data'),idI=$('#data_id');
function resetForm(){form[0].reset();idI.val('');form.find('.is-invalid').removeClass('is-invalid');form.find('.invalid-feedback').remove();modal.find('.modal-title').text('Tambah Jadwal Kemasan');resetItems()}
$('#btn-add').on('click',function(){resetForm();modal.modal('show')});
modal.on('hidden.bs.modal',resetForm);

window.onSave=function(){
    const id=idI.val(),url=id?updateUrl.replace('__ID__',id):storeUrl;
    const fd=new FormData();fd.append('_token',csrf);
    ['schedule_id','date','spk_kemasan_ref','line_packaging_id','product_name','shift','operator','status','notes'].forEach(function(k){fd.append(k,document.getElementById('f_'+k).value)});
    fd.append('items',JSON.stringify(collectItems()));
    if(id)fd.append('_method','PUT');
    $.ajax({url,type:'POST',data:fd,processData:false,contentType:false,dataType:'json',success:function(d){Swal.fire({title:'Sukses!',text:d.message,icon:'success',confirmButtonText:'OK'}).then(function(){resetForm();modal.modal('hide');tbl.ajax.reload(null,false)})},error:function(x){const r=x.responseJSON||{};if(x.status===422&&r.errors){Swal.fire({icon:'error',title:'Validasi Gagal',text:Object.values(r.errors).flat().join('\n')})}else{Swal.fire({icon:'error',title:'Error',text:r.message||'Terjadi kesalahan.'})}}})};

$('#table-data').on('click','.btn-edit',function(){const id=$(this).data('id');resetForm();$.get(showUrl.replace('__ID__',id)).done(function(r){const d=r.data||{};idI.val(d.id);['schedule_id','date','spk_kemasan_ref','line_packaging_id','product_name','shift','operator','status','notes'].forEach(function(k){const el=document.getElementById('f_'+k);if(el)el.value=d[k]??''});populateItems(d.items??[]);modal.find('.modal-title').text('Edit Jadwal Kemasan');modal.modal('show')}).fail(function(){Swal.fire({icon:'error',title:'Gagal',text:'Tidak dapat mengambil data.'})})});

$('#table-data').on('click','.btn-delete',function(){const id=$(this).data('id');Swal.fire({title:'Hapus jadwal ini?',text:'Data yang dihapus tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(function(r){if(!r.isConfirmed)return;$.ajax({url:deleteUrl.replace('__ID__',id),method:'POST',data:{_method:'DELETE'},success:function(r2){Swal.fire({icon:'success',title:r2.message||'Data dihapus',timer:1500,showConfirmButton:false});tbl.ajax.reload(null,false)},error:function(x){Swal.fire({icon:'error',title:'Gagal',text:(x.responseJSON||{}).message||'Gagal menghapus.'})}})})});
</script>
@endpush