# ERP Menu List

## Material Management `#`

- Supplier Master `supplier-master`
- Supplier Group `supplier-group`
- Supplier Center `supplier-center`
- Supplier Balance Summary `supplier-balance-summary`
- Purchase Request `#`
  - New Purchase Request `new-purchase-request`
  - Purchase Request List `purchase-request-list`
  - Purchase Request Fulfilment Report `purchase-request-fulfilment-report`
- Purchase Order `#`
  - New Purchase Order `new-purchase-order`
  - Purchase Order List `purchase-order-list`
  - Purchase Fulfillment Report `purchase-fulfillment-report`
  - Daily Purchase Order Report `daily-purchase-order-report`
- Purchase Invoice `#`
  - New Purchase Invoice `new-purchase-invoice`
  - Purchase Invoice List `purchase-invoice-list`
  - Daily Purchase Invoice Report `daily-purchase-invoice-report`
  - Monthly Purchase by Supplier Report `monthly-purchase-by-supplier-report`
- STBJ `stbj`
- Supplier Payment `#`
  - New Supplier Payment `new-supplier-payment`
  - New Supplier Down Payment `new-supplier-down-payment`
  - Supplier Payment List `supplier-payment-list`
  - Supp. Outstanding List `supp-outstanding-list`
  - Daily Supplier Payment Report `daily-supplier-payment-report`
  - Daily Supplier Payment List `daily-supplier-payment-list`
- Purchase Return `#`
  - New Purchase Return `new-purchase-return`
  - Purchase Return List `purchase-return-list`
- SJBB `sjbb`
- Stock Adjustment `#`
  - Stock Adjustment Use `stock-adjustment-use`
  - New Stock Adjustment (Standard) `new-stock-adjustment-standard`
  - New Stock Adjustment (Internal Use) `new-stock-adjustment-internal-use`
  - Stock Adjustment List `stock-adjustment-list`
  - Daily Stock Adjustment Report `daily-stock-adjustment-report`
  - Daily Stock Adjustment Track Report `daily-stock-adjustment-track-report`
  - Daily Stock Adjustment Cost Report `daily-stock-adjustment-cost-report`
- Stock Transfer `#`
  - New Stock Transfer `new-stock-transfer`
  - Stock Transfer List `stock-transfer-list`
  - Stock Transfer Shipment Preparation `stock-transfer-shipment-preparation`
  - Stock Transfer Shipment Preparation List `stock-transfer-shipment-preparation-list`
  - New Stock Transfer Request `new-stock-transfer-request`
  - Stock Transfer Request List `stock-transfer-request-list`
  - Daily Stock Transfer Report `daily-stock-transfer-report`
  - Stock Transfer Fulfilment `stock-transfer-fulfilment`
- Stock Conversion `stock-convertion`
- Material Template `material-template`

## Sales & Distribution `#`
## Sales & Distribution `#`

- Customer Master `customer-master`
  Komponen :Id,Name,NIK,Nama (NIK),NPWP,SIM,Marketing,Credit Limit,Due Date Warning,Warehouse,Active,Contact,Position,Address1,Address2,Kecamatan,Kabupaten,City,ZIP,Channel Outlet,Rayon Sales,Province,Country,Phone,Mobile_Phone,Email,Note,Price List Id,Term
  Fungsi : Mengelola data induk (master data) pelanggan secara komprehensif mulai dari identitas, alamat, kontak, hingga kebijakan kredit dan daftar harga.

- Customer Group `customer-group`
  Komponen :Id,name,description,AR Account 
  Fungsi : Mengelompokkan pelanggan berdasarkan segmen atau tipe tertentu serta memetakan akun Piutang (AR Account) yang sesuai pada buku besar.

- Customer Area `customer-area`
  Komponen :Id,Area
  Fungsi : Menentukan pembagian wilayah atau zonasi operasional penjualan dan distribusi pelanggan.

- WA Name `wa-name`
  Komponen :Id,Customer ID,Name,Phone Number,Role/Position,Is Primary
  Fungsi : Mengelola daftar kontak WhatsApp terverifikasi milik pelanggan untuk kebutuhan pengiriman notifikasi, dokumen, dan komunikasi operasional.

- Customer Tools `customer-tools`
  Komponen :Id,Customer ID,Tool Name,Serial Number,Qty,Condition,Loan Date,Status,Note
  Fungsi : Mencatat dan memantau peminjaman atau alokasi aset/peralatan pendukung penjualan yang dipinjamkan ke pelanggan (misal: mesin dispenser, pendingin, atau display banner).

- Customer Centre `customer-centre`
  Komponen :Id,Customer ID,Centre Code,Centre Name,Address,PIC Name,Phone,Email,Warehouse ID
  Fungsi : Mengelola data cabang, titik serah, atau unit lokasi penyerahan barang milik pelanggan utama/korporat.

- Customer Balance Summary `customer-balance-summary`
  Komponen :Customer ID,Name,Currency,Beginning Balance,Total Invoice,Total Payment,Total Return,Ending Balance,Credit Limit,Available Credit
  Fungsi : Menyajikan ringkasan posisi saldo piutang pelanggan, sisa batas kredit, dan histori akumulasi mutasi secara real-time.
- AR Warehouse Report `ar-warehouse-report`
  Komponen :Warehouse ID,Warehouse Name,Customer ID,Customer Name,Invoice No,Invoice Date,Due Date,Outstanding Amount,Age (Days)
  Fungsi : Laporan rincian piutang usaha yang dikelompokkan berdasarkan gudang pemenuhan pesanan.
- Customer Point `customer-point`
  - Point Setting
  Komponen :Point(num)
  Fungsi : Mengatur rasio dasar konversi transaksi menjadi poin loyalitas pelanggan.
  - Customer Point Promo Rule
  Komponen : Category ID,Name,1 Point = ? Qty,UOM Id
  Fungsi : Mengatur aturan khusus perolehan poin berdasarkan kuantitas pembelian kategori produk tertentu.
  - Category Exception
  Komponen : Id,Category
  Fungsi : Menentukan pengecualian kategori produk yang tidak berhak mendapatkan poin loyalitas.
  - Product Point Claim Setup
  Komponen : Id,Product,Point
  Fungsi : Mengatur katalog item produk beserta jumlah poin yang dibutuhkan untuk melakukan klaim/penukaran.
  - Claim Product
  Komponen : Customer ID,Member ID,Name,Point Reguler,Point Promo,Point Type,Doc. ID,Date,Warehouse ID,User, Type Name/Id, Note, Total Point Claim
  Note : baris bisa dropdown berisi (Product ID,Name,Description,Qty,UOM Id,Point,Total Point Claim)
  Fungsi : Transaksi penukaran poin milik pelanggan dengan produk atau reward tertentu.
  - Claim Product Daily Report
  Komponen : Date, Claim Doc No, Customer ID, Customer Name, Product ID, Qty Claimed, Total Points Deducted, User
  Fungsi : Laporan harian transaksi klaim reward dan pengeluaran poin pelanggan.

- Sales Order `sales-order`
  - Sales Order List
    Komponen : No., Date, Warehouse, Customer Id, Name, Area, WA, Note, Disc. %, Disc. Amt., Total, Currency, Status, Term, Sales, Contract No, Doc. Type
    Fungsi : Mengelola dokumen pemesanan barang dari pelanggan sebelum diproses ke tahap pengiriman.
  - Sales Order Fulfilment 
    Komponen : Cust. ID, Name, Area, Sales Order, SO Date, Warehouse, Note, Status, Product ID, Name, Description, SO Qty, SO UOM ID, SI Date, SI Qty, SI UOM ID, Qty Diff, Tonase
    Fungsi : Memantau tingkat pemenuhan kuantitas barang dari Sales Order menjadi Sales Invoice/pengiriman.
- Daily Sales Order Report
    Komponen : Date, SO No, Customer Name, Salesman, Total Amount, Status, Warehouse
    Fungsi : Laporan rekapitulasi harian pembuatan dan status dokumen Sales Order.
- Daily Sales Order Invoice Report
    Komponen : Date, SO No, SI No, Customer Name, SO Amount, Invoiced Amount, Fulfilment Rate (%)
    Fungsi : Laporan perbandingan harian antara nilai pesanan (SO) dengan nilai yang telah berhasil ditagihkan (Invoice).
- Packing `packing`
  Komponen :Packing No, Date, SO No, Customer ID, Warehouse ID, Packing Staff, Total Box/Package, Weight, Status, Note
  Fungsi : Mengelola proses pengemasan barang di gudang berdasarkan pesanan penjualan sebelum diserahkan ke tim kurir/pengiriman.

- Sales Invoice `sales-invoice`
  - Sales Invoice List, 
    Komponen : No.,Date,Due Date,Doc. Type,Printed Status,Purchase Note,Warehouse,Sales Order,No. Faktur,Customer Id,Name,Area,WA,Note,Curr.,Total,Disc. %,Disc. Amt.,Status,Term,User,Outstanding,Delivery Status
    Fungsi : Mengelola tagihan penjualan resmi kepada pelanggan atas barang yang telah dikirimkan.
  - Shipment Priority 
    Komponen : Priority No, Invoice No, SO No, Customer ID, Area, Total Weight/Volume, Promised Date, Status
    Fungsi : Mengatur urutan prioritas pengiriman barang berdasarkan kriteria pelanggan atau tanggal janji serah.
  - Sales Promo Report 
    Komponen : Promo ID, Promo Name, Invoice No, Customer Name, Product ID, Discount Amount, Free Goods Qty
    Fungsi : Laporan efektivitas dan rekap penggunaan program promosi pada transaksi penjualan.
  - Sales Profit Report
    Komponen : Invoice No, Date, Customer Name, Product ID, Qty, Selling Price, HPP/Cost, Gross Profit, Profit Margin (%)
    Fungsi : Laporan analisis keuntungan kotor penjualan berdasarkan selisih harga jual dan harga pokok penjualan (HPP).
  - Sales Omset Report
    Komponen : Period, Salesman, Area, Customer Group, Total Gross Sales, Total Discount, Total Net Omset
    Fungsi : Laporan rekapitulasi total pencapaian omset penjualan bersih per periode.
  - Sales Void Report
    Komponen : Void Date, Doc No (SO/SI), Customer Name, Original Amount, Void Reason, Authorized User
    Fungsi : Laporan riwayat pembatalan (void) dokumen transaksi penjualan beserta alasannya.
  - Sales Commision Report 
    Komponen : Salesman ID, Salesman Name, Period, Total Omset, Target, Commission Rate (%), Total Commission
    Fungsi : Laporan perhitungan komisi penjualan untuk wiraniaga berdasarkan pencapaian target.
  - Invoice Payment Report 
    Komponen : Invoice No, Invoice Date, Customer Name, Total Invoice, Total Paid, Balance Due, Last Payment Date, Status
    Fungsi : Laporan riwayat dan status pelunasan faktur penjualan.
  - Profit Loss report 
    Komponen : Period, Total Sales Revenue, Sales Return, Cost of Goods Sold (HPP), Gross Margin, Operating Expenses, Net Sales Profit
    Fungsi : Laporan ringkasan laba rugi operasional yang dihasilkan dari aktivitas penjualan.
  - Sales Reports
    filter Rentang Tanggal
    filter series & brand
    filter VAT or non VAT 
    filter sales{
    Sales by Customer
    Sales by Product
    Sales by Supplier
    Sales by Salesman
    Sales by Category
    }
    Fungsi : Modul pelaporan penjualan multi-dimensi dengan berbagai kombinasi filter analisis.
- Tanda Terima Penagihan `tanda-terima-penagihan`
  Komponen :TTP No, TTP Date, Collector Name, Customer ID, Total Invoice Count, Total Amount, Due Date, Status, Note
  Fungsi : Mengelola dokumen penyerahan lembar faktur tagihan kepada penagih/kolektor untuk melakukan penagihan ke lokasi pelanggan.

- Customer Payment `customer-payment`
  - Customer Payment List 
    komponen : Payment No., Date, Date Complete, Warehouse, No. TTP, Customer Id, Name, Account, Total, Status, Currency, Rate, Note, Def. Sales, type payment (Reguler/Down)
    Fungsi : Mengelola daftar seluruh transaksi penerimaan kas/bank dari pelanggan baik untuk pelunasan maupun uang muka.
  - Cust. Outstanding List 
    Komponen : Invoice No, Customer Id, Customer Name, City, Date, Due Date, Age (Days), Curr, Total, Outstanding, Term, Invoiced, Warehouse, Sales, Note
    Fungsi : Memantau daftar faktur penjualan yang belum dilunasi beserta umur piutangnya.
  - Daily Customer Payment Report 
    Komponen : Date, Payment No, Customer Name, Payment Method, Total Paid, Account Name, User
    Fungsi : Laporan harian penerimaan pembayaran dari pelanggan.
  - Outstanding per Customer Report 
    Komponen : Customer ID, Customer Name, Total Invoices, Total Outstanding Amount, Credit Limit, Exceeded Amount
    Fungsi : Laporan total sisa piutang yang dikelompokkan per pelanggan.
  - Customer Payment Check 
    Komponen : Check/Giro No, Bank Name, Maturity Date, Customer ID, Amount, Status (Clearing/Bounced/Passed)
    Fungsi : Memantau dan memverifikasi status pembayaran menggunakan instrumen Cek atau Giro.
  - Customer Outstanding per Date Report 
    Komponen : As of Date, Customer ID, Customer Name, Current, 1-30 Days, 31-60 Days, 61-90 Days, >90 Days, Total Outstanding
    Fungsi : Laporan analisis umur piutang (Aging AR Report) pada tanggal posisi tertentu.

- Sales Return `sales-return`
  - Sales Return List 
    Komponen : No.,Date,Warehouse,Customer Id,Name,Area,WA,Disc. %,Disc. Amt.,Total,Currency,Status,Note,Term,Sales,SI Returned
    Fungsi : Mengelola penerimaan kembali barang yang dijual akibat kerusakan, retur komersial, atau kesalahan pengiriman.
  - Daily Sales Return Report 
    Komponen : Date, Return No, Customer Name, Product ID, Qty Returned, Total Amount, Reason, Warehouse ID
    Fungsi : Laporan harian pengembalian barang dan pemotongan tagihan piutang.

- Tanda Terima Invoice `tanda-terima-invoice`
  Komponen :TTI No, Date, Customer ID, Customer Name, Invoice List (No, Date, Amount), Received By (Customer PIC), Received Date, Return Status
  Fungsi : Mengelola dokumen bukti bahwa fisik faktur/tagihan telah diterima dengan baik oleh pelanggan untuk memicu perhitungan tanggal jatuh tempo.

- Delivery Order `delivery-order`
  Komponen :DO No, Date, SO No, SI No, Warehouse ID, Customer ID, Driver Name, Vehicle No, Delivery Address, Status, Expeditor
  Fungsi : Mengelola dokumen surat jalan pengeluaran barang dari gudang untuk dikirimkan ke alamat pelanggan.

- Shipment Preparation `shipment-preparation`
  Komponen :Prep No, Date, Warehouse ID, DO List, Total Weight, Total Volume, Fleet/Vehicle Type, Route Area, Status
  Fungsi : Mengonsolidasi beberapa dokumen surat jalan/DO ke dalam rencana pemuatan armada dan rute pengiriman.

- Purchase Note `purchase-note`
  Komponen :Note No, Date, Customer ID, PO Customer No, Attachment, Description, Validation Status
  Fungsi : Mencatat dan memverifikasi nomor serta lampiran Surat Pesanan (PO) fisik resmi yang diterbitkan oleh pihak pelanggan.

- Sales Commission `sales-commission`
  Komponen :Comm No, Date, Period, Salesman ID, Calculation Base (Omset/Pelunasan), Target Amount, Achieved Amount, Commission Rate, Total Commission Paid, Status
  Fungsi : Memproses perhitungan dan persetujuan pencairan komisi penjualan untuk staf penjualan.

- Tax `tax`
  Komponen :Tax Doc No, Invoice No, Tax Code (PPN/PPh), Customer NPWP, DPP Amount, Tax Amount, Tax Invoice No (Faktur Pajak), Status Export/EFaktur
  Fungsi : Mengelola pencatatan kewajiban pajak penjualan (seperti PPN) dan integrasi pembuatan Seri Faktur Pajak.

## Transit Area `#`

- Daily Sales Invoice Report `/daily-sales-invoice-report`
  Komponen : Date, Warehouse, Cust. ID, Name, Area, Sales Invoice, Delivery Order, Prod. ID, Name, UOM, Qty, Price, Disc. %, Disc. Amount, Total Potongan, Total, DPP, PPN, Due Date, Tonase, Sales, Brand, Note
  Fungsi : Menyajikan laporan harian rincian Faktur Penjualan per item barang lengkap dengan nilai DPP, PPN, tonase, dan salesman terkait pada Transit Area/Depo.

- Daily Sales PO Closing Report `/daily-sales-po-closing-report`
  Komponen : Date, Warehouse, Cust. ID, Name, Area, Sales Invoice, Delivery Order, Prod. ID, Name, UOM, Qty, Price, Disc. %, Disc. Amount, Total Potongan, Total, DPP, PPN, Grand Total, Due Date, Tonase, Note
  Fungsi : Memantau harian transaksi penutupan pesanan (PO Closing) yang telah dipenuhi hingga penerbitan faktur penjualan dan pengiriman barang.

- Daily Sales Return Report `/daily-sales-return-report`
  Komponen : Date, Kode Area, Area, Cust. ID, Name, Sales Invoice, Prod. ID, Prod. Name, UOM, Qty, Price, Disc. %, Total Potongan, Grand Total, Total Invoice
  Fungsi : Menyajikan laporan harian pengembalian barang (retur) dari pelanggan beserta pemotongan nilai faktur tagihannya.

- Daily Sales by Brand Report `/daily-sales-by-brand-report`
  Komponen : Date, Warehouse, Area, Brand ID, Brand Name, Total Qty Sold, Gross Amount, Discount Amount, Net Sales Amount, Percentage Contribution (%)
  Fungsi : Menampilkan rekapitulasi pencapaian omset dan volume penjualan harian yang dikelompokkan berdasarkan merek/brand produk.

- Daily Payment Recap Report `/daily-payment-recap-report`
  Komponen : No TTP, Date, Kode Area, Area, Cust. ID, Name, Sales Invoice, Bank, Cash, Discount, Lain-Lain, Retur, Total Bank In, Outstanding, Note, Tgl. TTP, Payment ID, Due Date, Invoice Total, Term, Diskon Promo (%)
  Fungsi : Menyajikan rekapitulasi harian pelunasan piutang pelanggan dari berbagai instrumen pembayaran (Bank, Kas, Diskon, Retur) terhadap faktur tagihan.

- Cheque Management `/cheque-management`
  Komponen : Date, Cust. ID, Name, No. BG, Bank, Valid Date, Amount, Valid, Note, Payment
  Fungsi : Mengelola dan memverifikasi status kelayakan instrumen Bilyet Giro (BG) dan Cek yang diterima dari pelanggan sebelum dikliringkan.

- RLHP (Rincian Laporan Hasil Penagihan) `/rlhp`
  Komponen : Doc. ID, Doc. Date, Payment From Date, Payment To Date, Depo, Tipe, Total Cash, Total Giro, Notes, User ID
  Fungsi : Mengonsolidasi pencatatan hasil penagihan harian oleh kolektor/kasir depo berdasarkan rincian penerimaan tunai dan giro.

- AR per Customer Report `/ar-per-customer-report`
  Komponen : Warehouse, Area, Cust. ID, Name, Saldo Awal, Penjualan, PO Closing, Bank, Cash, Discount, Lain-Lain, Retur, Saldo Akhir, Sisa Piutang, Selisih, Salesman, < 45, > 45, > 90, > 120
  Fungsi : Laporan mutasi piutang lengkap per pelanggan beserta analisis umur piutang (Aging AR) dalam segmen rentang hari tertentu.

- Customer AR Position Report `/customer-ar-position-report`
  Komponen : Warehouse, Area, Cust. ID, Name, Sales, Saldo Piutang, Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember, Saldo Piutang, Total Piutang
  Fungsi : Memantau posisi dan tren perkembangan saldo piutang pelanggan secara bulanan selama satu tahun berjalan.

- Invoice Customer AR List Report `/invoice-customer-ar-list-report`
  Komponen : Warehouse, Area, Cust. ID, Name, Sales, Saldo Piutang, Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember, Saldo Piutang, Total Piutang
  Fungsi : Menyajikan rincian daftar faktur outstanding milik pelanggan yang terdistribusi berdasarkan bulan penerbitan.

- Salesman AR List PMB `/salesman-ar-list-pmb`
  Komponen : Salesman, Collection 53-90, Collection > 90, Total Collection, Ach. Coll. 0-52, Ach. Coll. 53-90, Ach. Coll. >90, Total Ach., Percentage
  Fungsi : Laporan evaluasi kinerjasa pencapaian penagihan piutang (Collection) oleh masing-masing salesman berdasarkan kategori umur piutang.

- Invoice Expedition `/invoice-expedition`
  Komponen : Doc. ID, Date, Warehouse, Salesman, Notes, User ID
  Fungsi : Mengelola pengiriman dan serah terima dokumen fisik faktur penjualan dari depo pusat ke salesman/kolektor lapangan.

- Shipping Invoice Expedition `/shipping-invoice-expedition`
  Komponen : Doc. ID, Date, Warehouse, Salesman, Notes, User ID
  Fungsi : Mengelola pengiriman berkas faktur dan surat jalan yang dilampirkan bersama armada pengiriman barang ke lokasi pelanggan.

- Transit Area Target `/transit-area-target`
  Komponen : Warehouse, Target
  Fungsi : Menetapkan kuota target penjualan dan penagihan bulanan untuk masing-masing lokasi Transit Area/Depo.

- UBM Daily Control Progress Sales Report `/ubm-daily-control-progress-sales-report`
  Komponen : Transit Area, Target Bulanan, Toleransi, Belum Tercapai, Tahun Lalu, Bulan Lalu, Pencapaian TA, Target Hari Ini, Akumulasi, % Target, % Target TLR
  Fungsi : Laporan kontrol harian untuk memantau laju pencapaian target penjualan depo dibandingkan dengan periode lalu dan batas toleransi.

- Transit Area New Brand `/transit-area-new-brand`
  Komponen : id, Brand
  Fungsi : Mengatur pendaftaran dan penetapan penanganan produk merek baru (New Brand) di Transit Area/Depo.

- UBM New Product Sales Report `/ubm-new-product-sales-report`
  Komponen : Transit Area, Noo
  Fungsi : Memantau kinerja penetrasi produk baru dan penambahan Outlet Baru (NOO - New Open Outlet) pada Transit Area.

- UBM Collection Progress Report `/ubm-collection-progress-report`
  Komponen : Transit Area, Collection 53-90, Collection > 90, Total Collection, Uncollected, Days Before, Target, Accumulation, Collection Tertagih (%), Rangking
  Fungsi : Memantau dan meranking progres pencapaian penagihan piutang overdue per Transit Area secara komparatif.

- Daily Sales Achievement Report `/daily-sales-achievement-report`
  Komponen : Transit Area, Salesman, Target
  Fungsi : Menyajikan laporan harian persentase pencapaian target penjualan oleh tim sales di Transit Area.

- PMB (Penetapan & Monitoring Bonus) `/pmb`
  Komponen : Period, Transit Area, Salesman ID, Target Collection, Achieved Collection, Incentive Rate, Penalty Amount, Total PMB Bonus, Status
  Fungsi : Mengelola skema insentif, pemantauan target pencapaian penagihan, dan kalkulasi bonus bulanan salesman/depo (PMB).

## System Menu

- Dashboard `/`
- Setting `setting`
- Master `master`
  - User `user`
  - Role `role-menu`
  - Config App `configuration`
  - Area `area`