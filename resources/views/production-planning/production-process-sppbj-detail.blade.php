@extends('layouts.layout-minimal')
@section('title', 'Detail SPPBJ')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-sm btn-outline-secondary" onclick="window.close()"><i class="bi bi-x-lg me-1"></i>Tutup</button>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-primary bg-opacity-10 py-2">
        <h6 class="mb-0 text-primary"><i class="bi bi-info-circle me-1"></i>Header Informasi</h6>
    </div>
    <div class="card-body py-2">
        <div class="row g-2" style="font-size:0.85rem;">
            <div class="col-md-2"><small class="text-muted d-block">Tgl. Jadwal</small><strong>2026-08-19</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">No. SPKP</small><strong>SPKP-260819-001</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Kelompok Produk</small><strong>Cat Tembok</strong></div>
            <div class="col-md-3"><small class="text-muted d-block">Nama Produk</small><strong>Cat Tembok Putih 25kg</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Batch No</small><strong>BATCH-CM01</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Tipe Product</small><span class="badge bg-info">Water Based</span></div>
            <div class="col-md-2"><small class="text-muted d-block">Formulasi</small><strong>CM-WB-001</strong></div>
            <div class="col-md-1"><small class="text-muted d-block">Basis</small><strong>25.00</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Total Basis</small><strong>25.50</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Status</small><span class="badge bg-warning text-dark">In Progress</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Notes</small><span>Proses Color Matching</span></div>
            <div class="col-md-2"><small class="text-muted d-block">Production ID</small><strong>PRD-2026-101</strong></div>
            <div class="col-md-2"><small class="text-muted d-block">Jadwal</small><strong>JAD-2026-0819-101</strong></div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs" id="sppbjTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabBahanBakuCM" type="button"><i class="bi bi-box-seam me-1"></i>Bahan Baku CM</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabProduction" type="button"><i class="bi bi-gear me-1"></i>Production</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabKemasan" type="button"><i class="bi bi-box me-1"></i>Permintaan Kemasan</button>
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

    {{-- TAB BAHAN BAKU CM --}}
    <div class="tab-pane fade show active" id="tabBahanBakuCM" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th class="text-end">Tonase</th>
                        <th>Batch No.</th>
                        <th class="text-end">Kebutuhan</th>
                        <th>UOM</th>
                        <th class="text-end">Realisasi</th>
                        <th>UOM</th>
                        <th class="text-center">Change Batch</th>
                        <th class="text-center">Checklist</th>
                        <th>Warehouse</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Pigment White CM-101</td><td class="text-end">3.00</td><td>CM-BB-001</td><td class="text-end">3.10</td><td>Kg</td><td class="text-end">3.08</td><td>Kg</td><td class="text-center">-</td><td class="text-center"><input type="checkbox" checked></td><td>WH-CM-A</td></tr>
                    <tr><td>Resin Emulsion CM-201</td><td class="text-end">8.00</td><td>CM-BB-002</td><td class="text-end">8.00</td><td>Kg</td><td class="text-end">8.05</td><td>Kg</td><td class="text-center">-</td><td class="text-center"><input type="checkbox" checked></td><td>WH-CM-B</td></tr>
                    <tr><td>Additive - Dispersant</td><td class="text-end">0.50</td><td>CM-BB-003</td><td class="text-end">0.50</td><td>Kg</td><td class="text-end">0.50</td><td>Kg</td><td class="text-center">-</td><td class="text-center"><input type="checkbox" checked></td><td>WH-CM-C</td></tr>
                    <tr><td>Water - H2O</td><td class="text-end">13.50</td><td>CM-BB-004</td><td class="text-end">13.90</td><td>Kg</td><td class="text-end">13.87</td><td>Kg</td><td class="text-center">-</td><td class="text-center"><input type="checkbox"></td><td>WH-CM-D</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary me-1"><i class="bi bi-save me-1"></i>Save</button>
            <button class="btn btn-sm btn-info me-1"><i class="bi bi-person me-1"></i>Leader Formulasi</button>
            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-circle me-1"></i>Complete</button>
            <button class="btn btn-sm btn-outline-dark"><i class="bi bi-printer me-1"></i>Print CM</button>
        </div>
    </div>

    {{-- TAB PRODUCTION --}}
    <div class="tab-pane fade" id="tabProduction" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Uraian</th>
                        <th>Batch</th>
                        <th class="text-end">%</th>
                        <th class="text-end">Tonase (Kg)</th>
                        <th class="text-end">Realisasi (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Pengecekan Base dari SPKP</td><td>BATCH-A001</td><td class="text-end">100%</td><td class="text-end">25.00</td><td class="text-end">25.00</td></tr>
                    <tr><td>Penimbangan Bahan CM</td><td>BATCH-CM01</td><td class="text-end">100%</td><td class="text-end">25.50</td><td class="text-end">25.50</td></tr>
                    <tr><td>Pencampuran / Mixing</td><td>BATCH-CM01</td><td class="text-end">100%</td><td class="text-end">25.50</td><td class="text-end">25.50</td></tr>
                    <tr><td>Pengecekan Warna (Visual)</td><td>BATCH-CM01</td><td class="text-end">100%</td><td class="text-end">25.50</td><td class="text-end">25.50</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary me-1"><i class="bi bi-save me-1"></i>Save</button>
            <button class="btn btn-sm btn-info me-1"><i class="bi bi-search me-1"></i>Identifikasi Product</button>
            <button class="btn btn-sm btn-secondary me-1"><i class="bi bi-box me-1"></i>Laporan Hasil Kemas</button>
            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-circle me-1"></i>Complete</button>
            <button class="btn btn-sm btn-warning me-1"><i class="bi bi-play-circle me-1"></i>Proses CM</button>
            <button class="btn btn-sm btn-info"><i class="bi bi-stop-circle me-1"></i>Selesai CM</button>
        </div>
    </div>

    {{-- TAB PERMINTAAN KEMASAN --}}
    <div class="tab-pane fade" id="tabKemasan" role="tabpanel">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:0.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th class="text-end">Kebutuhan</th>
                        <th>UOM</th>
                        <th class="text-end">Realisasi</th>
                        <th>UOM</th>
                        <th>Date</th>
                        <th>Pengganti Reject</th>
                        <th class="text-end">Kurang (OK)</th>
                        <th class="text-end">Sisa (OK)</th>
                        <th>Warehouse</th>
                        <th class="text-end">Reject</th>
                        <th>Warehouse Reject</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Kaleng 0.9L</td><td class="text-end">100</td><td>Pcs</td><td class="text-end">100</td><td>Pcs</td><td>2026-08-19</td><td>-</td><td class="text-end">0</td><td class="text-end">0</td><td>WH-Kemas-A</td><td class="text-end">0</td><td>-</td><td>-</td></tr>
                    <tr><td>Label Cat Tembok</td><td class="text-end">100</td><td>Pcs</td><td class="text-end">102</td><td>Pcs</td><td>2026-08-19</td><td>-</td><td class="text-end">0</td><td class="text-end">2</td><td>WH-Kemas-B</td><td class="text-end">0</td><td>-</td><td>-</td></tr>
                    <tr><td>Seal Cap</td><td class="text-end">100</td><td>Pcs</td><td class="text-end">98</td><td>Pcs</td><td>2026-08-19</td><td>2 pcs dari reject lot lama</td><td class="text-end">0</td><td class="text-end">0</td><td>WH-Kemas-C</td><td class="text-end">2</td><td>WH-Kemas-C</td><td>2026-08-19</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button class="btn btn-sm btn-primary me-1"><i class="bi bi-save me-1"></i>Save</button>
            <button class="btn btn-sm btn-info me-1"><i class="bi bi-person me-1"></i>T Operator Print</button>
            <button class="btn btn-sm btn-secondary me-1"><i class="bi bi-person me-1"></i>Leader Kemasan</button>
            <button class="btn btn-sm btn-warning me-1"><i class="bi bi-gear me-1"></i>Produksi</button>
            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-circle me-1"></i>Complete</button>
            <button class="btn btn-sm btn-outline-dark"><i class="bi bi-upc-scan me-1"></i>Print Barcode</button>
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
                    <tr><td>PRD-2026-101</td><td>Cat Tembok Putih 25kg</td><td>WH-FG-01</td><td class="text-end">25.00</td><td>Kg</td><td>BATCH-CM01</td></tr>
                    <tr><td>PRD-2026-101</td><td>Cat Tembok Putih 25kg (Sisa)</td><td>WH-FG-01</td><td class="text-end">0.50</td><td>Kg</td><td>BATCH-CM01-S</td></tr>
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
                    <tr><td>Viskositas (KU)</td><td>90 - 110 KU</td><td class="text-success">100 KU</td><td class="text-success">103 KU</td><td class="text-success">99 KU</td></tr>
                    <tr><td>Solid Content (%)</td><td>55 - 65%</td><td class="text-success">59.5%</td><td class="text-success">60.1%</td><td class="text-success">58.9%</td></tr>
                    <tr><td>pH</td><td>8.0 - 9.5</td><td class="text-success">8.8</td><td class="text-success">9.0</td><td class="text-success">8.6</td></tr>
                    <tr><td>Hiding Power</td><td>&ge; 95%</td><td class="text-success">98.0%</td><td class="text-success">97.2%</td><td class="text-success">96.5%</td></tr>
                    <tr><td>Finishing</td><td>Smooth</td><td class="text-success">Smooth</td><td class="text-success">Smooth</td><td class="text-success">Smooth</td></tr>
                    <tr><td>Warna (Visual)</td><td>Match with Standard</td><td class="text-success">Pass</td><td class="text-success">Pass</td><td class="text-success">Pass</td></tr>
                    <tr><td>Daya Tutup (Contrast Ratio)</td><td>&ge; 0.95</td><td class="text-success">0.98</td><td class="text-success">0.97</td><td class="text-success">0.96</td></tr>
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
                    <div class="col-md-2"><small class="text-muted d-block">Batch Name</small><strong>BATCH-CM01</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Mesin</small><strong>CM-01</strong></div>
                    <div class="col-md-2"><small class="text-muted d-block">Tonase</small><strong>25.50 Kg</strong></div>
                    <div class="col-md-4"><small class="text-muted d-block">Notes</small><span>Warna sesuai standar</span></div>
                    <div class="col-md-2"><small class="text-muted d-block">User Id</small><strong>supervisor_rina</strong></div>
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
                    <tr><td>CM-BB-001</td><td>Pigment White CM-101</td><td>Kg</td><td>WH-CM-A</td><td class="text-end">0.60</td><td>OK</td><td class="text-end">0.60</td><td>OK</td><td class="text-end">0.60</td><td>OK</td><td class="text-end">0.60</td><td>OK</td><td class="text-end">0.60</td><td>OK</td><td class="text-end">0.00</td><td class="text-end">3.00</td><td>2026-08-19</td></tr>
                    <tr><td>CM-BB-002</td><td>Resin Emulsion CM-201</td><td>Kg</td><td>WH-CM-B</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">1.60</td><td>OK</td><td class="text-end">0.00</td><td class="text-end">8.00</td><td>2026-08-19</td></tr>
                    <tr><td>CM-BB-003</td><td>Additive - Dispersant</td><td>Kg</td><td>WH-CM-C</td><td class="text-end">0.10</td><td>OK</td><td class="text-end">0.10</td><td>OK</td><td class="text-end">0.10</td><td>OK</td><td class="text-end">0.10</td><td>OK</td><td class="text-end">0.10</td><td>OK</td><td class="text-end">0.00</td><td class="text-end">0.50</td><td>2026-08-19</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- TAB RESULT --}}
    <div class="tab-pane fade" id="tabResult" role="tabpanel">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success bg-opacity-10 py-2"><h6 class="mb-0 text-success"><i class="bi bi-check-circle me-1"></i>Kesimpulan Produksi CM</h6></div>
                    <div class="card-body" style="font-size:0.85rem;">
                        <div class="mb-2"><small class="text-muted">Status Produksi</small><br><span class="badge bg-success">Completed</span></div>
                        <div class="mb-2"><small class="text-muted">Total Batch Diproduksi</small><br><strong>1 Batch</strong></div>
                        <div class="mb-2"><small class="text-muted">Total Hasil Produksi</small><br><strong>25.50 Kg</strong></div>
                        <div class="mb-2"><small class="text-muted">Selisih</small><br><strong class="text-warning">0.50 Kg (Recanning)</strong></div>
                        <div class="mb-2"><small class="text-muted">Base Reference</small><br><strong>BATCH-A001</strong></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info bg-opacity-10 py-2"><h6 class="mb-0 text-info"><i class="bi bi-clipboard-check me-1"></i>Keputusan QC</h6></div>
                    <div class="card-body" style="font-size:0.85rem;">
                        <div class="mb-2"><small class="text-muted">Status QC</small><br><span class="badge bg-success">QC Approved</span></div>
                        <div class="mb-2"><small class="text-muted">Keterangan</small><br><span>Semua pengujian sesuai standar. Warna match dengan standard.</span></div>
                        <div class="mb-2"><small class="text-muted">Keputusan</small><br>
                            <button class="btn btn-sm btn-success me-1"><i class="bi bi-check-lg me-1"></i>Approve</button>
                            <button class="btn btn-sm btn-danger me-1" disabled><i class="bi bi-x-lg me-1"></i>Reject</button>
                            <button class="btn btn-sm btn-warning" disabled><i class="bi bi-arrow-repeat me-1"></i>Rework ADU CM</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
