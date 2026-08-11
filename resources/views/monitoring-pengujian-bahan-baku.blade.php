@extends('layouts.layout')
@section('title','Monitoring Pengujian Bahan Baku')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label form-label-sm">Search</label><input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Product / Batch / Supplier..."></div>
            <div class="col-md-2"><label class="form-label form-label-sm">Keputusan</label>
                <select class="form-select form-select-sm" id="filterKeputusan"><option value="all">All</option><option>Approve</option><option>Reject</option><option>Rework</option></select>
            </div>
            <div class="col-md-2"><label class="form-label form-label-sm">Dari Tanggal</label><input type="date" class="form-control form-control-sm" id="filterDateFrom"></div>
            <div class="col-md-2"><label class="form-label form-label-sm">Sampai Tanggal</label><input type="date" class="form-control form-control-sm" id="filterDateTo"></div>
            <div class="col-md-4 text-end"><button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Pengujian Bahan Baku</button></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Approve</small><h5 class="fw-bold mb-0 text-success" id="statApprove">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-danger shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Reject</small><h5 class="fw-bold mb-0 text-danger" id="statReject">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Rework</small><h5 class="fw-bold mb-0 text-warning" id="statRework">-</h5></div></div></div>
    <div class="col-md-3"><div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2"><small class="text-muted">Total Pengujian</small><h5 class="fw-bold mb-0 text-primary" id="statTotal">-</h5></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;" id="mainTable">
                <thead class="table-dark">
                    <tr><th width="20">#</th><th>Product</th><th>Batch No</th><th>Supplier</th><th>Tgl Uji</th><th>User QC</th><th>Keputusan</th><th width="100">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-flask me-1"></i><span id="modalTitle">Tambah Pengujian Bahan Baku</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm"><input type="hidden" id="formId">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabGeneral" type="button"><i class="bi bi-info-circle me-1"></i>Header Info</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabChem" type="button"><i class="bi bi-droplet me-1"></i>Parameter Kimia &amp; Fisika</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabVis" type="button"><i class="bi bi-eye me-1"></i>Parameter Visual &amp; Teknis</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabFoot" type="button"><i class="bi bi-check2-square me-1"></i>Keputusan</button></li>
                    </ul>
                    <div class="tab-content">
<div class="tab-pane fade show active" id="tabGeneral">
                            <div class="card border-0 shadow-sm"><div class="card-body"><div class="row g-2">
                                <div class="col-md-2"><label class="form-label form-label-sm">Product ID</label><input type="text" class="form-control form-control-sm" id="product_id"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Product Name *</label><input type="text" class="form-control form-control-sm" id="product_name" required></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Batch Number *</label><input type="text" class="form-control form-control-sm" id="batch_number" required></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Supplier *</label><input type="text" class="form-control form-control-sm" id="supplier" required></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">User QC *</label><input type="text" class="form-control form-control-sm" id="user_qc" required></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-3"><label class="form-label form-label-sm">Tanggal Datang</label><input type="date" class="form-control form-control-sm" id="tanggal_datang"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Tanggal Uji</label><input type="date" class="form-control form-control-sm" id="tanggal_uji"></div>
                            </div></div></div>
                        </div>
                        <div class="tab-pane fade" id="tabChem">
                            <div class="card border-0 shadow-sm"><div class="card-body"><div class="row g-2">
                                <div class="col-md-3"><label class="form-label form-label-sm">Solid Content (%)</label><input type="number" step="0.1" class="form-control form-control-sm" id="solid_content"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Viscosity</label><input type="number" step="0.1" class="form-control form-control-sm" id="viscosity"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">pH</label><input type="number" step="0.1" class="form-control form-control-sm" id="ph"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Specific Gravity</label><input type="number" step="0.01" class="form-control form-control-sm" id="specific_gravity"></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-3"><label class="form-label form-label-sm">Kelembapan (%)</label><input type="number" step="0.1" class="form-control form-control-sm" id="kelembapan"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Berat (kg)</label><input type="number" step="0.01" class="form-control form-control-sm" id="berat"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Panjang (mm)</label><input type="number" class="form-control form-control-sm" id="panjang"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Lebar (mm)</label><input type="number" class="form-control form-control-sm" id="lebar"></div>
                            </div></div></div>
                        </div>
                        <div class="tab-pane fade" id="tabVis">
                            <div class="card border-0 shadow-sm"><div class="card-body"><div class="row g-2">
                                <div class="col-md-3"><label class="form-label form-label-sm">Appearance</label><select class="form-select form-select-sm" id="appearance"><option>Clear</option><option>Milky</option><option>Opaque</option><option>Crystalline</option></select></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Color Visual</label><input type="color" class="form-control form-control-sm form-control-color" id="color_visual"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Kebersihan</label><select class="form-select form-select-sm" id="kebersihan"><option>Bersih</option><option>Kotor</option></select></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Test Gantung</label><select class="form-select form-select-sm" id="test_gantung"><option>Pass</option><option>Fail</option></select></div>
                            </div><div class="row g-2 mt-1">
                                <div class="col-md-3"><label class="form-label form-label-sm">Kualitas Cetak</label><select class="form-select form-select-sm" id="kualitas_cetak"><option>Good</option><option>Fair</option><option>Poor</option></select></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Kerataan</label><select class="form-select form-select-sm" id="kerataan"><option>Rata</option><option>Tidak Rata</option></select></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Drop Test</label><select class="form-select form-select-sm" id="drop_test"><option>Pass</option><option>Fail</option></select></div>
                            </div></div></div>
                        </div>
                        <div class="tab-pane fade" id="tabFoot">
                            <div class="card border-0 shadow-sm"><div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-8"><label class="form-label form-label-sm">Catatan</label><textarea class="form-control form-control-sm" id="note" rows="3"></textarea></div>
                                    <div class="col-md-4">
                                        <label class="form-label form-label-sm">Kesimpulan</label><textarea class="form-control form-control-sm" id="kesimpulan" rows="2"></textarea>
                                        <label class="form-label form-label-sm mt-2">Keputusan *</label>
                                        <select class="form-select form-select-sm" id="keputusan" required><option value="Approve">Approve</option><option value="Reject">Reject</option><option value="Rework">Rework</option></select>
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
        <div class="modal-header bg-info text-white py-2"><h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Pengujian Bahan Baku</h6><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" id="detailContent"></div>
        <div class="modal-footer py-2"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection
@push('after-script')
<script>
function debounce(fn,ms){let t;return function(){clearTimeout(t);t=setTimeout(fn,ms);};}
function showToast(msg,type){var el=document.createElement('div');el.className='position-fixed top-0 end-0 p-3 z-3';el.innerHTML='<div class="toast show align-items-center text-bg-'+type+' border-0" role="alert"><div class="d-flex"><div class="toast-body">'+msg+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>';document.body.appendChild(el);setTimeout(function(){el.remove();},3000);}
function tBadge(v){return v==='OK'||v==='Pass'||v==='Bersih'||v==='Good'||v==='Rata'||v==='Clear'?'<span class="badge bg-success">'+v+'</span>':'<span class="badge bg-danger">'+v+'</span>';}
let table;
$(function(){
    table=$('#mainTable').DataTable({processing:true,serverSide:true,
        ajax:{url:'{{route("monitoring-pengujian-bahan-baku.table")}}',data:function(d){d.filter_search=$('#filterSearch').val();d.filter_keputusan=$('#filterKeputusan').val();d.filter_date_from=$('#filterDateFrom').val();d.filter_date_to=$('#filterDateTo').val();}},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'product_badge',name:'product_name',orderable:false},
            {data:'batch_badge',name:'batch_number',orderable:false},
            {data:'supplier',name:'supplier'},
            {data:'tanggal_uji_fmt',name:'tanggal_uji'},
            {data:'user_qc',name:'user_qc'},
            {data:'keputusan_badge',name:'keputusan',orderable:false},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],order:[[4,'desc']],language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup',debounce(function(){table.ajax.reload();},300));
    $('#filterKeputusan,#filterDateFrom,#filterDateTo').on('change',function(){table.ajax.reload();});
    loadStats();
});
function loadStats(){
    $.get('{{route("monitoring-pengujian-bahan-baku.table")}}',{draw:1,start:0,length:5000,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        var d=r.data||[];var a=0,re=0,ro=0;
        d.forEach(function(i){if(i.keputusan==='Approve')a++;else if(i.keputusan==='Reject')re++;else ro++;});
        $('#statApprove').text(a);$('#statReject').text(re);$('#statRework').text(ro);$('#statTotal').text(d.length);
    });
}
function openForm(){$('#modalTitle').text('Tambah Pengujian Bahan Baku');$('#mainForm')[0].reset();$('#formId').val('');$('button[data-bs-target="#tabGeneral"]').tab('show');new bootstrap.Modal('#formModal').show();}
function editRecord(id){
    $.get('{{url("/monitoring-pengujian-bahan-baku")}}/'+id,function(d){
        $('#modalTitle').text('Edit Pengujian Bahan Baku');$('#formId').val(d.id);
        $.each(d,function(k,v){if($('#'+k).length)$('#'+k).val(v||'');});
        $('button[data-bs-target="#tabGeneral"]').tab('show');new bootstrap.Modal('#formModal').show();
    });
}
function detailRecord(id){
    $.get('{{url("/monitoring-pengujian-bahan-baku")}}/'+id,function(d){
        var kb={Approve:'bg-success',Reject:'bg-danger',Rework:'bg-warning'};
        var h='<div class="row g-3" style="font-size:0.85rem;">';
        h+='<div class="col-md-6"><div class="card border-0 shadow-sm mb-3"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary">Header Info</h6></div><div class="card-body py-2"><div class="row g-2">';
        h+='<div class="col-4"><small class="text-muted d-block">Product</small><strong>'+(d.product_id||'-')+' - '+(d.product_name||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Batch No</small><span class="badge bg-secondary">'+(d.batch_number||'-')+'</span></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Supplier</small><strong>'+(d.supplier||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Tanggal Datang</small><strong>'+(d.tanggal_datang||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">Tanggal Uji</small><strong>'+(d.tanggal_uji||'-')+'</strong></div>';
        h+='<div class="col-4"><small class="text-muted d-block">User QC</small><strong>'+(d.user_qc||'-')+'</strong></div>';
        h+='</div></div></div></div>';
        h+='<div class="col-md-6"><div class="card border-0 shadow-sm mb-3"><div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success">Keputusan</h6></div><div class="card-body py-2">';
        h+='<small class="text-muted d-block">Keputusan</small><span class="badge '+(kb[d.keputusan]||'bg-secondary')+'">'+(d.keputusan||'-')+'</span>';
        h+='<br><small class="text-muted d-block mt-2">Kesimpulan</small><strong>'+(d.kesimpulan||'-')+'</strong>';
        h+='<br><small class="text-muted d-block mt-2">Note</small>'+(d.note||'-');
        h+='</div></div></div></div></div>';
        h+='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info">Parameter Kimia & Fisika</h6></div><div class="card-body py-2"><div class="row g-2">';
        var chem=[['Solid Content',d.solid_content,'%'],['Viscosity',d.viscosity,''],['pH',d.ph,''],['Specific Gravity',d.specific_gravity,''],['Kelembapan',d.kelembapan,'%'],['Berat',d.berat,'kg'],['Panjang',d.panjang,'mm'],['Lebar',d.lebar,'mm']];
        chem.forEach(function(c){h+='<div class="col"><small class="text-muted">'+c[0]+'</small><br><strong>'+(c[1]||'-')+' '+c[2]+'</strong></div>';});
        h+='</div></div></div>';
        h+='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-warning bg-opacity-10 py-2"><h6 class="mb-0 text-warning">Parameter Visual & Teknis</h6></div><div class="card-body py-2"><div class="row g-2">';
        var vis=[['Appearance',d.appearance],['Kebersihan',d.kebersihan],['Test Gantung',d.test_gantung],['Kualitas Cetak',d.kualitas_cetak],['Kerataan',d.kerataan],['Drop Test',d.drop_test]];
        vis.forEach(function(v){h+='<div class="col"><small class="text-muted">'+v[0]+'</small><br>'+tBadge(v[1])+'</div>';});
        h+='<div class="col"><small class="text-muted">Color Visual</small><br><span class="d-inline-block rounded" style="width:20px;height:20px;background:'+(d.color_visual||'#ccc')+';"></span> '+(d.color_visual||'-')+'</div>';
        h+='</div></div></div></div>';
        $('#detailContent').html(h);new bootstrap.Modal('#detailModal').show();
    });
}
function saveForm(){
    var id=$('#formId').val();var payload={};
    $('#mainForm input,#mainForm select,#mainForm textarea').each(function(){var el=$(this);if(el.attr('id')&&el.attr('id')!=='formId')payload[el.attr('id')]=el.val();});
    if(!payload.product_name){alert('Product Name wajib diisi');return;}
    if(!payload.batch_number){alert('Batch Number wajib diisi');return;}
    if(!payload.supplier){alert('Supplier wajib diisi');return;}
    if(!payload.user_qc){alert('User QC wajib diisi');return;}
    if(!payload.keputusan){alert('Keputusan wajib diisi');return;}
    var url=id?'{{url("/monitoring-pengujian-bahan-baku")}}/'+id:'{{route("monitoring-pengujian-bahan-baku.store")}}';
    var method=id?'PUT':'POST';if(id)payload._method='PUT';
    $.ajax({url:url,method:method,data:payload,success:function(r){bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();table.ajax.reload();loadStats();showToast(r.message||'Tersimpan','success');},error:function(x){alert('Error: '+x.responseText);}});
}
function deleteRecord(id){if(!confirm('Hapus data ini?'))return;$.ajax({url:'{{url("/monitoring-pengujian-bahan-baku")}}/'+id,method:'DELETE',data:{_method:'DELETE'},success:function(r){table.ajax.reload();loadStats();showToast(r.message||'Dihapus','success');}});}
</script>
@endpush