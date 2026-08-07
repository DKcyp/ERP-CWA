@extends('layouts.layout')
@section('title','Marketing Komisi Collection')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="TA / Marketing...">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Period</label>
                <input type="month" class="form-control form-control-sm" id="filterPeriod">
            </div>
            <div class="col-md-7 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Komisi</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-start border-4 border-danger shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Komisi >90 Hari</small>
            <h5 class="fw-bold mb-0 text-danger" id="statGt90">-</h5>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Komisi ≤90 Hari</small>
            <h5 class="fw-bold mb-0 text-primary" id="statLte90">-</h5>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Grand Total Komisi</small>
            <h5 class="fw-bold mb-0 text-success" id="statTotal">-</h5>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.78rem;" id="komisiTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Period</th>
                        <th>TA</th>
                        <th>Marketing</th>
                        <th colspan="5" class="text-center bg-danger bg-opacity-25">Piutang >90 Hari</th>
                        <th colspan="5" class="text-center bg-primary bg-opacity-25">Piutang ≤90 Hari</th>
                        <th class="text-end">Total</th>
                        <th width="90">Aksi</th>
                    </tr>
                    <tr>
                        <th></th><th></th><th></th><th></th>
                        <th class="text-end" style="font-size:0.75rem;">Target</th>
                        <th class="text-end" style="font-size:0.75rem;">Achv</th>
                        <th class="text-center" style="font-size:0.75rem;">%</th>
                        <th class="text-center" style="font-size:0.75rem;">Idx</th>
                        <th class="text-end" style="font-size:0.75rem;">Komisi</th>
                        <th class="text-end" style="font-size:0.75rem;">Target</th>
                        <th class="text-end" style="font-size:0.75rem;">Achv</th>
                        <th class="text-center" style="font-size:0.75rem;">%</th>
                        <th class="text-center" style="font-size:0.75rem;">Idx</th>
                        <th class="text-end" style="font-size:0.75rem;">Komisi</th>
                        <th></th><th></th>
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
                <h6 class="modal-title"><i class="bi bi-calculator me-1"></i><span id="modalTitle">Tambah Komisi Collection</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-person me-1"></i>Data Marketing</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3"><label class="form-label form-label-sm">Periode <span class="text-danger">*</span></label><input type="month" class="form-control form-control-sm" id="period" required></div>
                                <div class="col-md-4"><label class="form-label form-label-sm">TA <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="ta" required><option value="">-- Pilih TA --</option><option>TA Bandung</option><option>TA Jakarta</option><option>TA Semarang</option><option>TA Surabaya</option><option>TA Bogor</option></select>
                                </div>
                                <div class="col-md-5"><label class="form-label form-label-sm">Marketing <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="marketing" required><option value="">-- Pilih Marketing --</option><option>Ahmad Hidayat</option><option>Dewi Lestari</option><option>Rudi Hermawan</option><option>Siti Nurhaliza</option><option>Bambang Sutrisno</option><option>Lina Maulida</option><option>Andi Wijaya</option><option>Rina Susanti</option></select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="card border-danger h-100"><div class="card-header bg-danger bg-opacity-10 py-2"><h6 class="mb-0 text-danger"><i class="bi bi-clock-history me-1"></i>Piutang >90 Hari</h6></div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6"><label class="form-label form-label-sm">Target</label><input type="number" class="form-control form-control-sm" id="target_usia_piutang_gt90" min="0" oninput="autoCalc()"></div>
                                    <div class="col-6"><label class="form-label form-label-sm">Pencapaian</label><input type="number" class="form-control form-control-sm" id="pencapaian_gt90" min="0" oninput="autoCalc()"></div>
                                    <div class="col-4"><label class="form-label form-label-sm">Persentase</label><input type="text" class="form-control form-control-sm" id="pct_gt90_display" readonly></div>
                                    <div class="col-4"><label class="form-label form-label-sm">Index (≥80%)</label><input type="text" class="form-control form-control-sm fw-bold" id="idx_gt90_display" readonly></div>
                                    <div class="col-4"><label class="form-label form-label-sm fw-bold">Komisi</label><input type="text" class="form-control form-control-sm fw-bold text-danger" id="komisi_gt90_display" readonly></div>
                                </div>
                            </div></div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-primary h-100"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary"><i class="bi bi-clock me-1"></i>Piutang ≤90 Hari</h6></div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-6"><label class="form-label form-label-sm">Target</label><input type="number" class="form-control form-control-sm" id="target_usia_piutang_lte90" min="0" oninput="autoCalc()"></div>
                                    <div class="col-6"><label class="form-label form-label-sm">Pencapaian</label><input type="number" class="form-control form-control-sm" id="pencapaian_lte90" min="0" oninput="autoCalc()"></div>
                                    <div class="col-4"><label class="form-label form-label-sm">Persentase</label><input type="text" class="form-control form-control-sm" id="pct_lte90_display" readonly></div>
                                    <div class="col-4"><label class="form-label form-label-sm">Index (≥30%)</label><input type="text" class="form-control form-control-sm fw-bold" id="idx_lte90_display" readonly></div>
                                    <div class="col-4"><label class="form-label form-label-sm fw-bold">Komisi</label><input type="text" class="form-control form-control-sm fw-bold text-primary" id="komisi_lte90_display" readonly></div>
                                </div>
                            </div></div>
                        </div>
                    </div>

                    <div class="card border-success"><div class="card-body py-2 text-center">
                        <small class="text-muted">Total Komisi Bersih</small>
                        <h4 class="text-success fw-bold mb-0" id="total_komisi_display">Rp 0</h4>
                    </div></div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-success" onclick="saveForm()"><i class="bi bi-calculator me-1"></i>Hitung & Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Komisi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-footer py-2"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let table;
function fmtRp(v){ return 'Rp '+Number(v||0).toLocaleString('id'); }

$(function(){
    table = $('#komisiTable').DataTable({
        processing:true, serverSide:true, scrollX:true,
        ajax:{ url:'{{ route("marketing-komisi-collection.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
            d.filter_period = $('#filterPeriod').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'period_fmt',name:'period'},
            {data:'ta',name:'ta',render:function(d){return '<span class="badge bg-info border">'+d+'</span>'}},
            {data:'marketing',name:'marketing',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'target_gt90_fmt',name:'target_usia_piutang_gt90',className:'text-end'},
            {data:'achv_gt90_fmt',name:'pencapaian_gt90',className:'text-end'},
            {data:'pct_gt90_fmt',name:'persentase_gt90',className:'text-center'},
            {data:'index_gt90_fmt',name:'index_target_gt90',orderable:false,className:'text-center'},
            {data:'komisi_gt90_fmt',name:'komisi_gt90',className:'text-end'},
            {data:'target_lte90_fmt',name:'target_usia_piutang_lte90',className:'text-end'},
            {data:'achv_lte90_fmt',name:'pencapaian_lte90',className:'text-end'},
            {data:'pct_lte90_fmt',name:'persentase_lte90',className:'text-center'},
            {data:'index_lte90_fmt',name:'index_target_lte90',orderable:false,className:'text-center'},
            {data:'komisi_lte90_fmt',name:'komisi_lte90',className:'text-end'},
            {data:'total_komisi_fmt',name:'total_komisi',className:'text-end'},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[1,'desc']],
        language:{processing:'Memuat data...'},
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
    $('#filterPeriod').on('change', ()=>table.ajax.reload());
    loadStats();
});

function loadStats(){
    $.get('{{ route("marketing-komisi-collection.table") }}',{draw:1,start:0,length:500,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        const d=r.data||[];
        let g90=0,l90=0,tot=0;
        d.forEach(function(i){
            g90+=parseInt(String(i.komisi_gt90_fmt).replace(/[^0-9]/g,''))||0;
            l90+=parseInt(String(i.komisi_lte90_fmt).replace(/[^0-9]/g,''))||0;
            tot+=parseInt(String(i.total_komisi_fmt).replace(/[^0-9]/g,''))||0;
        });
        $('#statGt90').text(fmtRp(g90));
        $('#statLte90').text(fmtRp(l90));
        $('#statTotal').text(fmtRp(tot));
    });
}

function autoCalc(){
    const tGt=parseInt($('#target_usia_piutang_gt90').val())||0;
    const aGt=parseInt($('#pencapaian_gt90').val())||0;
    const pctGt=tGt>0?((aGt/tGt)*100):0;
    const idxGt=pctGt>=80?1.0:(pctGt>=60?0.7:0.3);
    const komGt=Math.round(aGt*0.005*idxGt);
    $('#pct_gt90_display').val(pctGt.toFixed(2)+'%').removeClass('text-success text-danger').addClass(pctGt>=80?'text-success':'text-danger');
    $('#idx_gt90_display').val(idxGt).removeClass('text-success text-warning text-danger').addClass(idxGt>=1?'text-success':idxGt>=0.7?'text-warning':'text-danger');
    $('#komisi_gt90_display').val(fmtRp(komGt));

    const tLt=parseInt($('#target_usia_piutang_lte90').val())||0;
    const aLt=parseInt($('#pencapaian_lte90').val())||0;
    const pctLt=tLt>0?((aLt/tLt)*100):0;
    const idxLt=pctLt>=30?1.5:(pctLt>=20?1.0:0.5);
    const komLt=Math.round(aLt*0.003*idxLt);
    $('#pct_lte90_display').val(pctLt.toFixed(2)+'%').removeClass('text-success text-danger').addClass(pctLt>=30?'text-success':'text-danger');
    $('#idx_lte90_display').val(idxLt).removeClass('text-success text-warning text-danger').addClass(idxLt>=1?'text-success':idxLt>=0.7?'text-warning':'text-danger');
    $('#komisi_lte90_display').val(fmtRp(komLt));

    $('#total_komisi_display').text(fmtRp(komGt+komLt));
}

function openForm(){
    $('#modalTitle').text('Tambah Komisi Collection');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    $('#pct_gt90_display,#idx_gt90_display,#komisi_gt90_display,#pct_lte90_display,#idx_lte90_display,#komisi_lte90_display,#total_komisi_display').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/marketing-komisi-collection') }}/${id}`, function(d){
        $('#modalTitle').text('Edit Komisi Collection');
        $('#formId').val(d.id);
        $('#period').val(d.period||'');
        $('#ta').val(d.ta||'');
        $('#marketing').val(d.marketing||'');
        $('#target_usia_piutang_gt90').val(d.target_usia_piutang_gt90||'');
        $('#pencapaian_gt90').val(d.pencapaian_gt90||'');
        $('#target_usia_piutang_lte90').val(d.target_usia_piutang_lte90||'');
        $('#pencapaian_lte90').val(d.pencapaian_lte90||'');
        autoCalc();
        new bootstrap.Modal('#formModal').show();
    });
}

function detailRecord(id){
    $.get(`{{ url('/marketing-komisi-collection') }}/${id}`, function(d){
        const html = `
        <div class="row g-3">
            <div class="col-12"><span class="badge bg-info border mb-2">${d.ta||'-'}</span> <strong>${d.marketing||'-'}</strong> <span class="text-muted ms-2">${d.period||'-'}</span></div>
            <div class="col-md-6">
                <div class="card border-danger"><div class="card-header bg-danger bg-opacity-10 py-2"><h6 class="mb-0 text-danger">Piutang >90 Hari</h6></div><div class="card-body py-2" style="font-size:0.85rem;">
                    <div class="d-flex justify-content-between mb-1"><span>Target</span><strong>${fmtRp(d.target_usia_piutang_gt90)}</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Pencapaian</span><strong>${fmtRp(d.pencapaian_gt90)}</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Persentase</span><strong>${d.persentase_gt90}%</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Index</span><span class="badge bg-success">${d.index_target_gt90}</span></div>
                    <div class="d-flex justify-content-between"><span>Komisi</span><strong class="text-danger">${fmtRp(d.komisi_gt90)}</strong></div>
                </div></div>
            </div>
            <div class="col-md-6">
                <div class="card border-primary"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary">Piutang ≤90 Hari</h6></div><div class="card-body py-2" style="font-size:0.85rem;">
                    <div class="d-flex justify-content-between mb-1"><span>Target</span><strong>${fmtRp(d.target_usia_piutang_lte90)}</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Pencapaian</span><strong>${fmtRp(d.pencapaian_lte90)}</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Persentase</span><strong>${d.persentase_lte90}%</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span>Index</span><span class="badge bg-success">${d.index_target_lte90}</span></div>
                    <div class="d-flex justify-content-between"><span>Komisi</span><strong class="text-primary">${fmtRp(d.komisi_lte90)}</strong></div>
                </div></div>
            </div>
            <div class="col-12 text-center"><hr><h5 class="text-success fw-bold">Total Komisi: ${fmtRp(d.total_komisi)}</h5></div>
        </div>`;
        $('#detailContent').html(html);
        new bootstrap.Modal('#detailModal').show();
    });
}

function saveForm(){
    const id = $('#formId').val();
    autoCalc();
    const payload = {
        period: $('#period').val(), ta: $('#ta').val(), marketing: $('#marketing').val(),
        target_usia_piutang_gt90: $('#target_usia_piutang_gt90').val(),
        pencapaian_gt90: $('#pencapaian_gt90').val(),
        target_usia_piutang_lte90: $('#target_usia_piutang_lte90').val(),
        pencapaian_lte90: $('#pencapaian_lte90').val(),
    };
    if(!payload.period||!payload.ta||!payload.marketing){alert('Periode/TA/Marketing wajib diisi');return;}

    const url = id ? `{{ url('/marketing-komisi-collection') }}/${id}` : '{{ route("marketing-komisi-collection.store") }}';
    const method = id ? 'PUT' : 'POST';
    if(id) payload._method = 'PUT';

    $.ajax({url, method, data:payload, success:function(r){
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
        table.ajax.reload();
        loadStats();
        showToast(r.message||'Data tersimpan','success');
    },error:function(xhr){alert('Error: '+xhr.responseText);}});
}

function deleteRecord(id){
    if(!confirm('Hapus komisi ini?'))return;
    $.ajax({url:`{{ url('/marketing-komisi-collection') }}/${id}`,method:'DELETE',data:{_method:'DELETE'},success:function(r){
        table.ajax.reload();
        loadStats();
        showToast(r.message||'Data dihapus','success');
    }});
}
</script>
@endpush
