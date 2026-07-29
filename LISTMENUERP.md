# ERP Menu List

## Master Data `#`

# ERP Menu List - Master Data `#`
- Product 
  - Product `product`
    Komponen : Product_Id, Name, Stock, UOM, Tonase, Kg, Def. Sales Price, Supplier, Barcode, Location, Type, Brand, Group, Category, Series, Quality, Active
    Fungsi : Mengelola data induk (master data) barang/produk utama secara komprehensif mulai dari identitas, spesifikasi, hingga parameter persediaan.
    Penjelasan UI : Tampilan awal berupa table menampilkan Product_Id, Name, Stock, UOM, Tonase, Kg, Def. Sales Price, Supplier, Barcode, Location, Type, Brand, Group, Category, Series, Quality, Active dan filter nama product, brand, dan group. Tombol tambah akan muncul pop up dengan form Name, Stock, UOM, Tonase, Kg, Def. Sales Price, Supplier, Barcode, Location, Type, Brand, Group, Category, Series, Quality, Active. Untuk edit muncul pop up form Name, Stock, UOM, Tonase, Kg, Def. Sales Price, Supplier, Barcode, Location, Type, Brand, Group, Category, Series, Quality, Active. Hapus muncul alert konfirmasi.

  - Brand `brand`
    Komponen : Brand_Id, Code, Name, Description, Active
    Fungsi : Mengelola data merek/brand dari produk untuk kebutuhan pengelompokan dan analisis laporan penjualan per merek.
    Penjelasan UI : Tampilan awal berupa table menampilkan Brand_Id, Code, Name, Description, Active dan filter nama brand dan code. Tombol tambah akan muncul pop up dengan form Code, Name, Description, Active. Untuk edit muncul pop up form Code, Name, Description, Active. Hapus muncul alert konfirmasi.

  - Group `group`
    Komponen : Group_Id, Code, Name, Description, Active
    Fungsi : Mengelompokkan produk berdasarkan divisi atau kelompok besar lini bisnis perusahaan.
    Penjelasan UI : Tampilan awal berupa table menampilkan Group_Id, Code, Name, Description, Active dan filter nama group dan code. Tombol tambah akan muncul pop up dengan form Code, Name, Description, Active. Untuk edit muncul pop up form Code, Name, Description, Active. Hapus muncul alert konfirmasi.

  - Category `category`
    Komponen : Category_Id, Code, Name, Group, Description, Active
    Fungsi : Mengelola kategorisasi produk yang lebih rinci di bawah struktur Group untuk pemetaan laporan dan aturan sistem.
    Penjelasan UI : Tampilan awal berupa table menampilkan Category_Id, Code, Name, Group, Description, Active dan filter nama category dan group. Tombol tambah akan muncul pop up dengan form Code, Name, Group, Description, Active. Untuk edit muncul pop up form Code, Name, Group, Description, Active. Hapus muncul alert konfirmasi.

  - Series `series`
    Komponen : Series_Id, Code, Name, Brand, Description, Active
    Fungsi : Mengelompokkan produk berdasarkan seri atau varian rilis dari suatu merek.
    Penjelasan UI : Tampilan awal berupa table menampilkan Series_Id, Code, Name, Brand, Description, Active dan filter nama series dan brand. Tombol tambah akan muncul pop up dengan form Code, Name, Brand, Description, Active. Untuk edit muncul pop up form Code, Name, Brand, Description, Active. Hapus muncul alert konfirmasi.

  - Hierarchie `hierarchie`
    Komponen : Hierarchy_Id, Level, Parent_Hierarchy, Code, Name, Active
    Fungsi : Membentuk struktur hirarki atau pohon taksonomi produk multi-tingkat (Parent-Child) untuk kebutuhan navigasi dan konsolidasi laporan.
    Penjelasan UI : Tampilan awal berupa table menampilkan Hierarchy_Id, Level, Parent_Hierarchy, Code, Name, Active dan filter nama hierarchy dan level. Tombol tambah akan muncul pop up dengan form Level, Parent_Hierarchy, Code, Name, Active. Untuk edit muncul pop up form Level, Parent_Hierarchy, Code, Name, Active. Hapus muncul alert konfirmasi.

  - Quality `quality`
    Komponen : Quality_Id, Code, Name, Grade, Description, Active
    Fungsi : Menentukan klasifikasi tingkatan kualitas/grade dari barang persediaan.
    Penjelasan UI : Tampilan awal berupa table menampilkan Quality_Id, Code, Name, Grade, Description, Active dan filter nama quality dan grade. Tombol tambah akan muncul pop up dengan form Code, Name, Grade, Description, Active. Untuk edit muncul pop up form Code, Name, Grade, Description, Active. Hapus muncul alert konfirmasi.

  - Unit of Measures `unit-of-measures`
    Komponen : UOM_Id, Code, Name, Description, Active
    Fungsi : Mengelola data induk satuan pengukuran standar barang (misal: PCS, KG, BOX, DUS, LTR).
    Penjelasan UI : Tampilan awal berupa table menampilkan UOM_Id, Code, Name, Description, Active dan filter nama uom dan code. Tombol tambah akan muncul pop up dengan form Code, Name, Description, Active. Untuk edit muncul pop up form Code, Name, Description, Active. Hapus muncul alert konfirmasi.

  - Discount `discount`
    Komponen : Discount_Id, Code, Name, Type, Value, Active
    Fungsi : Mengelola master skema potongan harga standar yang berlaku umum di sistem.
    Penjelasan UI : Tampilan awal berupa table menampilkan Discount_Id, Code, Name, Type, Value, Active dan filter nama discount dan type. Tombol tambah akan muncul pop up dengan form Code, Name, Type, Value, Active. Untuk edit muncul pop up form Code, Name, Type, Value, Active. Hapus muncul alert konfirmasi.

  - Price List `price-list`
    Komponen Header : Price_List_Id, Code, Name, Currency, Effective_Date, Expiry_Date, Active
    Komponen Detail : Product_Id, Product_Name, UOM, Price, Min_Qty, Discount_Percent
    Fungsi : Mengatur catalog/daftar harga jual berjenjang yang menampung banyak produk sekaligus berdasarkan segmen pelanggan, wilayah, atau skema komersial tertentu, serta mendukung pembuatan versi pembaruan catalog harga baru.
    Penjelasan UI : Tampilan awal berupa table header menampilkan Price_List_Id, Code, Name, Currency, Effective_Date, Expiry_Date, Active dan filter nama price list, code, dan currency. Tombol Detail akan memicu pop-up modal yang menampilkan daftar produk beserta harga di dalam price list tersebut. Tombol Tambah akan memicu pop-up modal form Header (Code, Name, Currency, Effective_Date, Expiry_Date, Active) beserta tabel input multi-product detail (Product, UOM, Price). Tombol Edit memicu pop-up modal form untuk mengubah data price list yang ada. Tombol Pembaruan memicu pop-up modal form yang memuat seluruh data dari price list yang dipilih, di mana ketika disimpan akan membuat record Price List baru (versioning/duplikasi). Hapus muncul alert konfirmasi.

  - Sales Discount `sales-discount`
    Komponen : Sales_Discount_Id, Name, Product_Id, Product_Name, Min_Qty, Disc_Percent, Disc_Amount, Valid_From, Valid_To, Active
    Fungsi : Mengatur matriks/aturan diskon khusus transaksi penjualan dengan cara memilih produk dari Master Product serta menentukan besaran diskon (persen/nominal atau acuan dari Master Discount) berdasarkan kelompok pelanggan, minimal kuantitas pembelian, dan periode berlaku.
    Penjelasan UI : Tampilan awal berupa table menampilkan Sales_Discount_Id, Name, Product_Name, Min_Qty, Disc_Percent, Disc_Amount, Valid_From, Valid_To, Active dan filter serries product serta product. Tombol Tambah akan memunculkan pop-up modal form berisi Name (pilih dari Master Customer Group), Product (pilih/search dari Master Product), Min_Qty, Disc_Percent, Disc_Amount (dapat diisi manual atau diambil dari Master Discount), Valid_From, Valid_To, dan Active. Untuk Edit akan memunculkan pop-up modal form dengan field yang sama untuk memperbarui aturan diskon. Hapus memunculkan alert konfirmasi.
    
  - Purchase Discount `purchase-discount`
    Komponen : Purchase_Discount_Id, Name, Supplier_Id, Supplier_Name, Product_Id, Product_Name, Min_Qty, Disc_Percent, Disc_Amount, Valid_From, Valid_To, Active
    Fungsi : Mengatur matriks/aturan potongan harga (diskon) pembelian yang didapatkan dari supplier dengan cara memilih supplier dari Master Supplier dan produk dari Master Product, serta menentukan besaran diskon berdasarkan minimal kuantitas pembelian dan periode berlaku.
    Penjelasan UI : Tampilan awal berupa table menampilkan Purchase_Discount_Id, Name, Supplier_Name, Product_Name, Min_Qty, Disc_Percent, Disc_Amount, Valid_From, Valid_To, Active dan filter supplier serta product. Tombol Tambah akan memunculkan pop-up modal form berisi Name, Supplier (pilih dari Master Supplier), Product (pilih/search dari Master Product), Min_Qty, Disc_Percent, Disc_Amount (dapat diisi manual atau diambil dari Master Discount), Valid_From, Valid_To, dan Active. Untuk Edit akan memunculkan pop-up modal form dengan field yang sama untuk memperbarui aturan diskon pembelian. Hapus memunculkan alert konfirmasi.

  - Product Cash Back `product-cash-back`
    Komponen : Cashback_Id, Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active
    Fungsi : Mengelola aturan program pengembalian dana (cashback) atas pembelian produk tertentu oleh pelanggan.
    Penjelasan UI : Tampilan awal berupa table menampilkan Cashback_Id, Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active dan filter nama program dan product. Tombol tambah akan muncul pop up dengan form Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active. Untuk edit muncul pop up form Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active. Hapus muncul alert konfirmasi.

  - Supplier Product `supplier-product`
    Komponen : Supplier_Product_Id, Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active
    Fungsi : Memetakan hubungan silang (cross-reference) antara produk perusahaan dengan kode/nama produk milik supplier.
    Penjelasan UI : Tampilan awal berupa table menampilkan Supplier_Product_Id, Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active dan filter supplier dan product. Tombol tambah akan muncul pop up dengan form Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active. Untuk edit muncul pop up form Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active. Hapus muncul alert konfirmasi.

  - Product Price Log `product-price-log`
    Komponen : Log_Id, Product, Price_List, Old_Price, New_Price, Changed_By, Change_Date, Reason
    Fungsi : Mencatat riwayat (audit trail) perubahan harga jual produk untuk pengawasan integritas harga.
    Penjelasan UI : Tampilan awal berupa table menampilkan Log_Id, Product, Price_List, Old_Price, New_Price, Changed_By, Change_Date, Reason dan filter product dan tanggal perubahan. Halaman ini bersifat Read-Only (tidak ada tombol tambah, edit, dan hapus).

  - UOM General Convertion `uom-general-convertion`
    Komponen : UOM_Convertion_Id, Product, From_UOM, To_UOM, Multiplier, Operator
    Fungsi : Mengatur rasio konversi antar satuan unit barang (misal: 1 BOX = 24 PCS) untuk perhitungan stok dan transaksi otomatis.
    Penjelasan UI : Tampilan awal berupa table menampilkan UOM_Convertion_Id, Product, From_UOM, To_UOM, Multiplier, Operator dan filter product dan UOM. Tombol tambah akan muncul pop up dengan form Product, From_UOM, To_UOM, Multiplier, Operator. Untuk edit muncul pop up form Product, From_UOM, To_UOM, Multiplier, Operator. Hapus muncul alert konfirmasi.

- Warehouse `warehouse`
  Komponen : Warehouse_Id, Code, Name, Address, PIC_Name, Phone, Active
  Fungsi : Mengelola data induk lokasi fisik/logis gudang penyimpanan barang persediaan.
  Penjelasan UI : Tampilan awal berupa table menampilkan Warehouse_Id, Code, Name, Address, PIC_Name, Phone, Active dan filter nama warehouse dan code. Tombol tambah akan muncul pop up dengan form Code, Name, Address, PIC_Name, Phone, Active. Untuk edit muncul pop up form Code, Name, Address, PIC_Name, Phone, Active. Hapus muncul alert konfirmasi.

- Currency `currency`
  Komponen : Currency_Id, Code, Name, Symbol, Is_Default, Active
  Fungsi : Mengelola daftar mata uang resmi yang digunakan dalam seluruh transaksi sistem.
  Penjelasan UI : Tampilan awal berupa table menampilkan Currency_Id, Code, Name, Symbol, Is_Default, Active dan filter code dan name. Tombol tambah akan muncul pop up dengan form Code, Name, Symbol, Is_Default, Active. Untuk edit muncul pop up form Code, Name, Symbol, Is_Default, Active. Hapus muncul alert konfirmasi.

- Rate `rate`
  Komponen : Rate_Id, Currency, Rate_Date, Rate_Value, Updated_By
  Fungsi : Mencatat dan mengelola riwayat nilai tukar kurs mata uang harian terhadap mata uang acuan (base currency).
  Penjelasan UI : Tampilan awal berupa table menampilkan Rate_Id, Currency, Rate_Date, Rate_Value, Updated_By dan filter currency dan tanggal rate. Tombol tambah akan muncul pop up dengan form Currency, Rate_Date, Rate_Value. Untuk edit muncul pop up form Currency, Rate_Date, Rate_Value. Hapus muncul alert konfirmasi.

- Payment Term `payment-term`
  Komponen : Term_Id, Code, Name, Days_Count, Description, Active
  Fungsi : Mengatur master ketentuan termin/jangka waktu jatuh tempo pembayaran transaksi.
  Penjelasan UI : Tampilan awal berupa table menampilkan Term_Id, Code, Name, Days_Count, Description, Active dan filter name dan code. Tombol tambah akan muncul pop up dengan form Code, Name, Days_Count, Description, Active. Untuk edit muncul pop up form Code, Name, Days_Count, Description, Active. Hapus muncul alert konfirmasi.

- Notes `notes`
  Komponen : Note_Id, Title, Module, Default_Text, Active
  Fungsi : Mengelola templat teks catatan/ketentuan standar yang otomatis muncul pada cetakan dokumen transaksi.
  Penjelasan UI : Tampilan awal berupa table menampilkan Note_Id, Title, Module, Default_Text, Active dan filter title dan module. Tombol tambah akan muncul pop up dengan form Title, Module, Default_Text, Active. Untuk edit muncul pop up form Title, Module, Default_Text, Active. Hapus muncul alert konfirmasi.

- Promo Buy N Get M `promo-buy-n-get-m`
  Komponen : Promo_Id, Name, Buy_Product, Buy_Qty, Get_Product, Get_Qty, Valid_From, Valid_To, Active
  Fungsi : Mengatur aturan promosi penjualan kustom beli N barang bonus M barang gratis.
  Penjelasan UI : Tampilan awal berupa table menampilkan Promo_Id, Name, Buy_Product, Buy_Qty, Get_Product, Get_Qty, Valid_From, Valid_To, Active dan filter promo name dan product. Tombol tambah akan muncul pop up dengan form Name, Buy_Product, Buy_Qty, Get_Product, Get_Qty, Valid_From, Valid_To, Active. Untuk edit muncul pop up form Name, Buy_Product, Buy_Qty, Get_Product, Get_Qty, Valid_From, Valid_To, Active. Hapus muncul alert konfirmasi.

- Employee `employee`
  Komponen : Employee_Id, NIK, Name, Department, Position, Email, Phone, Address, Active
  Fungsi : Mengelola data induk karyawan perusahaan yang terhubung dengan akses sistem, kearsipan, maupun hak komisi.
  Penjelasan UI : Tampilan awal berupa table menampilkan Employee_Id, NIK, Name, Department, Position, Email, Phone, Address, Active dan filter NIK, name, dan department. Tombol tambah akan muncul pop up dengan form NIK, Name, Department, Position, Email, Phone, Address, Active. Untuk edit muncul pop up form NIK, Name, Department, Position, Email, Phone, Address, Active. Hapus muncul alert konfirmasi.

- Commission `commission`
  Komponen : Commission_Id, Name, Target_Type, Min_Achieve, Max_Achieve, Rate_Percent, Active
  Fungsi : Mengatur tingkatan (tiering) dan parameter persentase komisi bagi staf penjualan.
  Penjelasan UI : Tampilan awal berupa table menampilkan Commission_Id, Name, Target_Type, Min_Achieve, Max_Achieve, Rate_Percent, Active dan filter name dan target_type. Tombol tambah akan muncul pop up dengan form Name, Target_Type, Min_Achieve, Max_Achieve, Rate_Percent, Active. Untuk edit muncul pop up form Name, Target_Type, Min_Achieve, Max_Achieve, Rate_Percent, Active. Hapus muncul alert konfirmasi.

- Department `department`
  Komponen : Department_Id, Code, Name, Manager, Description, Active
  Fungsi : Mengelola struktur bagian/departemen internal perusahaan untuk kebutuhan alokasi biaya dan pemohon transaksi.
  Penjelasan UI : Tampilan awal berupa table menampilkan Department_Id, Code, Name, Manager, Description, Active dan filter code dan name. Tombol tambah akan muncul pop up dengan form Code, Name, Manager, Description, Active. Untuk edit muncul pop up form Code, Name, Manager, Description, Active. Hapus muncul alert konfirmasi.

- Forwarder `forwarder`
  Komponen : Forwarder_Id, Code, Name, Contact_Person, Phone, Email, Address, Active
  Fungsi : Mengelola data induk mitra jasa pengiriman / ekspedisi pihak ketiga (3PL).
  Penjelasan UI : Tampilan awal berupa table menampilkan Forwarder_Id, Code, Name, Contact_Person, Phone, Email, Address, Active dan filter code dan name. Tombol tambah akan muncul pop up dengan form Code, Name, Contact_Person, Phone, Email, Address, Active. Untuk edit muncul pop up form Code, Name, Contact_Person, Phone, Email, Address, Active. Hapus muncul alert konfirmasi.

- Edition `edition`
  Komponen : Edition_Id, Code, Name, Release_Date, Description, Active
  Fungsi : Mengelola pengelompokan edisi atau versi rilis dari suatu produk (misal: Edisi Khusus, Edisi Impor, Edisi Musim).
  Penjelasan UI : Tampilan awal berupa table menampilkan Edition_Id, Code, Name, Release_Date, Description, Active dan filter code dan name. Tombol tambah akan muncul pop up dengan form Code, Name, Release_Date, Description, Active. Untuk edit muncul pop up form Code, Name, Release_Date, Description, Active. Hapus muncul alert konfirmasi.

- Bank `bank`
  Komponen : Bank_Id, Bank_Code, Bank_Name, Branch, Account_Number, Account_Name, Active
  Fungsi : Mengelola data akun rekening bank resmi perusahaan untuk transaksi penerimaan dan pengeluaran kas/bank.
  Penjelasan UI : Tampilan awal berupa table menampilkan Bank_Id, Bank_Code, Bank_Name, Branch, Account_Number, Account_Name, Active dan filter bank_name, account_number, dan account_name. Tombol tambah akan muncul pop up dengan form Bank_Code, Bank_Name, Branch, Account_Number, Account_Name, Active. Untuk edit muncul pop up form Bank_Code, Bank_Name, Branch, Account_Number, Account_Name, Active. Hapus muncul alert konfirmasi.

- Document `document`
  Komponen : Document_Id, Doc_Code, Doc_Name, Module, Numbering_Format, Last_Counter, Active
  Fungsi : Mengatur penamaan, format penomoran otomatis, dan kategori dokumen resmi di seluruh modul ERP.
  Penjelasan UI : Tampilan awal berupa table menampilkan Document_Id, Doc_Code, Doc_Name, Module, Numbering_Format, Last_Counter, Active dan filter doc_code, doc_name, dan module. Tombol tambah akan muncul pop up dengan form Doc_Code, Doc_Name, Module, Numbering_Format, Last_Counter, Active. Untuk edit muncul pop up form Doc_Code, Doc_Name, Module, Numbering_Format, Last_Counter, Active. Hapus muncul alert konfirmasi.

  - Supplier
    - Supplier Master `supplier-master`
      Komponen : Supplier_Id, Supplier_Code, Name, Supplier_Group, Supplier_Center, Phone, Email, Address, Term_of_Payment, Status, Contact_Person, NPWP
      Fungsi : Mengelola data induk (master data) supplier/vendor mulai dari profil, kontak, alamat, hingga ketentuan termin pembayaran.
      Penjelasan UI : Tampilan awal berupa table menampilkan Supplier_Id, Supplier_Code, Name, Supplier_Group, Supplier_Center, Phone, Email, Address, Term_of_Payment, Status, Contact_Person, NPWP dan filter nama supplier, supplier group, dan supplier center. Tombol tambah akan muncul pop up dengan form Supplier_Code, Name, Supplier_Group, Supplier_Center, Phone, Email, Address, Term_of_Payment, Status, Contact_Person, NPWP. Untuk edit muncul pop up form Supplier_Code, Name, Supplier_Group, Supplier_Center, Phone, Email, Address, Term_of_Payment, Status, Contact_Person, NPWP. Hapus muncul alert konfirmasi.

    - Supplier Group `supplier-group`
      Komponen : Group_Id, Code, Name, Description, AP_Account
      Fungsi : Mengelompokkan supplier berdasarkan jenis/kategori serta pemetaan akun Hutang (AP Account) pada buku besar.
      Penjelasan UI : Tampilan awal berupa table menampilkan Group_Id, Code, Name, Description, AP_Account dan filter nama group dan code. Tombol tambah akan muncul pop up dengan form Code, Name, Description, AP_Account. Untuk edit muncul pop up form Code, Name, Description, AP_Account. Hapus muncul alert konfirmasi.

    - Supplier Center `supplier-center`
      Komponen : Center_Id, Code, Name
      Fungsi : Mengelola pembagian wilayah/pusat area pemasok untuk kebutuhan pengelompokan geografis.
      Penjelasan UI : Tampilan awal berupa table menampilkan Center_Id, Code, Name dan filter nama center dan code. Tombol tambah akan muncul pop up dengan form Code, Name. Untuk edit muncul pop up form Code, Name. Hapus muncul alert konfirmasi.

    - Supplier Balance Summary `supplier-balance-summary`
      Komponen : Supplier_Id, Name, Currency, Beginning_Balance, Total_Invoice, Total_Payment, Total_Return, Ending_Balance, Total_AP
      Fungsi : Menyajikan ringkasan posisi saldo hutang usaha per supplier beserta mutasi penambahan tagihan dan pelunasan secara real-time.
      Penjelasan UI : Tampilan awal berupa table menampilkan Supplier_Id, Name, Currency, Beginning_Balance, Total_Invoice, Total_Payment, Total_Return, Ending_Balance, Total_AP dan filter nama supplier, currency, dan rentang tanggal. Halaman ini bersifat Read-Only (Laporan/Ringkasan), sehingga tidak terdapat tombol tambah, edit, maupun hapus.

##

Create Doc. From CSV

## Material Management `#`

- Supplier Master `supplier-master`
  Komponen : Supplier ID, Supplier Code, Name, Supplier Group ID, Supplier Center ID, Phone, Email, Address, Term of Payment, Status, Contact Person, NPWP
  Fungsi : Mengelola data induk (master data) supplier/vendor mulai dari profil, kontak, alamat, hingga ketentuan termin pembayaran.

- Supplier Group `supplier-group`
  Komponen : Group ID, Code, Name, Description, AP Account
  Fungsi : Mengelompokkan supplier berdasarkan jenis/kategori serta pemetaan akun Hutang (AP Account) pada buku besar.

- Supplier Center `supplier-center`
  Komponen : Center ID, Code, Name
  Fungsi : Mengelola pembagian wilayah/pusat area pemasok untuk kebutuhan pengelompokan geografis.

- Supplier Balance Summary `supplier-balance-summary`
  Komponen : Supplier ID, Name, Currency, Beginning Balance, Total Invoice, Total Payment, Total Return, Ending Balance, Total AP
  Fungsi : Menyajikan ringkasan posisi saldo hutang usaha per supplier beserta mutasi penambahan tagihan dan pelunasan secara real-time.

- Purchase Request `#`
  - New Purchase Request `new-purchase-request`
    Komponen : PR No, Date, Requester User, Department, Warehouse ID, Material ID, Qty Requested, UOM ID, Note
    Fungsi : Form pengajuan permintaan pemesanan barang/material baru oleh departemen internal sebelum dibuatkan PO.
  - Purchase Request List `purchase-request-list`
    Komponen : PR No, Date, Requester, Department, Total Item, Status (Draft/Pending/Approved/Rejected/Fulfilled), Note
    Fungsi : Menampilkan dan mengelola daftar seluruh dokumen pengajuan Permintaan Pembelian (PR) beserta status persetujuannya.
  - Purchase Request Fulfilment Report `purchase-request-fulfilment-report`
    Komponen : PR No, Date, Department, Material ID, Name, Qty Requested, Qty Fulfilled, Qty Outstanding, Status
    Fungsi : Memantau tingkat pemenuhan barang yang diminta pada PR menjadi dokumen Purchase Order (PO).

- Purchase Order `#`
  - New Purchase Order `new-purchase-order`
    Komponen : PO No, Date, Supplier ID, PR No Ref, Payment Term, Material ID, Qty Ordered, Unit Price, Discount, Tax, Subtotal, Total Amount, Note
    Fungsi : Form pembuatan surat pesanan pembelian resmi kepada supplier berdasarkan kesepakatan harga dan termin.
  - Purchase Order List `purchase-order-list`
    Komponen : PO No, Date, Supplier Name, PR Ref, Subtotal, Tax, Discount, Total Amount, Status (Draft/Approved/Partial/Closed/Cancelled)
    Fungsi : Menampilkan dan mengelola daftar seluruh dokumen Purchase Order (PO) yang telah diterbitkan.
  - Purchase Fulfillment Report `purchase-fulfillment-report`
    Komponen : PO No, PO Date, Supplier Name, Material ID, Qty Ordered, Qty Received (STBJ), Qty Outstanding, Fulfilment Rate (%)
    Fungsi : Memantau rasio pemenuhan penerimaan barang fisik oleh supplier terhadap pesanan PO.
  - Daily Purchase Order Report `daily-purchase-order-report`
    Komponen : Date, PO No, Supplier Name, Total Amount, Status, User ID
    Fungsi : Laporan harian rekapitulasi pembuatan dan status dokumen Purchase Order.

- Purchase Invoice `#`
  - New Purchase Invoice `new-purchase-invoice`
    Komponen : Invoice No, Date, Due Date, Supplier ID, PO No Ref, STBJ No Ref, Material ID, Qty, Unit Price, Subtotal, Total Amount, Note
    Fungsi : Form pencatatan tagihan/faktur pembelian resmi dari supplier atas barang yang telah diterima.
  - Purchase Invoice List `purchase-invoice-list`
    Komponen : Invoice No, Date, Due Date, Supplier Name, PO Ref, STBJ Ref, Total Amount, Paid Amount, Outstanding, Status (Unpaid/Partial/Paid)
    Fungsi : Menampilkan daftar seluruh faktur tagihan pembelian beserta status pelunasannya.
  - Daily Purchase Invoice Report `daily-purchase-invoice-report`
    Komponen : Date, Invoice No, Supplier Name, Total Amount, Status, Due Date
    Fungsi : Laporan harian rincian penerbitan faktur pembelian dari supplier.
  - Monthly Purchase by Supplier Report `monthly-purchase-by-supplier-report`
    Komponen : Period, Supplier ID, Name, Total PO Count, Total Invoice Amount, Total Paid Amount, Total Outstanding
    Fungsi : Laporan akumulasi nilai transaksi pembelian per supplier dalam periode bulanan.

- STBJ `stbj`
  Komponen : STBJ No, Date, Supplier ID, PO No Ref, Warehouse ID, SJ Supplier No, Material ID, Qty Received, Qty Accepted, Qty Rejected, UOM ID, Note
  Fungsi : Mengelola pencatatan Surat Tanda Bukti Jalan (STBJ/Goods Receipt) atas penerimaan fisik barang dari supplier di gudang.

- Supplier Payment `#`
  - New Supplier Payment `new-supplier-payment`
    Komponen : Payment No, Date, Supplier ID, Payment Method (Transfer/Cash/Giro), Account ID, Invoice No Ref, Amount Paid, Total Paid, Ref No, Note
    Fungsi : Form pencatatan transaksi pembayaran/pelunasan hutang atas faktur pembelian ke supplier.
  - New Supplier Down Payment `new-supplier-down-payment`
    Komponen : Payment No, Date, Supplier ID, PO No Ref, Payment Method, Account ID, Total DP Amount, Ref No, Note
    Fungsi : Form pencatatan pembayaran uang muka (Down Payment) kepada supplier sebelum barang diterima/faktur terbit.
  - Supplier Payment List `supplier-payment-list`
    Komponen : Payment No, Date, Supplier Name, Payment Type (Regular/DP), Payment Method, Total Paid, Ref No, Status
    Fungsi : Menampilkan daftar riwayat transaksi pembayaran dan uang muka ke supplier.
  - Supp. Outstanding List `supp-outstanding-list`
    Komponen : Invoice No, Supplier ID, Supplier Name, Invoice Date, Due Date, Age (Days), Total Amount, Paid Amount, Outstanding Amount
    Fungsi : Memantau rincian faktur hutang pembelian yang belum dilunasi beserta analisis umur hutang (AP Aging).
  - Daily Supplier Payment Report `daily-supplier-payment-report`
    Komponen : Date, Payment No, Supplier Name, Payment Method, Total Paid, Account Name, User ID
    Fungsi : Laporan harian pengeluaran kas/bank untuk pembayaran hutang supplier.
  - Daily Supplier Payment List `daily-supplier-payment-list`
    Komponen : Date, Payment No, Supplier ID, Supplier Name, Payment Type, Total Amount, Ref No
    Fungsi : Menampilkan daftar transaksi harian pembayaran supplier untuk pengawasan kas/bank keluar.

- Purchase Return `#`
  - New Purchase Return `new-purchase-return`
    Komponen : Return No, Date, Supplier ID, Invoice No Ref, STBJ No Ref, Material ID, Qty Returned, Unit Price, Subtotal, Total Return Amount, Reason, Status
    Fungsi : Form pengajuan retur/pengembalian barang ke supplier akibat kerusakan, ketidaksesuaian, atau klaim komersial.
  - Purchase Return List `purchase-return-list`
    Komponen : Return No, Date, Supplier Name, Invoice Ref, Total Return Amount, Reason, Status (Draft/Approved/Completed)
    Fungsi : Menampilkan dan mengelola daftar dokumen retur pembelian barang.

- SJBB `sjbb`
  Komponen : SJBB No, Date, Supplier ID, Type (IN/OUT), Status, Material ID, Qty, UOM ID, Notes
  Fungsi : Mengelola Surat Jalan Bukti Barter (SJBB) untuk pencatatan transaksi penukaran/barter barang dengan pihak supplier/partner.

- Stock Adjustment `#`
  - Stock Adjustment Use `stock-adjustment-use`
    Komponen : Adjustment No, Date, Warehouse ID, Material ID, System Qty, Physical Qty, Qty Diff, Cost Per Unit, Total Cost, Reason, User
    Fungsi : Mengelola alokasi pemakaian barang persediaan untuk kebutuhan internal operasional perusahaan.
  - New Stock Adjustment (Standard) `new-stock-adjustment-standard`
    Komponen : Adjustment No, Date, Warehouse ID, Material ID, System Qty, Physical Qty, Qty Diff, Cost Per Unit, Total Cost Diff, Reason, Status
    Fungsi : Form pencatatan penyesuaian stok standar berdasarkan hasil perbandingan hitung fisik (Stock Opname) vs sistem.
  - New Stock Adjustment (Internal Use) `new-stock-adjustment-internal-use`
    Komponen : Adjustment No, Date, Warehouse ID, Department ID, Material ID, Qty Used, Cost Per Unit, Total Cost, Reason, Status
    Fungsi : Form pencatatan penyesuaian stok khusus untuk barang yang dikeluarkan demi pemakaian internal.
  - Stock Adjustment List `stock-adjustment-list`
    Komponen : Adjustment No, Date, Warehouse Name, Adjustment Type (Standard/Internal Use), Total Cost Diff, Reason, Status
    Fungsi : Menampilkan daftar seluruh transaksi penyesuaian stok dan pemakaian internal.
  - Daily Stock Adjustment Report `daily-stock-adjustment-report`
    Komponen : Date, Adjustment No, Warehouse Name, Adjustment Type, Total Item, Total Cost Diff, User ID
    Fungsi : Laporan harian rekapitulasi transaksi penyesuaian stok gudang.
  - Daily Stock Adjustment Track Report `daily-stock-adjustment-track-report`
    Komponen : Date, Adjustment No, Warehouse Name, Material ID, Material Name, System Qty, Physical Qty, Qty Diff, Reason
    Fungsi : Laporan rekapitulasi audit jejak mutasi kuantitas barang yang mengalami penyesuaian stok.
  - Daily Stock Adjustment Cost Report `daily-stock-adjustment-cost-report`
    Komponen : Date, Adjustment No, Warehouse Name, Material ID, Material Name, Qty Diff, Unit Cost, Total Valuation Diff
    Fungsi : Laporan analisis dampak finansial (nilai selisih biaya) dari transaksi penyesuaian stok harian.

- Stock Transfer `#`
  - New Stock Transfer `new-stock-transfer`
    Komponen : Transfer No, Date, From Warehouse ID, To Warehouse ID, Material ID, Qty Requested, Qty Shipped, Status, Notes
    Fungsi : Form pengiriman mutasi stok barang langsung antar gudang.
  - Stock Transfer List `stock-transfer-list`
    Komponen : Transfer No, Date, From Warehouse, To Warehouse, Total Items, Status (Requested/Prepared/In-Transit/Completed/Cancelled)
    Fungsi : Menampilkan daftar seluruh dokumen mutasi/transfer barang antar gudang.
  - Stock Transfer Shipment Preparation `stock-transfer-shipment-preparation`
    Komponen : Prep No, Prep Date, Stock Transfer No Ref, Driver Name, Vehicle No, Total Weight, Status
    Fungsi : Form persiapan muat barang dan alokasi armada pengangkutan untuk proses transfer stok antar gudang.
  - Stock Transfer Shipment Preparation List `stock-transfer-shipment-preparation-list`
    Komponen : Prep No, Prep Date, Transfer No Ref, Driver Name, Vehicle No, Status
    Fungsi : Menampilkan daftar dokumen persiapan armada pengiriman transfer stok.
  - New Stock Transfer Request `new-stock-transfer-request`
    Komponen : Request No, Date, Requester Warehouse ID, Source Warehouse ID, Material ID, Qty Requested, Notes, Status
    Fungsi : Form pengajuan permintaan pasokan stok dari gudang pemohon ke gudang sumber.
  - Stock Transfer Request List `stock-transfer-request-list`
    Komponen : Request No, Date, Requester Warehouse, Source Warehouse, Total Items, Status
    Fungsi : Menampilkan daftar dokumen permintaan transfer stok antar gudang.
  - Daily Stock Transfer Report `daily-stock-transfer-report`
    Komponen : Date, Transfer No, From Warehouse, To Warehouse, Material ID, Qty Shipped, Qty Received, Status
    Fungsi : Laporan harian rekapitulasi aktivitas pengiriman dan penerimaan mutasi barang antar gudang.
  - Stock Transfer Fulfilment `stock-transfer-fulfilment`
    Komponen : Request No, Transfer No, Material ID, Qty Requested, Qty Shipped, Qty Received, Qty Balance, Fulfilment Rate (%)
    Fungsi : Memantau tingkat pemenuhan pengiriman barang berdasarkan pengajuan permintaan transfer stok.

- Stock Conversion `stock-convertion`
  Komponen : Conversion No, Date, Warehouse ID, Material Template ID, Output Material ID, Output Qty Produced, Raw Material ID, Qty Consumed, Notes
  Fungsi : Mengelola transaksi perakitan/konversi stok (mengonversi beberapa stok bahan baku menjadi produk jadi berdasarkan resep).

- Material Template `material-template`
  Komponen : Template ID, Template Code, Template Name, Target Material ID, Target Output Qty, Raw Material ID, Qty Needed, UOM ID, Description
  Fungsi : Mengelola formulasi resep / Bill of Materials (BOM) standar yang digunakan sebagai acuan proses konversi/perakitan stok.

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