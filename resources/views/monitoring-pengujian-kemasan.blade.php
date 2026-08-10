@extends('layouts.layout')
@section('title','Monitoring Pengujian Kemasan')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label form-label-sm">Search</label><input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Product ID / Nama..."></div>
            <div class="col-md-2"><label class="form-label form-label-sm">Keputusan</label>
                <select class="form-select form-select-sm" id="filterKeputusan"><option value="all">All</option><option>Approve</option><option>Reject</option><option>Rework</option></select>
            </div>
            <div class="col-md-7 text-end"><button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Pengujian Kemasan</button></div>
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
            <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;" id="testTable">
                <thead class="table-dark">
                    <tr><th width="20">#</th><th>Date</th><th>Product ID</th><th>Product Name</th><th>User QC</th><th>Dimensi (P x L x T)</th><th>Keputusan</th><th>Note</th><th width="100">Aksi</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-box me-1"></i><span id="modalTitle">Tambah Pengujian Kemasan</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm"><input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header Info</h6></div>
                    <div class="card-body"><div class="row g-2">
                        <div class="col-md-2"><label class="form-label form-label-sm">Date</label><input type="date" class="form-control form-control-sm" id="date"></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Product ID</label><input type="text" class="form-control form-control-sm" id="product_id" placeholder="PRD-XXX"></div>
                        <div class="col-md-4"><label class="form-label form-label-sm">Product Name *</label><input type="text" class="form-control form-control-sm" id="product_name" required></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">User QC *</label><input type="text" class="form-control form-control-sm" id="user_qc" required></div>
                    </div></div></div>

                    <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-rulers me-1"></i>Dimensi Fisik (mm)</h6></div>
                    <div class="card-body"><div class="row g-2">
                        <div class="col"><label class="form-label form-label-sm">P [mm]</label><input type="number" class="form-control form-control-sm" id="dim_p" min="0"></div>
                        <div class="col"><label class="form-label form-label-sm">L [mm]</label><input type="number" class="form-control form-control-sm" id="dim_l" min="0"></div>
                        <div class="col"><label class="form-label form-label-sm">T [mm]</label><input type="number" class="form-control form-control-sm" id="dim_t" min="0"></div>
                        <div class="col"><label class="form-label form-label-sm">A [mm]</label><input type="number" class="form-control form-control-sm" id="dim_a" min="0"></div>
                        <div class="col"><label class="form-label form-label-sm">B [mm]</label><input type="number" class="form-control form-control-sm" id="dim_b" min="0"></div>
                        <div class="col"><label class="form-label form-label-sm">T [mm]</label><input type="number" class="form-control form-control-sm" id="dim_t2" min="0"></div>
                        <div class="col"><label class="form-label form-label-sm">S [mm]</label><input type="number" class="form-control form-control-sm" id="dim_s" min="0"></div>
                    </div></div></div>

                    <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-clipboard-check me-1"></i>Spesifikasi yang Diuji</h6></div>
                    <div class="card-body"><div class="row g-2">
                        <div class="col-md-3"><label class="form-label form-label-sm">Kebersihan</label><select class="form-select form-select-sm" id="test_kebersihan"><option>OK</option><option>Not OK</option></select></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Kualitas</label><select class="form-select form-select-sm" id="test_kualitas"><option>OK</option><option>Not OK</option></select></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Layout</label><select class="form-select form-select-sm" id="test_layout"><option>OK</option><option>Not OK</option></select></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Drop Test</label><select class="form-select form-select-sm" id="test_drop"><option>Pass</option><option>Fail</option></select></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Seep Test</label><select class="form-select form-select-sm" id="test_seep"><option>Pass</option><option>Fail</option></select></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Ball Test</label><select class="form-select form-select-sm" id="test_ball"><option>Pass</option><option>Fail</option></select></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Dimensi Visual</label><select class="form-select form-select-sm" id="test_dimensi_visual"><option>OK</option><option>Not OK</option></select></div>
                    </div></div></div>

                    <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-check2-square me-1"></i>Keputusan</h6></div>
                    <div class="card-body"><div class="row g-2">
                        <div class="col-md-4"><label class="form-label form-label-sm">Kesimpulan</label><input type="text" class="form-control form-control-sm" id="kesimpulan"></div>
                        <div class="col-md-3"><label class="form-label form-label-sm">Keputusan *</label><select class="form-select form-select-sm" id="keputusan" required><option value="Approve">Approve</option><option value="Reject">Reject</option><option value="Rework">Rework</option></select></div>
                        <div class="col-md-5"><label class="form-label form-label-sm">Note</label><textarea class="form-control form-control-sm" id="note" rows="2"></textarea></div>
                    </div></div></div>
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
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header bg-info text-white py-2"><h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Pengujian</h6><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" id="detailContent"></div>
        <div class="modal-footer py-2"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>
@endsection

@push('after-script')
<script>
let table;
function testBadge(v){return v==='OK'||v==='Pass'?'<span class="badge bg-success">'+v+'</span>':'<span class="badge bg-danger">'+v+'</span>';}
$(function(){
    table=$('#testTable').DataTable({processing:true,serverSide:true,
        ajax:{url:'{{route("monitoring-pengujian-kemasan.table")}}',data:function(d){d.filter_search=$('#filterSearch').val();d.filter_keputusan=$('#filterKeputusan').val();}},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'date_fmt',name:'date'},
            {data:'product_id',name:'product_id',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'product_name',name:'product_name'},
            {data:'user_qc',name:'user_qc'},
            {data:'dimensi_fmt',name:'dim_p'},
            {data:'keputusan_badge',name:'keputusan',orderable:false},
            {data:'note',name:'note'},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],order:[[1,'desc']],language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup',debounce(()=>table.ajax.reload(),300));
    $('#filterKeputusan').on('change',()=>table.ajax.reload());
    loadStats();
});
function loadStats(){
    $.get('{{route("monitoring-pengujian-kemasan.table")}}',{draw:1,start:0,length:500,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        const d=r.data||[];let a=0,re=0,ro=0;
        d.forEach(function(i){if(i.keputusan==='Approve')a++;if(i.keputusan==='Reject')re++;if(i.keputusan==='Rework')ro++;});
        $('#statApprove').text(a);$('#statReject').text(re);$('#statRework').text(ro);$('#statTotal').text(d.length);
    });
}
function openForm(){$('#modalTitle').text('Tambah Pengujian Kemasan');$('#mainForm')[0].reset();$('#formId').val('');new bootstrap.Modal('#formModal').show();}
function editRecord(id){
    $.get('{{url("/monitoring-pengujian-kemasan")}}/'+id,function(d){
        $('#modalTitle').text('Edit Pengujian Kemasan');$('#formId').val(d.id);
        $.each(d,function(k,v){if($('#'+k).length)$('#'+k).val(v||'');});
        new bootstrap.Modal('#formModal').show();
    });
}
function detailRecord(id){
    $.get('{{url("/monitoring-pengujian-kemasan")}}/'+id,function(d){
        const kb={Approve:'bg-success',Reject:'bg-danger',Rework:'bg-warning'};
        const html='<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary">Header Info</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">'+
            '<div class="col-md-3"><small class="text-muted d-block">Date</small><strong>'+(d.date||'-')+'</strong></div>'+
            '<div class="col-md-3"><small class="text-muted d-block">Product ID</small><strong>'+(d.product_id||'-')+'</strong></div>'+
            '<div class="col-md-3"><small class="text-muted d-block">Product Name</small><strong>'+(d.product_name||'-')+'</strong></div>'+
            '<div class="col-md-3"><small class="text-muted d-block">User QC</small><strong>'+(d.user_qc||'-')+'</strong></div>'+
            '</div></div></div>'+
            '<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info">Dimensi Fisik</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">'+
            '<div class="col"><small>P</small><br><strong>'+(d.dim_p||'-')+' mm</strong></div>'+
            '<div class="col"><small>L</small><br><strong>'+(d.dim_l||'-')+' mm</strong></div>'+
            '<div class="col"><small>T</small><br><strong>'+(d.dim_t||'-')+' mm</strong></div>'+
            '<div class="col"><small>A</small><br><strong>'+(d.dim_a||'-')+' mm</strong></div>'+
            '<div class="col"><small>B</small><br><strong>'+(d.dim_b||'-')+' mm</strong></div>'+
            '<div class="col"><small>T2</small><br><strong>'+(d.dim_t2||'-')+' mm</strong></div>'+
            '<div class="col"><small>S</small><br><strong>'+(d.dim_s||'-')+' mm</strong></div>'+
            '</div></div></div>'+
            '<div class="card border-0 shadow-sm mb-3"><div class="card-header bg-warning bg-opacity-10 py-2"><h6 class="mb-0 text-warning">Spesifikasi Diuji</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">'+
            '<div class="col-md-3"><small>Kebersihan</small><br>'+testBadge(d.test_kebersihan)+'</div>'+
            '<div class="col-md-3"><small>Kualitas</small><br>'+testBadge(d.test_kualitas)+'</div>'+
            '<div class="col-md-3"><small>Layout</small><br>'+testBadge(d.test_layout)+'</div>'+
            '<div class="col-md-3"><small>Drop Test</small><br>'+testBadge(d.test_drop)+'</div>'+
            '<div class="col-md-3"><small>Seep Test</small><br>'+testBadge(d.test_seep)+'</div>'+
            '<div class="col-md-3"><small>Ball Test</small><br>'+testBadge(d.test_ball)+'</div>'+
            '<div class="col-md-3"><small>Dimensi Visual</small><br>'+testBadge(d.test_dimensi_visual)+'</div>'+
            '</div></div></div>'+
            '<div class="card border-0 shadow-sm"><div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success">Keputusan</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">'+
            '<div class="col-md-4"><small>Kesimpulan</small><br><strong>'+(d.kesimpulan||'-')+'</strong></div>'+
            '<div class="col-md-3"><small>Keputusan</small><br><span class="badge '+(kb[d.keputusan]||'bg-secondary')+'">'+(d.keputusan||'-')+'</span></div>'+
            '<div class="col-md-5"><small>Note</small><br>'+(d.note||'-')+'</div>'+
            '</div></div></div>';
        $('#detailContent').html(html);new bootstrap.Modal('#detailModal').show();
    });
}
function saveForm(){
    var id=$('#formId').val();var payload={};
    $('#mainForm input,#mainForm select,#mainForm textarea').each(function(){var el=$(this);if(el.attr('id')&&el.attr('id')!=='formId')payload[el.attr('id')]=el.val();});
    if(!payload.product_name){alert('Product Name wajib diisi');return;}
    if(!payload.user_qc){alert('User QC wajib diisi');return;}
    var url=id?'{{url("/monitoring-pengujian-kemasan")}}/'+id:'{{route("monitoring-pengujian-kemasan.store")}}';
    var method=id?'PUT':'POST';if(id)payload._method='PUT';
    $.ajax({url:url,method:method,data:payload,success:function(r){bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();table.ajax.reload();loadStats();showToast(r.message||'Tersimpan','success');},error:function(x){alert('Error: '+x.responseText);}});
}
function deleteRecord(id){if(!confirm('Hapus data ini?'))return;$.ajax({url:'{{url("/monitoring-pengujian-kemasan")}}/'+id,method:'DELETE',data:{_method:'DELETE'},success:function(r){table.ajax.reload();loadStats();showToast(r.message||'Dihapus','success');}});}
</script>
@endpush
