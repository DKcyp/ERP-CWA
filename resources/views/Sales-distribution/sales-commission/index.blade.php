@extends('layouts.layout')
@section('title','Sales Commission')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Comm No / Salesman / Period...">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Status</label>
                <select class="form-select form-select-sm" id="filterStatus">
                    <option value="all">All</option>
                    <option value="DRAFT">Draft</option>
                    <option value="CALCULATED">Calculated</option>
                    <option value="APPROVED">Approved</option>
                    <option value="PAID">Paid</option>
                    <option value="REJECTED">Rejected</option>
                </select>
            </div>
            <div class="col-md-7 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Commission</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Commission</small>
            <h5 class="fw-bold mb-0 text-primary" id="statTotal">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Pending Approval</small>
            <h5 class="fw-bold mb-0 text-warning" id="statPending">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Approved</small>
            <h5 class="fw-bold mb-0 text-success" id="statApproved">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-info shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Paid</small>
            <h5 class="fw-bold mb-0 text-info" id="statPaid">-</h5>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;" id="commissionTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Comm No</th>
                        <th>Date</th>
                        <th>Period</th>
                        <th>Salesman ID</th>
                        <th>Calculation Base</th>
                        <th class="text-end">Target Amount</th>
                        <th class="text-end">Achieved Amount</th>
                        <th>Rate</th>
                        <th class="text-end">Commission Paid</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-calculator me-1"></i><span id="modalTitle">Tambah Commission</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-person me-1"></i>Data Wiraniaga</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Comm No <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="comm_no" placeholder="Auto-generated" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm" id="date" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Period <span class="text-danger">*</span></label>
                                    <input type="month" class="form-control form-control-sm" id="period" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Salesman ID <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="salesman_id" required>
                                        <option value="">-- Pilih Wiraniaga --</option>
                                        <option>SLS-001</option><option>SLS-002</option><option>SLS-003</option><option>SLS-004</option><option>SLS-005</option>
                                        <option>SLS-006</option><option>SLS-007</option><option>SLS-008</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Calculation Base</label>
                                    <select class="form-select form-select-sm" id="calculation_base">
                                        <option value="Omset">Omset</option>
                                        <option value="Pelunasan">Pelunasan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-calculator me-1"></i>Kalkulasi Komisi</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Target Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" id="target_amount" min="0" oninput="hitungKomisi()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Achieved Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" id="achieved_amount" min="0" oninput="hitungKomisi()">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Rate % <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" id="commission_rate" min="0" max="100" step="0.01" oninput="hitungKomisi()">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Achv %</label>
                                    <input type="text" class="form-control form-control-sm" id="achv_pct" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm fw-bold text-primary">Total Commission</label>
                                    <input type="text" class="form-control form-control-sm fw-bold text-primary" id="total_commission_display" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label form-label-sm">Status</label>
                                    <select class="form-select form-select-sm" id="status">
                                        <option value="DRAFT">Draft</option>
                                        <option value="CALCULATED">Calculated</option>
                                        <option value="APPROVED">Approved</option>
                                        <option value="PAID">Paid</option>
                                        <option value="REJECTED">Rejected</option>
                                    </select>
                                </div>
                            </div>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Commission</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-success" id="btnApprove" onclick="approveRecord()"><i class="bi bi-check-circle me-1"></i>Approve</button>
                <button type="button" class="btn btn-sm btn-danger" id="btnReject" onclick="rejectRecord()"><i class="bi bi-x-circle me-1"></i>Reject</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let table, currentDetailId = null;
const salesmanNames = { 'SLS-001':'Ahmad Hidayat','SLS-002':'Dewi Lestari','SLS-003':'Rudi Hermawan','SLS-004':'Siti Nurhaliza','SLS-005':'Bambang Sutrisno','SLS-006':'Lina Maulida','SLS-007':'Andi Wijaya','SLS-008':'Rina Susanti' };

function formatRp(v){ return 'Rp '+Number(v||0).toLocaleString('id')}

$(function(){
    table = $('#commissionTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("sales-commission.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
            d.filter_status = $('#filterStatus').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'comm_no',name:'comm_no',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'date_fmt',name:'date'},
            {data:'period',name:'period'},
            {data:'salesman_id',name:'salesman_id',render:function(d){return '<span class="text-primary fw-semibold">'+d+'</span><br><small class="text-muted">'+(salesmanNames[d]||'')+'</small>'}},
            {data:'calculation_base_badge',name:'calculation_base',orderable:false},
            {data:'target_fmt',name:'target_amount',className:'text-end'},
            {data:'achieved_fmt',name:'achieved_amount',className:'text-end'},
            {data:'rate_fmt',name:'commission_rate'},
            {data:'total_commission_fmt',name:'total_commission_paid',className:'text-end fw-bold text-success'},
            {data:'status_badge',name:'status',orderable:false},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[2,'desc']],
        language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
    $('#filterStatus').on('change', ()=>table.ajax.reload());

    $.get('{{ route("sales-commission.table") }}',{draw:1,start:0,length:200,'columns[0][data]':'DT_RowIndex','order[0][column]':2,'order[0][dir]':'desc'},function(r){
        const d=r.data||[];
        let total=0,pending=0,approved=0,paid=0;
        d.forEach(function(i){total+=i.total_commission_paid||0; if(i.status==='CALCULATED')pending++; if(i.status==='APPROVED')approved++; if(i.status==='PAID')paid++});
        $('#statTotal').text(formatRp(total));
        $('#statPending').text(pending);
        $('#statApproved').text(approved);
        $('#statPaid').text(paid);
    });
});

function hitungKomisi(){
    const target=parseFloat($('#target_amount').val())||0;
    const achieved=parseFloat($('#achieved_amount').val())||0;
    const rate=parseFloat($('#commission_rate').val())||0;
    const pct=target>0?((achieved/target)*100):0;
    const totalComm=Math.round(achieved*(rate/100));
    $('#achv_pct').val(pct.toFixed(1)+'%').removeClass('text-success text-danger').addClass(pct>=100?'text-success':'text-danger');
    $('#total_commission_display').val(formatRp(totalComm));
    $('#total_commission_paid').val(totalComm);
}

function openForm(){
    $('#modalTitle').text('Tambah Commission');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    $('#achv_pct').val('');
    $('#total_commission_display').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/sales-commission') }}/${id}`, function(r){
        const d=r.data||{};
        $('#modalTitle').text('Edit Commission');
        $('#formId').val(d.id);
        $('#comm_no').val(d.comm_no||'');
        $('#date').val(d.date||'');
        $('#period').val(d.period||'');
        $('#salesman_id').val(d.salesman_id||'');
        $('#calculation_base').val(d.calculation_base||'Omset');
        $('#target_amount').val(d.target_amount||'');
        $('#achieved_amount').val(d.achieved_amount||'');
        $('#commission_rate').val(d.commission_rate||'');
        $('#total_commission_paid').val(d.total_commission_paid||'');
        $('#status').val(d.status||'DRAFT');
        hitungKomisi();
        new bootstrap.Modal('#formModal').show();
    });
}

function detailRecord(id){
    currentDetailId = id;
    $.get(`{{ url('/sales-commission') }}/${id}`, function(r){
        const d=r.data||{};
        const sName = salesmanNames[d.salesman_id]||'-';
        const statusBadge = {DRAFT:'bg-secondary',CALCULATED:'bg-primary',APPROVED:'bg-success',PAID:'bg-info text-dark',REJECTED:'bg-danger'}[d.status]||'bg-secondary';
        const baseBadge = d.calculation_base==='Pelunasan'?'bg-warning text-dark':'bg-light text-dark border';
        const target=d.target_amount||0, achieved=d.achieved_amount||0, pct=target>0?((achieved/target)*100).toFixed(1):'0.0';

        const html = `
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary"><i class="bi bi-person me-1"></i>Data Wiraniaga</h6></div>
            <div class="card-body py-2">
                <div class="row g-2" style="font-size:0.85rem;">
                    <div class="col-md-3"><small class="text-muted d-block">Comm No</small><strong>${d.comm_no||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Date</small><strong>${d.date||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Period</small><strong>${d.period||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Status</small><span class="badge ${statusBadge}">${d.status||'-'}</span></div>
                    <div class="col-md-4"><small class="text-muted d-block">Salesman ID</small><strong class="text-primary">${d.salesman_id||'-'}</strong><br><small class="text-muted">${sName}</small></div>
                    <div class="col-md-4"><small class="text-muted d-block">Calculation Base</small><span class="badge ${baseBadge}">${d.calculation_base||'Omset'}</span></div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success"><i class="bi bi-calculator me-1"></i>Kalkulasi Komisi</h6></div>
            <div class="card-body py-2">
                <div class="row g-2" style="font-size:0.85rem;">
                    <div class="col-md-3"><small class="text-muted d-block">Target Amount</small><strong>${formatRp(target)}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Achieved Amount</small><strong>${formatRp(achieved)}</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Achievement %</small><strong class="${pct>=100?'text-success':'text-danger'}">${pct}%</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Commission Rate</small><strong>${d.commission_rate||0}%</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Total Commission</small><h5 class="text-success fw-bold mb-0">${formatRp(d.total_commission_paid||0)}</h5></div>
                </div>
            </div>
        </div>`;
        $('#detailContent').html(html);
        const modal = new bootstrap.Modal('#detailModal');
        $('#btnApprove').toggle(d.status==='CALCULATED'||d.status==='DRAFT');
        $('#btnReject').toggle(d.status!=='REJECTED'&&d.status!=='PAID');
        modal.show();
    });
}

function approveRecord(){
    if(!currentDetailId) return;
    const payload = {status:'APPROVED'};
    $.ajax({url:`{{ url('/sales-commission') }}/${currentDetailId}`,method:'POST',data:{...payload,_method:'PUT'},success:function(){
        bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
        table.ajax.reload();
        showToast('Commission disetujui','success');
    }});
}

function rejectRecord(){
    if(!currentDetailId) return;
    const payload = {status:'REJECTED'};
    $.ajax({url:`{{ url('/sales-commission') }}/${currentDetailId}`,method:'POST',data:{...payload,_method:'PUT'},success:function(){
        bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
        table.ajax.reload();
        showToast('Commission ditolak','danger');
    }});
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {
        comm_no: $('#comm_no').val(), date: $('#date').val(), period: $('#period').val(),
        salesman_id: $('#salesman_id').val(), calculation_base: $('#calculation_base').val(),
        target_amount: $('#target_amount').val(), achieved_amount: $('#achieved_amount').val(),
        commission_rate: $('#commission_rate').val(), total_commission_paid: $('#target_amount').val() ? Math.round(parseFloat($('#achieved_amount').val()||0)*(parseFloat($('#commission_rate').val()||0)/100)) : 0,
        status: $('#status').val(),
    };
    if(!payload.date){alert('Date wajib diisi');return;}
    if(!payload.period){alert('Period wajib diisi');return;}
    if(!payload.salesman_id){alert('Salesman wajib dipilih');return;}
    if(!payload.target_amount){alert('Target wajib diisi');return;}

    const url = id ? `{{ url('/sales-commission') }}/${id}` : '{{ route("sales-commission.store") }}';
    const method = id ? 'PUT' : 'POST';
    if(id) payload._method = 'PUT';

    $.ajax({url, method, data:payload, success:function(r){
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
        table.ajax.reload();
        showToast(r.message||'Data tersimpan','success');
    }, error:function(xhr){
        const e=xhr.responseJSON||{};
        if(xhr.status===422&&e.errors){alert('Validation: '+Object.values(e.errors).flat().join(', '));}
        else alert('Error: '+xhr.responseText);
    }});
}

function deleteRecord(id){
    if(!confirm('Hapus commission ini?'))return;
    $.ajax({url:`{{ url('/sales-commission') }}/${id}`,method:'DELETE',data:{_method:'DELETE'},success:function(r){
        table.ajax.reload();
        showToast(r.message||'Data dihapus','success');
    }});
}
</script>
@endpush
