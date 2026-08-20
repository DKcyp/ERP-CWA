@extends('layouts.layout-minimal')
@section('title', 'Detail Riset Result Report')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-sm btn-outline-secondary" onclick="window.close()"><i class="bi bi-x-lg me-1"></i>Tutup</button>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-primary bg-opacity-10 py-2">
        <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i>Header Informasi LHR</h6>
    </div>
    <div class="card-body py-2">
        <div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-2"><small class="text-muted d-block">Riset ID</small><strong>RST-001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">No. LHR</small><strong>LHR-001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Product ID</small><strong>PRD-1001</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Nama Formula</small><strong>Cat Tembok Putih Premium</strong></div>
            <div class="col-md-1"><small class="text-muted d-block">FA</small><strong>FA-001</strong></div>
            <div class="col-md-1"><small class="text-muted d-block">Rev</small><strong>2</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Report ID</small><strong>RES-001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Created Date</small><strong>2026-08-15</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Status</small><span class="badge bg-success">Approved</span></div>
            <div class="col-md-2"><small class="text-muted d-block">User ID</small><strong>rina</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Substart</small><strong>Cat Dasar</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Pemakaian</small><strong>Interior</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Hapus STD Lama</small><strong>Tidak</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Last Status Update</small><strong>2026-08-18</strong></div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs" id="resultTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabFormulasi" type="button"><i class="bi bi-list-ol me-1"></i>Formulasi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabPengujian" type="button"><i class="bi bi-clipboard-check me-1"></i>Hasil Pengujian</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAplikasi" type="button"><i class="bi bi-gear me-1"></i>Data Aplikasi</button>
    </li>
</ul>

<div class="tab-content border border-top-0 rounded-bottom p-3 bg-white shadow-sm">

    {{-- TAB FORMULASI --}}
    <div class="tab-pane fade show active" id="tabFormulasi" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th width="20">#</th>
                        <th>Kode Bahan</th>
                        <th>Nama Bahan</th>
                        <th class="text-center">Urutan Proses</th>
                        <th class="text-end">Jumlah %</th>
                        <th class="text-end">Adjustment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>BB-001</td><td>Titanium Dioxide (TiO2)</td><td class="text-center">1</td><td class="text-end">15.00</td><td class="text-end">0.00</td></tr>
                    <tr><td>2</td><td>BB-002</td><td>Resin Acrylic Emulsion</td><td class="text-center">2</td><td class="text-end">35.00</td><td class="text-end">+0.50</td></tr>
                    <tr><td>3</td><td>BB-003</td><td>Calcium Carbonate</td><td class="text-center">3</td><td class="text-end">25.00</td><td class="text-end">0.00</td></tr>
                    <tr><td>4</td><td>BB-004</td><td>Water</td><td class="text-center">4</td><td class="text-end">20.00</td><td class="text-end">-0.50</td></tr>
                    <tr><td>5</td><td>BB-005</td><td>Additive - Thickener</td><td class="text-center">5</td><td class="text-end">5.00</td><td class="text-end">0.00</td></tr>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                        <td class="text-end"><strong>100.00%</strong></td>
                        <td class="text-end"><strong>0.00</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- TAB HASIL PENGUJIAN --}}
    <div class="tab-pane fade" id="tabPengujian" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Pengujian</th>
                        <th>Spek</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Viskositas (KU)</td><td>90 - 110 KU</td></tr>
                    <tr><td>Solid Content (%)</td><td>55 - 65%</td></tr>
                    <tr><td>pH</td><td>8.0 - 9.5</td></tr>
                    <tr><td>Hiding Power</td><td>&ge; 95%</td></tr>
                    <tr><td>Finishing</td><td>Smooth</td></tr>
                    <tr><td>Warna (Visual)</td><td>Match with Standard</td></tr>
                    <tr><td>Daya Tutup (Contrast Ratio)</td><td>&ge; 0.95</td></tr>
                    <tr><td>Adhesion (Cross Cut)</td><td>ASTM 5B</td></tr>
                    <tr><td>Ketahanan Air</td><td>&ge; 48 jam no blister</td></tr>
                    <tr><td>Ketahanan UV</td><td>&ge; 500 jam no crack</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB DATA APLIKASI --}}
    <div class="tab-pane fade" id="tabAplikasi" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th style="width:40%">Data dan Metode Aplikasi</th>
                        <th>Ketentuan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Spray Gun HVLP - Tekanan 20-25 psi, Nozzle 1.4-1.8mm, jarak 15-20cm dari permukaan</td>
                        <td>Suhu lingkungan 25-30C, Kelembaban &lt;80%, Permukaan bersih dari debu dan minyak. Gunakan masker dan sarung tangan.</td>
                    </tr>
                    <tr>
                        <td>Roller Cat - Ukuran roller 9-12 inch, durasi 2-3 menit per lapis, arah vertikal lalu horizontal</td>
                        <td>Daya tutup minimal 95% setelah 2 lapis. Waktu kering antar lapis minimal 2 jam.</td>
                    </tr>
                    <tr>
                        <td>Brush Manual - Kuas bulu sintetis ukuran 2-3 inch, sapuan searah, tekanan merata</td>
                        <td>Untuk area sempit dan sudut. Pastikan tidak ada bekas kuas yang terlihat.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- FOOTER: INSTRUKSI & JENIS SARINGAN --}}
<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-light py-2"><h6 class="mb-0"><i class="bi bi-funnel me-1"></i>Instruksi & Jenis Saringan</h6></div>
    <div class="card-body py-2">
        <div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-4">
                <label class="form-label form-label-sm">Instruksi Penyaringan</label>
                <select class="form-select form-select-sm">
                    <option selected>Gunakan saringan mesh 200 untuk penyaringan bahan baku pigmen</option>
                    <option>Untuk produk base, gunakan saringan mesh 100</option>
                    <option>Penyaringan kemasan: periksa kondisi kemasan sebelum diisi</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label form-label-sm">Jenis Saringan</label>
                <select class="form-select form-select-sm">
                    <option selected>Mesh 200 (75 micron)</option>
                    <option>Mesh 100 (150 micron)</option>
                    <option>Mesh 325 (44 micron)</option>
                    <option>Filter Cloth - Nylon</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label form-label-sm">Notes</label>
                <textarea class="form-control form-control-sm" rows="2" placeholder="Catatan tambahan...">Lulus semua spesifikasi. Formula direkomendasikan untuk produksi massal.</textarea>
            </div>
        </div>
    </div>
</div>
@endsection
