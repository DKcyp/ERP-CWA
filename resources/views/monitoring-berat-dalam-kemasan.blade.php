@extends('layouts.layout')
@section('title','Monitoring Berat Dalam Kemasan')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label form-label-sm">Search</label><input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Production ID / Product / Batch..."></div>
            <div class="col-md-2"><label class="form-label form-label-sm">Dari Tanggal</label><input type="date" class="form-control form-control-sm" id="filterDateFrom"></div>
            <div class="col-md-2"><label class="form-label form-label-sm">Sampai Tanggal</label><input type="date" class="form-control form-control-sm" id="filterDateTo"></div>
            <div class="col-md-5 text-end"><button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Sampling Berat</button></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Total Sampling</small><h5 class="fw-bold mb-0 text-primary" id="statTotal">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Hari Ini</small><h5 class="fw-bold mb-0 text-success" id="statToday">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-info shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Batch Aktif</small><h5 class="fw-bold mb-0 text-info" id="statBatches">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Produk Diuji</small><h5 class="fw-bold mb-0 text-warning" id="statProducts">-</h5></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.78rem;" id="mainTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Date</th>
                        <th>Production ID</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Batch No</th>
                        <th class="text-center" style="min-width:200px;">Kaleng 0.1L (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Kaleng 0.2L (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Kaleng 0.4L (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Kaleng 0.45L (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Kaleng 0.9L (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Kaleng (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Galon (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Pail (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Liter (A/T/Ak)</th>
                        <th class="text-center" style="min-width:200px;">Kaleng 1L (A/T/Ak)</th>
                        <th>User ID</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-speedometer me-1"></i><span id="modalTitle">Tambah Sampling Berat</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm"><input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header Info</h6></div>
                    <div class="card-body"><div class="row g-2">
                        <div class="col-md-2"><label class="form-label form-label-sm">Production ID</label><input type="text" class="form-control form-control-sm" id="production_id" placeholder="PRD-XXXXXX-XXX"></div>
                        <div class="col-md-2"><label class="form-label form-label-sm">Date Test *</label><input type="date" class="form-control form-control-sm" id="date_test" required></div>
                        <div class="col-md-2"><label class="form-label form-label-sm">Product ID</label><input type="text" class="form-control form-control-sm" id="product_id" placeholder="PRD-XXX"></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Product Name *</label><input type="text" class="form-control form-control-sm" id="product_name" required></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Batch No *</label><input type="text" class="form-control form-control-sm" id="batch_no" required></div>
                    </div><div class="row g-2 mt-1">
                        <div class="col-md-3"><label class="form-label form-label-sm">User ID *</label><input type="text" class="form-control form-control-sm" id="user_id" required></div>
                        <div class="col-md-9 d-flex align-items-end"><div class="form-text">Isi berat dalam gram (g). Kosongkan jika kemasan tidak digunakan.</div></div>
                    </div></div></div>

                    <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-grid-3x3-gap me-1"></i>Matriks Input Penimbangan (gram)</h6></div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" style="font-size:0.8rem;">
                                <thead class="table-secondary">
                                    <tr><th style="min-width:140px;">Varian Kemasan</th><th class="text-center" width="33%">Awal (g)</th><th class="text-center" width="33%">Tengah (g)</th><th class="text-center" width="33%">Akhir (g)</th></tr>
                                </thead>
                                <tbody id="matrixBody"></tbody>
                            </table>
                        </div>
                    </div></div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="saveForm()"><i class="bi bi-check-lg me-1"></i>Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-xl"><div class="modal-content">
        <div class="modal-header bg-info text-white py-2"><h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Monitoring Berat</h6><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" id="detailContent"></div>
        <div class="modal-footer py-2"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection

@push('after-script')
<script>
const containers = [
    {key:'kaleng_01',label:'Kaleng 0.1L'},{key:'kaleng_02',label:'Kaleng 0.2L'},
    {key:'kaleng_04',label:'Kaleng 0.4L'},{key:'kaleng_045',label:'Kaleng 0.45L'},
    {key:'kaleng_09',label:'Kaleng 0.9L'},{key:'kaleng',label:'Kaleng'},
    {key:'galon',label:'Galon'},{key:'pail',label:'Pail'},
    {key:'liter',label:'Liter'},{key:'kaleng_1l',label:'Kaleng 1L'},
];

function buildMatrix(){
    let html='';
    containers.forEach(c=>{
        html+='<tr><td class="fw-bold">'+c.label+'</td>';
        html+='<td><input type="number" step="0.01" class="form-control form-control-sm" id="'+c.key+'_awal" placeholder="0"></td>';
        html+='<td><input type="number" step="0.01" class="form-control form-control-sm" id="'+c.key+'_tengah" placeholder="0"></td>';
        html+='<td><input type="number" step="0.01" class="form-control form-control-sm" id="'+c.key+'_akhir" placeholder="0"></td></tr>';
    });
    $('#matrixBody').html(html);
}
function debounce(fn,ms){let t;return function(){clearTimeout(t);t=setTimeout(fn,ms);};}
function showToast(msg,type){var toast=document.createElement('div');toast.className='position-fixed top-0 end-0 p-3 z-3';toast.innerHTML='<div class="toast show align-items-center text-bg-'+type+' border-0" role="alert"><div class="d-flex"><div class="toast-body">'+msg+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';document.body.appendChild(toast);setTimeout(function(){toast.remove();},3000);}

let table;
function fmtWeight(v){return v!=null?v:'-';}
$(function(){
    buildMatrix();
    table=$('#mainTable').DataTable({processing:true,serverSide:true,
        ajax:{url:'{{route("monitoring-berat-dalam-kemasan.table")}}',data:function(d){d.filter_search=$('#filterSearch').val();d.filter_date_from=$('#filterDateFrom').val();d.filter_date_to=$('#filterDateTo').val();}},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'date_test_fmt',name:'date_test',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'production_id',name:'production_id'},
            {data:'product_id',name:'product_id'},
            {data:'product_name',name:'product_name'},
            {data:'batch_no',name:'batch_no',render:function(d){return '<span class="badge bg-secondary">'+d+'</span>'}},
            {data:'kaleng_01_fmt',name:'kaleng_01_awal',orderable:false},
            {data:'kaleng_02_fmt',name:'kaleng_02_awal',orderable:false},
            {data:'kaleng_04_fmt',name:'kaleng_04_awal',orderable:false},
            {data:'kaleng_045_fmt',name:'kaleng_045_awal',orderable:false},
            {data:'kaleng_09_fmt',name:'kaleng_09_awal',orderable:false},
            {data:'kaleng_fmt',name:'kaleng_awal',orderable:false},
            {data:'galon_fmt',name:'galon_awal',orderable:false},
            {data:'pail_fmt',name:'pail_awal',orderable:false},
            {data:'liter_fmt',name:'liter_awal',orderable:false},
            {data:'kaleng_1l_fmt',name:'kaleng_1l_awal',orderable:false},
            {data:'user_id',name:'user_id'},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],order:[[1,'desc']],language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup',debounce(function(){table.ajax.reload();},300));
    $('#filterDateFrom,#filterDateTo').on('change',function(){table.ajax.reload();});
    loadStats();
});
function loadStats(){
    $.get('{{route("monitoring-berat-dalam-kemasan.table")}}',{draw:1,start:0,length:5000,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        var d=r.data||[];var today=new Date().toISOString().slice(0,10);var st=0,sb={},sp={};
        d.forEach(function(i){if(i.date_test===today)st++;sb[i.batch_no]=1;sp[i.product_id]=1;});
        $('#statTotal').text(d.length);$('#statToday').text(st);$('#statBatches').text(Object.keys(sb).length);$('#statProducts').text(Object.keys(sp).length);
    });
}
function openForm(){$('#modalTitle').text('Tambah Sampling Berat');$('#mainForm')[0].reset();$('#formId').val('');buildMatrix();new bootstrap.Modal('#formModal').show();}
function editRecord(id){
    $.get('{{url("/monitoring-berat-dalam-kemasan")}}/'+id,function(d){
        $('#modalTitle').text('Edit Sampling Berat');$('#formId').val(d.id);
        $.each(d,function(k,v){if($('#'+k).length){$('#'+k).val(v||'');}});
        new bootstrap.Modal('#formModal').show();
    });
}
function detailRecord(id){
    $.get('{{url("/monitoring-berat-dalam-kemasan")}}/'+id,function(d){
        var html='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary">Header Info</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">'+
            '<div class="col-md-2"><small class="text-muted d-block">Production ID</small><strong>'+(d.production_id||'-')+'</strong></div>'+
            '<div class="col-md-2"><small class="text-muted d-block">Date Test</small><strong>'+(d.date_test||'-')+'</strong></div>'+
            '<div class="col-md-2"><small class="text-muted d-block">Product ID</small><strong>'+(d.product_id||'-')+'</strong></div>'+
            '<div class="col-md-3"><small class="text-muted d-block">Product Name</small><strong>'+(d.product_name||'-')+'</strong></div>'+
            '<div class="col-md-3"><small class="text-muted d-block">Batch No</small><strong>'+(d.batch_no||'-')+'</strong></div>'+
            '</div><div class="row g-2 mt-1" style="font-size:0.85rem;">'+
            '<div class="col-md-3"><small class="text-muted d-block">User ID</small><strong>'+(d.user_id||'-')+'</strong></div>'+
            '</div></div></div>';
        html+='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info">Matriks Berat (gram)</h6></div><div class="card-body p-2"><div class="table-responsive"><table class="table table-bordered table-sm mb-0" style="font-size:0.82rem;">';
        html+='<thead class="table-secondary"><tr><th>Varian Kemasan</th><th class="text-center">Awal</th><th class="text-center">Tengah</th><th class="text-center">Akhir</th><th class="text-center">Rata-rata</th></tr></thead><tbody>';
        containers.forEach(function(c){
            var aw=d[c.key+'_awal']||0,tg=d[c.key+'_tengah']||0,ak=d[c.key+'_akhir']||0;
            var avg=(((aw*1)+(tg*1)+(ak*1))/3).toFixed(2);
            html+='<tr><td class="fw-bold">'+c.label+'</td><td class="text-end">'+aw+'</td><td class="text-end">'+tg+'</td><td class="text-end">'+ak+'</td><td class="text-end fw-bold text-primary">'+avg+'</td></tr>';
        });
        html+='</tbody></table></div></div></div>';
        $('#detailContent').html(html);new bootstrap.Modal('#detailModal').show();
    });
}
function saveForm(){
    var id=$('#formId').val();var payload={};
    $('#mainForm input,#mainForm select,#mainForm textarea').each(function(){var el=$(this);if(el.attr('id')&&el.attr('id')!=='formId')payload[el.attr('id')]=el.val();});
    if(!payload.product_name){alert('Product Name wajib diisi');return;}
    if(!payload.batch_no){alert('Batch No wajib diisi');return;}
    if(!payload.user_id){alert('User ID wajib diisi');return;}
    if(!payload.date_test){alert('Date Test wajib diisi');return;}
    var url=id?'{{url("/monitoring-berat-dalam-kemasan")}}/'+id:'{{route("monitoring-berat-dalam-kemasan.store")}}';
    var method=id?'PUT':'POST';if(id)payload._method='PUT';
    $.ajax({url:url,method:method,data:payload,success:function(r){bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();table.ajax.reload();loadStats();showToast(r.message||'Tersimpan','success');},error:function(x){alert('Error: '+x.responseText);}});
}
function deleteRecord(id){if(!confirm('Hapus data ini?'))return;$.ajax({url:'{{url("/monitoring-berat-dalam-kemasan")}}/'+id,method:'DELETE',data:{_method:'DELETE'},success:function(r){table.ajax.reload();loadStats();showToast(r.message||'Dihapus','success');}});}
</script>
@endpush
