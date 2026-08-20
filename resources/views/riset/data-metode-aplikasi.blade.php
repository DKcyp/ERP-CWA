@extends('layouts.layout')
@section('title', 'Data dan Metode Aplikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-flask me-2"></i>Data dan Metode Aplikasi</h4>
        <small class="text-muted">Riset - Standar Metode & Parameter Pengaplikasian Produk</small>
    </div>
    <div>
        <button class="btn btn-sm btn-primary" onclick="openForm()"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm">Search</label>
                <input type="text" class="form-control form-control-sm" id="filterSearch" placeholder="Cari metode atau ketentuan...">
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilter()"><i class="bi bi-x-circle me-1"></i>Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0" style="font-size:0.82rem;">
            <thead class="table-dark">
                <tr>
                    <th width="20">#</th>
                    <th>Data dan Metode Aplikasi</th>
                    <th>Ketentuan</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title"><i class="bi bi-flask me-1"></i><span id="modalTitle">Tambah Data & Metode Aplikasi</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="mainForm">
                    <input type="hidden" id="formId">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Data dan Metode Aplikasi <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" id="metode_aplikasi" rows="3" placeholder="Deskripsikan metode dan cara aplikasi..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Ketentuan <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" id="ketentuan" rows="3" placeholder="Ketentuan dan parameter teknis..." required></textarea>
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
let data = [
    {id:1, metode_aplikasi:'Spray Gun HVLP - Tekanan 20-25 psi, Nozzle 1.4-1.8mm, jarak 15-20cm dari permukaan', ketentuan:'Suhu lingkungan 25-30C, Kelembaban <80%, Permukaan bersih dari debu dan minyak. Gunakan masker dan sarung tangan.'},
    {id:2, metode_aplikasi:'Roller Cat - Ukuran roller 9-12 inch, durasi 2-3 menit per lapis, arah vertikal lalu horizontal', ketentuan:'Daya tutup minimal 95% setelah 2 lapis. Waktu kering antar lapis minimal 2 jam.'},
    {id:3, metode_aplikasi:'Brush Manual - Kuas bulu sintetis ukuran 2-3 inch, sapuan searah, tekanan merata', ketentuan:'Untuk area sempit dan sudut. Pastikan tidak ada bekas kuas yang terlihat. 1 lapis saja untuk touch-up.'},
    {id:4, metode_aplikasi:'Immersion Dipping - Rendam produk dalam larutan cat selama 3-5 detik, angkat perlahan', ketentuan:'Konsentrasi cat 15-20%. Suhu larutan 20-25C. Kocok setiap 30 menit. Keringkan dengan hot air 60C.'},
    {id:5, metode_aplikasi:'Electrostatic Spray - Tegangan 60-80kV, Current 10-20uA, jarak 25-30cm', ketentuan:'Grounding pada objek wajib. Kelembaban <60%. Spray booth dengan exhaust minimal 0.5 m/s.'},
    {id:6, metode_aplikasi:'Powder Coating Spray - Tegangan 40-60kV, Tebal film 60-80 micron, curing 180C/20menit', ketentuan:'Objek harus grounded. Debu powder tidak boleh melebihi 2g/m3. Gunakan FIR oven untuk curing.'},
];

let editingId = null;

function renderTable(filtered) {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = (filtered || data).map((d, i) => `
        <tr>
            <td>${i + 1}</td>
            <td style="max-width:400px">${d.metode_aplikasi}</td>
            <td style="max-width:400px">${d.ketentuan}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editRecord(${d.id})"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRecord(${d.id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `).join('');
}

function filterData() {
    const s = document.getElementById('filterSearch').value.toLowerCase();
    const f = data.filter(d => d.metode_aplikasi.toLowerCase().includes(s) || d.ketentuan.toLowerCase().includes(s));
    renderTable(f);
}

function resetFilter() {
    document.getElementById('filterSearch').value = '';
    renderTable(data);
}

function openForm() {
    editingId = null;
    $('#modalTitle').text('Tambah Data & Metode Aplikasi');
    $('#mainForm')[0].reset();
    $('#formId').val('');
    new bootstrap.Modal('#formModal').show();
}

function editRecord(id) {
    const d = data.find(x => x.id === id);
    if (!d) return;
    editingId = id;
    $('#modalTitle').text('Edit Data & Metode Aplikasi');
    $('#formId').val(id);
    $('#metode_aplikasi').val(d.metode_aplikasi);
    $('#ketentuan').val(d.ketentuan);
    new bootstrap.Modal('#formModal').show();
}

function saveForm() {
    const metode = $('#metode_aplikasi').val().trim();
    const ketentuan = $('#ketentuan').val().trim();
    if (!metode) { alert('Data dan Metode Aplikasi wajib diisi'); return; }
    if (!ketentuan) { alert('Ketentuan wajib diisi'); return; }

    if (editingId) {
        const d = data.find(x => x.id === editingId);
        if (d) { d.metode_aplikasi = metode; d.ketentuan = ketentuan; }
    } else {
        const newId = Math.max(...data.map(x => x.id)) + 1;
        data.push({ id: newId, metode_aplikasi: metode, ketentuan: ketentuan });
    }
    bootstrap.Modal.getInstance(document.getElementById('formModal')).hide();
    renderTable(data);
}

function deleteRecord(id) {
    if (!confirm('Hapus data ini?')) return;
    data = data.filter(x => x.id !== id);
    renderTable(data);
}

$(function() {
    renderTable(data);
    $('#filterSearch').on('keyup', filterData);
});
</script>
@endpush
