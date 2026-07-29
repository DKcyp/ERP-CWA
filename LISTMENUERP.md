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
    Fungsi : Mengelola aturan program pengembalian dana (cashback) atas pembelian produk tertentu oleh supplier.
    Penjelasan UI : Tampilan awal berupa table menampilkan Cashback_Id, Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active dan filter nama program dan product. Tombol tambah akan muncul pop up dengan form Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active. Untuk edit muncul pop up form Name, Supplier, Product, Min_Purchase, Cashback_Value, Valid_From, Valid_To, Active. Hapus muncul alert konfirmasi.

  - Supplier Product `supplier-product`
    Komponen : Supplier_Product_Id, Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active
    Fungsi : Memetakan hubungan silang (cross-reference) antara produk perusahaan dengan kode/nama produk milik supplier.
    Penjelasan UI : Tampilan awal berupa table menampilkan Supplier_Product_Id, Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active dan filter supplier dan product. Tombol tambah akan muncul pop up dengan form Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active. Untuk edit muncul pop up form Supplier, Product, Supplier_Item_Code, Supplier_Item_Name, Lead_Time_Days, Active. Hapus muncul alert konfirmasi.

  - UOM General Convertion `uom-general-convertion`
    Komponen : UOM_Convertion_Id, Product, From_UOM, To_UOM, Multiplier, Operator
    Fungsi : Mengatur rasio konversi antar satuan unit barang (misal: 1 BOX = 24 PCS) untuk perhitungan stok dan transaksi otomatis.
    Penjelasan UI : Tampilan awal berupa table menampilkan UOM_Convertion_Id, Product, From_UOM, To_UOM, Multiplier, Operator dan filter product dan UOM. Tombol tambah akan muncul pop up dengan form Product, From_UOM, To_UOM, Multiplier, Operator. Untuk edit muncul pop up form Product, From_UOM, To_UOM, Multiplier, Operator. Hapus muncul alert konfirmasi.

- Warehouse `warehouse`
  Komponen : Warehouse_Id, Code, Name, Address, PIC_Name, Phone, Note, Use_VAT, Active, Allow_Negative_Stock, View_In_Sales_Return
  Fungsi : Mengelola data induk lokasi fisik/logis gudang penyimpanan barang persediaan beserta pengaturan aturan operasionalnya (seperti pajak VAT, izin stok minus, pencatatan retur penjualan, dan catatan internal).
  Penjelasan UI : Tampilan awal berupa table menampilkan Warehouse_Id, Code, Name, Address, PIC_Name, Phone, Note, Use_VAT, Active, Allow_Negative_Stock, View_In_Sales_Return dan filter nama warehouse serta code. Tombol Tambah akan memunculkan pop-up modal form dengan field input Code, Name, Address, PIC_Name, Phone, Note, serta checkbox opsi Use_VAT, Active, Allow_Negative_Stock, dan View_In_Sales_Return. Untuk Edit akan memunculkan pop-up modal form dengan field dan checkbox yang sama untuk memperbarui data gudang. Hapus memunculkan alert konfirmasi.

- Currency `currency`
  Komponen : Currency_Id, Code, Name, Symbol, Is_Default, Active
  Fungsi : Mengelola daftar mata uang resmi yang digunakan dalam seluruh transaksi sistem.
  Penjelasan UI : Tampilan awal berupa table menampilkan Currency_Id, Code, Name, Symbol, Is_Default, Active dan filter code dan name. Tombol tambah akan muncul pop up dengan form Code, Name, Symbol, Is_Default, Active. Untuk edit muncul pop up form Code, Name, Symbol, Is_Default, Active. Hapus muncul alert konfirmasi.

- Rate `rate`
  Komponen : Rate_Id, Currency, Rate_Date, Rate_Value, Updated_By
  Fungsi : Mencatat dan mengelola riwayat nilai tukar kurs mata uang harian terhadap mata uang acuan (base currency).
  Penjelasan UI : Tampilan awal berupa table menampilkan Rate_Id, Currency, Rate_Date, Rate_Value, Updated_By dan filter currency dan tanggal rate. Tombol tambah akan muncul pop up dengan form Currency, Rate_Date, Rate_Value. Untuk edit muncul pop up form Currency, Rate_Date, Rate_Value. Hapus muncul alert konfirmasi.

- Payment Term `payment-term`
  Komponen : Term_Id, Payment_Discount_Percent, If_Paid_Within_Days, Net_Due_In_Days, Cash_On_Delivery, Default_Term_For_Not_COD, Sales_Discount_Id
  Fungsi : Mengatur master ketentuan termin pembayaran transaksi, potongan harga pembayaran awal (early payment discount), periode jatuh tempo bersih, opsi Cash On Delivery (COD), serta pengaitan ke skema diskon penjualan.
  Penjelasan UI : Tampilan awal berupa table menampilkan Term_Id, Payment_Discount_Percent, If_Paid_Within_Days, Net_Due_In_Days, Cash_On_Delivery, Default_Term_For_Not_COD, Sales_Discount dan filter Term_Id serta Sales_Discount. Tombol Tambah akan memunculkan pop-up modal form dengan field input Id, Payment Discount (%), If paid within (day(s)), Net Due in (day(s)), checkbox Cash On Delivery, checkbox Default Term for Not COD, serta dropdown Sales Discount (pilih dari Master Sales Discount). Untuk Edit akan memunculkan pop-up modal form dengan field dan checkbox yang sama untuk memperbarui data termin pembayaran. Hapus memunculkan alert konfirmasi.

- Notes `notes`
  Komponen : Note_Id, Title, Module, Default_Text, Active
  Fungsi : Mengelola templat teks catatan/ketentuan standar yang otomatis muncul pada cetakan dokumen transaksi.
  Penjelasan UI : Tampilan awal berupa table menampilkan Note_Id, Title, Module, Default_Text, Active dan filter title dan module. Tombol tambah akan muncul pop up dengan form Title, Module, Default_Text, Active. Untuk edit muncul pop up form Title, Module, Default_Text, Active. Hapus muncul alert konfirmasi.

- Promo Buy N Get M `promo-buy-n-get-m`
  Komponen : Promo_Id, Name, Date_From, Date_To, Buy_Product_Id, Buy_Product_Name, Buy_Qty, Get_Product_Id, Get_Product_Name, Get_Qty, Get_Discount_Amount, Get_Discount_Percentage, Sales_Invoice_Discount_Amount, Sales_Invoice_Discount_Percentage
  Fungsi : Mengatur aturan promosi penjualan kustom "Beli N Barang Bonus M Barang" beserta pengaturan potongan harga/diskon tambahan pada produk bonus maupun pada level Faktur Penjualan (Sales Invoice).
  Penjelasan UI : Tampilan awal berupa table menampilkan Promo_Id, Name, Date_From, Date_To, Buy_Product_Name, Buy_Qty, Get_Product_Name, Get_Qty, Get_Discount_Percentage, Sales_Invoice_Discount_Percentage dan filter promo name serta product. Tombol Tambah akan memunculkan pop-up modal form berstruktur:
    1. Header Info : Promo Id, Name, Date From (DatePicker), Date To (DatePicker).
    2. Seksi Buy   : Product Id (Lookup Search), Name (Read-only), Buy Qty.
    3. Seksi Get   : Product Id (Lookup Search), Name (Read-only), Get Qty, Discount Amount, Discount Percentage.
    4. Seksi Sales Invoice : Discount Amount, Discount Percentage.
  Untuk Edit akan memunculkan pop-up modal form dengan struktur dan field yang sama untuk memperbarui data promosi. Hapus memunculkan alert konfirmasi.

- Employee `employee`
  Komponen : Employee_Id, Name, User_Id, Commission_Id, Active, Transit_Area_Id
  Fungsi : Mengelola data induk karyawan perusahaan serta menghubungkan profil karyawan dengan akun sistem (User Id), skema komisi (Commission Id), dan wilayah/gudang penugasan (Transit Area).
  Penjelasan UI : Tampilan awal berupa table menampilkan Employee_Id, Name, User_Id, Commission_Id, Active, Transit_Area dan filter Name, User_Id, serta Transit_Area. Tombol Tambah akan memunculkan pop-up modal form dengan field input Employee_Id, Name, dropdown User_Id (pilih dari Master User), dropdown Commission_Id (pilih dari Master Commission), checkbox Active, serta dropdown Transit_Area (pilih dari Master Warehouse/Transit Area). Untuk Edit akan memunculkan pop-up modal form dengan field, dropdown, dan checkbox yang sama untuk memperbarui data karyawan. Hapus memunculkan alert konfirmasi.

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
  Komponen : Edition_Id, Code, Name, Start_Date, End_Date, Description, Active
  Fungsi : Mengelola pengelompokan edisi atau versi rilis dari suatu produk (misal: Edisi Khusus, Edisi Impor, Edisi Musim) beserta penentuan batas periode awal (Start Date) dan akhir (End Date) berlakunya edisi tersebut.
  Penjelasan UI : Tampilan awal berupa table menampilkan Edition_Id, Code, Name, Start_Date, End_Date, Description, Active dan filter code, name, serta rentang tanggal (Start/End Date). Tombol Tambah akan memunculkan pop-up modal form dengan field input Code, Name, Start_Date (DatePicker), End_Date (DatePicker), Description, dan checkbox Active. Untuk Edit akan memunculkan pop-up modal form dengan field input, DatePicker, dan checkbox yang sama untuk memperbarui data edisi. Hapus memunculkan alert konfirmasi.

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
##

Create Doc. From CSV

## Material Management `#`

- Dashboard Material Management `material-dashboard`
  Komponen : Total_PR_Pending, Total_PO_Active, Total_STBJ_Today, Total_AP_Outstanding, Stock_Alert_Count, Purchasing_Progress_Tracker (PR -> PO -> STBJ -> Invoice), Chart_PO_Vs_STBJ, Chart_Monthly_Purchase
  Fungsi : Menyajikan gambaran umum secara visual mengenai metrik kunci operasional material management, pemantauan alur progres pembelian (purchasing progress pipeline) secara kontinu, status dokumen (PR/PO/STBJ), posisi hutang usaha, dan peringatan stok.
  Penjelasan UI : Tampilan awal berupa dashboard interaktif berisi ringkasan widget angka (card stat), modul visual widget/pipeline Monitoring Progres Purchasing (menampilkan persentase & tahap penyelesaian pengajuan PR hingga penagihan Invoice), diagram grafik batang/garis trend transaksi pembelian, serta tabel ringkas notifikasi dokumen pending. Halaman bersifat Read-Only dan hanya menyediakan filter periode tanggal dan gudang.

- Purchase Request `#`
  - Purchase Request List `purchase-request-list`
    Komponen : PR_No, Date, Requester, Department, Total_Item, Total_Qty_Requested, Total_Qty_Ordered, Status, Note
    Fungsi : Menampilkan dan mengelola daftar seluruh dokumen pengajuan Permintaan Pembelian (PR) beserta status pemenuhannya (Draft / Approved / Partial PO / Fulfilled / Rejected).
    Penjelasan UI : Tampilan awal berupa table menampilkan PR_No, Date, Requester, Department, Total_Item, Total_Qty_Requested, Total_Qty_Ordered, Status, Note dan filter PR_No, department, status, serta rentang tanggal. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Requester, Department, Note) dan Table Detail Items (Material, Qty Requested, UOM, Required Date). Untuk Edit memunculkan pop-up form dengan struktur yang sama. Hapus memunculkan alert konfirmasi.

  - Purchase Request Fulfilment Report `purchase-request-fulfilment-report`
    Komponen : PR_No, PR_Date, Department, Material_Id, Material_Name, Qty_Requested, Qty_Ordered_Total, Qty_Outstanding, Linked_PO_Numbers, Status
    Fungsi : Memantau tingkat pemenuhan dan histori penarikan item barang pada PR yang dipecah menjadi beberapa PO secara bertahap dan di tanggal berbeda.
    Penjelasan UI : Tampilan awal berupa table menampilkan PR_No, PR_Date, Department, Material_Id, Material_Name, Qty_Requested, Qty_Ordered_Total, Qty_Outstanding, Linked_PO_Numbers, Status dan filter PR_No, department, material, dan status pemenuhan (Unfulfilled / Partial / Completed). Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

- Purchase Order `#`
  - Purchase Order List `purchase-order-list`
    Komponen : PO_No, Date, Supplier_Name, PR_Ref_No, Subtotal, Tax, Discount, Total_Amount, Status
    Fungsi : Menampilkan dan mengelola daftar dokumen Purchase Order (PO) yang diterbitkan (termasuk PO parsial yang menarik sisa barang dari satu dokumen PR di tanggal berbeda).
    Penjelasan UI : Tampilan awal berupa table menampilkan PO_No, Date, Supplier_Name, PR_Ref_No, Subtotal, Tax, Discount, Total_Amount, Status dan filter PO_No, PR_Ref_No, supplier, status, dan rentang tanggal. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Supplier, PR_Ref_No [Lookup PR], Payment Term, Tax, Discount) di mana saat PR dipilih, sistem otomatis menampilkan sisa kuantitas barang yang belum di-PO (Outstanding Qty) pada Table Detail Items (Material, Qty Available from PR, Qty to Order, Unit Price, Subtotal). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

  - Purchase Fulfillment Report `purchase-fulfillment-report`
    Komponen : PO_No, PO_Date, Supplier_Name, PR_Ref_No, Material_Id, Qty_Ordered, Qty_Received, Qty_Outstanding, Fulfilment_Rate
    Fungsi : Memantau rasio pemenuhan penerimaan barang fisik (STBJ) oleh supplier atas pesanan PO yang terhubung ke referensi PR.
    Penjelasan UI : Tampilan awal berupa table menampilkan PO_No, PO_Date, Supplier_Name, PR_Ref_No, Material_Id, Qty_Ordered, Qty_Received, Qty_Outstanding, Fulfilment_Rate dan filter PO_No, PR_Ref_No, supplier, material, dan rentang tanggal. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

  - Daily Purchase Order Report `daily-purchase-order-report`
    Komponen : Date, PO_No, PR_Ref_No, Supplier_Name, Total_Amount, Status, User_Id
    Fungsi : Laporan harian rekapitulasi pembuatan dan status dokumen Purchase Order harian beserta jejak nomor PR referensinya.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, PO_No, PR_Ref_No, Supplier_Name, Total_Amount, Status, User_Id dan filter tanggal harian, supplier, status, dan user. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.
    
- Purchase Invoice `#`
  - Purchase Invoice List `purchase-invoice-list`
    Komponen : Invoice_No, Date, Due_Date, Supplier_Name, PO_Ref, STBJ_No_Ref, Total_Amount, Paid_Amount, Outstanding, Status
    Fungsi : Menampilkan daftar seluruh faktur tagihan pembelian beserta status pelunasannya.
    Penjelasan UI : Tampilan awal berupa table menampilkan Invoice_No, Date, Due_Date, Supplier_Name, PO_Ref, STBJ_No_Ref, Total_Amount, Paid_Amount, Outstanding, Status dan filter Invoice_No, supplier, status pembayaran, dan rentang tanggal. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Supplier, PO Ref / STBJ Ref, Due Date, Tax) dan Table Detail Items. Untuk Edit memunculkan pop-up form dengan data terisi. Hapus memunculkan alert konfirmasi.

  - Daily Purchase Invoice Report `daily-purchase-invoice-report`
    Komponen : Date, Invoice_No, Supplier_Name, Total_Amount, Status, Due_Date
    Fungsi : Laporan harian rincian penerbitan faktur pembelian dari supplier.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Invoice_No, Supplier_Name, Total_Amount, Status, Due_Date dan filter tanggal harian, supplier, dan status invoice. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

  - Monthly Purchase by Supplier Report `monthly-purchase-by-supplier-report`
    Komponen : Period, Supplier_Id, Supplier_Name, Total_PO_Count, Total_Invoice_Amount, Total_Paid_Amount, Total_Outstanding
    Fungsi : Laporan akumulasi nilai transaksi pembelian per supplier dalam periode bulanan.
    Penjelasan UI : Tampilan awal berupa table menampilkan Period, Supplier_Id, Supplier_Name, Total_PO_Count, Total_Invoice_Amount, Total_Paid_Amount, Total_Outstanding dan filter periode bulan/tahun serta supplier. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

- STBJ `stbj`
  Komponen : STBJ_No, Date, Supplier_Id, PO_No_Ref, Warehouse_Id, SJ_Supplier_No, Material_Id, Qty_Received, Qty_Accepted, Qty_Rejected, UOM_Id, Note
  Fungsi : Mengelola pencatatan Surat Tanda Bukti Jalan (STBJ/Goods Receipt) atas penerimaan fisik barang dari supplier di gudang.
  Penjelasan UI : Tampilan awal berupa table menampilkan STBJ_No, Date, Supplier_Id, PO_No_Ref, Warehouse_Id, SJ_Supplier_No, Status dan filter STBJ_No, PO_No_Ref, supplier, dan warehouse. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Supplier, PO Ref, Warehouse, SJ Supplier No) dan Table Detail Items (Material, Qty Received, Qty Accepted, Qty Rejected, UOM, Note). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

- Supplier Payment `#`
  - Supplier Payment List `supplier-payment-list`
    Komponen : Payment_No, Date, Supplier_Name, Payment_Type, Payment_Method, Total_Paid, Ref_No, Status
    Fungsi : Menampilkan daftar riwayat transaksi pembayaran dan uang muka ke supplier.
    Penjelasan UI : Tampilan awal berupa table menampilkan Payment_No, Date, Supplier_Name, Payment_Type, Payment_Method, Total_Paid, Ref_No, Status dan filter Payment_No, supplier, payment_type, dan rentang tanggal. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Supplier, Payment Type, Payment Method, Account Bank, Ref No) dan Table Allocation Invoice (Invoice No, Outstanding, Amount Paid). Untuk Edit memunculkan pop-up form dengan data terisi. Hapus memunculkan alert konfirmasi.

  - Supp. Outstanding List `supp-outstanding-list`
    Komponen : Invoice_No, Supplier_Id, Supplier_Name, Invoice_Date, Due_Date, Age_Days, Total_Amount, Paid_Amount, Outstanding_Amount
    Fungsi : Memantau rincian faktur hutang pembelian yang belum dilunasi beserta analisis umur hutang (AP Aging).
    Penjelasan UI : Tampilan awal berupa table menampilkan Invoice_No, Supplier_Id, Supplier_Name, Invoice_Date, Due_Date, Age_Days, Total_Amount, Paid_Amount, Outstanding_Amount dan filter supplier, kriteria umur hutang (AP Aging Bracket), dan due date. Halaman bersifat Read-Only (Laporan/Monitoring) tanpa tombol tambah, edit, dan hapus.

  - Daily Supplier Payment Report `daily-supplier-payment-report`
    Komponen : Date, Payment_No, Supplier_Name, Payment_Method, Total_Paid, Account_Name, User_Id
    Fungsi : Laporan harian pengeluaran kas/bank untuk pembayaran hutang supplier.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Payment_No, Supplier_Name, Payment_Method, Total_Paid, Account_Name, User_Id dan filter tanggal harian, payment method, dan account name. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

  - Daily Supplier Payment List `daily-supplier-payment-list`
    Komponen : Date, Payment_No, Supplier_Id, Supplier_Name, Payment_Type, Total_Amount, Ref_No
    Fungsi : Menampilkan daftar transaksi harian pembayaran supplier untuk pengawasan kas/bank keluar.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Payment_No, Supplier_Id, Supplier_Name, Payment_Type, Total_Amount, Ref_No dan filter tanggal harian, supplier, dan payment type. Halaman ini berfungsi sebagai ringkasan harian log transaksi tanpa tombol tambah, edit, dan hapus.

- Purchase Return `#`
  - Purchase Return List `purchase-return-list`
    Komponen : Return_No, Date, Supplier_Name, Invoice_Ref, Total_Return_Amount, Reason, Status
    Fungsi : Menampilkan dan mengelola daftar dokumen retur pembelian barang.
    Penjelasan UI : Tampilan awal berupa table menampilkan Return_No, Date, Supplier_Name, Invoice_Ref, Total_Return_Amount, Reason, Status dan filter Return_No, supplier, invoice_ref, dan status. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Supplier, Invoice Ref, Reason) dan Table Detail Items (Material, Qty Return, Unit Price, Total Return Amount). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

- SJBB `sjbb`
  Komponen : SJBB_No, Date, Supplier_Id, Type, Status, Material_Id, Qty, UOM_Id, Notes
  Fungsi : Mengelola Surat Jalan Bukti Barter (SJBB) untuk pencatatan transaksi penukaran/barter barang dengan pihak supplier/partner.
  Penjelasan UI : Tampilan awal berupa table menampilkan SJBB_No, Date, Supplier_Id, Type, Status, Notes dan filter SJBB_No, supplier, type (IN/OUT), dan status. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Supplier, Type IN/OUT, Notes) dan Table Detail Items (Material, Qty, UOM). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

- Stock Adjustment `#`
  - Stock Adjustment List `stock-adjustment-list`
    Komponen : Adjustment_No, Date, Warehouse_Name, Adjustment_Type, Total_Cost_Diff, Reason, Status
    Fungsi : Menampilkan daftar seluruh transaksi penyesuaian stok dan pemakaian internal.
    Penjelasan UI : Tampilan awal berupa table menampilkan Adjustment_No, Date, Warehouse_Name, Adjustment_Type, Total_Cost_Diff, Reason, Status dan filter Adjustment_No, warehouse, adjustment_type, dan rentang tanggal. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Warehouse, Adjustment Type, Reason) dan Table Detail Items (Material, System Qty, Physical Qty, Qty Diff, Unit Cost). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

  - Daily Stock Adjustment Report `daily-stock-adjustment-report`
    Komponen : Date, Adjustment_No, Warehouse_Name, Adjustment_Type, Total_Item, Total_Cost_Diff, User_Id
    Fungsi : Laporan harian rekapitulasi transaksi penyesuaian stok gudang.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Adjustment_No, Warehouse_Name, Adjustment_Type, Total_Item, Total_Cost_Diff, User_Id dan filter tanggal harian, warehouse, dan adjustment type. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

  - Daily Stock Adjustment Track Report `daily-stock-adjustment-track-report`
    Komponen : Date, Adjustment_No, Warehouse_Name, Material_Id, Material_Name, System_Qty, Physical_Qty, Qty_Diff, Reason
    Fungsi : Laporan rekapitulasi audit jejak mutasi kuantitas barang yang mengalami penyesuaian stok.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Adjustment_No, Warehouse_Name, Material_Id, Material_Name, System_Qty, Physical_Qty, Qty_Diff, Reason dan filter tanggal, warehouse, dan material. Halaman bersifat Read-Only (Laporan/Audit Track) tanpa tombol tambah, edit, dan hapus.

  - Daily Stock Adjustment Cost Report `daily-stock-adjustment-cost-report`
    Komponen : Date, Adjustment_No, Warehouse_Name, Material_Id, Material_Name, Qty_Diff, Unit_Cost, Total_Valuation_Diff
    Fungsi : Laporan analisis dampak finansial (nilai selisih biaya) dari transaksi penyesuaian stok harian.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Adjustment_No, Warehouse_Name, Material_Id, Material_Name, Qty_Diff, Unit_Cost, Total_Valuation_Diff dan filter tanggal, warehouse, dan material. Halaman bersifat Read-Only (Laporan/Financial Impact) tanpa tombol tambah, edit, dan hapus.

- Stock Transfer `#`
  - Stock Transfer List `stock-transfer-list`
    Komponen : Transfer_No, Date, From_Warehouse, To_Warehouse, Total_Items, Status
    Fungsi : Menampilkan daftar seluruh dokumen mutasi/transfer barang antar gudang.
    Penjelasan UI : Tampilan awal berupa table menampilkan Transfer_No, Date, From_Warehouse, To_Warehouse, Total_Items, Status dan filter Transfer_No, from_warehouse, to_warehouse, dan status. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, From Warehouse, To Warehouse, Notes) dan Table Detail Items (Material, Qty Transfer, UOM). Untuk Edit memunculkan pop-up form dengan data terisi. Hapus memunculkan alert konfirmasi.

  - Stock Transfer Shipment Preparation List `stock-transfer-shipment-preparation-list`
    Komponen : Prep_No, Prep_Date, Transfer_No_Ref, Driver_Name, Vehicle_No, Status
    Fungsi : Menampilkan daftar dokumen persiapan armada pengiriman transfer stok.
    Penjelasan UI : Tampilan awal berupa table menampilkan Prep_No, Prep_Date, Transfer_No_Ref, Driver_Name, Vehicle_No, Status dan filter Prep_No, Transfer_No_Ref, driver, dan status. Tombol Tambah akan memunculkan pop-up modal form berisi Prep Date, Transfer No Ref, Driver Name, Vehicle No, dan Status. Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

  - Stock Transfer Request List `stock-transfer-request-list`
    Komponen : Request_No, Date, Requester_Warehouse, Source_Warehouse, Total_Items, Status
    Fungsi : Menampilkan daftar dokumen permintaan transfer stok antar gudang.
    Penjelasan UI : Tampilan awal berupa table menampilkan Request_No, Date, Requester_Warehouse, Source_Warehouse, Total_Items, Status dan filter Request_No, requester_warehouse, source_warehouse, dan status. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Requester Warehouse, Source Warehouse, Reason) dan Table Detail Items (Material, Qty Requested). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

  - Daily Stock Transfer Report `daily-stock-transfer-report`
    Komponen : Date, Transfer_No, From_Warehouse, To_Warehouse, Material_Id, Qty_Shipped, Qty_Received, Status
    Fungsi : Laporan harian rekapitulasi aktivitas pengiriman dan penerimaan mutasi barang antar gudang.
    Penjelasan UI : Tampilan awal berupa table menampilkan Date, Transfer_No, From_Warehouse, To_Warehouse, Material_Id, Qty_Shipped, Qty_Received, Status dan filter tanggal harian, warehouse, material, dan status. Halaman bersifat Read-Only (Laporan/Report) tanpa tombol tambah, edit, dan hapus.

  - Stock Transfer Fulfilment `stock-transfer-fulfilment`
    Komponen : Request_No, Transfer_No, Material_Id, Qty_Requested, Qty_Shipped, Qty_Received, Qty_Balance, Fulfilment_Rate
    Fungsi : Memantau tingkat pemenuhan pengiriman barang berdasarkan pengajuan permintaan transfer stok.
    Penjelasan UI : Tampilan awal berupa table menampilkan Request_No, Transfer_No, Material_Id, Qty_Requested, Qty_Shipped, Qty_Received, Qty_Balance, Fulfilment_Rate dan filter Request_No, Transfer_No, material, dan status pemenuhan. Halaman bersifat Read-Only (Laporan/Monitoring) tanpa tombol tambah, edit, dan hapus.

- Stock Conversion `stock-convertion`
  Komponen : Conversion_No, Date, Warehouse_Id, Material_Template_Id, Output_Material_Id, Output_Qty_Produced, Raw_Material_Id, Qty_Consumed, Notes
  Fungsi : Mengelola transaksi perakitan/konversi stok (mengonversi beberapa stok bahan baku menjadi produk jadi berdasarkan resep).
  Penjelasan UI : Tampilan awal berupa table menampilkan Conversion_No, Date, Warehouse_Id, Material_Template_Id, Output_Material_Id, Output_Qty_Produced, Notes dan filter Conversion_No, warehouse, material_template, dan rentang tanggal. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Date, Warehouse, Material Template [Lookup Template], Output Material, Output Qty Produced) dan Table Detail Raw Materials Consumed (Raw Material, Qty Needed, Qty Consumed). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.

- Material Template `material-template`
  Komponen : Template_Id, Template_Code, Template_Name, Target_Material_Id, Target_Output_Qty, Raw_Material_Id, Qty_Needed, UOM_Id, Description
  Fungsi : Mengelola formulasi resep / Bill of Materials (BOM) standar yang digunakan sebagai acuan proses konversi/perakitan stok.
  Penjelasan UI : Tampilan awal berupa table menampilkan Template_Id, Template_Code, Template_Name, Target_Material_Id, Target_Output_Qty, Description dan filter template_code, template_name, dan target_material. Tombol Tambah akan memunculkan pop-up modal form berstruktur Header (Template Code, Template Name, Target Material [Lookup Product], Target Output Qty, Description) dan Table Detail Raw Materials (Raw Material [Lookup Product], Qty Needed, UOM). Untuk Edit memunculkan pop-up form dengan field yang sama. Hapus memunculkan alert konfirmasi.
  
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