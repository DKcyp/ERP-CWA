# Skema Database Material Management & Sales Distribution ERP CWA

---

## 1. Kelompok Master Data Supplier & Material

### `supplier_groups` (Grup Supplier)
- `id` (char 26 / ULID / Primary Key)
- `code` (varchar 50, Unique)
- `name` (varchar 100)
- `description` (text, Nullable)
- `created_at`, `updated_at`, `deleted_at`

### `supplier_centers` (Pusat / Area Supplier)
- `id` (char 26, Primary Key)
- `code` (varchar 50, Unique)
- `name` (varchar 100)
- `created_at`, `updated_at`, `deleted_at`

### `suppliers` (Master Supplier Utama)
- `id` (char 26, Primary Key)
- `supplier_code` (varchar 50, Unique)
- `name` (varchar 150)
- `supplier_group_id` (foreign key -> `supplier_groups.id`)
- `supplier_center_id` (foreign key -> `supplier_centers.id`)
- `phone` (varchar 30)
- `email` (varchar 100)
- `address` (text)
- `term_of_payment` (integer, TOP Hari)
- `status` (boolean / tinyint)
- `created_at`, `updated_at`, `deleted_at`

### `materials` / `products` (Master Barang / Material)
- `id` (char 26, Primary Key)
- `item_code` (varchar 50, Unique)
- `item_name` (varchar 150)
- `category_id` (foreign key)
- `unit_id` (foreign key)
- `cost_price` (decimal 15,2)
- `min_stock` (decimal 12,2)
- `max_stock` (decimal 12,2)
- `status` (boolean)
- `created_at`, `updated_at`, `deleted_at`

### `warehouses` (Master Gudang)
- `id` (char 26, Primary Key)
- `warehouse_code` (varchar 50, Unique)
- `warehouse_name` (varchar 100)
- `location` (text)
- `created_at`, `updated_at`, `deleted_at`

---

## 2. Purchase Request (PR - Permintaan Pembelian)

### `purchase_requests` (Header PR)
- `id` (char 26, Primary Key)
- `pr_number` (varchar 50, Unique)
- `pr_date` (date)
- `requester_user_id` (foreign key -> `users.id`)
- `department` (varchar 100)
- `status` (enum: 'DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'FULFILLED')
- `note` (text)
- `created_at`, `updated_at`, `deleted_at`

### `purchase_request_details` (Detail PR)
- `id` (char 26, Primary Key)
- `purchase_request_id` (foreign key -> `purchase_requests.id`)
- `material_id` (foreign key -> `materials.id`)
- `qty_requested` (decimal 12,2)
- `qty_fulfilled` (decimal 12,2, default 0)
- `unit_id` (foreign key)
- `notes` (text)
- `created_at`, `updated_at`

---

## 3. Purchase Order (PO - Pesanan Pembelian)

### `purchase_orders` (Header PO)
- `id` (char 26, Primary Key)
- `po_number` (varchar 50, Unique)
- `po_date` (date)
- `supplier_id` (foreign key -> `suppliers.id`)
- `purchase_request_id` (foreign key -> `purchase_requests.id`, Nullable)
- `payment_term_days` (integer)
- `subtotal` (decimal 15,2)
- `tax_amount` (decimal 15,2)
- `discount_amount` (decimal 15,2)
- `total_amount` (decimal 15,2)
- `status` (enum: 'DRAFT', 'APPROVED', 'PARTIAL', 'CLOSED', 'CANCELLED')
- `note` (text)
- `created_at`, `updated_at`, `deleted_at`

### `purchase_order_details` (Detail PO)
- `id` (char 26, Primary Key)
- `purchase_order_id` (foreign key -> `purchase_orders.id`)
- `material_id` (foreign key -> `materials.id`)
- `qty_ordered` (decimal 12,2)
- `qty_received` (decimal 12,2, default 0)
- `unit_price` (decimal 15,2)
- `discount` (decimal 15,2)
- `tax` (decimal 15,2)
- `subtotal` (decimal 15,2)
- `created_at`, `updated_at`

---

## 4. STBJ (Surat Tanda Bukti Jalan / Goods Receipt Note - Penerimaan Barang)

### `goods_receipts` / `stbj` (Header STBJ)
- `id` (char 26, Primary Key)
- `stbj_number` (varchar 50, Unique)
- `stbj_date` (date)
- `supplier_id` (foreign key -> `suppliers.id`)
- `purchase_order_id` (foreign key -> `purchase_orders.id`)
- `warehouse_id` (foreign key -> `warehouses.id`)
- `sj_supplier_number` (varchar 100, No Surat Jalan Supplier)
- `note` (text)
- `created_at`, `updated_at`, `deleted_at`

### `goods_receipt_details` / `stbj_details` (Detail STBJ)
- `id` (char 26, Primary Key)
- `stbj_id` (foreign key -> `goods_receipts.id`)
- `material_id` (foreign key -> `materials.id`)
- `qty_received` (decimal 12,2)
- `qty_accepted` (decimal 12,2)
- `qty_rejected` (decimal 12,2)
- `unit_id` (foreign key)
- `note` (text)
- `created_at`, `updated_at`

---

## 5. Purchase Invoice (Faktur Pembelian)

### `purchase_invoices` (Header Invoice)
- `id` (char 26, Primary Key)
- `invoice_number` (varchar 50, Unique)
- `invoice_date` (date)
- `due_date` (date)
- `supplier_id` (foreign key -> `suppliers.id`)
- `purchase_order_id` (foreign key -> `purchase_orders.id`, Nullable)
- `stbj_id` (foreign key -> `goods_receipts.id`, Nullable)
- `currency` (varchar 10, default 'IDR')
- `rate` (integer, exchange rate terhadap IDR, default 1)
- `total` (decimal 15,2, total invoice dalam foreign currency)
- `paid_amount` (decimal 15,2, total yang sudah dibayar, default 0)
- `outstanding` (decimal 15,2, derived: total - paid_amount)
- `term` (varchar 50, payment term e.g. Net 14, Net 30)
- `status` (enum: 'DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'PAID')
- `note` (text)
- `created_at`, `updated_at`, `deleted_at`

### `purchase_invoice_details` (Detail Invoice)
- `id` (char 26, Primary Key)
- `purchase_invoice_id` (foreign key -> `purchase_invoices.id`)
- `material_id` (foreign key -> `materials.id`)
- `qty` (decimal 12,2)
- `unit_price` (decimal 15,2)
- `subtotal` (decimal 15,2)
- `created_at`, `updated_at`

---

## 6. Supplier Payment & Down Payment (DP)

### `supplier_payments` (Header Pembayaran Supplier)
- `id` (char 26, Primary Key)
- `payment_number` (varchar 50, Unique)
- `payment_date` (date)
- `supplier_id` (varchar 50)
- `supplier_name` (varchar 150)
- `currency` (varchar 10)
- `rate` (integer, exchange rate terhadap IDR, default 1)
- `invoice_date` (date, tanggal invoice terkait)
- `subtotal` (decimal 15,2, total sebelum diskon)
- `discount_percent` (decimal 5,2, persentase diskon)
- `discount_amount` (decimal 15,2, nominal diskon)
- `lain_lain` (decimal 15,2, biaya lain-lain)
- `total_payment` (decimal 15,2, total akhir: subtotal - diskon + lain-lain)
- `total_paid` (decimal 15,2, total yang sudah dibayarkan)
- `account_id` (varchar 50, nomor rekening)
- `account` (varchar 100, nama bank/rekening)
- `user_name` (varchar 150)
- `complete_date` (date, Nullable)
- `stbj_number` (varchar 50)
- `invoice_number` (varchar 50)
- `note` (text)
- `note_detail` (text, catatan detail)
- `payment_type` (varchar 20)
- `status` (enum: 'DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'PAID')
- `created_at`, `updated_at`, `deleted_at`

### `supplier_payment_details` (Detail Alokasi Invoice)
- `id` (char 26, Primary Key)
- `supplier_payment_id` (foreign key -> `supplier_payments.id`)
- `purchase_invoice_id` (foreign key -> `purchase_invoices.id`)
- `amount_paid` (decimal 15,2)
- `created_at`, `updated_at`

---

## 7. Purchase Return (Retur Pembelian) & SJBB

### `purchase_returns` (Header Retur Pembelian)
- `id` (char 26, Primary Key)
- `return_number` (varchar 50, Unique)
- `return_date` (date)
- `warehouse` (varchar 100)
- `supplier_id` (varchar 50)
- `supplier_name` (varchar 150)
- `currency` (varchar 10, default 'IDR')
- `term` (varchar 50)
- `discount_percent` (decimal 5,2)
- `discount_amount` (decimal 15,2)
- `total_return_amount` (decimal 15,2, dihitung dari items - diskon)
- `user_name` (varchar 150)
- `account` (varchar 100)
- `price_list` (varchar 50)
- `note` (text)
- `status` (enum: 'DRAFT', 'APPROVED', 'COMPLETED')
- `items` (array of objects: material, qty, unit, price)
- `created_at`, `updated_at`, `deleted_at`

### `purchase_return_details` (Detail Retur Pembelian)
- `id` (char 26, Primary Key)
- `purchase_return_id` (foreign key -> `purchase_returns.id`)
- `material_id` (foreign key -> `materials.id`)
- `qty_returned` (decimal 12,2)
- `unit_price` (decimal 15,2)
- `subtotal` (decimal 15,2)
- `created_at`, `updated_at`

### `sjbb` (Surat Jalan Bukti Barter)
- `id` (char 26, Primary Key)
- `sjbb_number` (varchar 50, Unique)
- `sjbb_date` (date)
- `supplier_id` (varchar 50)
- `supplier_name` (varchar 150)
- `type` (enum: 'IN', 'OUT')
- `status` (varchar 50)
- `notes` (text)
- `created_at`, `updated_at`, `deleted_at`

---

## 8. Stock Adjustment (Penyesuaian Stok)

### `stock_adjustments` (Header Penyesuaian Stok)
- `id` (char 26, Primary Key)
- `adjustment_number` (varchar 50, Unique)
- `adjustment_date` (date)
- `warehouse` (varchar 100)
- `department` (varchar 100)
- `adjustment_type` (enum: 'STANDARD', 'INTERNAL_USE')
- `use_for` (varchar 200)
- `transfer_to_ta` (varchar 50)
- `product_group` (varchar 100)
- `pic` (varchar 100)
- `user_id` (varchar 50)
- `reason` (text)
- `status` (enum: 'DRAFT', 'APPROVED', 'COMPLETED')
- `items` (array of objects: material, system_qty, physical_qty, cost_per_unit)
- `created_at`, `updated_at`, `deleted_at`

### `stock_adjustment_details` (Detail Penyesuaian Stok)
- `id` (char 26, Primary Key)
- `stock_adjustment_id` (foreign key -> `stock_adjustments.id`)
- `material` (varchar 150, nama material)
- `system_qty` (decimal 12,2)
- `physical_qty` (decimal 12,2)
- `qty_diff` (decimal 12,2, derived: system_qty - physical_qty)
- `cost_per_unit` (decimal 15,2)
- `total_cost_diff` (decimal 15,2, derived: qty_diff * cost_per_unit)
- `created_at`, `updated_at`

---

## 9. Stock Transfer (Transfer Stok Antar Gudang)

### `stock_transfers` (Header Transfer Stok)
- `id` (char 26, Primary Key)
- `transfer_number` (varchar 50, Unique)
- `transfer_date` (date)
- `from_warehouse_id` (foreign key -> `warehouses.id`)
- `to_warehouse_id` (foreign key -> `warehouses.id`)
- `status` (enum: 'REQUESTED', 'PREPARED', 'IN_TRANSIT', 'COMPLETED', 'CANCELLED')
- `notes` (text)
- `created_at`, `updated_at`, `deleted_at`

### `stock_transfer_details` (Detail Transfer Stok)
- `id` (char 26, Primary Key)
- `stock_transfer_id` (foreign key -> `stock_transfers.id`)
- `material_id` (foreign key -> `materials.id`)
- `qty_requested` (decimal 12,2)
- `qty_shipped` (decimal 12,2, default 0)
- `qty_received` (decimal 12,2, default 0)
- `created_at`, `updated_at`

---

## 10. Stock Conversion & Material Template (BOM)

### `material_templates` (Template Resep Material / BOM)
- `id` (char 26, Primary Key)
- `template_code` (varchar 50, Unique)
- `template_name` (varchar 150)
- `target_material_id` (foreign key -> `materials.id`)
- `target_output_qty` (decimal 12,2)
- `description` (text)
- `created_at`, `updated_at`, `deleted_at`

### `material_template_details` (Komponen Bahan Baku Template)
- `id` (char 26, Primary Key)
- `material_template_id` (foreign key -> `material_templates.id`)
- `raw_material_id` (foreign key -> `materials.id`)
- `qty_needed` (decimal 12,2)
- `unit_id` (foreign key)
- `created_at`, `updated_at`

### `stock_conversions` (Transaksi Eksekusi Konversi)
- `id` (char 26, Primary Key)
- `conversion_number` (varchar 50, Unique)
- `conversion_date` (date)
- `warehouse_id` (foreign key -> `warehouses.id`)
- `material_template_id` (foreign key -> `material_templates.id`)
- `output_qty_produced` (decimal 12,2)
- `notes` (text)
- `created_at`, `updated_at`, `deleted_at`

---

## 11. Stock Ledger (Kartu Stok / Mutasi Stok Real-Time)

### `stock_ledgers` (Kartu Mutasi Stok)
- `id` (char 26, Primary Key)
- `material_id` (foreign key -> `materials.id`)
- `warehouse_id` (foreign key -> `warehouses.id`)
- `reference_type` (varchar 50: 'STBJ', 'PURCHASE_RETURN', 'STOCK_ADJUSTMENT', 'STOCK_TRANSFER', 'STOCK_CONVERSION')
- `reference_id` (char 26)
- `qty_in` (decimal 12,2, default 0)
- `qty_out` (decimal 12,2, default 0)
- `balance_qty` (decimal 12,2)
- `cost_price` (decimal 15,2)
- `created_at`, `updated_at`, `deleted_at`

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


