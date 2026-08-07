@extends('layouts.layout')
@section('title','Index Komisi Collection')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Tipe kategori...">
            </div>
            <div class="col-md-8 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Index</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.85rem;" id="indexTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Type</th>
                        <th class="text-end">Min (%)</th>
                        <th class="text-end">Max (%)</th>
                        <th class="text-center">Index Commission</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-medium py-2">
                <h6 class="modal-title"><i class="bi bi-calculator me-1"></i><span id="modalTitle">Tambah Index</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Type <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="type" required>
                                <option value="">-- Pilih --</option>
                                <option>Kolektibilitas 1</option><option>Kolektibilitas 2</option>
                                <option>Kolektibilitas 3</option><option>Kolektibilitas 4</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label form-label-sm">Min (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="min" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label form-label-sm">Max (%) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="max" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Index Commission <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm" id="index_commission" min="0" max="10" step="0.01" required>
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
@endsection

@push('after-script')
<script>
let table;
$(function(){
    table = $('#indexTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("index-komisi-collection.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'type',name:'type',render:function(d){return '<span class="badge bg-light text-dark border">'+d+'</span>'}},
            {data:'min_fmt',name:'min',className:'text-end'},
            {data:'max_fmt',name:'max',className:'text-end'},
            {data:'index_fmt',name:'index_commission',className:'text-center'},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[1,'asc']],
        language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
});

function openForm(){
    $('#modalTitle').text('Tambah Index');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/index-komisi-collection') }}/${id}`, function(d){
        $('#modalTitle').text('Edit Index');
        $('#formId').val(d.id);
        $('#type').val(d.type||'');
        $('#min').val(d.min??'');
        $('#max').val(d.max??'');
        $('#index_commission').val(d.index_commission??'');
        new bootstrap.Modal('#formModal').show();
    });
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {
        type: $('#type').val(), min: $('#min').val(), max: $('#max').val(),
        index_commission: $('#index_commission').val(),
    };
    if(!payload.type){alert('Type wajib dipilih');return;}
    if(payload.min===''||payload.max===''){alert('Min/Max wajib diisi');return;}
    if(payload.index_commission===''){alert('Index wajib diisi');return;}

    const url = id ? `{{ url('/index-komisi-collection') }}/${id}` : '{{ route("index-komisi-collection.store") }}';
    const method = id ? 'PUT' : 'POST';
    if(id) payload._method = 'PUT';

    $.ajax({url, method, data:payload, success:function(r){
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
        table.ajax.reload();
        showToast(r.message||'Data tersimpan','success');
    },error:function(xhr){
        const e=xhr.responseJSON||{};
        alert('Error: '+(e.message||xhr.responseText));
    }});
}

function deleteRecord(id){
    if(!confirm('Hapus index ini?'))return;
    $.ajax({url:`{{ url('/index-komisi-collection') }}/${id}`,method:'DELETE',data:{_method:'DELETE'},success:function(r){
        table.ajax.reload();
        showToast(r.message||'Data dihapus','success');
    }});
}
</script>
@endpush
