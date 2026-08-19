@extends('layouts.layout')
@section('title', 'Detail SPKP - Surat Perintah Kerja Produksi Base')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Detail SPKP</h4>
        <small class="text-muted">Surat Perintah Kerja Produksi Base</small>
    </div>
    <div>
        <a href="{{ url('/production-process-spkp') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-primary bg-opacity-10 py-2">
        <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i>Header Informasi</h6>
    </div>
    <div class="card-body py-2">
        <div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-2"><small class="text-muted d-block">Tgl. Jadwal</small><strong id="hdrJadwal">2026-08-19</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">No. SPKP</small><strong id="hdrNoSpkp">SPKP-260819-001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Kelompok Produk</small><strong id="hdrKelompok">Cat Tembok</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Nama Produk</small><strong id="hdrProduk">Cat Tembok Putih 25kg</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Batch No</small><strong id="hdrBatch">BATCH-A001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Tipe Product</small><span class="badge bg-info" id="hdrTipe">Water Based</span></div>
            <div class="col-md-2"><small class="text-muted d-block">Formulasi</small><strong id="hdrFormulasi">FM-WB-001</strong></div>
            <div class="col-md-1"><small class="text-muted d-block">Basis</small><strong id="hdrBasis">25.00</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Total Basis</small><strong id="hdrTotalBasis">25.50</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Status</small><span class="badge bg-success" id="hdrStatus">In Progress</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Notes</small><span id="hdrNotes">Proses giling bahan baku base</span></div>
            <div class="col-md-2"><small class="text-muted d-block">Production ID</small><strong id="hdrProdId">PRD-2026-001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Jadwal</small><strong id="hdrJadwalRef">JAD-2026-0819-001</strong></div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs" id="spkpTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabBahanBaku" type="button"><i class="bi bi-box-seam me-1"></i>Bahan Baku</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabProduction" type="button"><i class="bi bi-gear me-1"></i>Production</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabRealisasi" type="button"><i class="bi bi-truck me-1"></i>Realisasi</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabQC" type="button"><i class="bi bi-clipboard-check me-1"></i>QC</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAdjustment" type="button"><i class="bi bi-sliders me-1"></i>Adjustment</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabResult" type="button"><i class="bi bi-check2-square me-1"></i>Result</button>
    </li>
</ul>

<div class="tab-content border border-top-0 rounded-bottom p-3 bg-white shadow-sm">

    {{-- TAB BAHAN BAKU --}}
    <div class="tab-pane fade show active" id="tabBahanBaku" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th class="text-end">Tonase</th>
                        <th class="text-center">Urutan Proses</th>
                        <th>Batch No.</th>
                        <th class="text-end">Kebutuhan</th>
                        <th>UOM</th>
                        <th>Kemasan</th>
                        <th class="text-center">Checklist</th>
                        <th class="text-end">Realisasi</th>
                        <th>UOM</th>
                        <th class="text-center">Change Batch</th>
                        <th>Warehouse</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Titanium Dioxide (TiO2)</td><td class="text-end">5.00</td><td class="text-center">1</td><td>BB-001</td><td class="text-end">5.25</td><td>Kg</td><td>Sack 25kg</td><td class="text-center"><input type="checkbox" checked></td><td class="text-end">5.20</td><td>Kg</td><td class="text-center">-</td><td>WH-Gudang A</td></tr>
                    <tr><td>Calcium Carbonate (CaCO3)</td><td class="text-end">10.00</td><td class="text-center">2</td><td>BB-002</td><td class="text-end">10.50</td><td>Kg</td><td>Sack 50kg</td><td class="text-center"><input type="checkbox" checked></td><td class="text-end">10.45</td><td>Kg</td><td class="text-center">-</td><td>WH-Gudang A</td></tr>
                    <tr><td>Resin Emulsion</td><td class="text-end">8.00</td><td class="text-center">3</td><td>BB-003</td><td class="text-end">8.00</td><td>Kg</td><td>Drum 200L</td><td class="text-center"><input type="checkbox" checked></td><td class="text-end">8.10</td><td>Kg</td><td class="text-center">-</td><td>WH-Gudang B</td></tr>
                    <tr><td>Pigment White</td><td class="text-end">1.50</td><td class="text-center">4</td><td>BB-004</td><td class="text-end">1.50</td><td>Kg</td><td>Pail 5kg</td><td class="text-center"><input type="checkbox"></td><td class="text-end">0.00</td><td>Kg</td><td class="text-center">-</td><td>WH-Gudang B</td></tr>
                    <tr><td>Additive - Thickener</td><td class="text-end">0.50</td><td class="text-center">5</td><td>BB-005</td><td class="text-end">0.50</td><td>Kg</td><td>Can 1kg</td><td class="text-center"><input type="checkbox"></td><td class="text-end">0.00</td><td>Kg</td><td class="text-center">-</td><td>WH-Gudang C</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary me-1"><i class="bi bi-save me-1"></i>Simpan</button>
            <button class="btn btn-sm btn-info me-1"><i class="bi bi-person me-1"></i>Leader Formulasi</button>
            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-circle me-1"></i>Complete</button>
            <button class="btn btn-sm btn-outline-dark"><i class="bi bi-printer me-1"></i>Print RM</button>
        </div>
    </div>

    {{-- TAB PRODUCTION --}}
    <div class="tab-pane fade" id="tabProduction" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Urutan Proses</th>
                        <th>Instruksi</th>
                        <th>Kemasan</th>
                        <th class="text-center">Checklist</th>
                        <th>Tanggal Mulai</th>
                        <th>Jam Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Jam Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="text-center">1</td><td>Penimbangan bahan baku sesuai formulasi</td><td>-</td><td class="text-center"><input type="checkbox" checked></td><td>2026-08-19</td><td>08:00</td><td>2026-08-19</td><td>09:30</td></tr>
                    <tr><td class="text-center">2</td><td>Pencampuran bahan dalam mixer</td><td>-</td><td class="text-center"><input type="checkbox" checked></td><td>2026-08-19</td><td>09:30</td><td>2026-08-19</td><td>11:00</td></tr>
                    <tr><td class="text-center">3</td><td>Proses giling menggunakan grinding mill</td><td>-</td><td class="text-center"><input type="checkbox"></td><td>2026-08-19</td><td>13:00</td><td>-</td><td>-</td></tr>
                    <tr><td class="text-center">4</td><td>Pengecekan kekentalan dan warna</td><td>-</td><td class="text-center"><input type="checkbox"></td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary me-1"><i class="bi bi-save me-1"></i>Save</button>
            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-circle me-1"></i>Complete</button>
            <button class="btn btn-sm btn-warning me-1"><i class="bi bi-play-circle me-1"></i>Proses Base</button>
            <button class="btn btn-sm btn-info"><i class="bi bi-stop-circle me-1"></i>Selesai Base</button>
        </div>
    </div>

    {{-- TAB REALISASI --}}
    <div class="tab-pane fade" id="tabRealisasi" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Warehouse</th>
                        <th class="text-end">Qty</th>
                        <th>UOM</th>
                        <th>Batch No.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>PRD-2026-001</td><td>Cat Tembok Putih 25kg</td><td>WH-FG-01</td><td class="text-end">25.00</td><td>Kg</td><td>BATCH-A001</td></tr>
                    <tr><td>PRD-2026-001</td><td>Cat Tembok Putih 25kg (Sisa)</td><td>WH-FG-01</td><td class="text-end">0.50</td><td>Kg</td><td>BATCH-A001-S</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Save</button>
        </div>
    </div>

    {{-- TAB QC --}}
    <div class="tab-pane fade" id="tabQC" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Pengujian</th>
                        <th>Standard</th>
                        <th>Hasil Pengujian 1</th>
                        <th>Hasil Pengujian 2</th>
                        <th>Hasil Pengujian 3</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Viskositas (KU)</td><td>90 - 110 KU</td><td class="text-success">102 KU</td><td class="text-success">98 KU</td><td class="text-success">105 KU</td></tr>
                    <tr><td>Solid Content (%)</td><td>55 - 65%</td><td class="text-success">58.2%</td><td class="text-success">59.1%</td><td class="text-success">57.8%</td></tr>
                    <tr><td>pH</td><td>8.0 - 9.5</td><td class="text-success">8.7</td><td class="text-success">8.9</td><td class="text-success">8.5</td></tr>
                    <tr><td>Hiding Power</td><td>&ge; 95%</td><td class="text-success">97.5%</td><td class="text-success">96.8%</td><td class="text-danger">93.2%</td></tr>
                    <tr><td>Finishing</td><td>Smooth</td><td class="text-success">Smooth</td><td class="text-success">Smooth</td><td class="text-warning">Slightly Rough</td></tr>
                    <tr><td>Warna (Visual)</td><td>Match with Standard</td><td class="text-success">Pass</td><td class="text-success">Pass</td><td class="text-success">Pass</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB ADJUSTMENT --}}
    <div class="tab-pane fade" id="tabAdjustment" role="tabpanel">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body py-2">
                <div class="row g-2" style="font-size:0.85rem;">
                    <div class="col-md-2"><small class="text-muted d-block">Date</small><strong>2026-08-19</strong></div>
                    <div class="col-md-3"><small class="text-muted d-block">Product Name</small><strong>Cat Tembok Putih 25kg</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Batch Name</small><strong>BATCH-A001</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Mesin</small><strong>GR-01</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Tonase</small><strong>25.00 Kg</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Notes</small><span>Tidak ada catatan</span></div>
                    <div class="col-md-2"><small class="text-muted d-block">User Id</small><strong>supervisor_andi</strong></div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Product ID</th>
                        <th>Nama Bahan</th>
                        <th>UOM</th>
                        <th>Warehouse</th>
                        <th class="text-end">1</th>
                        <th>FC1</th>
                        <th class="text-end">2</th>
                        <th>FC 2</th>
                        <th class="text-end">3</th>
                        <th>FC 3</th>
                        <th class="text-end">4</th>
                        <th>FC4</th>
                        <th class="text-end">5</th>
                        <th>FC 5</th>
                        <th class="text-end">Pengembalian</th>
                        <th class="text-end">Jumlah</th>
                        <th>Release Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>BB-001</td><td>Titanium Dioxide (TiO2)</td><td>Kg</td><td>WH-Gudang A</td><td class="text-end">1.00</td><td>OK</td><td class="text-end">1.00</td><td>OK</td><td class="text-end">1.00</td><td>OK</td><td class="text-end">1.00</td><td>OK</td><td class="text-end">1.00</td><td>OK</td><td class="text-end">0.00</td><td class="text-end">5.00</td><td>2026-08-19</td></tr>
                    <tr><td>BB-002</td><td>Calcium Carbonate (CaCO3)</td><td>Kg</td><td>WH-Gudang A</td><td class="text-end">2.00</td><td>OK</td><td class="text-end">2.00</td><td>OK</td><td class="text-end">2.00</td><td>OK</td><td class="text-end">2.00</td><td>OK</td><td class="text-end">2.00</td><td>OK</td><td class="text-end">0.00</td><td class="text-end">10.00</td><td>2026-08-19</td></tr>
                    <tr><td>BB-003</td><td>Resin Emulsion</td><td>Kg</td><td>WH-Gudang B</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">0.00</td><td class="text-end">8.00</td><td>2026-08-19</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB RESULT --}}
    <div class="tab-pane fade" id="tabResult" role="tabpanel">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success"><i class="bi bi-check-circle me-1"></i>Kesimpulan Produksi</h6></div>
                    <div class="card-body" style="font-size:0.85rem;">
                        <div class="mb-2"><small class="text-muted">Status Produksi</small><br><span class="badge bg-success">Completed</span></div>
                        <div class="mb-2"><small class="text-muted">Total Batch Diproduksi</small><br><strong>1 Batch</strong></div>
                        <div class="mb-2"><small class="text-muted">Total Hasil Produksi</small><br><strong>25.50 Kg</strong></div>
                        <div class="mb-2"><small class="text-muted">Selisih</small><br><strong class="text-warning">0.50 Kg (Recanning)</strong></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info"><i class="bi bi-clipboard-check me-1"></i>Keputusan QC</h6></div>
                    <div class="card-body" style="font-size:0.85rem;">
                        <div class="mb-2"><small class="text-muted">Status QC</small><br><span class="badge bg-warning text-dark">Need Review</span></div>
                        <div class="mb-2"><small class="text-muted">Keterangan</small><br><span>Hiding Power pengujian ke-3 di bawah standar (93.2% &lt; 95%)</span></div>
                        <div class="mb-2"><small class="text-muted">Keputusan</small><br>
                            <button class="btn btn-sm btn-success me-1" disabled><i class="bi bi-check-lg me-1"></i>Approve</button>
                            <button class="btn btn-sm btn-danger me-1" disabled><i class="bi bi-x-lg me-1"></i>Reject</button>
                            <button class="btn btn-sm btn-warning" disabled><i class="bi bi-arrow-repeat me-1"></i>Rework ADU</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
