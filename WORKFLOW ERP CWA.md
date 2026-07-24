# Workflow ERP CWA

---

## 1. Material Management — Alur Pembelian & Persediaan

### 1.1 Purchase Request → Purchase Order → STBJ → Purchase Invoice → Supplier Payment

```
[USER] → Purchase Request (PR)
           │
           ▼
      APPROVED?
           │
     ┌─────┴─────┐
     │           │
    YES         NO → REJECTED
     │
     ▼
  Purchase Order (PO) ────→ Supplier
     │
     ▼
  Supplier kirim barang + Surat Jalan (SJ)
     │
     ▼
  STBJ (Surat Tanda Bukti Jalan / Goods Receipt)
     │
     ├── qty_diterima = qty_dipesan → PO CLOSED
     ├── qty_diterima < qty_dipesan → PO PARTIAL
     │
     ▼
  Purchase Invoice (dari Supplier)
     │
     ▼
  Supplier Payment
     ├── REGULAR (bayar invoice)
     └── DOWN_PAYMENT (DP ke supplier)
```

### 1.2 Purchase Return & SJBB

```
  Purchase Return (Retur ke Supplier)
     │
     ├── Mengacu ke Purchase Invoice
     ├── Mengacu ke STBJ (barang sudah diterima)
     │
     ▼
  SJBB (Surat Jalan Bukti Barter)
     ├── IN  (barang kembali dari retur)
     └── OUT (barang dikirim balik ke supplier)
```

### 1.3 Stock Adjustment

```
  Stock Adjustment
     ├── STANDARD     → selisih fisik vs sistem
     └── INTERNAL_USE → pemakaian internal
           │
           ▼
      DRAFT → APPROVED → Update Stock Ledger
```

### 1.4 Stock Transfer (Antar Gudang)

```
  Stock Transfer Request
     │
     ▼
  Shipment Preparation (scan/siapkan barang)
     │
     ▼
  IN_TRANSIT (dari gudang asal)
     │
     ▼
  COMPLETED (diterima di gudang tujuan)
```

### 1.5 Stock Conversion (BOM / Template)

```
  Material Template (Resep BOM)
     ├── target_material (hasil jadi)
     └── raw_materials (bahan baku) + qty

  Stock Conversion
     │
     ▼
  Eksekusi: kurangi stok bahan → tambah stok hasil jadi
     │
     ▼
  Stock Ledger
```

---

## 2. Sales & Distribution — Alur Penjualan

### 2.1 Sales Order → Packing → Delivery Order → Sales Invoice → Customer Payment

```
  Customer Master / Customer Group / Customer Area
     │
     ▼
  Sales Order (SO) dari Customer
     │
     ▼
  Packing (persiapan barang)
     │
     ▼
  Shipment Preparation
     │
     ▼
  Delivery Order (DO / Surat Jalan)
     │
     ▼
  Sales Invoice (tagihan ke customer)
     │
     ▼
  Customer Payment
     │
     └── Tanda Terima Penagihan
     └── Tanda Terima Invoice
```

### 2.2 Sales Return

```
  Sales Return (Retur dari Customer)
     │
     ▼
  Update stok & invoice
```

---

## 3. Stock Ledger (Kartu Stok)

Semua transaksi masuk/keluar barang dicatat di `stock_ledgers`:

| Transaksi | IN | OUT |
|---|---|---|
| STBJ | ✓ | |
| Purchase Return | | ✓ |
| Stock Adjustment | ✓ | ✓ |
| Stock Transfer | ✓ (tujuan) | ✓ (asal) |
| Stock Conversion | ✓ (hasil) | ✓ (bahan) |
| Sales/Delivery | | ✓ |
| Sales Return | ✓ | |

---

## 4. Alur Status Transaksi

| Modul | Status Flow |
|---|---|
| Purchase Request | DRAFT → PENDING → APPROVED / REJECTED → FULFILLED |
| Purchase Order | DRAFT → APPROVED → PARTIAL / CLOSED → CANCELLED |
| Purchase Invoice | UNPAID → PARTIAL → PAID |
| Stock Adjustment | DRAFT → APPROVED |
| Stock Transfer | REQUESTED → PREPARED → IN_TRANSIT → COMPLETED / CANCELLED |
| Purchase Return | DRAFT → APPROVED → COMPLETED |

