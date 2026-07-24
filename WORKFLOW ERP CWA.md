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
