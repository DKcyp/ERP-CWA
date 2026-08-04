@extends('layouts.layout')
@section('title', 'SPPPK - Surat Perintah Persiapan & Penggunaan Kemasan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-box-seam me-2"></i>SPPPK</h4>
        <small class="text-muted">Surat Perintah Persiapan & Penggunaan Kemasan</small>
    </div>
    <div>
        <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="SPPPK No / Batch / Product...">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Date From</label>
                <input type="date" class="form-control form-control-sm" id="filterDateFrom">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Date To</label>
                <input type="date" class="form-control form-control-sm" id="filterDateTo">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Status</label>
                <select class="form-select form-select-sm" id="filterStatus">
                    <option value="all">All</option>
                    <option value="Draft">Draft</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-sm btn-primary me-1" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah SPPPK</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()"><i class="bi bi-x-circle me-1"></i>Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table id="spppkTable" class="table table-hover table-sm mb-0" style="font-size:0.82rem;">
            <thead class="table-dark">
                <tr>
                    <th width="20">#</th>
                    <th>SPPPK No</th>
                    <th>Date</th>
                    <th>Batch No</th>
                    <th>Product Name</th>
                    <th>Packaging Line</th>
                    <th>Package Type</th>
                    <th>Target Pcs</th>
                    <th>Target Kg</th>
                    <th>Actual Pcs</th>
                    <th>Actual Kg</th>
                    <th>Status</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-box-seam me-1"></i><span id="modalTitle">Tambah SPPPK</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header Info</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">SPPPK No</label>
                                    <input type="text" class="form-control form-control-sm" id="spppk_no" placeholder="Auto-generated" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm" id="date" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Created By</label>
                                    <input type="text" class="form-control form-control-sm" id="created_by" value="User Login">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Production ID</label>
                                    <input type="text" class="form-control form-control-sm" id="production_id" placeholder="PRD-LST-XXXX">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Batch No</label>
                                    <input type="text" class="form-control form-control-sm" id="batch_no" placeholder="BN-XXXX">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="product_name" placeholder="Product name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Packaging Line ID <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="packaging_line_id" required>
                                        <option value="">-- Pilih --</option>
                                        <option>PACK-01</option><option>PACK-02</option><option>PACK-03</option><option>PACK-04</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-box me-1"></i>Detail Spesifikasi Kemasan</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Package Type <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="package_type" required>
                                        <option value="">-- Pilih --</option>
                                        <option>Kaleng 0.1L</option><option>Kaleng 0.9L</option><option>Galon 5L</option><option>Galon 10L</option><option>Pail 15L</option><option>Pail 20L</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Target Packing Qty (Pcs)</label>
                                    <input type="number" class="form-control form-control-sm" id="target_packing_qty_pcs" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Target Weight (Kg)</label>
                                    <input type="number" class="form-control form-control-sm" id="target_weight_kg" step="0.01" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Tare Weight Check (Kg)</label>
                                    <input type="number" class="form-control form-control-sm" id="tare_weight_check" step="0.01" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-box-arrow-in-down me-1"></i>Realisasi Filling & Penimbangan</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Actual Packed (Pcs)</label>
                                    <input type="number" class="form-control form-control-sm" id="actual_packed_pcs" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Actual Packed (Kg)</label>
                                    <input type="number" class="form-control form-control-sm" id="actual_packed_kg" step="0.01" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Reject/Damaged Packaging (Pcs)</label>
                                    <input type="number" class="form-control form-control-sm" id="reject_packaging_pcs" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Status</label>
                                    <select class="form-select form-select-sm" id="status">
                                        <option value="Draft">Draft</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm">Operator Packing</label>
                                    <input type="text" class="form-control form-control-sm" id="operator_packing" placeholder="Operator name">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label form-label-sm">Notes</label>
                                    <textarea class="form-control form-control-sm" id="notes" rows="2" placeholder="Notes..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="saveForm()"><i class="bi bi-check-lg me-1"></i>Simpan SPPPK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail SPPPK</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-script')
<script>
let table;
$(function(){
    table = $('#spppkTable').DataTable({
        processing: true, serverSide: true, ajax: { url: '{{ route("production-process-spppk.table") }}', data: function(d) {
            d.filter_search = $('#filterSearch').val();
            d.filter_date_from = $('#filterDateFrom').val();
            d.filter_date_to = $('#filterDateTo').val();
            d.filter_status = $('#filterStatus').val();
        }},
        columns: [
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'spppk_no',name:'spppk_no'},
            {data:'date_fmt',name:'date_fmt'},
            {data:'batch_no',name:'batch_no'},
            {data:'product_name',name:'product_name'},
            {data:'packaging_line_id',name:'packaging_line_id'},
            {data:'package_type',name:'package_type'},
            {data:'target_pcs_fmt',name:'target_pcs_fmt',className:'text-end'},
            {data:'target_kg_fmt',name:'target_kg_fmt',className:'text-end'},
            {data:'actual_pcs_fmt',name:'actual_pcs_fmt',className:'text-end'},
            {data:'actual_kg_fmt',name:'actual_kg_fmt',className:'text-end'},
            {data:'status_badge',name:'status_badge'},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order: [[2,'desc']],
        language: { processing:'Memuat data...' },
        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch, #filterStatus').on('change', () => table.ajax.reload());
    $('#filterSearch').on('keyup', debounce(() => table.ajax.reload(), 300));
});

function resetFilter(){ $('#filterSearch').val(''); $('#filterDateFrom').val(''); $('#filterDateTo').val(''); $('#filterStatus').val('all'); table.ajax.reload(); }

function openForm(){
    $('#modalTitle').text('Tambah SPPPK');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/production-process-spppk') }}/${id}`, function(d){
        $('#modalTitle').text('Edit SPPPK');
        $('#formId').val(d.id);
        $('#spppk_no').val(d.spppk_no||'');
        $('#date').val(d.date||'');
        $('#created_by').val(d.created_by||'');
        $('#production_id').val(d.production_id||'');
        $('#batch_no').val(d.batch_no||'');
        $('#product_name').val(d.product_name||'');
        $('#packaging_line_id').val(d.packaging_line_id||'');
        $('#package_type').val(d.package_type||'');
        $('#target_packing_qty_pcs').val(d.target_packing_qty_pcs||'');
        $('#target_weight_kg').val(d.target_weight_kg||'');
        $('#tare_weight_check').val(d.tare_weight_check||'');
        $('#actual_packed_pcs').val(d.actual_packed_pcs||'');
        $('#actual_packed_kg').val(d.actual_packed_kg||'');
        $('#reject_packaging_pcs').val(d.reject_packaging_pcs||'');
        $('#operator_packing').val(d.operator_packing||'');
        $('#notes').val(d.notes||'');
        $('#status').val(d.status||'Draft');
        new bootstrap.Modal('#formModal').show();
    });
}

function detailRecord(id){
    $.get(`{{ url('/production-process-spppk') }}/${id}`, function(d){
        const statusBadge = d.status === 'Completed' ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>'
            : d.status === 'In Progress' ? '<span class="badge bg-info"><i class="bi bi-hourglass-split me-1"></i>In Progress</span>'
            : '<span class="badge bg-secondary"><i class="bi bi-pencil me-1"></i>Draft</span>';
        const html = `
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i>Header Info</h6></div>
            <div class="card-body py-2">
                <div class="row g-2" style="font-size:0.85rem;">
                    <div class="col-md-3"><small class="text-muted d-block">SPPPK No</small><strong>${d.spppk_no||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Date</small><strong>${d.date||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Created By</small><strong>${d.created_by||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Production ID</small><strong>${d.production_id||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Batch No</small><strong>${d.batch_no||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Product Name</small><strong>${d.product_name||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Packaging Line</small><strong>${d.packaging_line_id||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Status</small>${statusBadge}</div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info"><i class="bi bi-box me-1"></i>Detail Spesifikasi Kemasan</h6></div>
            <div class="card-body py-2">
                <div class="row g-2" style="font-size:0.85rem;">
                    <div class="col-md-3"><small class="text-muted d-block">Package Type</small><strong>${d.package_type||'-'}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Target Qty (Pcs)</small><strong>${(d.target_packing_qty_pcs||0).toLocaleString()}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Target Weight (Kg)</small><strong>${(d.target_weight_kg||0).toLocaleString('id',{minimumFractionDigits:2})}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Tare Weight Check</small><strong>${(d.tare_weight_check||0).toLocaleString('id',{minimumFractionDigits:2})} Kg</strong></div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success"><i class="bi bi-box-arrow-in-down me-1"></i>Realisasi Filling & Penimbangan</h6></div>
            <div class="card-body py-2">
                <div class="row g-2" style="font-size:0.85rem;">
                    <div class="col-md-3"><small class="text-muted d-block">Actual Packed (Pcs)</small><strong>${(d.actual_packed_pcs||0).toLocaleString()}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Actual Packed (Kg)</small><strong>${(d.actual_packed_kg||0).toLocaleString('id',{minimumFractionDigits:2})}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Reject Pcs</small><strong class="${(d.reject_packaging_pcs||0) > 0 ? 'text-danger' : 'text-success'}">${d.reject_packaging_pcs||0}</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Operator Packing</small><strong>${d.operator_packing||'-'}</strong></div>
                    <div class="col-12"><small class="text-muted d-block">Notes</small><span>${d.notes||'-'}</span></div>
                </div>
            </div>
        </div>`;
        $('#detailContent').html(html);
        new bootstrap.Modal('#detailModal').show();
    });
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {
        spppk_no: $('#spppk_no').val(), date: $('#date').val(), created_by: $('#created_by').val(),
        production_id: $('#production_id').val(), batch_no: $('#batch_no').val(), product_name: $('#product_name').val(),
        packaging_line_id: $('#packaging_line_id').val(), package_type: $('#package_type').val(),
        target_packing_qty_pcs: $('#target_packing_qty_pcs').val(), target_weight_kg: $('#target_weight_kg').val(),
        tare_weight_check: $('#tare_weight_check').val(), actual_packed_pcs: $('#actual_packed_pcs').val(),
        actual_packed_kg: $('#actual_packed_kg').val(), reject_packaging_pcs: $('#reject_packaging_pcs').val(),
        operator_packing: $('#operator_packing').val(), notes: $('#notes').val(), status: $('#status').val(),
    };
    if(!payload.date){ alert('Date wajib diisi'); return; }
    if(!payload.product_name){ alert('Product Name wajib diisi'); return; }
    if(!payload.packaging_line_id){ alert('Packaging Line wajib dipilih'); return; }
    if(!payload.package_type){ alert('Package Type wajib dipilih'); return; }

    const url = id ? `{{ url('/production-process-spppk') }}/${id}` : '{{ route("production-process-spppk.store") }}';
    const method = id ? 'PUT' : 'POST';
    if(id) payload._method = 'PUT';

    $.ajax({ url, method, data: payload, success: function(r){
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
        table.ajax.reload();
        showToast(r.message || 'Data tersimpan', 'success');
    }, error: function(xhr){ alert('Error: '+xhr.responseText); }});
}

function deleteRecord(id){
    if(!confirm('Hapus SPPPK ini?')) return;
    $.ajax({ url: `{{ url('/production-process-spppk') }}/${id}`, method:'DELETE', data:{_method:'DELETE'}, success: function(r){
        table.ajax.reload();
        showToast(r.message || 'Data dihapus', 'success');
    }});
}
</script>
@endpush
