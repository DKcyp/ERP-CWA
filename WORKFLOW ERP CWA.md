# Workflow ERP CWA

---

## 1. Material Management — Alur Pembelian & Persediaan

### 1.1 Purchase Request → Purchase Order → STBJ → Purchase Invoice → Supplier Payment

[USER / DEPARTEMEN] → Purchase Request (PR)
                           │
                           ▼
                       APPROVED?
                           │
                     ┌─────┴─────┐
                     │           │
                    YES          NO → REJECTED
                     │
                     ▼
           Purchase Order (PO) ──────→ Supplier
                     │
                     ▼
           Supplier Kirim Barang + Surat Jalan (SJ)
                     │
                     ▼
     STBJ (Surat Tanda Bukti Jalan / Goods Receipt)
                     │
                     ├── Barang Cacat/Ditolak → Purchase Return (Nota Retur)
                     ├── Barter Barang → SJBB (Surat Jalan Bukti Barter)
                     │
                     ├── qty_diterima = qty_dipesan → PO CLOSED
                     └── qty_diterima < qty_dipesan → PO PARTIAL
                     │
                     ▼
           Purchase Invoice (dari Supplier)
                     │
                     ▼
           Supplier Payment
                     ├── REGULAR (Bayar Invoice)
                     └── DOWN_PAYMENT (Uang Muka DP)


### 1.2 Alur Manajemen Stok & Internal (Adjustment, Transfer, Conversion)

[MANAJEMEN GUDANG]
        │
        ├─→ Stock Opname / Selisih ──→ Stock Adjustment
        │                                  ├── STANDARD (Opname Fisik)
        │                                  └── INTERNAL_USE (Pemakaian Sendiri)
        │
        ├─→ Mutasi Antar Gudang    ──→ Stock Transfer
        │                                  ├── Transfer Request
        │                                  └── Shipment Preparation
        │
        └─→ Perakitan / Production ──→ Material Template (BOM / Resep)
                                           │
                                           ▼
                                    Stock Conversion (Potong Bahan Baku, Tambah Produk Jadi)

### 1.1 Customer PO → Sales Order → Packing → Delivery Order → Sales Invoice → Customer Payment

[CUSTOMER] → Purchase Order (PO / Purchase Note)
                   │
                   ▼
            VALIDATED?
                   │
             ┌─────┴─────┐
             │           │
            YES          NO → REJECTED / VOID
             │
             ▼
        Sales Order (SO)
             │
             ▼
    LIMIT KREDIT & STOK OK?
             │
             ┌─────┴─────┐
             │           │
            YES          NO → REJECTED / HOLD
             │
             ▼
     APPROVED Sales Order
             │
             ▼
     Packing & Boxing (Gudang)
             │
             ▼
    Shipment Preparation (Konsolidasi Fleet)
             │
             ▼
    Delivery Order (DO / Surat Jalan) ──→ Kirim ke Customer
             │
             ├── Kirim Sesuai → Barang Diterima Customer
             └── Kirim Cacat / Batal → Sales Return (Nota Retur)
             │
             ▼
    Sales Invoice (SI / Faktur Penjualan)
             │
             ├── Terbit TTI (Tanda Terima Invoice ke Customer)
             └── Terbit TTP (Tanda Terima Penagihan ke Kolektor)
             │
             ▼
    Customer Payment (Pelunasan)
             ├── REGULAR (Bayar Invoice)
             └── DOWN_PAYMENT (Uang Muka)
             │
             ▼
    ┌────────┴────────┐
    │                 │
    ▼                 ▼
Poin Loyalty     Komisi Sales
(Customer Point) (Sales Commission)

### 1.1 Ekspedisi Dokumen & Alur Distribusi Faktur Depo

[KANTOR PUSAT / GUDANG]
         │
         ├── Cetak Faktur & Surat Jalan
         │
         ▼
Shipping Invoice Expedition / Invoice Expedition
         │
         ├── Penyerahan Berkas ke Kurir Armada / Salesman
         │
         ▼
     [LAPANGAN / PELANGGAN]
         │
         ├── Barang & Faktur Diterima Pelanggan
         │
         ▼
  Laporan Transaksi Harian Depo
         ├── Daily Sales Invoice Report
         ├── Daily Sales PO Closing Report
         ├── Daily Sales Return Report
         └── Daily Sales by Brand Report


### 1.2 Alur Penagihan, RLHP & Manajemen Piutang (AR) Depo

[KOLEKTOR / SALESMAN DEPO] ──→ Penagihan Lapangan
                                      │
                                      ▼
                      RLHP (Rincian Laporan Hasil Penagihan)
                                      │
                                ┌─────┴─────┐
                                │           │
                              CASH         GIRO / CHEQUE
                                │           │
                                │           ▼
                                │    Cheque Management (Validasi & Kliring)
                                │           │
                                └─────┬─────┘
                                      │
                                      ▼
                          Daily Payment Recap Report
                                      │
                                      ▼
                        Kontrol Saldo & Posisi Piutang
                              ├── AR per Customer Report
                              ├── Customer AR Position Report
                              └── Invoice Customer AR List Report


### 1.3 Alur Kontrol Target, Monitoring Sales & Bonus (PMB)

[MANAJEMEN TRANSIT AREA]
         │
         ├── Penetapan Parameter Depo
         │      ├── Transit Area Target (Target Bulanan)
         │      └── Transit Area New Brand (Fokus Produk Baru)
         │
         ▼
   Monitoring Kinerja Harian Depo
         ├── UBM Daily Control Progress Sales Report
         ├── UBM New Product Sales Report (Penetrasi NOO)
         ├── Daily Sales Achievement Report
         └── UBM Collection Progress Report (Ranking Penagihan)
         │
         ▼
  Salesman AR List PMB (Evaluasi Koleksi Piutang Sales)
         │
         ▼
  PMB (Penetapan & Monitoring Bonus Salesman / Depo)

========================================================================================================
                             DIAGRAM ALUR OPERASIONAL SYSTEM ERP
========================================================================================================

[ 1. MASTER DATA SETUP ]
   ├── Master Material (Bahan Baku, Aditif, Solvent, Kemasan)
   ├── Master Formula / BOM (Resep Base, Target Viskositas, Target Micron)
   ├── Master Color Matching (Resep Takaran Pasta Warna)
   ├── Master Kemasan (Kaleng, Galon, Pail, Berat & Tare)
   ├── Master Mesin (Grinding, Mixer, Filling Line)
   └── Master Komisi (Tarif Komisi Operator)
            │
            ▼
[ 2. PERENCANAAN PRODUKSI (PRODUCTION PLANNING) ]
   ├── Monitor KPI & Antrean ─────────────► Dashboard Production Planning
   ├── Input Pesanan Pelanggan ───────────► Pre SPK List
   ├── Terbit SPK Resmi ──────────────────► SPK List
   ├── Cek Ketersediaan Stok Material ───► Production Material Check Stock ─── (Stok Kurang? -> PR/PO)
   ├── Plotting Waktu & Mesin ───────────► Production Scheduling
   ├── Terbit Panduan Kerja Harian ──────► Daily Schedule Report
   └── Persiapan Kemasan Fisik ──────────► SPK Kemasan & Jadwal Kemasan
            │
            ▼
[ 3. EKSEKUSI PRODUKSI BASE & GILING (PRODUCTION EXECUTION) ]
   ├── Buka Dokumen Kerja Utama ─────────► Production List
   ├── Potong Stok Bahan Baku ───────────► Stock Release (Gudang Bahan Baku)
   ├── Olah Adonan Dasar ────────────────► Realisasi Jadwal Base / Base per Mesin
   └── Cek Speed & Kehalusan Micron (u) ─► Monitoring Mesin Grinding
            │
            ▼
[ 4. PEWARNAAN & TINTING (COLOR MATCHING) ]
   ├── Pencampuran Pasta Warna ──────────► Realisasi Jadwal Pasta
   ├── Proses Tinting Adonan ────────────► Realisasi Jadwal CM
   └── Penanda Selesai Pewarnaan ────────► Draf STBJ Production
            │
            ▼
[ 5. QUALITY CONTROL GATE (GERBANG QC) ]
   └── Inspeksi Sample (Warna, Viskositas, Daya Tutup)
            │
            ├──────► [ STATUS: REJECT / REWORK ]
            │              │
            │              └──► Kembali ke "Realisasi Jadwal CM" (Tambah Pigmen/Pasta Perbaikan)
            │
            └──────► [ STATUS: QC APPROVED ]
                           │
                           ▼
[ 6. PENGEMASAN (CANNING & PACKING) ]
   ├── Pengisian Cat (Filling) ──────────► Realisasi Jadwal Canning & Packing
   ├── Variasi Wadah Kemasan ────────────► Kaleng (0.1L-1L), Galon, Pail
   └── Timbang Berat Awal & Akhir ────────► Catat Sisa & Yield Hasil Kemas
            │
            ▼
[ 7. SERAH TERIMA, RILIS STOK & KOMISI ]
   ├── Terbit Bukti Selesai Packing ─────► Final STBJ Production
   ├── Otorisasi Pelepasan Barang ───────► Release Production
   ├── Verifikasi Fisik & Inbound ────────► STBJ Material Management (Gudang Produk Jadi)
   │                                           │
   │                                           └──► Stok Bertambah di "Available Stock"
   │
   └── Hitung Komisi Operator ───────────► Production Commission
            │
            ▼
[ 8. ANALISIS, AUDIT & PELAPORAN (REPORTS) ]
   ├── Laporan Operasional Harian ───────► Production Report (Daily Base, Result, STBJ)
   ├── Laporan Audit Stok & Jejak ────────► Product Report (Stock Track, Min-Max, Summary)
   └── Laporan Biaya & HPP Real-time ────► Material Cost Report & Result COGS Report

========================================================================================================