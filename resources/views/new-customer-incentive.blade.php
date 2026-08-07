@extends('layouts.layout')
@section('title','New Customer Incentive')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="TA / Sales / Customer...">
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm">Transit Area</label>
                <select class="form-select form-select-sm" id="filterTA">
                    <option value="all">All TA</option>
                    <option>TA Bandung</option><option>TA Jakarta</option><option>TA Semarang</option><option>TA Surabaya</option><option>TA Bogor</option>
                </select>
            </div>
            <div class="col-md-7 text-end">
                <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah Incentive</button>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card border-start border-4 border-primary shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Insentif Sales</small>
            <h5 class="fw-bold mb-0 text-primary" id="statSales">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-info shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Insentif BDH</small>
            <h5 class="fw-bold mb-0 text-info" id="statBDH">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-warning shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Total Bonus DOS</small>
            <h5 class="fw-bold mb-0 text-warning" id="statDOS">-</h5>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-4 border-success shadow-sm h-100"><div class="card-body py-2">
            <small class="text-muted">Grand Total</small>
            <h5 class="fw-bold mb-0 text-success" id="statTotal">-</h5>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;" id="incentiveTable">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>TA</th>
                        <th>Sales</th>
                        <th>Customer</th>
                        <th>Pemilik</th>
                        <th>City</th>
                        <th class="text-end">Insentif Sales</th>
                        <th class="text-end">Insentif BDH</th>
                        <th class="text-end">Bonus DOS</th>
                        <th class="text-end">Total</th>
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
                <h6 class="modal-title"><i class="bi bi-cash-coin me-1"></i><span id="modalTitle">Tambah Incentive</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-geo-alt me-1"></i>Data Area & Sales</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Transit Area (TA) <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="ta" required>
                                        <option value="">-- Pilih TA --</option>
                                        <option>TA Bandung</option><option>TA Jakarta</option><option>TA Semarang</option><option>TA Surabaya</option><option>TA Bogor</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Sales <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" id="sales" required>
                                        <option value="">-- Pilih Sales --</option>
                                        <option>Ahmad Hidayat</option><option>Dewi Lestari</option><option>Rudi Hermawan</option><option>Siti Nurhaliza</option>
                                        <option>Bambang Sutrisno</option><option>Lina Maulida</option><option>Andi Wijaya</option><option>Rina Susanti</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">City</label>
                                    <input type="text" class="form-control form-control-sm" id="city">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-shop me-1"></i>Data Customer Baru (NOO)</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Customer <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="customer" placeholder="Nama customer baru" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Pemilik</label>
                                    <input type="text" class="form-control form-control-sm" id="pemilik" placeholder="Nama pemilik">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label form-label-sm">Alamat</label>
                                    <input type="text" class="form-control form-control-sm" id="alamat" placeholder="Alamat toko">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-calculator me-1"></i>Alokasi Insentif</h6></div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Insentif Sales <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" id="insentif_sales" min="0" oninput="hitungTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Insentif BDH (30%)</label>
                                    <input type="number" class="form-control form-control-sm" id="insentif_bdh" min="0" oninput="hitungTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm">Bonus DOS (15%)</label>
                                    <input type="number" class="form-control form-control-sm" id="bonus_dos" min="0" oninput="hitungTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label form-label-sm fw-bold text-success">Total</label>
                                    <input type="text" class="form-control form-control-sm fw-bold text-success" id="total_display" readonly>
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
                <h6 class="modal-title"><i class="bi bi-eye me-1"></i>Detail Incentive</h6>
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
function formatRp(v){ return 'Rp '+Number(v||0).toLocaleString('id'); }

$(function(){
    table = $('#incentiveTable').DataTable({
        processing:true, serverSide:true,
        ajax:{ url:'{{ route("new-customer-incentive.table") }}', data:function(d){
            d.filter_search = $('#filterSearch').val();
            d.filter_ta = $('#filterTA').val();
        }},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false},
            {data:'ta_badge',name:'ta',orderable:false},
            {data:'sales',name:'sales',render:function(d){return '<strong>'+d+'</strong>'}},
            {data:'customer',name:'customer'},
            {data:'pemilik',name:'pemilik'},
            {data:'city',name:'city'},
            {data:'insentif_sales_fmt',name:'insentif_sales',className:'text-end'},
            {data:'insentif_bdh_fmt',name:'insentif_bdh',className:'text-end'},
            {data:'bonus_dos_fmt',name:'bonus_dos',className:'text-end'},
            {data:'total_fmt',name:'total',className:'text-end'},
            {data:'action',name:'action',orderable:false,searchable:false},
        ],
        order:[[1,'asc']],
        language:{processing:'Memuat data...'},
        dom:'<"row"<"col-sm-6"l><"col-sm-6"f>>rtip',
    });
    $('#filterSearch').on('keyup', debounce(()=>table.ajax.reload(),300));
    $('#filterTA').on('change', ()=>table.ajax.reload());

    loadStats();
});

function loadStats(){
    $.get('{{ route("new-customer-incentive.table") }}',{draw:1,start:0,length:500,'columns[0][data]':'DT_RowIndex','order[0][column]':1,'order[0][dir]':'asc'},function(r){
        const d=r.data||[];
        let s=0,b=0,dos=0,t=0;
        d.forEach(function(i){
            s+=parseInt(String(i.insentif_sales_fmt).replace(/[^0-9]/g,''))||0;
            b+=parseInt(String(i.insentif_bdh_fmt).replace(/[^0-9]/g,''))||0;
            dos+=parseInt(String(i.bonus_dos_fmt).replace(/[^0-9]/g,''))||0;
            t+=parseInt(String(i.total_fmt).replace(/[^0-9]/g,''))||0;
        });
        $('#statSales').text(formatRp(s));
        $('#statBDH').text(formatRp(b));
        $('#statDOS').text(formatRp(dos));
        $('#statTotal').text(formatRp(t));
    });
}

function hitungTotal(){
    const is=parseInt($('#insentif_sales').val())||0;
    const bdh=parseInt($('#insentif_bdh').val())||Math.round(is*0.3);
    const dos=parseInt($('#bonus_dos').val())||Math.round(is*0.15);
    $('#insentif_bdh').val(bdh);
    $('#bonus_dos').val(dos);
    $('#total_display').val(formatRp(is+bdh+dos));
}

function openForm(){
    $('#modalTitle').text('Tambah Incentive');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    $('#total_display').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id){
    $.get(`{{ url('/new-customer-incentive') }}/${id}`, function(d){
        $('#modalTitle').text('Edit Incentive');
        $('#formId').val(d.id);
        $('#ta').val(d.ta||'');
        $('#sales').val(d.sales||'');
        $('#customer').val(d.customer||'');
        $('#pemilik').val(d.pemilik||'');
        $('#alamat').val(d.alamat||'');
        $('#city').val(d.city||'');
        $('#insentif_sales').val(d.insentif_sales||'');
        $('#insentif_bdh').val(d.insentif_bdh||'');
        $('#bonus_dos').val(d.bonus_dos||'');
        hitungTotal();
        new bootstrap.Modal('#formModal').show();
    });
}

function detailRecord(id){
    $.get(`{{ url('/new-customer-incentive') }}/${id}`, function(d){
        const html = `
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-primary bg-opacity-10 py-2"><h6 class="mb-0 text-primary"><i class="bi bi-geo-alt me-1"></i>Data Area & Sales</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-4"><small class="text-muted d-block">Transit Area</small><span class="badge bg-info border">${d.ta||'-'}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Sales</small><strong>${d.sales||'-'}</strong></div>
            <div class="col-md-4"><small class="text-muted d-block">City</small><strong>${d.city||'-'}</strong></div>
        </div></div></div>
        <div class="card border-0 shadow-sm mb-3"><div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info"><i class="bi bi-shop me-1"></i>Data Customer Baru</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-4"><small class="text-muted d-block">Customer</small><strong>${d.customer||'-'}</strong></div>
            <div class="col-md-4"><small class="text-muted d-block">Pemilik</small><strong>${d.pemilik||'-'}</strong></div>
            <div class="col-md-4"><small class="text-muted d-block">Alamat</small><strong>${d.alamat||'-'}</strong></div>
        </div></div></div>
        <div class="card border-0 shadow-sm"><div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success"><i class="bi bi-cash-coin me-1"></i>Alokasi Insentif</h6></div><div class="card-body py-2"><div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-3"><small class="text-muted d-block">Insentif Sales</small><strong class="text-primary">${formatRp(d.insentif_sales)}</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Insentif BDH (30%)</small><strong class="text-info">${formatRp(d.insentif_bdh)}</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Bonus DOS (15%)</small><strong class="text-warning">${formatRp(d.bonus_dos)}</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Grand Total</small><h5 class="text-success fw-bold mb-0">${formatRp(d.total)}</h5></div>
        </div></div></div>`;
        $('#detailContent').html(html);
        new bootstrap.Modal('#detailModal').show();
    });
}

function saveForm(){
    const id = $('#formId').val();
    const payload = {
        ta: $('#ta').val(), sales: $('#sales').val(), customer: $('#customer').val(),
        pemilik: $('#pemilik').val(), alamat: $('#alamat').val(), city: $('#city').val(),
        insentif_sales: $('#insentif_sales').val(), insentif_bdh: $('#insentif_bdh').val(), bonus_dos: $('#bonus_dos').val(),
    };
    if(!payload.ta){alert('TA wajib dipilih');return;}
    if(!payload.sales){alert('Sales wajib dipilih');return;}
    if(!payload.customer){alert('Customer wajib diisi');return;}

    const url = id ? `{{ url('/new-customer-incentive') }}/${id}` : '{{ route("new-customer-incentive.store") }}';
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
    if(!confirm('Hapus incentive ini?'))return;
    $.ajax({url:`{{ url('/new-customer-incentive') }}/${id}`,method:'DELETE',data:{_method:'DELETE'},success:function(r){
        table.ajax.reload();
        loadStats();
        showToast(r.message||'Data dihapus','success');
    }});
}
</script>
@endpush
