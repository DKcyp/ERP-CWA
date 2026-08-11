@extends('layouts.layout')
@section('title','Monitoring SPPBJ (Quality Control CM / Finished Goods)')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label form-label-sm">Search</label><input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Product / Batch / User..."></div>
            <div class="col-md-2"><label class="form-label form-label-sm">Keputusan</label>
                <select class="form-select form-select-sm" id="filterKeputusan"><option value="all">All</option><option>QC Approved</option><option>Reject CM</option><option>Rework ADU CM</option></select>
            </div>
            <div class="col-md-7 text-end"><button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Uji SPPBJ</button></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">QC Approved</small><h5 class="fw-bold mb-0 text-success" id="statApprove">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-danger shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Reject CM</small><h5 class="fw-bold mb-0 text-danger" id="statReject">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Rework ADU CM</small><h5 class="fw-bold mb-0 text-warning" id="statRework">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Total Uji</small><h5 class="fw-bold mb-0 text-primary" id="statTotal">-</h5></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.8rem;" id="mainTable">
                <thead class="table-dark">
                    <tr><th width="20">#</th><th>Product</th><th>Batch No</th><th>Type Prod</th><th>Waktu</th><th>User</th><th style="min-width:150px;">Parameter Fisikokimia</th><th style="min-width:130px;">Visual &amp; Fisik</th><th>Keputusan</th><th width="100">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-clipboard-check me-1"></i><span id="modalTitle">Tambah Uji SPPBJ</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm"><input type="hidden" id="formId">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabGeneral" type="button"><i class="bi bi-info-circle me-1"></i>General &amp; Batch Ref</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabWarna" type="button"><i class="bi bi-palette me-1"></i>Pengujian Warna &amp; Cat</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabKemasan" type="button"><i class="bi bi-box me-1"></i>QC Kemasan &amp; Pengisian</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabFooter" type="button"><i class="bi bi-check2-square me-1"></i>Keputusan Final</button></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tabGeneral">
                            <div class="card border-0 shadow-sm"><div class="card-body"><div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Product ID</label><input type="text" class="form-control form-control-sm" id="product_id"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Product Name *</label><input type="text" class="form-control form-control-sm" id="product_name" required></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Type Production</label><select class="form-select form-select-sm" id="type_production"><option>CM</option><option>CM Pasta</option><option>CM Base</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Batch No *</label><input type="text" class="form-control form-control-sm" id="batch_no" required></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">User ID *</label><input type="text" class="form-control form-control-sm" id="user_id" required></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-3"><label class="form-label form-label-sm">Tgl. Mulai</label><input type="datetime-local" class="form-control form-control-sm" id="tgl_mulai"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Tgl. Selesai</label><input type="datetime-local" class="form-control form-control-sm" id="tgl_selesai"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Appearance</label><select class="form-select form-select-sm" id="appearance"><option>Clear</option><option>Milky</option><option>Opaque</option></select></div>
                            </div></div></div>
                        </div>
                        <div class="tab-pane fade" id="tabWarna">
                            <div class="card border-0 shadow-sm"><div class="card-body"><div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Fineness (u)</label><input type="number" step="0.1" class="form-control form-control-sm" id="fineness"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Viskositas (ku)</label><input type="number" step="0.1" class="form-control form-control-sm" id="viskositas_ku"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Colour</label><input type="text" class="form-control form-control-sm" id="colour"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Hiding Power (%)</label><input type="number" step="0.01" class="form-control form-control-sm" id="hiding_power"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">SG (gr/ml)</label><input type="number" step="0.01" class="form-control form-control-sm" id="sg"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">pH</label><input type="number" step="0.1" class="form-control form-control-sm" id="ph"></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-2"><label class="form-label form-label-sm">Solid Content (%)</label><input type="number" step="0.1" class="form-control form-control-sm" id="solid_content"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Viskositas (detik)</label><input type="number" class="form-control form-control-sm" id="viskositas_detik"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Viskositas NK2 (detik)</label><input type="number" step="0.1" class="form-control form-control-sm" id="viskositas_nk2"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Gloss (%)</label><input type="number" step="0.1" class="form-control form-control-sm" id="gloss"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Colour Strenght (%)</label><input type="number" step="0.1" class="form-control form-control-sm" id="colour_strenght"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Adhesi</label><select class="form-select form-select-sm" id="adhesi"><option>OK</option><option>Not OK</option></select></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-2"><label class="form-label form-label-sm">Matching Test</label><select class="form-select form-select-sm" id="matching_test"><option>Pass</option><option>Fail</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Cycle Time (mnt)</label><input type="number" class="form-control form-control-sm" id="cycle_time"></div>
                            </div></div></div>
                        </div>
                        <div class="tab-pane fade" id="tabKemasan">
                            <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0">Pengujian Visual &amp; Fisik</h6></div>
                            <div class="card-body"><div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Layout</label><select class="form-select form-select-sm" id="layout"><option>OK</option><option>Not OK</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Drop Test</label><select class="form-select form-select-sm" id="drop_test"><option>Pass</option><option>Fail</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Seep Test</label><select class="form-select form-select-sm" id="seep_test"><option>Pass</option><option>Fail</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Ball Test</label><select class="form-select form-select-sm" id="ball_test"><option>Pass</option><option>Fail</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Miss Print</label><select class="form-select form-select-sm" id="miss_print"><option>None</option><option>Minor</option><option>Major</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Teks</label><select class="form-select form-select-sm" id="teks"><option>OK</option><option>Not OK</option></select></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-2"><label class="form-label form-label-sm">Tampilan</label><select class="form-select form-select-sm" id="tampilan"><option>Good</option><option>Fair</option><option>Poor</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Kebersihan Kemasan</label><select class="form-select form-select-sm" id="kebersihan_kemasan"><option>Bersih</option><option>Kotor</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Kualitas Cetakan</label><select class="form-select form-select-sm" id="kualitas_cetakan"><option>Good</option><option>Fair</option><option>Poor</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Berat (g)</label><input type="number" step="0.1" class="form-control form-control-sm" id="berat"></div>
                            </div></div></div>
                            <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0">Dimensi Kemasan</h6></div>
                            <div class="card-body"><div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Tinggi</label><input type="number" class="form-control form-control-sm" id="dim_tinggi"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Atas</label><input type="number" class="form-control form-control-sm" id="dim_atas"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Panjang</label><input type="number" class="form-control form-control-sm" id="dim_panjang"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Diameter Luar</label><input type="number" class="form-control form-control-sm" id="dim_diameter_luar"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Ring Dalam</label><input type="number" class="form-control form-control-sm" id="dim_ring_dalam"></div>
                            </div></div></div>
                            <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0">Pengujian Aset Pendukung</h6></div>
                            <div class="card-body"><div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Tinggi</label><input type="number" class="form-control form-control-sm" id="tinggi"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Panjang</label><input type="number" class="form-control form-control-sm" id="panjang"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Lebar</label><input type="number" class="form-control form-control-sm" id="lebar"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">P/L Bibir Kuas</label><input type="number" class="form-control form-control-sm" id="panjang_lebar_bibir_kuas"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Stapler Test</label><select class="form-select form-select-sm" id="stapler_test"><option>Pass</option><option>Fail</option></select></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-2"><label class="form-label form-label-sm">Berat 5'' &amp; 6''</label><input type="number" step="0.1" class="form-control form-control-sm" id="berat_5_6"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">P/L Bibir Kuas 5/6</label><input type="number" class="form-control form-control-sm" id="panjang_lebar_bibir_kuas_5_6"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Tinggi 5'' &amp; 6''</label><input type="number" class="form-control form-control-sm" id="tinggi_5_6"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Kualitas Cetakan 2</label><select class="form-select form-select-sm" id="kualitas_cetakan_2"><option>Good</option><option>Fair</option><option>Poor</option></select></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Stapler (4-5 bar)</label><select class="form-select form-select-sm" id="stapler_test_4_5"><option>Pass</option><option>Fail</option></select></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-2"><label class="form-label form-label-sm">Panjang 5'' &amp; 6''</label><input type="number" class="form-control form-control-sm" id="panjang_5_6"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Lebar 5'' &amp; 6''</label><input type="number" class="form-control form-control-sm" id="lebar_5_6"></div>
                            </div></div></div>
                        </div>
                        <div class="tab-pane fade" id="tabFooter">
                            <div class="card border-0 shadow-sm"><div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-8"><label class="form-label form-label-sm">Ringkasan QC</label><textarea class="form-control form-control-sm" id="note" rows="4" placeholder="Ringkasan hasil uji CM..."></textarea></div>
                                    <div class="col-md-4">
                                        <label class="form-label form-label-sm">Kesimpulan</label><textarea class="form-control form-control-sm" id="kesimpulan" rows="2"></textarea>
                                        <label class="form-label form-label-sm mt-2">Keputusan Final *</label>
                                        <select class="form-select form-select-sm" id="keputusan" required><option value="QC Approved">QC Approved</option><option value="Reject CM">Reject CM</option><option value="Rework ADU CM">Rework ADU CM</option></select>
                                    </div>
                                </div>
                            </div></div>
                        </div>
                    </div>
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
        <div class="modal-header bg-info text-white py-2"><h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Monitoring SPPBJ</h6><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" id="detailContent"></div>
        <div class="modal-footer py-2"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
function debounce(fn,ms){let t;return function(){clearTimeout(t);t=setTimeout(fn,ms);};}
function showToast(msg,type){var el=document.createElement('div');el.className='position-fixed top-0 end-0 p-3 z-3';el.innerHTML='<div class="toast show align-items-center text-bg-'+type+' border-0" role="alert"><div class="d-flex"><div class="toast-body">'+msg+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';document.body.appendChild(el);setTimeout(function(){el.remove();},3000);}
function tBadge(v){return v==='OK'||v==='Pass'||v==='Bersih'||v==='Good'||v==='None'?'<span class="badge bg-success">'+v+'</span>':'<span class="badge bg-danger">'+v+'</span>';}
let table;
$(function(){
    table=$('#mainTable').DataTable({processing:true,serverSide:true,
        ajax:{url:'{{route("monitoring-sppbj.table")}}',data:function(d){d.filter_search=$('#filterSearch').val();d.filter_keputusan=$('#filterKeputusan').val();}},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'product_badge',name:'product_name',orderable:false},
            {data:'batch_badge',name:'batch_no',orderable:false},
            {data:'type_production',name:'type_production'},
            {data:'date_range',name:'tgl_mulai',orderable:false},
            {data:'user_id',name:'user_id'},
            {data:'param_summary',name:'fineness',orderable:false},
            {data:'visual_summary',name:'layout',orderable:false},
            {data:'keputusan_badge',name:'keputusan',orderable:false},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],order:[[1,'desc']],language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup',debounce(function(){table.ajax.reload();},300));
    $('#filterKeputusan').on('change',function(){table.ajax.reload();});
    loadStats();
});
function loadStats(){
    $.get('{{route("monitoring-sppbj.table")}}',{draw:1,start:0,length:5000,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        var d=r.data||[];var a=0,re=0,ro=0;
        d.forEach(function(i){if(i.keputusan==='QC Approved')a++;else if(i.keputusan==='Reject CM')re++;else ro++;});
        $('#statApprove').text(a);$('#statReject').text(re);$('#statRework').text(ro);$('#statTotal').text(d.length);
    });
}
function openForm(){$('#modalTitle').text('Tambah Uji SPPBJ');$('#mainForm')[0].reset();$('#formId').val('');$('button[data-bs-target="#tabGeneral"]').tab('show');new bootstrap.Modal('#formModal').show();}
function editRecord(id){
    $.get('{{url("/monitoring-sppbj")}}/'+id,function(d){
        $('#modalTitle').text('Edit Uji SPPBJ');$('#formId').val(d.id);
        $.each(d,function(k,v){if($('#'+k).length)$('#'+k).val(v||'');});
        $('button[data-bs-target="#tabGeneral"]').tab('show');new bootstrap.Modal('#formModal').show();
    });
}
function detailRecord(id){
    $.get('{{url("/monitoring-sppbj")}}/'+id,function(d){
        var kb={'QC Approved':'bg-success','Reject CM':'bg-danger','Rework ADU CM':'bg-warning'};
        var h='<div class="row g-3" style="font-size:0.85rem;">';
        h+='<div class="col-md-6"><div class="card border-0 shadow-sm mb-3"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary">Header Info</h6></div><div class="card-body py-2"><div class="row g-2">';
        h+='<div class="col-4"><small class="text-muted d-block">Product</small><strong>'+(d.product_id||'-')+' - '+(d.product_name||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Batch No</small><span class="badge bg-secondary">'+(d.batch_no||'-')+'</span></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Type</small><strong>'+(d.type_production||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Mulai</small><strong>'+(d.tgl_mulai||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Selesai</small><strong>'+(d.tgl_selesai||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">User</small><strong>'+(d.user_id||'-')+'</strong></div>';
        h+='</div></div></div></div>';
        h+='<div class="col-md-6"><div class="card border-0 shadow-sm mb-3"><div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success">Keputusan</h6></div><div class="card-body py-2">';
        h+='<small class="text-muted d-block">Keputusan</small><span class="badge '+(kb[d.keputusan]||'bg-secondary')+'">'+(d.keputusan||'-')+'</span>';
        h+='<br><small class="text-muted d-block mt-2">Kesimpulan</small><strong>'+(d.kesimpulan||'-')+'</strong>';
        h+='<br><small class="text-muted d-block mt-2">Catatan</small>'+(d.note||'-');
        h+='</div></div></div></div></div>';
        h+='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info">Parameter Warna & Cat</h6></div><div class="card-body py-2"><div class="row g-2">';
        var chem=[['Fineness',d.fineness,'u'],['Viskositas',d.viskositas_ku,'ku'],['SG',d.sg,'gr/ml'],['pH',d.ph,''],['Solid',d.solid_content,'%'],['Gloss',d.gloss,'%'],['Hiding Power',d.hiding_power,'%'],['Colour Str.',d.colour_strenght,'%'],['Visk Detik',d.viskositas_detik,''],['Visk NK2',d.viskositas_nk2,''],['Cycle',d.cycle_time,'mnt'],['Colour',d.colour,''],['Matching',d.matching_test,''],['Adhesi',d.adhesi,'']];
        chem.forEach(function(c){h+='<div class="col"><small class="text-muted">'+c[0]+'</small><br><strong>'+(c[1]||'-')+' '+c[2]+'</strong></div>';});
        h+='</div></div></div>';
        h+='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-warning bg-opacity-10 py-2"><h6 class="mb-0 text-warning">QC Kemasan & Pengisian</h6></div><div class="card-body py-2"><div class="row g-2">';
        var vis=[['Layout',d.layout],['Drop',d.drop_test],['Seep',d.seep_test],['Ball',d.ball_test],['Miss Print',d.miss_print],['Teks',d.teks],['Tampilan',d.tampilan],['Kebersihan',d.kebersihan_kemasan],['Kualitas Cetakan',d.kualitas_cetakan],['Stapler',d.stapler_test]];
        vis.forEach(function(v){h+='<div class="col"><small class="text-muted">'+v[0]+'</small><br>'+tBadge(v[1])+'</div>';});
        h+='</div></div></div></div>';
        $('#detailContent').html(h);new bootstrap.Modal('#detailModal').show();
    });
}
function saveForm(){
    var id=$('#formId').val();var payload={};
    $('#mainForm input,#mainForm select,#mainForm textarea').each(function(){var el=$(this);if(el.attr('id')&&el.attr('id')!=='formId')payload[el.attr('id')]=el.val();});
    if(!payload.product_name){alert('Product Name wajib diisi');return;}
    if(!payload.batch_no){alert('Batch No wajib diisi');return;}
    if(!payload.user_id){alert('User ID wajib diisi');return;}
    if(!payload.keputusan){alert('Keputusan wajib diisi');return;}
    var url=id?'{{url("/monitoring-sppbj")}}/'+id:'{{route("monitoring-sppbj.store")}}';
    var method=id?'PUT':'POST';if(id)payload._method='PUT';
    $.ajax({url:url,method:method,data:payload,success:function(r){bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();table.ajax.reload();loadStats();showToast(r.message||'Tersimpan','success');},error:function(x){alert('Error: '+x.responseText);}});
}
function deleteRecord(id){if(!confirm('Hapus data ini?'))return;$.ajax({url:'{{url("/monitoring-sppbj")}}/'+id,method:'DELETE',data:{_method:'DELETE'},success:function(r){table.ajax.reload();loadStats();showToast(r.message||'Dihapus','success');}});}
</script>
@endpush