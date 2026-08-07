@extends('layouts.layout')
@section('title','Non Customer')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="ID / Nama / Contact / Kota...">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Status</label>
                <select class="form-select form-select-sm" id="filterStatus">
                    <option value="all">All</option>
                    <option value="Prospect">Prospect</option>
                    <option value="In Follow-up">In Follow-up</option>
                    <option value="Converted">Converted</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="col-md-7 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Non Customer</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card border-start border-4 border-info shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Prospect</small>
            <h5 class="fw-bold mb-0 text-info" id="statProspect">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">In Follow-up</small>
            <h5 class="fw-bold mb-0 text-warning" id="statFollowup">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Converted</small>
            <h5 class="fw-bold mb-0 text-success" id="statConverted">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-danger shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Rejected</small>
            <h5 class="fw-bold mb-0 text-danger" id="statRejected">-</h5>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.8rem;" id="ncTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>NC ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Position</th>
                        <th>City</th>
                        <th>Channel</th>
                        <th>Phone</th>
                        <th>Mobile</th>
                        <th>Created</th>
                        <th>Status</th>
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
                <h6 class="modal-title"><i class="bi bi-person-plus me-1"></i><span id="modalTitle">Tambah Non Customer</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i>Header Info</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3"><label class="form-label form-label-sm">NC ID</label><input type="text" class="form-control form-control-sm" id="non_customer_id" placeholder="Auto" readonly></div>
                                <div class="col-md-4"><label class="form-label form-label-sm">Nama <span class="text-danger">*</span></label><input type="text" class="form-control form-control-sm" id="name" required></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Channel Outlet</label>
                                    <select class="form-select form-select-sm" id="channel_outlet"><option value="">-- Pilih --</option><option>Toko Cat</option><option>Bangunan</option><option>Toko Besi</option><option>Toko Cat Online</option><option>Agen Paint</option><option>Contractor</option><option>Toko Material</option><option>Paint Shop</option></select>
                                </div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Status</label>
                                    <select class="form-select form-select-sm" id="status"><option value="Prospect">Prospect</option><option value="In Follow-up">In Follow-up</option><option value="Converted">Converted</option><option value="Rejected">Rejected</option></select>
                                </div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Created Date</label><input type="date" class="form-control form-control-sm" id="created_date"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Kode Area</label><input type="text" class="form-control form-control-sm" id="kode_area" placeholder="022"></div>
                                <div class="col-md-6"><label class="form-label form-label-sm">Note</label><input type="text" class="form-control form-control-sm" id="note" placeholder="Catatan prospek"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-geo-alt me-1"></i>Alamat Lengkap</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-6"><label class="form-label form-label-sm">Address 1</label><input type="text" class="form-control form-control-sm" id="address1" placeholder="Jl. ..."></div>
                                <div class="col-md-6"><label class="form-label form-label-sm">Address 2</label><input type="text" class="form-control form-control-sm" id="address2" placeholder="RT/RW, Kelurahan"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Kecamatan</label><input type="text" class="form-control form-control-sm" id="kecamatan"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Kabupaten</label><input type="text" class="form-control form-control-sm" id="kabupaten"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Kota</label><input type="text" class="form-control form-control-sm" id="city"></div>
                                <div class="col-md-1"><label class="form-label form-label-sm">ZIP</label><input type="text" class="form-control form-control-sm" id="zip" maxlength="5"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Provinsi</label><input type="text" class="form-control form-control-sm" id="province"></div>
                                <div class="col-md-1"><label class="form-label form-label-sm">Country</label><input type="text" class="form-control form-control-sm" id="country" value="Indonesia"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-person-lines-fill me-1"></i>Kontak PIC</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3"><label class="form-label form-label-sm">Contact Person</label><input type="text" class="form-control form-control-sm" id="contact_person"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Position</label><input type="text" class="form-control form-control-sm" id="position" placeholder="Owner/Manager"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Phone</label><input type="text" class="form-control form-control-sm" id="phone" placeholder="022-XXXXXXX"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Mobile Phone</label><input type="text" class="form-control form-control-sm" id="mobile_phone" placeholder="08XXXXXXXXXX"></div>
                                <div class="col-md-2"><label class="form-label form-label-sm">Email</label><input type="email" class="form-control form-control-sm" id="email"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">NPWP</label><input type="text" class="form-control form-control-sm" id="npwp"></div>
                                <div class="col-md-3"><label class="form-label form-label-sm">Employee ID</label><input type="text" class="form-control form-control-sm" id="employee_id" placeholder="EMP-XXX"></div>
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
                <h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Non Customer</h6>
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
$(function(){
    table = $('#ncTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("non-customer.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
            d.filter_status = $('#filterStatus').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'non_customer_id',name:'non_customer_id',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'name',name:'name'},
            {data:'contact_person',name:'contact_person'},
            {data:'position',name:'position'},
            {data:'city',name:'city'},
            {data:'channel_outlet',name:'channel_outlet',render:function(d){return '<span class="badge bg-light text-dark border">'+d+'</span>'}},
            {data:'phone',name:'phone'},
            {data:'mobile_phone',name:'mobile_phone'},
            {data:'created_date_fmt',name:'created_date'},
            {data:'status_badge',name:'status',orderable:false},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[1,'asc']],
        language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
    $('#filterStatus').on('change', ()=>table.ajax.reload());

    $.get('{{ route("non-customer.table") }}',{draw:1,start:0,length:200,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        const d=r.data||[];
        let p=0,f=0,c=0,x=0;
        d.forEach(function(i){if(i.status==='Prospect')p++;if(i.status==='In Follow-up')f++;if(i.status==='Converted')c++;if(i.status==='Rejected')x++});
        $('#statProspect').text(p);
        $('#statFollowup').text(f);
        $('#statConverted').text(c);
        $('#statRejected').text(x);
    });
});

function openForm(){
    $('#modalTitle').text('Tambah Non Customer');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/non-customer') }}/${id}`, function(d){
        $('#modalTitle').text('Edit Non Customer');
        $('#formId').val(d.id);
        $.each(d,function(k,v){if($('#'+k).length)$('#'+k).val(v||'');});
        new bootstrap.Modal('#formModal').show();
    });
}

function detailRecord(id){
    $.get(`{{ url('/non-customer') }}/${id}`, function(d){
        const sb = {Prospect:'bg-info','In Follow-up':'bg-warning text-dark',Converted:'bg-success',Rejected:'bg-danger'};
        const html = `
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i>Header Info</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-3"><small class="text-muted d-block">NC ID</small><strong>${d.non_customer_id||'-'}</strong></div>
            <div class="col-md-4"><small class="text-muted d-block">Name</small><strong>${d.name||'-'}</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Status</small><span class="badge ${sb[d.status]||'bg-secondary'}">${d.status||'-'}</span></div>
            <div class="col-md-2"><small class="text-muted d-block">Channel</small><span class="badge bg-light text-dark border">${d.channel_outlet||'-'}</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Created</small>${d.created_date||'-'}</div>
            <div class="col-md-3"><small class="text-muted d-block">Kode Area</small>${d.kode_area||'-'}</div>
            <div class="col-md-6"><small class="text-muted d-block">Note</small>${d.note||'-'}</div>
        </div></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info"><i class="bi bi-geo-alt me-1"></i>Alamat</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">
            <div class="col-12"><small class="text-muted d-block">Address</small>${d.address1||''} ${d.address2||''}</div>
            <div class="col-md-2"><small>Kecamatan</small><br><strong>${d.kecamatan||'-'}</strong></div>
            <div class="col-md-2"><small>Kabupaten</small><br><strong>${d.kabupaten||'-'}</strong></div>
            <div class="col-md-2"><small>Kota</small><br><strong>${d.city||'-'}</strong></div>
            <div class="col-md-1"><small>ZIP</small><br><strong>${d.zip||'-'}</strong></div>
            <div class="col-md-2"><small>Provinsi</small><br><strong>${d.province||'-'}</strong></div>
            <div class="col-md-2"><small>Country</small><br><strong>${d.country||'-'}</strong></div>
        </div></div></div>
        <div class="card border-0 shadow-sm"><div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success"><i class="bi bi-person-lines-fill me-1"></i>Kontak PIC</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-3"><small>Contact</small><br><strong>${d.contact_person||'-'}</strong></div>
            <div class="col-md-2"><small>Position</small><br><strong>${d.position||'-'}</strong></div>
            <div class="col-md-2"><small>Phone</small><br><strong>${d.phone||'-'}</strong></div>
            <div class="col-md-3"><small>Mobile</small><br><strong>${d.mobile_phone||'-'}</strong></div>
            <div class="col-md-3"><small>Email</small><br><strong>${d.email||'-'}</strong></div>
            <div class="col-md-3"><small>NPWP</small><br><strong>${d.npwp||'-'}</strong></div>
            <div class="col-md-3"><small>Employee ID</small><br><strong>${d.employee_id||'-'}</strong></div>
        </div></div></div>`;
        $('#detailContent').html(html);
        new bootstrap.Modal('#detailModal').show();
    });
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {};
    $('#mainForm input, #mainForm select, #mainForm textarea').each(function(){
        const el = $(this);
        if(el.attr('id') && el.attr('id')!=='formId') payload[el.attr('id')] = el.val();
    });
    if(!payload.name){alert('Nama wajib diisi');return;}

    const url = id ? `{{ url('/non-customer') }}/${id}` : '{{ route("non-customer.store") }}';
    const method = id ? 'PUT' : 'POST';
    if(id) payload._method = 'PUT';

    $.ajax({url, method, data:payload, success:function(r){
        bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
        table.ajax.reload();
        showToast(r.message||'Data tersimpan','success');
    },error:function(xhr){alert('Error: '+xhr.responseText);}});
}

function deleteRecord(id){
    if(!confirm('Hapus Non Customer ini?'))return;
    $.ajax({url:`{{ url('/non-customer') }}/${id}`,method:'DELETE',data:{_method:'DELETE'},success:function(r){
        table.ajax.reload();
        showToast(r.message||'Data dihapus','success');
    }});
}
</script>
@endpush
