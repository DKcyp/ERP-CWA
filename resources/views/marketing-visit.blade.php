@extends('layouts.layout')
@section('title','Marketing Visit')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="ID / Nama...">
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
                <label class="form-label form-label-sm">NOO</label>
                <select class="form-select form-select-sm" id="filterNoo">
                    <option value="all">All</option>
                    <option value="Y">Ya</option>
                    <option value="N">Tidak</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Visit</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Kunjungan</small>
            <h5 class="fw-bold mb-0 text-primary" id="statTotal">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">NOO Ditemukan</small>
            <h5 class="fw-bold mb-0 text-success" id="statNOO">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-info shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Canvas</small>
            <h5 class="fw-bold mb-0 text-info" id="statCanvas">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Prospeksi</small>
            <h5 class="fw-bold mb-0 text-warning" id="statProspek">-</h5>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0" style="font-size:0.85rem;" id="visitTable">
            <thead class="table-dark">
                <tr>
                    <th width="20">#</th>
                    <th>Date</th>
                    <th>Hari</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Tipe</th>
                    <th>NOO</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-geo-alt me-1"></i><span id="modalTitle">Tambah Marketing Visit</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="date" required onchange="autoHari()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Hari</label>
                            <input type="text" class="form-control form-control-sm" id="hari" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">ID Referensi</label>
                            <select class="form-select form-select-sm" id="id_ref" onchange="autoName()">
                                <option value="">-- Pilih Customer/Non-Customer --</option>
                                <optgroup label="Non-Customer">
                                    <option value="NC-00001" data-name="PT Maju Jaya 1">NC-00001 - PT Maju Jaya 1</option>
                                    <option value="NC-00002" data-name="CV Berkah 2">NC-00002 - CV Berkah 2</option>
                                    <option value="NC-00003" data-name="Toko Sinar 3">NC-00003 - Toko Sinar 3</option>
                                    <option value="NC-00004" data-name="UD Makmur 4">NC-00004 - UD Makmur 4</option>
                                    <option value="NC-00005" data-name="PT Sentosa 5">NC-00005 - PT Sentosa 5</option>
                                    <option value="NC-00006" data-name="CV Pelangi 6">NC-00006 - CV Pelangi 6</option>
                                    <option value="NC-00007" data-name="Toko Abadi 7">NC-00007 - Toko Abadi 7</option>
                                    <option value="NC-00008" data-name="UD Sejahtera 8">NC-00008 - UD Sejahtera 8</option>
                                    <option value="NC-00009" data-name="PT Bintang 9">NC-00009 - PT Bintang 9</option>
                                    <option value="NC-00010" data-name="CV Cahaya 10">NC-00010 - CV Cahaya 10</option>
                                </optgroup>
                                <optgroup label="Customer">
                                    <option value="CUST-001" data-name="PT Maju Jaya Abadi">CUST-001 - PT Maju Jaya Abadi</option>
                                    <option value="CUST-002" data-name="CV Berkah Mulia">CUST-002 - CV Berkah Mulia</option>
                                    <option value="CUST-003" data-name="PT Sinar Terang Perkasa">CUST-003 - PT Sinar Terang Perkasa</option>
                                    <option value="CUST-004" data-name="CV Pelangi Cat Indonesia">CUST-004 - CV Pelangi Cat Indonesia</option>
                                    <option value="CUST-005" data-name="PT Sentosa Paint">CUST-005 - PT Sentosa Paint</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Tipe Kunjungan <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="tipe" required>
                                <option value="">-- Pilih --</option>
                                <option value="Canvas">Canvas</option>
                                <option value="Routine">Routine</option>
                                <option value="Prospeksi">Prospeksi</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="noo_check" onchange="$('#noo').val(this.checked?'Y':'N')">
                                <label class="form-check-label fw-semibold" for="noo_check">NOO (Outlet Baru)</label>
                            </div>
                            <input type="hidden" id="noo" value="N">
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
    table = $('#visitTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("marketing-visit.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
            d.filter_date_from = $('#filterDateFrom').val();
            d.filter_date_to = $('#filterDateTo').val();
            d.filter_noo = $('#filterNoo').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'date_fmt',name:'date'},
            {data:'hari',name:'hari'},
            {data:'id_ref',name:'id_ref',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'name',name:'name'},
            {data:'tipe_badge',name:'tipe',orderable:false},
            {data:'noo_badge',name:'noo',orderable:false},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[1,'desc']],
        language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
    $('#filterNoo').on('change', ()=>table.ajax.reload());

    loadStats();
});

function loadStats(){
    $.get('{{ route("marketing-visit.table") }}',{draw:1,start:0,length:500,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        const d=r.data||[];
        let total=d.length,noo=0,canvas=0,prospek=0;
        d.forEach(function(i){if(i.noo==='Y')noo++;if(i.tipe==='Canvas')canvas++;if(i.tipe==='Prospeksi')prospek++});
        $('#statTotal').text(total);
        $('#statNOO').text(noo);
        $('#statCanvas').text(canvas);
        $('#statProspek').text(prospek);
    });
}

function autoHari(){
    const d=$('#date').val();
    if(!d)return;
    const days=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $('#hari').val(days[new Date(d).getDay()]);
}

function autoName(){
    const sel=document.getElementById('id_ref');
    const opt=sel.options[sel.selectedIndex];
    if(opt&&opt.dataset.name) $('#name').val(opt.dataset.name);
}

function openForm(){
    $('#modalTitle').text('Tambah Marketing Visit');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    $('#noo').val('N');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/marketing-visit') }}/${id}`, function(d){
        $('#modalTitle').text('Edit Marketing Visit');
        $('#formId').val(d.id);
        $('#date').val(d.date||'');
        $('#hari').val(d.hari||'');
        $('#id_ref').val(d.id_ref||'');
        $('#name').val(d.name||'');
        $('#tipe').val(d.tipe||'');
        $('#noo').val(d.noo||'N');
        $('#noo_check').prop('checked', d.noo==='Y');
        new bootstrap.Modal('#formModal').show();
    });
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {
        date: $('#date').val(), hari: $('#hari').val(), id_ref: $('#id_ref').val(),
        name: $('#name').val(), tipe: $('#tipe').val(), noo: $('#noo').val(),
    };
    if(!payload.date){alert('Date wajib diisi');return;}
    if(!payload.name){alert('Nama wajib diisi');return;}
    if(!payload.tipe){alert('Tipe wajib dipilih');return;}

    const url = id ? `{{ url('/marketing-visit') }}/${id}` : '{{ route("marketing-visit.store") }}';
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
    if(!confirm('Hapus kunjungan ini?'))return;
    $.ajax({url:`{{ url('/marketing-visit') }}/${id}`,method:'DELETE',data:{_method:'DELETE'},success:function(r){
        table.ajax.reload();
        loadStats();
        showToast(r.message||'Data dihapus','success');
    }});
}
</script>
@endpush
