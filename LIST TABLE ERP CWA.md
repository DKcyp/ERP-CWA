# Skema Database Material Management & Sales Distribution ERP CWA

---

## 1. Kelompok Master Data Supplier & Material

1. MASTER DATA SUPPLIER
--------------------------------------------------------------------------------
Tabel: supplier_groups
Deskripsi: Grup & Kategori Supplier (`supplier-group`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - code                 : VARCHAR(50)   | UNIQUE, NOT NULL | Kode grup supplier
  - name                 : VARCHAR(100)  | NOT NULL    | Nama grup supplier
  - description          : TEXT          | NULLABLE    | Deskripsi kriteria
  - ap_account_id        : CHAR(26)      | FK          | Relasi ke chart_of_accounts.id
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: supplier_centers
Deskripsi: Pusat / Wilayah Area Supplier (`supplier-center`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - code                 : VARCHAR(50)   | UNIQUE, NOT NULL | Kode area supplier
  - name                 : VARCHAR(100)  | NOT NULL    | Nama area/cabang supplier
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: suppliers
Deskripsi: Master Data Supplier Utama (`supplier-master`, `supplier-balance-summary`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - supplier_code        : VARCHAR(50)   | UNIQUE, NOT NULL | Kode unik supplier
  - name                 : VARCHAR(150)  | NOT NULL    | Nama resmi vendor
  - supplier_group_id    : CHAR(26)      | FK          | Relasi ke supplier_groups.id
  - supplier_center_id   : CHAR(26)      | FK          | Relasi ke supplier_centers.id
  - phone                : VARCHAR(30)   | NULLABLE    | Nomor telepon kontak
  - email                : VARCHAR(100)  | NULLABLE    | Alamat email resmi
  - address              : TEXT          | NULLABLE    | Alamat lengkap vendor
  - term_of_payment      : INT           | NOT NULL    | DEFAULT 0 (TOP Hari)
  - status               : BOOLEAN       | NOT NULL    | DEFAULT true (Status Aktif)
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp


2. PURCHASE REQUEST & PURCHASE ORDER
--------------------------------------------------------------------------------
Tabel: purchase_requests
Deskripsi: Header Permintaan Pembelian (`new-purchase-request`, `purchase-request-list`, `purchase-request-fulfilment-report`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - pr_number            : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen PR
  - pr_date              : DATE          | NOT NULL    | Tanggal pengajuan PR
  - requester_user_id    : CHAR(26)      | FK          | Relasi ke users.id
  - department           : VARCHAR(100)  | NOT NULL    | Departemen pemohon
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'FULFILLED'
  - note                 : TEXT          | NULLABLE    | Catatan tambahan PR
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: purchase_request_details
Deskripsi: Detail Item Permintaan Pembelian
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - purchase_request_id  : CHAR(26)      | FK          | Relasi ke purchase_requests.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_requested        : DECIMAL(12,2) | NOT NULL    | Jumlah yang diminta
  - qty_fulfilled        : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (Sudah di-PO)
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - notes                : TEXT          | NULLABLE    | Spesifikasi/catatan barang
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: purchase_orders
Deskripsi: Header Pesanan Pembelian (`new-purchase-order`, `purchase-order-list`, `purchase-fulfillment-report`, `daily-purchase-order-report`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - po_number            : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen PO
  - po_date              : DATE          | NOT NULL    | Tanggal terbit PO
  - supplier_id          : CHAR(26)      | FK          | Relasi ke suppliers.id
  - purchase_request_id  : CHAR(26)      | FK          | Relasi ke purchase_requests.id (Nullable)
  - payment_term_days    : INT           | NOT NULL    | DEFAULT 0 (Termin Hari)
  - subtotal             : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - tax_amount           : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - discount_amount      : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - total_amount         : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (Grand Total)
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'APPROVED', 'PARTIAL', 'CLOSED', 'CANCELLED'
  - note                 : TEXT          | NULLABLE    | Ketentuan / Instruksi PO
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: purchase_order_details
Deskripsi: Detail Item Pesanan Pembelian
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - purchase_order_id    : CHAR(26)      | FK          | Relasi ke purchase_orders.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_ordered          : DECIMAL(12,2) | NOT NULL    | Jumlah dipesan
  - qty_received         : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (Sudah diterima)
  - unit_price           : DECIMAL(15,2) | NOT NULL    | Harga satuan deal
  - discount             : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - tax                  : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - subtotal             : DECIMAL(15,2) | NOT NULL    | Total harga bersih item
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir


3. RECEIVING (STBJ) & PURCHASE INVOICE
--------------------------------------------------------------------------------
Tabel: goods_receipts
Deskripsi: Header Surat Tanda Bukti Jalan / Goods Receipt (`stbj`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - stbj_number          : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen STBJ
  - stbj_date            : DATE          | NOT NULL    | Tanggal penerimaan fisik
  - supplier_id          : CHAR(26)      | FK          | Relasi ke suppliers.id
  - purchase_order_id    : CHAR(26)      | FK          | Relasi ke purchase_orders.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - sj_supplier_number   : VARCHAR(100)  | NULLABLE    | No Surat Jalan Supplier
  - note                 : TEXT          | NULLABLE    | Catatan inspek/penerimaan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: goods_receipt_details
Deskripsi: Detail Fisik Penerimaan Barang STBJ
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - goods_receipt_id     : CHAR(26)      | FK          | Relasi ke goods_receipts.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_received         : DECIMAL(12,2) | NOT NULL    | Jumlah barang datang
  - qty_accepted         : DECIMAL(12,2) | NOT NULL    | Jumlah barang lolos QC
  - qty_rejected         : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (Ditolak)
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - note                 : TEXT          | NULLABLE    | Alasan reject / remark
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: purchase_invoices
Deskripsi: Header Faktur Pembelian (`new-purchase-invoice`, `purchase-invoice-list`, `daily-purchase-invoice-report`, `monthly-purchase-by-supplier-report`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - invoice_number       : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Faktur Pembelian
  - invoice_date         : DATE          | NOT NULL    | Tanggal terbit faktur
  - due_date             : DATE          | NOT NULL    | Jatuh tempo pembayaran
  - supplier_id          : CHAR(26)      | FK          | Relasi ke suppliers.id
  - purchase_order_id    : CHAR(26)      | FK          | Relasi ke purchase_orders.id (Nullable)
  - goods_receipt_id     : CHAR(26)      | FK          | Relasi ke goods_receipts.id (Nullable)
  - total_amount         : DECIMAL(15,2) | NOT NULL    | Total kewajiban hutang
  - paid_amount          : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (Terbayar)
  - status               : ENUM          | NOT NULL    | 'UNPAID', 'PARTIAL', 'PAID'
  - note                 : TEXT          | NULLABLE    | Catatan Keuangan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: purchase_invoice_details
Deskripsi: Detail Rincian Item Tagihan Invoice Pembelian
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - purchase_invoice_id  : CHAR(26)      | FK          | Relasi ke purchase_invoices.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty                  : DECIMAL(12,2) | NOT NULL    | Jumlah ditagihkan
  - unit_price           : DECIMAL(15,2) | NOT NULL    | Harga satuan tagihan
  - subtotal             : DECIMAL(15,2) | NOT NULL    | Subtotal tagihan item
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir


4. SUPPLIER PAYMENT, RETURN & SJBB
--------------------------------------------------------------------------------
Tabel: supplier_payments
Deskripsi: Header Pembayaran Supplier (`new-supplier-payment`, `new-supplier-down-payment`, `supplier-payment-list`, `supp-outstanding-list`, `daily-supplier-payment-report`, `daily-supplier-payment-list`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - payment_number       : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Bukti Kas/Bank Keluar
  - payment_date         : DATE          | NOT NULL    | Tanggal eksekusi bayar
  - supplier_id          : CHAR(26)      | FK          | Relasi ke suppliers.id
  - payment_type         : ENUM          | NOT NULL    | 'REGULAR', 'DOWN_PAYMENT'
  - payment_method       : ENUM          | NOT NULL    | 'TRANSFER', 'CASH', 'GIRO'
  - total_paid           : DECIMAL(15,2) | NOT NULL    | Nominal dibayarkan
  - reference_number     : VARCHAR(100)  | NULLABLE    | No Rekening/Giro/Ref Bank
  - note                 : TEXT          | NULLABLE    | Keterangan pembayaran
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: supplier_payment_details
Deskripsi: Detail Alokasi Pembayaran ke Invoice AP
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - supplier_payment_id  : CHAR(26)      | FK          | Relasi ke supplier_payments.id
  - purchase_invoice_id  : CHAR(26)      | FK          | Relasi ke purchase_invoices.id
  - amount_paid          : DECIMAL(15,2) | NOT NULL    | Porsi nominal dialokasikan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: purchase_returns
Deskripsi: Header Retur Pembelian (`new-purchase-return`, `purchase-return-list`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - return_number        : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Dokumen Retur
  - return_date          : DATE          | NOT NULL    | Tanggal pengembalian
  - supplier_id          : CHAR(26)      | FK          | Relasi ke suppliers.id
  - purchase_invoice_id  : CHAR(26)      | FK          | Relasi ke purchase_invoices.id (Nullable)
  - goods_receipt_id     : CHAR(26)      | FK          | Relasi ke goods_receipts.id (Nullable)
  - total_return_amount  : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (Nilai Retur)
  - reason               : TEXT          | NULLABLE    | Alasan pengembalian barang
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'APPROVED', 'COMPLETED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: purchase_return_details
Deskripsi: Detail Barang Dikembalikan ke Supplier
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - purchase_return_id   : CHAR(26)      | FK          | Relasi ke purchase_returns.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_returned         : DECIMAL(12,2) | NOT NULL    | Kuantitas retur
  - unit_price           : DECIMAL(15,2) | NOT NULL    | Harga patokan klaim retur
  - subtotal             : DECIMAL(15,2) | NOT NULL    | Subtotal nilai retur item
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: sjbb
Deskripsi: Surat Jalan Bukti Barter (`sjbb`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - sjbb_number          : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen SJBB
  - sjbb_date            : DATE          | NOT NULL    | Tanggal transaksi barter
  - supplier_id          : CHAR(26)      | FK          | Relasi ke suppliers.id
  - type                 : ENUM          | NOT NULL    | 'IN', 'OUT'
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'ISSUED', 'COMPLETED', 'CANCELLED'
  - notes                : TEXT          | NULLABLE    | Catatan khusus barter
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: sjbb_details
Deskripsi: Detail Item Barang Barter (SJBB)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - sjbb_id              : CHAR(26)      | FK          | Relasi ke sjbb.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty                  : DECIMAL(12,2) | NOT NULL    | Jumlah fisik barter
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - notes                : TEXT          | NULLABLE    | Kondisi barang / remark
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir


5. STOCK ADJUSTMENT & STOCK TRANSFER
--------------------------------------------------------------------------------
Tabel: stock_adjustments
Deskripsi: Header Penyesuaian Stok (`stock-adjustment-use`, `new-stock-adjustment-standard`, `new-stock-adjustment-internal-use`, `stock-adjustment-list`, `daily-stock-adjustment-report`, `daily-stock-adjustment-track-report`, `daily-stock-adjustment-cost-report`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - adjustment_number    : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Dokumen Adjustment
  - adjustment_date      : DATE          | NOT NULL    | Tanggal SO / Adjustment
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - adjustment_type      : ENUM          | NOT NULL    | 'STANDARD', 'INTERNAL_USE'
  - reason               : TEXT          | NULLABLE    | Alasan selisih stok
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'APPROVED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: stock_adjustment_details
Deskripsi: Detail Selisih Fisik vs Sistem
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - stock_adjustment_id  : CHAR(26)      | FK          | Relasi ke stock_adjustments.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - system_qty           : DECIMAL(12,2) | NOT NULL    | Kuantitas terdata di sistem
  - physical_qty         : DECIMAL(12,2) | NOT NULL    | Kuantitas hitung fisik
  - qty_diff             : DECIMAL(12,2) | NOT NULL    | Selisih (physical_qty - system_qty)
  - cost_per_unit        : DECIMAL(15,2) | NOT NULL    | HPP per unit barang
  - total_cost_diff      : DECIMAL(15,2) | NOT NULL    | Total finansial selisih
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: stock_transfers
Deskripsi: Header Transfer Barang Antar Gudang (`new-stock-transfer`, `stock-transfer-list`, `new-stock-transfer-request`, `stock-transfer-request-list`, `daily-stock-transfer-report`, `stock-transfer-fulfilment`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - transfer_number      : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Dokumen Mutasi
  - transfer_date        : DATE          | NOT NULL    | Tanggal pengiriman transfer
  - from_warehouse_id    : CHAR(26)      | FK          | Relasi ke warehouses.id (Gudang Asal)
  - to_warehouse_id      : CHAR(26)      | FK          | Relasi ke warehouses.id (Gudang Tujuan)
  - status               : ENUM          | NOT NULL    | 'REQUESTED', 'PREPARED', 'IN_TRANSIT', 'COMPLETED', 'CANCELLED'
  - notes                : TEXT          | NULLABLE    | Instruksi pengiriman
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: stock_transfer_details
Deskripsi: Detail Items Transfer Antar Gudang
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - stock_transfer_id    : CHAR(26)      | FK          | Relasi ke stock_transfers.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_requested        : DECIMAL(12,2) | NOT NULL    | Kuantitas diminta
  - qty_shipped          : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (Dikirim gudang asal)
  - qty_received         : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (Diterima gudang tujuan)
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: stock_transfer_shipments
Deskripsi: Persiapan Pengiriman Transfer Gudang (`stock-transfer-shipment-preparation`, `stock-transfer-shipment-preparation-list`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - prep_number          : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Surat Persiapan Muatan
  - prep_date            : DATE          | NOT NULL    | Tanggal persiapan muat
  - stock_transfer_id    : CHAR(26)      | FK          | Relasi ke stock_transfers.id
  - driver_name          : VARCHAR(100)  | NULLABLE    | Driver armada pengangkut
  - vehicle_number       : VARCHAR(30)   | NULLABLE    | Plat nomor armada
  - status               : ENUM          | NOT NULL    | 'PREPARED', 'DISPATCHED', 'DELIVERED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir


6. STOCK CONVERSION & MATERIAL TEMPLATE (BOM)
--------------------------------------------------------------------------------
Tabel: material_templates
Deskripsi: Bill of Materials Header (`material-template`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - template_code        : VARCHAR(50)   | UNIQUE, NOT NULL | Kode formula BOM
  - template_name        : VARCHAR(150)  | NOT NULL    | Nama resep / racikan
  - target_material_id   : CHAR(26)      | FK          | Relasi ke materials.id (Hasil Jadi)
  - target_output_qty    : DECIMAL(12,2) | NOT NULL    | DEFAULT 1.00 (Output standar)
  - description          : TEXT          | NULLABLE    | Instruksi perakitan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

Tabel: material_template_details
Deskripsi: Detail Komponen Bahan Baku BOM
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - material_template_id : CHAR(26)      | FK          | Relasi ke material_templates.id
  - raw_material_id      : CHAR(26)      | FK          | Relasi ke materials.id (Bahan Baku)
  - qty_needed           : DECIMAL(12,2) | NOT NULL    | Kuantitas bahan baku per resep
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

Tabel: stock_conversions
Deskripsi: Header Eksekusi Perakitan / Konversi Stok (`stock-convertion`)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - conversion_number    : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen konversi
  - conversion_date      : DATE          | NOT NULL    | Tanggal pengerjaan
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - material_template_id : CHAR(26)      | FK          | Relasi ke material_templates.id
  - output_qty_produced  : DECIMAL(12,2) | NOT NULL    | Kuantitas hasil jadi yang diproduksi
  - notes                : TEXT          | NULLABLE    | Catatan proses perakitan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp
================================================================================
---

### `customer_groups`

- **Deskripsi:** Grup & Segmen Pelanggan
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - name                 : VARCHAR(100)  | NOT NULL    | Nama grup pelanggan
  - description          : TEXT          | NULLABLE    | Deskripsi segmen
  - ar_account_id        : CHAR(26)      | FK          | Relasi ke chart_of_accounts.id
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

### `customer_areas`

- **Deskripsi:** Zonasi & Area Penjualan
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - area_code            : VARCHAR(50)   | UNIQUE, NOT NULL | Kode wilayah
  - area_name            : VARCHAR(100)  | NOT NULL    | Nama wilayah / zonasi
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

### `customers`

- **Deskripsi:** Master Data Pelanggan Utama (Customer Master)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - customer_code        : VARCHAR(50)   | UNIQUE, NOT NULL | Kode unik pelanggan
  - name                 : VARCHAR(150)  | NOT NULL    | Nama resmi pelanggan / toko
  - nik                  : VARCHAR(20)   | NULLABLE    | Nomor NIK KTP
  - nama_nik             : VARCHAR(150)  | NULLABLE    | Nama sesuai NIK KTP
  - npwp                 : VARCHAR(30)   | NULLABLE    | Nomor NPWP Pajak
  - sim                  : VARCHAR(30)   | NULLABLE    | Nomor SIM
  - customer_group_id    : CHAR(26)      | FK          | Relasi ke customer_groups.id
  - customer_area_id     : CHAR(26)      | FK          | Relasi ke customer_areas.id
  - marketing_id         : CHAR(26)      | FK          | Relasi ke users.id (Salesman PIC)
  - price_list_id        : CHAR(26)      | FK          | Relasi ke price_lists.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - term_of_payment      : INT           | NOT NULL    | DEFAULT 0 (Termin Hari)
  - credit_limit         : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (Batas Kredit)
  - due_date_warning_days: INT           | NOT NULL    | DEFAULT 7 (Warning H- Jatuh Tempo)
  - channel_outlet       : VARCHAR(50)   | NULLABLE    | Saluran distribusi
  - rayon_sales          : VARCHAR(50)   | NULLABLE    | Rayon / rute sales
  - address1             : TEXT          | NOT NULL    | Alamat utama
  - address2             : TEXT          | NULLABLE    | Alamat tambahan
  - kecamatan            : VARCHAR(100)  | NULLABLE    | Kecamatan
  - kabupaten            : VARCHAR(100)  | NULLABLE    | Kabupaten / Kota
  - city                 : VARCHAR(100)  | NOT NULL    | Kota
  - province             : VARCHAR(100)  | NOT NULL    | Provinsi
  - country              : VARCHAR(100)  | NOT NULL    | DEFAULT 'Indonesia'
  - zip_code             : VARCHAR(10)   | NULLABLE    | Kode Pos
  - phone                : VARCHAR(30)   | NULLABLE    | Telepon kantor
  - mobile_phone         : VARCHAR(30)   | NULLABLE    | No HP
  - email                : VARCHAR(100)  | NULLABLE    | Email
  - contact_person       : VARCHAR(100)  | NULLABLE    | Nama Kontak PIC
  - contact_position     : VARCHAR(50)   | NULLABLE    | Jabatan PIC
  - active               : BOOLEAN       | NOT NULL    | DEFAULT true (Status Aktif)
  - note                 : TEXT          | NULLABLE    | Catatan pelanggan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

### `customer_wa_contacts`

- **Deskripsi:** Kontak WhatsApp Terverifikasi Pelanggan
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - name                 : VARCHAR(100)  | NOT NULL    | Nama kontak WA
  - phone_number         : VARCHAR(30)   | NOT NULL    | Nomor telepon WhatsApp
  - role_position        : VARCHAR(50)   | NULLABLE    | Jabatan (Pemilik, Keuangan, dll)
  - is_primary           : BOOLEAN       | NOT NULL    | DEFAULT false
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `customer_tools`

- **Deskripsi:** Peminjaman Asset & Tools Pendukung Penjualan
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - tool_name            : VARCHAR(150)  | NOT NULL    | Nama aset (Dispenser, Showcase, dll)
  - serial_number        : VARCHAR(100)  | NULLABLE    | Nomor seri aset
  - qty                  : DECIMAL(12,2) | NOT NULL    | DEFAULT 1.00
  - condition            : VARCHAR(50)   | NOT NULL    | Kondisi barang
  - loan_date            : DATE          | NOT NULL    | Tanggal peminjaman
  - status               : ENUM          | NOT NULL    | 'LOANED', 'RETURNED', 'LOST', 'BROKEN'
  - note                 : TEXT          | NULLABLE    | Catatan spesifikasi
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan record
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update terakhir

### `customer_centres`

- **Deskripsi:** Data Cabang / Titik Serah Pelanggan (Ship-To)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - centre_code          : VARCHAR(50)   | UNIQUE, NOT NULL | Kode cabang pelanggan
  - centre_name          : VARCHAR(150)  | NOT NULL    | Nama cabang / titik penyerahan
  - address              : TEXT          | NOT NULL    | Alamat lokasi cabang
  - pic_name             : VARCHAR(100)  | NULLABLE    | PIC cabang
  - phone                : VARCHAR(30)   | NULLABLE    | Telepon cabang
  - email                : VARCHAR(100)  | NULLABLE    | Email cabang
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

## ## 2. Loyalty Point & Reward Pelanggan

### `customer_point_settings`

- **Deskripsi:** Pengaturan Dasar Konversi Poin
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - point_ratio          : DECIMAL(15,2) | NOT NULL    | Nominal transaksi per 1 point
  - is_active            : BOOLEAN       | NOT NULL    | DEFAULT true
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu penetapan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `customer_point_promo_rules`

- **Deskripsi:** Aturan Bonus Poin Per Kategori Produk
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - name                 : VARCHAR(100)  | NOT NULL    | Nama aturan promo poin
  - category_id          : CHAR(26)      | FK          | Relasi ke categories.id
  - qty_threshold        : DECIMAL(12,2) | NOT NULL    | Jumlah Qty untuk 1 poin
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `customer_point_category_exceptions`

- **Deskripsi:** Pengecualian Kategori Produk Poin
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - category_id          : CHAR(26)      | FK          | Relasi ke categories.id
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan

### `product_point_claim_setups`

- **Deskripsi:** Katalog Penukaran Poin Produk
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - points_required      : DECIMAL(12,2) | NOT NULL    | Jumlah poin yang dibutuhkan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu penetapan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `customer_point_claims`

- **Deskripsi:** Header Transaksi Klaim Poin
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - doc_number           : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Dokumen Klaim
  - claim_date           : DATE          | NOT NULL    | Tanggal Klaim Poin
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - member_id            : VARCHAR(50)   | NULLABLE    | Nomor Anggota/Member
  - point_regular        : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00
  - point_promo          : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00
  - total_point_claimed  : DECIMAL(12,2) | NOT NULL    | Total poin terpakai
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - user_id              : CHAR(26)      | FK          | Relasi ke users.id
  - note                 : TEXT          | NULLABLE    | Catatan klaim
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `customer_point_claim_details`

- **Deskripsi:** Detail Item Produk Klaim Poin
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - claim_id             : CHAR(26)      | FK          | Relasi ke customer_point_claims.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty                  : DECIMAL(12,2) | NOT NULL    | Jumlah barang klaim
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - point_per_item       : DECIMAL(12,2) | NOT NULL    | Poin per unit barang
  - total_points         : DECIMAL(12,2) | NOT NULL    | Subtotal poin
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan

## ## 3. Pesanan Penjualan & Verifikasi PO (Sales Order)

### `purchase_notes`

- **Deskripsi:** Verifikasi PO Fisik Pelanggan
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - note_number          : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor registrasi verifikasi PO
  - note_date            : DATE          | NOT NULL    | Tanggal verifikasi
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - po_customer_number   : VARCHAR(100)  | NOT NULL    | Nomor PO resmi pelanggan
  - attachment_path      : TEXT          | NULLABLE    | File unggahan scan PO
  - description          : TEXT          | NULLABLE    | Keterangan verifikasi
  - validation_status    : ENUM          | NOT NULL    | 'PENDING', 'VALIDATED', 'REJECTED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `sales_orders`

- **Deskripsi:** Header Pesanan Penjualan (Sales Order)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - so_number            : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen SO
  - so_date              : DATE          | NOT NULL    | Tanggal pemesanan
  - doc_type             : VARCHAR(50)   | NOT NULL    | DEFAULT 'REGULAR'
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - customer_centre_id   : CHAR(26)      | FK          | Relasi ke customer_centres.id (Nullable)
  - customer_area_id    : CHAR(26)      | FK          | Relasi ke customer_areas.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - salesman_id          : CHAR(26)      | FK          | Relasi ke users.id
  - purchase_note_id     : CHAR(26)      | FK          | Relasi ke purchase_notes.id (Nullable)
  - contract_number      : VARCHAR(100)  | NULLABLE    | Nomor kontrak payung
  - term_of_payment      : INT           | NOT NULL    | DEFAULT 0 (Termin Hari)
  - currency_code        : VARCHAR(10)   | NOT NULL    | DEFAULT 'IDR'
  - discount_percent     : DECIMAL(5,2)  | NOT NULL    | DEFAULT 0.00
  - discount_amount      : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - total_amount         : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (Grand Total)
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'APPROVED', 'PARTIAL_FULFILLED', 'COMPLETED', 'CANCELLED'
  - wa_contact_number    : VARCHAR(30)   | NULLABLE    | No WA penerima dokumen
  - note                 : TEXT          | NULLABLE    | Catatan pesanan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

### `sales_order_details`

- **Deskripsi:** Detail Item Pesanan Penjualan
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - sales_order_id       : CHAR(26)      | FK          | Relasi ke sales_orders.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_ordered          : DECIMAL(12,2) | NOT NULL    | Kuantitas dipesan
  - qty_fulfilled        : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - unit_price           : DECIMAL(15,2) | NOT NULL    | Harga satuan deal
  - discount_amount      : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - subtotal             : DECIMAL(15,2) | NOT NULL    | Total harga bersih item
  - weight_tonase        : DECIMAL(12,3) | NOT NULL    | DEFAULT 0.000 (Berat Ton)
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

## ## 4. Packing, Pengiriman & Logistik

### `packings`

- **Deskripsi:** Pengemasan Barang Gudang
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - packing_number       : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen packing
  - packing_date         : DATE          | NOT NULL    | Tanggal pengemasan
  - sales_order_id       : CHAR(26)      | FK          | Relasi ke sales_orders.id
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - packing_staff_id     : CHAR(26)      | FK          | Relasi ke users.id
  - total_box_package    : INT           | NOT NULL    | DEFAULT 1 (Jumlah Koli)
  - total_weight         : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (Total KG)
  - status               : ENUM          | NOT NULL    | 'PACKING', 'READY_FOR_SHIPMENT', 'CANCELLED'
  - note                 : TEXT          | NULLABLE    | Instruksi pengemasan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `delivery_orders`

- **Deskripsi:** Surat Jalan / Delivery Order (DO)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - do_number            : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Surat Jalan DO
  - do_date              : DATE          | NOT NULL    | Tanggal pengiriman
  - sales_order_id       : CHAR(26)      | FK          | Relasi ke sales_orders.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - driver_name          : VARCHAR(100)  | NULLABLE    | Nama Pengemudi
  - vehicle_number       : VARCHAR(30)   | NULLABLE    | Plat Nomor Armada
  - expeditor            : VARCHAR(100)  | NULLABLE    | Ekspedisi / Kurir
  - delivery_address     : TEXT          | NOT NULL    | Alamat tujuan pengiriman
  - status               : ENUM          | NOT NULL    | 'PREPARED', 'IN_TRANSIT', 'DELIVERED', 'FAILED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

### `shipment_preparations`

- **Deskripsi:** Konsolidasi & Rencana Armada
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - prep_number          : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Rencana Muat
  - prep_date            : DATE          | NOT NULL    | Tanggal jadwal keberangkatan
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - route_area_id        : CHAR(26)      | FK          | Relasi ke customer_areas.id
  - fleet_vehicle_type   : VARCHAR(50)   | NULLABLE    | Jenis kendaraan
  - total_weight         : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (KG)
  - total_volume         : DECIMAL(12,2) | NOT NULL    | DEFAULT 0.00 (M3)
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'SCHEDULED', 'LOADING', 'DISPATCHED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `shipment_preparation_dos`

- **Deskripsi:** Mapping Konsolidasi DO ke Armada
Kolom:
  - id                       : CHAR(26)  | PRIMARY KEY | ULID Unique Identifier
  - shipment_preparation_id  : CHAR(26)  | FK          | Relasi ke shipment_preparations.id
  - delivery_order_id        : CHAR(26)  | FK          | Relasi ke delivery_orders.id
  - sequence_order           : INT       | NOT NULL    | DEFAULT 1 (Urutan Bongkar)
  - created_at               : TIMESTAMP | NOT NULL    | Waktu pencatatan

## ## 5. Faktur Penjualan, TTI, & TTP (Sales Invoicing)

### `sales_invoices`

- **Deskripsi:** Faktur Penjualan (Sales Invoice / SI)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - invoice_number       : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Faktur Penjualan
  - invoice_date         : DATE          | NOT NULL    | Tanggal terbit faktur
  - due_date             : DATE          | NOT NULL    | Tanggal jatuh tempo
  - doc_type             : VARCHAR(50)   | NOT NULL    | DEFAULT 'REGULAR'
  - printed_status       : BOOLEAN       | NOT NULL    | DEFAULT false
  - delivery_order_id    : CHAR(26)      | FK          | Relasi ke delivery_orders.id (Nullable)
  - sales_order_id       : CHAR(26)      | FK          | Relasi ke sales_orders.id
  - faktur_pajak_number  : VARCHAR(50)   | NULLABLE    | Nomor Seri Faktur Pajak
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - customer_area_id     : CHAR(26)      | FK          | Relasi ke customer_areas.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - user_id              : CHAR(26)      | FK          | Relasi ke users.id
  - term_of_payment      : INT           | NOT NULL    | DEFAULT 0 (Termin Hari)
  - currency_code        : VARCHAR(10)   | NOT NULL    | DEFAULT 'IDR'
  - total_amount         : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (Grand Total)
  - discount_percent     : DECIMAL(5,2)  | NOT NULL    | DEFAULT 0.00
  - discount_amount      : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - outstanding_amount   : DECIMAL(15,2) | NOT NULL    | Sisa piutang terhutang
  - status               : ENUM          | NOT NULL    | 'UNPAID', 'PARTIAL', 'PAID', 'VOID'
  - delivery_status      : ENUM          | NOT NULL    | 'PENDING', 'DELIVERED', 'RETURNED'
  - wa_contact_number    : VARCHAR(30)   | NULLABLE    | No WA dikirimi tagihan
  - purchase_note_info   : VARCHAR(100)  | NULLABLE    | Info No PO pelanggan
  - note                 : TEXT          | NULLABLE    | Catatan faktur
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

### `sales_invoice_details`

- **Deskripsi:** Detail Rincian Item Tagihan Faktur
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - sales_invoice_id     : CHAR(26)      | FK          | Relasi ke sales_invoices.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty                  : DECIMAL(12,2) | NOT NULL    | Jumlah ditagihkan
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - unit_price           : DECIMAL(15,2) | NOT NULL    | Harga satuan jual
  - hpp_cost             : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00 (HPP per unit)
  - subtotal             : DECIMAL(15,2) | NOT NULL    | Subtotal tagihan item
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `shipment_priorities`

- **Deskripsi:** Prioritas Pengiriman Invoice & Barang
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - priority_no          : INT           | NOT NULL    | Urutan prioritas (1, 2, 3...)
  - sales_invoice_id     : CHAR(26)      | FK          | Relasi ke sales_invoices.id
  - sales_order_id       : CHAR(26)      | FK          | Relasi ke sales_orders.id
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - customer_area_id     : CHAR(26)      | FK          | Relasi ke customer_areas.id
  - total_weight_volume  : VARCHAR(100)  | NULLABLE    | Estimasi bobot/volume
  - promised_date        : DATE          | NOT NULL    | Tanggal janji serah
  - status               : ENUM          | NOT NULL    | 'QUEUED', 'IN_PROGRESS', 'COMPLETED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu registrasi

### `tanda_terima_invoices`

- **Deskripsi:** Bukti Penyerahan Fisik Tagihan ke Customer (TTI)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - tti_number           : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor dokumen TTI
  - tti_date             : DATE          | NOT NULL    | Tanggal pengiriman tagihan
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - received_by_pic      : VARCHAR(100)  | NULLABLE    | Nama PIC pelanggan penerima
  - received_date        : DATE          | NULLABLE    | Tanggal diterima pelanggan
  - return_status        : ENUM          | NOT NULL    | 'DELIVERED', 'PENDING_RETURN', 'RETURNED_TO_OFFICE'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `tanda_terima_invoice_details`

- **Deskripsi:** Rincian Invoice Terlampir TTI
Kolom:
  - id                      : CHAR(26)   | PRIMARY KEY | ULID Unique Identifier
  - tanda_terima_invoice_id : CHAR(26)   | FK          | Relasi ke tanda_terima_invoices.id
  - sales_invoice_id        : CHAR(26)   | FK          | Relasi ke sales_invoices.id
  - amount                  : DECIMAL(15,2)| NOT NULL  | Nilai tagihan faktur
  - created_at              : TIMESTAMP  | NOT NULL    | Waktu pencatatan

### `tanda_terima_penagihans`

- **Deskripsi:** Dokumen Penyerahan Penagihan ke Kolektor (TTP)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - ttp_number           : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Surat Jalan Kolektor
  - ttp_date             : DATE          | NOT NULL    | Tanggal penugasan
  - collector_user_id    : CHAR(26)      | FK          | Relasi ke users.id
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - total_invoice_count  : INT           | NOT NULL    | DEFAULT 1
  - total_amount         : DECIMAL(15,2) | NOT NULL    | Total nilai tagihan
  - due_date             : DATE          | NOT NULL    | Target tanggal kembali
  - status               : ENUM          | NOT NULL    | 'ASSIGNED', 'IN_COLLECTION', 'COMPLETED', 'PARTIAL'
  - note                 : TEXT          | NULLABLE    | Instruksi penagihan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

## ## 6. Penerimaan Pembayaran & Piutang (AR Payment)

### `customer_payments`

- **Deskripsi:** Header Penerimaan Pembayaran Pelanggan
Kolom:
  - id                        : CHAR(26)  | PRIMARY KEY | ULID Unique Identifier
  - payment_number            : VARCHAR(50)| UNIQUE, NOT NULL | No Bukti Kas/Bank Masuk
  - payment_date              : DATE      | NOT NULL    | Tanggal penerimaan bayar
  - date_completed            : DATE      | NULLABLE    | Tanggal dana efektif cair
  - warehouse_id              : CHAR(26)  | FK          | Relasi ke warehouses.id
  - tanda_terima_penagihan_id : CHAR(26)  | FK          | Relasi ke tanda_terima_penagihans.id (Nullable)
  - customer_id               : CHAR(26)  | FK          | Relasi ke customers.id
  - account_id                : CHAR(26)  | FK          | Relasi ke chart_of_accounts.id
  - payment_type              : ENUM      | NOT NULL    | 'REGULAR', 'DOWN_PAYMENT'
  - payment_method            : ENUM      | NOT NULL    | 'TRANSFER', 'CASH', 'GIRO', 'CHECK'
  - total_amount              : DECIMAL(15,2)| NOT NULL | Total uang diterima
  - currency_code             : VARCHAR(10)| NOT NULL    | DEFAULT 'IDR'
  - exchange_rate             : DECIMAL(10,4)| NOT NULL | DEFAULT 1.0000
  - default_sales_id          : CHAR(26)  | FK          | Relasi ke users.id
  - status                    : ENUM      | NOT NULL    | 'DRAFT', 'POSTED', 'CANCELLED'
  - note                      : TEXT      | NULLABLE    | Catatan pembayaran
  - created_at                : TIMESTAMP | NOT NULL    | Waktu pencatatan
  - updated_at                : TIMESTAMP | NOT NULL    | Waktu update
  - deleted_at                : TIMESTAMP | NULLABLE    | Soft delete timestamp

### `customer_payment_details`

- **Deskripsi:** Detail Alokasi Pembayaran ke Faktur
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - customer_payment_id  : CHAR(26)      | FK          | Relasi ke customer_payments.id
  - sales_invoice_id     : CHAR(26)      | FK          | Relasi ke sales_invoices.id
  - amount_paid          : DECIMAL(15,2) | NOT NULL    | Nominal porsi pelunasan
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `customer_payment_checks`

- **Deskripsi:** Instrumen Pembayaran Cek & Giro
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - customer_payment_id  : CHAR(26)      | FK          | Relasi ke customer_payments.id
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - check_giro_number    : VARCHAR(100)  | NOT NULL    | Nomor Lembar Cek / Giro
  - bank_name            : VARCHAR(100)  | NOT NULL    | Bank Penerbit Cek/Giro
  - maturity_date        : DATE          | NOT NULL    | Tanggal Jatuh Tempo
  - amount               : DECIMAL(15,2) | NOT NULL    | Nominal Cek/Giro
  - status               : ENUM          | NOT NULL    | 'CLEARING', 'BOUNCED', 'PASSED', 'CANCELLED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu penerimaan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

## ## 7. Retur Penjualan, Komisi Sales & Pajak

### `sales_returns`

- **Deskripsi:** Header Retur Penjualan (Sales Return)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - return_number        : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Nota Retur Penjualan
  - return_date          : DATE          | NOT NULL    | Tanggal pengembalian
  - sales_invoice_id     : CHAR(26)      | FK          | Relasi ke sales_invoices.id
  - customer_id          : CHAR(26)      | FK          | Relasi ke customers.id
  - customer_area_id     : CHAR(26)      | FK          | Relasi ke customer_areas.id
  - warehouse_id         : CHAR(26)      | FK          | Relasi ke warehouses.id
  - salesman_id          : CHAR(26)      | FK          | Relasi ke users.id
  - currency_code        : VARCHAR(10)   | NOT NULL    | DEFAULT 'IDR'
  - discount_percent     : DECIMAL(5,2)  | NOT NULL    | DEFAULT 0.00
  - discount_amount      : DECIMAL(15,2) | NOT NULL    | DEFAULT 0.00
  - total_amount         : DECIMAL(15,2) | NOT NULL    | Total nilai nota retur
  - status               : ENUM          | NOT NULL    | 'DRAFT', 'APPROVED', 'COMPLETED', 'CANCELLED'
  - wa_contact_number    : VARCHAR(30)   | NULLABLE    | No WA penerima dokumen
  - term_of_payment      : INT           | NOT NULL    | DEFAULT 0
  - note                 : TEXT          | NULLABLE    | Alasan retur
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update
  - deleted_at           : TIMESTAMP     | NULLABLE    | Soft delete timestamp

### `sales_return_details`

- **Deskripsi:** Detail Item Barang Retur
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - sales_return_id      : CHAR(26)      | FK          | Relasi ke sales_returns.id
  - material_id          : CHAR(26)      | FK          | Relasi ke materials.id
  - qty_returned         : DECIMAL(12,2) | NOT NULL    | Jumlah dikembalikan
  - unit_id              : CHAR(26)      | FK          | Relasi ke units.id
  - unit_price           : DECIMAL(15,2) | NOT NULL    | Harga patokan retur
  - subtotal             : DECIMAL(15,2) | NOT NULL    | Subtotal nilai retur
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pembuatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update

### `sales_commissions`

- **Deskripsi:** Perhitungan & Pencairan Komisi Salesman
Kolom:
  - id                      : CHAR(26)   | PRIMARY KEY | ULID Unique Identifier
  - commission_number       : VARCHAR(50)| UNIQUE, NOT NULL | Nomor Bukti Komisi
  - commission_date         : DATE       | NOT NULL    | Tanggal pengerjaan komisi
  - period                  : VARCHAR(20)| NOT NULL    | Periode (misal: '2026-07')
  - salesman_id             : CHAR(26)   | FK          | Relasi ke users.id
  - calculation_base        : ENUM       | NOT NULL    | 'OMSET', 'COLLECTION'
  - target_amount           : DECIMAL(15,2)| NOT NULL  | Target penjualan
  - achieved_amount         : DECIMAL(15,2)| NOT NULL  | Capaian aktual
  - commission_rate_percent : DECIMAL(5,2) | NOT NULL  | Persentase komisi (%)
  - total_commission_paid   : DECIMAL(15,2)| NOT NULL  | Total nominal hak komisi
  - status                  : ENUM       | NOT NULL    | 'DRAFT', 'APPROVED', 'PAID'
  - created_at              : TIMESTAMP  | NOT NULL    | Waktu pencatatan
  - updated_at              : TIMESTAMP  | NOT NULL    | Waktu update

### `sales_taxes`

- **Deskripsi:** Pencatatan Pajak Penjualan (PPN / e-Faktur)
Kolom:
  - id                   : CHAR(26)      | PRIMARY KEY | ULID Unique Identifier
  - tax_doc_number       : VARCHAR(50)   | UNIQUE, NOT NULL | Nomor Registrasi Pajak
  - sales_invoice_id     : CHAR(26)      | FK          | Relasi ke sales_invoices.id
  - tax_code             : VARCHAR(20)   | NOT NULL    | DEFAULT 'PPN_11'
  - customer_npwp        : VARCHAR(30)   | NULLABLE    | NPWP Pelanggan
  - dpp_amount           : DECIMAL(15,2) | NOT NULL    | Dasar Pengenaan Pajak
  - tax_amount           : DECIMAL(15,2) | NOT NULL    | Nominal Pajak
  - faktur_pajak_number  : VARCHAR(50)   | NULLABLE    | Nomor Seri Faktur Pajak
  - efaktur_status       : ENUM          | NOT NULL    | 'UNEXPORTED', 'EXPORTED', 'APPROVED'
  - created_at           : TIMESTAMP     | NOT NULL    | Waktu pencatatan
  - updated_at           : TIMESTAMP     | NOT NULL    | Waktu update


