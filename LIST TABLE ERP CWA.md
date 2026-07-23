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
- `total_amount` (decimal 15,2)
- `paid_amount` (decimal 15,2, default 0)
- `status` (enum: 'UNPAID', 'PARTIAL', 'PAID')
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
- `supplier_id` (foreign key -> `suppliers.id`)
- `payment_type` (enum: 'REGULAR', 'DOWN_PAYMENT')
- `payment_method` (enum: 'TRANSFER', 'CASH', 'GIRO')
- `total_paid` (decimal 15,2)
- `reference_number` (varchar 100)
- `note` (text)
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
- `supplier_id` (foreign key -> `suppliers.id`)
- `purchase_invoice_id` (foreign key -> `purchase_invoices.id`, Nullable)
- `stbj_id` (foreign key -> `goods_receipts.id`, Nullable)
- `total_return_amount` (decimal 15,2)
- `reason` (text)
- `status` (enum: 'DRAFT', 'APPROVED', 'COMPLETED')
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
- `supplier_id` (foreign key -> `suppliers.id`)
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
- `warehouse_id` (foreign key -> `warehouses.id`)
- `adjustment_type` (enum: 'STANDARD', 'INTERNAL_USE')
- `reason` (text)
- `status` (enum: 'DRAFT', 'APPROVED')
- `created_at`, `updated_at`, `deleted_at`

### `stock_adjustment_details` (Detail Penyesuaian Stok)
- `id` (char 26, Primary Key)
- `stock_adjustment_id` (foreign key -> `stock_adjustments.id`)
- `material_id` (foreign key -> `materials.id`)
- `system_qty` (decimal 12,2)
- `physical_qty` (decimal 12,2)
- `qty_diff` (decimal 12,2)
- `cost_per_unit` (decimal 15,2)
- `total_cost_diff` (decimal 15,2)
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
- `created_at`
