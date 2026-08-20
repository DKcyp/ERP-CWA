# ERP Menu List

## ERP Menu List - Master Data `#`
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

  - Production
    - Specification Rule `specification-rule`
    Komponen :
    - Product Specification Rule `product-specification-rule`
    Komponen :
    - Production Setup `production-setup`
    Komponen :
    - Product Template `product-template`
    Komponen :
    - Default Warehouse `default-warehouse`
    Komponen :
    - Machine `machine`
    Komponen :
    - Labour Commission `labour-commission`
    Komponen :
    - Production Plan Name `production-plan-name`
    Komponen :
    - Product Type `product-type`
    Komponen :
    - Product HPP Master `product-hpp-master`
    Komponen :
    - Product Base `product-base`
    Komponen :
    - Production Reference `production-reference`
    Komponen :
    - Production Jadwal `production-jadwal`
    Komponen :
    - Production Produksi `production-produksi`
Komponen :

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
      2. Buy   : Product Id (Lookup Search), Name (Read-only), Buy Qty.
      3. Get   : Product Id (Lookup Search), Name (Read-only), Get Qty, Discount Amount, Discount Percentage.
      4. Sales Invoice : Discount Amount, Discount Percentage.
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
        
  - Customer
    - Customer Master `customer-master`
      Komponen : Id, Name, NIK, Nama (NIK), NPWP, SIM, Marketing, Credit Limit, Due Date Warning, Warehouse, Active, Contact, Position, Address1, Address2, Kecamatan, Kabupaten, City, ZIP, Channel Outlet, Rayon Sales, Province, Country, Phone, Mobile_Phone, Email, Note, Price List Id, Term
      Fungsi : Mengelola data induk (master data) pelanggan secara komprehensif mulai dari identitas, alamat, kontak, hingga kebijakan kredit dan daftar harga.
      Penjelasan UI : Tampilan awal berupa data grid berfitur pencarian cepat dan multi-filter (kategori, area, status aktif). Tombol [ + Tambah Customer ] memunculkan Modal Form / Drawer berstruktur Tab Navigasi: Tab Profil & Alamat, Tab Kontak PIC, Tab Finansial & Kredit (Credit Limit, Term, Price List), dan Tab Pengaturan Wilayah. Edit memunculkan modal terisi untuk pembaruan data, Hapus memunculkan alert konfirmasi.

    - Customer Group `customer-group`
      Komponen : Id, Name, Description, AR Account
      Fungsi : Mengelompokkan pelanggan berdasarkan segmen atau tipe tertentu serta memetakan akun Piutang (AR Account) yang sesuai pada buku besar.
      Penjelasan UI : Tampilan awal berupa table data grid menampilkan ID, Nama Kelompok, Deskripsi, dan Akun Piutang. Tombol Tambah memunculkan Modal Form input Nama, Deskripsi, serta Lookup Dropdown AR Account. Edit memunculkan form terisi, Hapus memunculkan alert konfirmasi.

    - Customer Area `customer-area`
      Komponen : Id, Area
      Fungsi : Menentukan pembagian wilayah atau zonasi operasional penjualan dan distribusi pelanggan.
      Penjelasan UI : Tampilan awal berupa table sederhana berfitur pencarian area. Tombol Tambah memunculkan Modal Form ringkas untuk input nama area/zonasi wilayah. Edit dan Hapus dilengkapi tombol aksi pada tiap baris.

    - WA Name `wa-name`
      Komponen : Id, Customer ID, Name, Phone Number, Role/Position, Is Primary
      Fungsi : Mengelola daftar kontak WhatsApp terverifikasi milik pelanggan untuk kebutuhan pengiriman notifikasi, dokumen, dan komunikasi operasional.
      Penjelasan UI : Tampilan awal berupa data grid kontak terhubung ID Pelanggan. Tombol Tambah memunculkan Modal Form dengan Autocomplete Customer ID, Nomor WhatsApp, Jabatan, serta Checkbox Is Primary.

    - Customer Tools `customer-tools`
      Komponen : Id, Customer ID, Tool Name, Serial Number, Qty, Condition, Loan Date, Status, Note
      Fungsi : Mencatat dan memantau peminjaman atau alokasi aset/peralatan pendukung penjualan yang dipinjamkan ke pelanggan (misal: mesin dispenser, pendingin, atau display banner).
      Penjelasan UI : Tampilan awal berupa table data grid berpenanda status pinjam (Dipinjam / Dikembalikan / Rusak). Tombol Tambah memunculkan Modal Form input Customer, Nama Alat, No. Seri, Qty, Kondisi, Tanggal Pinjam, dan Catatan.

    - Customer Centre `customer-centre`
      Komponen : Id, Customer ID, Centre Code, Centre Name, Address, PIC Name, Phone, Email, Warehouse ID
      Fungsi : Mengelola data cabang, titik serah, atau unit lokasi penyerahan barang milik pelanggan utama/korporat.
      Penjelasan UI : Tampilan awal berupa table data grid lokasi titik serah per pelanggan. Tombol Tambah memunculkan Modal Form berstruktur Kode/Nama Cabang, Alamat Lengkap, Kontak PIC, dan Pemetaan Gudang Alokasi.
  - marketing Area
    - master Area

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

## Production Planing
- Dashboard Production Planning `production-planning-dashboard`
  Komponen : Total_SPK_Active, Total_Target_Tonase_Today, Total_Realisasi_Tonase_Today, Overall_Schedule_Compliance_Percent, Machine_Grinding_Utilization_Percent, Overall_Yield_Percent, Alert_Material_Shortage_Count, Schedule_Status_Pipeline (Draft -> Planned -> In Progress -> QC Pending -> Completed), Chart_Hourly_Production_Output, Chart_Base_Vs_CM_Vs_Packing, Table_Urgent_SPK_Pending
  Fungsi : Menyajikan gambaran umum secara visual mengenai metrik kunci operasional perencanaan dan realisasi produksi pabrik cat, pemantauan status alur Perintah Kerja (SPK), utilisasi mesin giling, perolehan hasil produksi (yield), serta peringatan dini kekurangan stok bahan baku (pigmen/resin/solvent/kemasan).
  Penjelasan UI : Tampilan awal berupa dashboard interaktif berisi ringkasan widget angka (card stat) indikator performa utama (KPI), modul visual pipeline status SPK, diagram grafik garis/batang trend realisasi output tonase harian & per jam, grafik perbandingan tahapan produksi (Base vs CM vs Packing), serta tabel ringkas notifikasi SPK mendesak dan material yang kurang (shortage). Halaman bersifat Read-Only dan hanya menyediakan filter periode tanggal, shift, tipe adonan (Water Based/Solvent Based), dan lokasi gudang/pabrik.

- Pre Production
  - Pre SPK List `pre-spk-list`
    Komponen: Doc. ID, Date, Customer ID, Name, Total Qty, Total Tonase, Notes, User ID, Status
    Fungsi: Mengelola draf pengajuan Surat Perintah Kerja (SPK) awal berdasarkan pesanan pelanggan sebelum divalidasi menjadi dokumen SPK resmi.
    Penjelasan UI: Tampilan awal berupa table data grid menampilkan Doc. ID, Date, Customer ID, Name, Total Qty, Total Tonase, Status, dan Notes beserta filter rentang tanggal, customer, dan status. Tombol Tambah memunculkan Pop-up Modal Form berstruktur Header (Date, Customer ID, Name, Notes) dan Table Detail Items (Product ID, Target Qty, Target Tonase). Edit memunculkan modal form terisi, Hapus memunculkan alert konfirmasi.

  - SPK List `spk-list`
    Komponen: Doc. ID, Date, Delivery Date, Total Qty, Total Tonase, Total Qty Needs, Total Tonase Needs, Warehouse, Notes, User ID, Status
    Fungsi: Menampilkan dan mengelola dokumen Perintah Kerja produksi resmi yang menjadi acuan jadwal pabrik serta kalkulasi pemenuhan stok material.
    Penjelasan UI: Tampilan awal berupa table data grid menampilkan Doc. ID, Date, Delivery Date, Warehouse, Total Qty, Total Tonase, Status, dan User ID beserta filter tanggal, warehouse, dan status. Tombol Tambah memunculkan Pop-up Modal Form dengan penarikan data dari Pre SPK, mengalkulasi otomatis sisa kebutuhan (Total Qty Needs / Tonase Needs), serta menentukan gudang alokasi. Edit memunculkan modal form terisi, Hapus memunculkan alert konfirmasi.

  - Production Scheduling `production-scheduling`
    Komponen: Doc. ID, Tipe, FK, Date, SPK, SPK From Date, SPK To Date, Notes, User ID, Line Machine ID
    Fungsi: Mengatur plotting waktu, durasi pengerjaan, dan pengalokasian mesin/lini produksi berdasarkan dokumen SPK yang terbit.
    Penjelasan UI: Tampilan awal berupa visualisasi Gantt Chart interaktif dan tabel grid jadwal produksi. Dilengkapi filter rentang tanggal SPK (From-To Date), tipe adonan, dan pilihan mesin. Pengguna dapat menggeser/mengatur alokasi waktu jadwal produksi secara drag-and-drop atau memunculkan modal form input plotting jadwal baru.

  - Daily Schedule Report `daily-schedule-report`
    Komponen: Tanggal, Product Id, Name, Base, Formulasi, Batch NR, Basis, Basis (Kg), Total Basis (Kg), Hasil CM, Kode Mesin, Status, Realisasi, Lead Time, Dateline, No / On Time
    Fungsi: Menyajikan laporan harian rekapitulasi jadwal produksi, evaluasi capaian target, waktu pengerjaan (lead time vs dateline), serta indikator ketepatan waktu (On Time / Late).
    Penjelasan UI: Tampilan awal berupa table analitik Read-Only menampilkan seluruh komponen jadwal harian dilengkapi badge indikator visual warna hijau/merah pada kolom On Time/Late Status. Dilengkapi filter tanggal, mesin, status realisasi, serta tombol Export Excel/PDF.

- Kemasan
  - SPK Kemasan `spk-kemasan`
    Komponen: Doc. ID, Date, SPK_Ref_No, Product_ID, Package_Type, Target_Qty_Pcs, Target_Tonase, Status, Notes, User_ID
    Fungsi: Menerbitkan dan mengelola Surat Perintah Kerja khusus persiapan, pencetakan, dan penyediaan material kemasan (kaleng/galon/pail).
    Penjelasan UI: Tampilan awal berupa table data grid menampilkan Doc. ID, Date, SPK_Ref_No, Product_ID, Package_Type, Target_Qty_Pcs, Status, dan Notes beserta filter tanggal dan jenis kemasan. Tombol Tambah memunculkan Pop-up Modal Form berstruktur Header (Date, SPK Ref, Package Type, Target Pcs) dan Table Detail Packaging Specs. Edit memunculkan modal form terisi, Hapus memunculkan alert konfirmasi.

  - Jadwal Kemasan `jadwal-kemasan`
    Komponen: Schedule_ID, Date, SPK_Kemasan_Ref, Line_Packaging_ID, Product_Name, Target_Pcs, Shift, Operator_In_Charge, Status, Notes
    Fungsi: Menjadwalkan plotting pengoperasian lini mesin pengemasan (packaging line) dan alokasi tim operator berdasarkan SPK Kemasan.
    Penjelasan UI: Tampilan awal berupa kalender kerja/grid jadwal harian per packaging line dengan penanda warna shift. Dilengkapi filter tanggal, lini kemasan, dan shift. Tombol Tambah memunculkan modal form pemilihan SPK Kemasan Ref, Line ID, Shift, dan penetapan Operator In Charge.

- Production
  - Production List `production-list`
    Komponen: Production Id, Template Name, Formulasi, Basis, Qty Jadwal, FK, Jadwal, Produksi, Recanning, Batch No, No. SPKP, Date, No. Box Arsip, Tipe Product, Product Group, Reference, Machine, Status, User Id, Notes, Stock Release, Stock Receive, QC, Adjustment, Total Material, Total Realisasi, Selisih, Adj. Batch, Kesimpulan, Keputusan
    Fungsi: Hub utama pusat pencatatan dan pengelolaan dokumen eksekusi produksi menyeluruh, mulai dari penimbangan formulasi bahan, penarikan stok (Stock Release), pengolahan, pengujian QC, hingga penyesuaian batch.
    Penjelasan UI: Tampilan awal berupa data grid komprehensif berfitur pencarian cepat dan multi-filter status. Tombol Tambah memunculkan Form Modal berstruktur Tab Navigasi: Tab Header (Info Dokumen, SPKP, Mesin), Tab Formulasi & Material (Stock Release & Timbang), Tab Hasil Realisasi & Selisih, serta Tab Status QC & Keputusan Kelulusan. Edit memunculkan form terisi lengkap, Hapus memunculkan alert konfirmasi.

  - Release Production `release-production`
    Komponen: Production Id, User, Tanggal, Status, QC_Notes, Batch_No, Warehouse_Target
    Fungsi: Memproses otorisasi kelulusan produk jadi dari tim QC/Supervisor agar stok siap dirilis dan ditransfer dari lini produksi ke gudang penyimpanan.
    Penjelasan UI: Tampilan awal berupa daftar antrean verifikasi kelulusan batch produksi. Pengguna dapat memilih baris dokumen untuk memunculkan modal detail hasil QC, catatan pengujian, serta tombol aksi utama [ Approve / Release Stock ] atau [ Hold / Reject ] dengan penentuan gudang tujuan penerimaan.

  - Production Commission `production-commission`
    Komponen Filter : Employee (Select Employee), Date_From, Date_To, 
    Komponen Tab Commission : Date, Production, Paid, Payment_Date, Status, Machine, Commission, Qty, Amount, Total, Notes
    Komponen Tab Payment : Id, Date, Account, Total, Notes
    Komponen Tab Payment Detail : Production_Id, Commission, Qty, Amount, Total, Total_Detail
    Fungsi : Memantau kalkulasi rincian komisi operator/karyawan produksi berdasarkan aktivitas mesin dan jumlah hasil produksi, serta mengelola proses pencairan/pembayaran komisi terintegrasi.
    Penjelasan UI : Tampilan awal terdiri dari bagian Filter Atas dan 2 Tab Navigasi Utama:
      1. Filter Atas : Dropdown/Lookup Employee (Select Employee), DatePicker (From - To), tombol Refresh, dan checkbox Show COMPLETE only.
      2. Tab Navigasi :
        * Tab Commission : Menampilkan tabel daftar komisi dengan kolom Date, Production, Paid, Payment Date, Status, Machine, Commission, Qty, Amount, Total, Notes, tombol Print, serta tombol [ Pay Selected ] di sudut bawah untuk mencairkan komisi karyawan yang dipilih.
        * Tab Payment : Menampilkan tombol Print Payment Doc., tabel riwayat pembayaran komisi (Id, Date, Account, Total, Notes) pada atas, serta tabel Payment Detail (Production Id, Commission, Qty, Amount, Total, Total_Detail) pada bawah.

- Production Report
  - Daily Production Report `daily-production-report`
    Komponen: Date, Production_Id, Product_Name, Batch_No, Qty_Planned_Kg, Qty_Actual_Kg, Efficiency_Percent, Machine_ID, Status, Notes
    Fungsi: Menyajikan laporan harian rekapitulasi seluruh aktivitas produksi dan pencapaian efisiensi output produksi.
    Penjelasan UI: Tampilan berupa table analitik Read-Only dilengkapi Summary Card (Total Batch Diproduksi, Total Tonase, Rata-rata Efisiensi) di bagian atas, serta filter tanggal produksi, mesin, dan tipe produk.

  - Daily Production Base Report `daily-production-base-report`
    Komponen: Date, Production_Id, Base_Name, Batch_No, Machine_ID, Target_Base_Kg, Actual_Base_Kg, Variance_Kg, Operator, Notes
    Fungsi: Laporan harian khusus pemantauan hasil pencapaian pengolahan adonan dasar (base) dan selisih kuantitas target.
    Penjelasan UI: Tampilan berupa table analitik Read-Only dengan indikator warna deviasi (Variance KG) serta filter tanggal produksi, mesin, dan jenis base.

  - Daily Production Result Report `daily-production-result-report`
    Komponen: Date, Production_Id, Product_Name, Batch_No, Total_Output_Pcs, Total_Output_Kg, Reject_Qty_Kg, Yield_Percent, Notes
    Fungsi: Laporan rekapitulasi harian kuantitas produk jadi yang berhasil diproduksi beserta persentase perolehan (yield).
    Penjelasan UI: Tampilan berupa table Read-Only dengan ringkasan total produk jadi (Pcs/Kg) dan persentase afval/reject, dilengkapi filter tanggal dan kelompok produk.

  - Daily Production Result Batch Report (STBJ) `daily-production-result-batch-report`
    Komponen: Date, STBJ_No, Production_Id, Batch_No, Warehouse_Target, Total_Qty_Received_Pcs, Total_Weight_Kg, User_ID, Status
    Fungsi: Laporan penyerahan dan penerimaan harian hasil produksi ke gudang berbasis dokumen STBJ.
    Penjelasan UI: Tampilan berupa table verifikasi penerimaan barang fisik di gudang dengan filter tanggal STBJ, nomor batch, dan gudang tujuan.

  - Daily Production Commission `daily-production-commission-report`
    Komponen: Date, Employee_ID, Employee_Name, Machine_ID, Total_Batch_Handled, Total_Qty_Produced, Total_Commission_Amount, Notes
    Fungsi: Laporan harian rekapitulasi perolehan nilai komisi operator berdasarkan jumlah batch dan volume produksi.
    Penjelasan UI: Tampilan berupa table Read-Only terurut berdasarkan nama operator/karyawan dilengkapi ringkasan akumulasi komisi harian dan filter rentang tanggal.

  - Daily Production Material Cost Report `daily-production-material-cost-report`
    Komponen: Date, Production_Id, Material_ID, Material_Name, Qty_Used, UOM, Unit_Cost, Total_Material_Cost, Batch_No
    Fungsi: Laporan harian pemakaian bahan baku/penolong beserta total nilai biaya bahan pada setiap batch produksi.
    Penjelasan UI: Tampilan berupa table breakdown biaya bahan baku per nomor adonan/batch, dilengkapi filter tanggal, material ID, dan fitur ekspor data.

  - Daily Production Result COGS Report `daily-production-result-cogs-report`
    Komponen: Date, Production_Id, Product_Name, Batch_No, Total_Material_Cost, Overhead_Cost, Labor_Cost, Total_COGS, COGS_Per_Kg, COGS_Per_Pcs
    Fungsi: Laporan analisis perhitungan Harga Pokok Produksi (HPP/COGS) harian produk jadi secara komprehensif.
    Penjelasan UI: Tampilan berupa table analitik COGS yang mengurai komponen Biaya Material, Tenaga Kerja, dan Overhead per Kg/Pcs produk jadi dengan filter tanggal dan nama produk.

  - Daily Production Packaging Report `daily-production-packaging-report`
    Komponen: Date, Production_Id, Package_Type, Qty_Used_Pcs, Qty_Damaged_Pcs, Unit_Packaging_Cost, Total_Packaging_Cost, Notes
    Fungsi: Laporan harian penggunaan dan efisiensi material kemasan beserta analisis tingkat kemasan rusak (damage rate).
    Penjelasan UI: Tampilan berupa table laporan pemakaian kemasan (kaleng/galon/pail) dilengkapi persentase kemasan rusak dan filter jenis kemasan.

  - Daily Production Material Cost Recap Report `daily-production-material-cost-recap-report`
    Komponen: Period, Product_Group, Total_Production_Count, Total_Material_Cost_Accumulated, Average_Cost_Per_Kg, Variance_To_Standard
    Fungsi: Laporan rekapitulasi akumulasi nilai biaya bahan baku dalam periode harian/mingguan/bulanan terhadap standar biaya.
    Penjelasan UI: Tampilan berupa ringkasan eksekutif berbasis tabel dan grafik garis tren konsumsi biaya material dengan filter periode dan grup produk.

- Realisasi Jadwal Base `#`
  - Realisasi Jadwal Base List `realisasi-jadwal-base-list`
    Komponen : Doc_ID, Date, User_ID, Prod_Date, Shift, Type, Total_Product_Count, Total_Realisasi_KG, Status, Notes
    Fungsi : Menampilkan dan mengelola daftar seluruh dokumen realisasi pelaksanaan jadwal produksi bahan basis (base) beserta ringkasan total hasil produksi.
    Penjelasan UI : Tampilan awal berupa table data grid modern menampilkan Doc_ID, Date, Prod_Date, Shift, Type, Total_Product_Count, Total_Realisasi_KG, User_ID, Status, dan Notes beserta filter pencarian cepat, filter rentang tanggal, shift, dan type. Tombol [ + Tambah Realisasi ] akan memunculkan Pop-up Modal Form / Drawer Modern dengan struktur:
      1. Info Dokumen (Top Card) :
         - Doc ID (Auto-generated, Read-only badge)
         - Date (DatePicker)
         - User ID (Read-only / User login aktif)
      2. Parameter & Auto-Generate (Filter Bar) :
         - Prod. Date (DatePicker)
         - Shift (Dropdown: Shift 1, 2, 3)
         - Type (Dropdown: Water Based, Solvent Based, dll.)
         - Tombol [ Load / Tarik Jadwal ] untuk mengisi tabel detail secara otomatis dari Rencana Jadwal.
      3. Data Grid Detail (Dynamic Editable Table) :
         - Kolom: Nama Product (Autocomplete Lookup), Batch No., Mesin (Dropdown), Total Basis (KG), Realisasi (KG), Jam Mulai (TimePicker), Jam Selesai (TimePicker), Operator (Multi-select/Dropdown), dan Keterangan.
         - Mendukung fitur tambah/hapus baris manual (Add/Remove Row).
      4. Catatan & Aksion (Footer Form) :
         - Field Textarea Notes (Catatan Tambahan).
         - Tombol [ Batal ] dan [ Simpan Realisasi ].
    Untuk Edit memunculkan Pop-up Modal Form modern dengan data yang telah terisi untuk diperbarui. Hapus memunculkan alert konfirmasi modal.

  - Realisasi Jadwal Base Report `realisasi-jadwal-base-report`
    Komponen : Doc_ID, Date, Prod_Date, Shift, Type, Nama_Product, Batch_No, Mesin, Total_Basis_KG, Realisasi_KG, Variance_KG, Efficiency_Percent, Mulai, Selesai, Operator, Notes
    Fungsi : Menyajikan laporan analitik rekapitulasi realisasi jadwal produksi base, selisih pencapaian target (variance KG), serta persentase efisiensi produksi.
    Penjelasan UI : Tampilan awal berupa table analitik interaktif (Read-Only) menampilkan Doc_ID, Prod_Date, Shift, Type, Nama_Product, Batch_No, Mesin, Total_Basis_KG, Realisasi_KG, Variance_KG (dengan warna indikator hijau/merah), Efficiency_Percent, Jam Operasional (Mulai - Selesai), Operator, dan Keterangan. Dilengkapi dengan filter interaktif (Prod Date, Shift, Type, Mesin, Operator), tombol Export Excel/PDF, serta summary card total produksi di bagian atas tabel.

- Realisasi Jadwal CM `#`
  - Realisasi Jadwal CM List `realisasi-jadwal-cm-list`
    Komponen : Doc_ID, Date, User_ID, Prod_Date, Shift, Type, Schedule_Category, Total_Product_Count, Total_Realisasi_KG, Status, Notes
    Fungsi : Menampilkan dan mengelola daftar seluruh dokumen realisasi pelaksanaan jadwal produksi pencampuran warna (Color Matching - CM) berdasarkan tanggal produksi, shift, tipe adonan, dan kategori jadwal lokasi (Pusat/Cabang).
    Penjelasan UI : Tampilan awal berupa table data grid modern menampilkan Doc_ID, Date, Prod_Date, Shift, Type, Schedule_Category (Jadwal), Total_Product_Count, Total_Realisasi_KG, User_ID, Status, dan Notes beserta filter pencarian cepat, filter rentang tanggal, shift, type, dan lokasi jadwal. Tombol [ + Tambah Realisasi CM ] akan memunculkan Pop-up Modal Form / Drawer Modern berstruktur:
      1. Info Dokumen (Top Card) :
         - Doc ID (Auto-generated, Read-only badge)
         - Date (DatePicker)
         - User ID (Read-only / User login aktif)
      2. Parameter & Auto-Generate (Filter Bar) :
         - Prod. Date (DatePicker)
         - Shift (Dropdown: Shift 1, 2, 3)
         - Type (Dropdown: Water Based, Solvent Based, dll.)
         - Jadwal (Dropdown: Pusat, Cabang, dll.)
         - Tombol [ Load / Tarik Jadwal ] untuk mengisi tabel detail secara otomatis dari Rencana Jadwal CM.
      3. Data Grid Detail (Dynamic Editable Table) :
         - Kolom: Nama Product (Autocomplete Lookup), Kode Warna, Batch No., Mesin (Dropdown), Total Basis (KG), Realisasi (KG), Jam Mulai (TimePicker), Jam Selesai (TimePicker), Operator (Multi-select/Dropdown), Jadwal (Ref Location), dan Keterangan.
         - Mendukung fitur tambah/hapus baris manual (Add/Remove Row).
      4. Catatan & Aksion (Footer Form) :
         - Field Textarea Notes (Catatan Tambahan).
         - Tombol [ Batal ] dan [ Simpan Realisasi CM ].
    Untuk Edit memunculkan Pop-up Modal Form modern dengan data yang telah terisi untuk diperbarui. Hapus memunculkan alert konfirmasi modal.

  - Realisasi Jadwal CM Report `realisasi-jadwal-cm-report`
    Komponen : Doc_ID, Date, Prod_Date, Shift, Type, Schedule_Category, Nama_Product, Kode_Warna, Batch_No, Mesin, Total_Basis_KG, Realisasi_KG, Variance_KG, Efficiency_Percent, Mulai, Selesai, Operator, Notes
    Fungsi : Menyajikan laporan analitik rekapitulasi realisasi jadwal produksi Color Matching (CM), evaluasi pencapaian target per kode warna, selisih kuantitas basis (variance), serta efisiensi waktu operasional.
    Penjelasan UI : Tampilan awal berupa table analitik interaktif (Read-Only) menampilkan Doc_ID, Prod_Date, Shift, Type, Schedule_Category, Nama_Product, Kode_Warna, Batch_No, Mesin, Total_Basis_KG, Realisasi_KG, Variance_KG (dengan warna indikator visual), Efficiency_Percent, Jam Operasional (Mulai - Selesai), Operator, dan Keterangan. Dilengkapi dengan filter interaktif (Prod Date, Shift, Type, Jadwal, Kode Warna, Mesin, Operator), tombol Export Excel/PDF, serta summary card total pencampuran warna di bagian atas tabel.

- Realisasi Jadwal Canning dan Packing `#`
  - Realisasi Jadwal Canning dan Packing List `realisasi-jadwal-canning-packing-list`
    Komponen : Doc_ID, Date, User_ID, Prod_Date, Shift, Type, Schedule_Category, Total_Product_Count, Total_Realisasi_Canning_KG, Status, Notes
    Fungsi : Menampilkan dan mengelola daftar seluruh dokumen realisasi pengalengan (canning) dan pengemasan (packing) produk berdasarkan varian kemasan, berat, dan operator pelaksana.
    Penjelasan UI : Tampilan awal berupa table data grid modern menampilkan Doc_ID, Date, Prod_Date, Shift, Type, Schedule_Category (Jadwal), Total_Product_Count, Total_Realisasi_Canning_KG, User_ID, Status, dan Notes beserta filter pencarian cepat, filter rentang tanggal, shift, type, dan lokasi jadwal. Tombol [ + Tambah Realisasi Canning & Packing ] akan memunculkan Pop-up Modal Form / Drawer Modern berstruktur:
      1. Info Dokumen (Top Card) :
         - Doc ID (Auto-generated, Read-only badge)
         - Date (DatePicker)
         - User ID (Read-only / User login aktif)
      2. Parameter & Auto-Generate (Filter Bar) :
         - Prod. Date (DatePicker)
         - Shift (Dropdown: Shift 1, 2, 3)
         - Type (Dropdown: Water Based, Solvent Based, dll.)
         - Jadwal (Dropdown: Pusat, Cabang, dll.)
         - Tombol [ Load / Tarik Jadwal ] untuk mengisi tabel detail secara otomatis dari Rencana Jadwal Canning & Packing.
      3. Data Grid Detail (Dynamic Editable Table) :
         - Identitas & Komposisi: Kode Warna, Warna, Batch No., Basis (KG), Realisasi CM (KG).
         - Detail Kemasan (PCS): Kaleng 0.1L, Kaleng 0.2L, Kaleng 0.4L, Kaleng 0.45L, Kaleng 0.9L, Kaleng (PCS), Galon (PCS), Pail (PCS), Liter (PCS), Kaleng 500ML (PCS), Kaleng 1L (PCS).
         - Realisasi & Waktu Operasional: Realisasi Canning (KG), Tgl Kemas (DatePicker), Tgl Selesai (DatePicker), Sisa Hasil Kemas.
         - Penimbangan & Operator: Berat Awal, Berat Akhir, Selisih, Operator Canning (Dropdown/Multi-select), Operator Packing (Dropdown/Multi-select), Jadwal (Ref Location), dan Keterangan.
         - Mendukung fitur tambah/hapus baris manual (Add/Remove Row).
      4. Catatan & Aksion (Footer Form) :
         - Field Textarea Notes (Catatan Tambahan).
         - Tombol [ Batal ] dan [ Simpan Realisasi Canning & Packing ].
    Untuk Edit memunculkan Pop-up Modal Form modern dengan data yang telah terisi untuk diperbarui. Hapus memunculkan alert konfirmasi modal.

  - Realisasi Jadwal Canning dan Packing Report `realisasi-jadwal-canning-packing-report`
    Komponen : Doc_ID, Date, Prod_Date, Shift, Type, Schedule_Category, Kode_Warna, Warna, Batch_No, Basis_KG, Realisasi_CM_KG, Detail_Kemasan_Pcs_Summary, Realisasi_Canning_KG, Yield_Percent, Berat_Awal, Berat_Akhir, Selisih_KG, Operator_Canning, Operator_Packing, Notes
    Fungsi : Menyajikan laporan analitik rekapitulasi hasil pengalengan dan pengemasan, evaluasi persentase perolehan (*yield*), selisih penimbangan bahan, dan performa operator.
    Penjelasan UI : Tampilan awal berupa table analitik interaktif (Read-Only) menampilkan Doc_ID, Prod_Date, Shift, Type, Schedule_Category, Kode_Warna, Warna, Batch_No, Basis_KG, Realisasi_CM_KG, Rincian Kemasan (Kaleng/Galon/Pail), Realisasi_Canning_KG, Yield_Percent, Berat Awal, Berat Akhir, Selisih (dengan warna indikator visual), Operator Canning, Operator Packing, dan Keterangan. Dilengkapi dengan filter interaktif (Prod Date, Shift, Type, Jadwal, Kode Warna, Operator), tombol Export Excel/PDF, serta summary card total hasil pengemasan di bagian atas tabel.

- Realisasi Jadwal Base per Mesin `#`
  - Realisasi Jadwal Base per Mesin List `realisasi-jadwal-base-per-mesin-list`
    Komponen : Doc_ID, Date, User_ID, Prod_Date, Shift, Machine, Type, Total_Product_Count, Total_Realisasi_KG, Status, Notes
    Fungsi : Menampilkan dan mengelola pencatatan realisasi produksi bahan basis (base) secara spesifik per mesin, termasuk pelacakan jam/waktu pada setiap tahapan proses produksi (pengisian air, proses giling, cek kehalusan, cek akhir, hingga penurunan).
    Penjelasan UI : Tampilan awal berupa table data grid modern menampilkan Doc_ID, Date, Prod_Date, Shift, Machine, Type, Total_Product_Count, Total_Realisasi_KG, User_ID, Status, dan Notes beserta filter pencarian cepat, filter rentang tanggal, shift, mesin, dan type. Tombol [ + Tambah Realisasi Base per Mesin ] akan memunculkan Pop-up Modal Form / Drawer Modern berstruktur:
      1. Seksi Info Dokumen (Top Card) :
         - Doc ID (Auto-generated, Read-only badge)
         - Date (DatePicker)
         - User ID (Read-only / User login aktif)
      2. Seksi Parameter & Auto-Generate (Filter Bar) :
         - Prod. Date (DatePicker)
         - Shift (Dropdown: Shift 1, 2, 3)
         - Machine (Dropdown pilihan mesin spesifik)
         - Type (Dropdown: Water Based, Solvent Based, dll.)
         - Tombol [ Load / Tarik Jadwal ] untuk mengisi tabel detail secara otomatis dari Rencana Jadwal Base per Mesin.
      3. Seksi Data Grid Detail (Dynamic Editable Table) :
         - Informasi Produk & Kuantitas: Nama Product (Autocomplete Lookup), Batch No., Total Basis (KG), Realisasi (KG).
         - Detail Tahapan Waktu Proses: Pengisian Air - Start (TimePicker), Pengisian Air - Finish (TimePicker), Start Proses (TimePicker), Cek Kehalusan - Start (TimePicker), Cek Kehalusan - Finish (TimePicker), Cek Akhir - Start (TimePicker), Cek Akhir - Finish (TimePicker), Penurunan (TimePicker).
         - Personel & Catatan: Operator (Dropdown/Multi-select) dan Keterangan.
         - Mendukung fitur tambah/hapus baris manual (Add/Remove Row).
      4. Seksi Catatan & Aksion (Footer Form) :
         - Field Textarea Notes (Catatan Tambahan).
         - Tombol [ Batal ] dan [ Simpan Realisasi per Mesin ].
    Untuk Edit memunculkan Pop-up Modal Form modern dengan data yang telah terisi untuk diperbarui. Hapus memunculkan alert konfirmasi modal.

  - Realisasi Jadwal Base per Mesin Report `realisasi-jadwal-base-per-mesin-report`
    Komponen : Doc_ID, Date, Prod_Date, Shift, Machine, Type, Nama_Product, Batch_No, Total_Basis_KG, Realisasi_KG, Duration_Pengisian_Air, Duration_Proses_Giling, Duration_Cek_Kehalusan, Duration_Cek_Akhir, Duration_Total_Process, Operator, Notes
    Fungsi : Menyajikan laporan analitik rekapitulasi realisasi produksi per mesin, durasi waktu per tahapan proses (cycle time analysis), efisiensi penggunaan mesin, serta evaluasi pencapaian kuantitas.
    Penjelasan UI : Tampilan awal berupa table analitik interaktif (Read-Only) menampilkan Doc_ID, Prod_Date, Shift, Machine, Type, Nama_Product, Batch_No, Total_Basis_KG, Realisasi_KG, Rincian Jam Tahapan Proses (Start-Finish Air, Giling, Cek Kehalusan, Cek Akhir, Penurunan), Total Durasi Proses (Jam/Menit), Operator, dan Keterangan. Dilengkapi dengan filter interaktif (Prod Date, Shift, Mesin, Type, Operator), tombol Export Excel/PDF, serta summary card total jam kerja mesin dan total output produksi di bagian atas tabel.

- Realisasi Jadwal Pasta `#`
  - Realisasi Jadwal Pasta List `realisasi-jadwal-pasta-list`
    Komponen : Doc_ID, Date, User_ID, Type, Total_Pasta_Count, Total_Realisasi_KG, Status, Notes
    Fungsi : Menampilkan dan mengelola pencatatan realisasi produksi pewarna/pasta (pigment paste) berdasarkan alokasi jadwal, waktu tunggu, durasi pengerjaan (lead time), serta kepatuhan batas waktu (dateline).
    Penjelasan UI : Tampilan awal berupa table data grid modern menampilkan Doc_ID, Date, Type, Total_Pasta_Count, Total_Realisasi_KG, User_ID, Status, dan Notes beserta filter pencarian cepat, filter rentang tanggal, dan type. Tombol [ + Tambah Realisasi Pasta ] akan memunculkan Pop-up Modal Form / Drawer Modern berstruktur:
      1. Seksi Info Dokumen (Top Card) :
         - Doc ID (Auto-generated, Read-only badge)
         - Date (DatePicker)
         - User ID (Read-only / User login aktif)
         - Type (Dropdown: Water Based, Solvent Based, dll.)
      2. Seksi Data Grid Detail (Dynamic Editable Table) :
         - Identitas & Waktu Jadwal: Date (DatePicker), Shift (Dropdown), Kode Pasta (Lookup/Autocomplete), Name (Read-only Pasta Name), Batch No., Mesin (Dropdown), Tgl Jadwal (DatePicker), Lead Time, Dateline (DatePicker), Status.
         - Kuantitas & Pencapaian: Total Basis (KG), Realisasi (KG), Selisih (KG - Auto Calculated), Percentage (% - Auto Calculated).
         - Operasional & Waktu: Mulai (TimePicker), Selesai (TimePicker), Waktu Tunggu (Jam/Menit), Operator (Multi-select/Dropdown).
         - Mendukung fitur tambah/hapus baris manual (Add/Remove Row).
      3. Seksi Catatan & Aksion (Footer Form) :
         - Field Textarea Notes (Catatan Tambahan).
         - Tombol [ Batal ] dan [ Simpan Realisasi Pasta ].
    Untuk Edit memunculkan Pop-up Modal Form modern dengan data yang telah terisi untuk diperbarui. Hapus memunculkan alert konfirmasi modal.

  - Realisasi Jadwal Pasta Report `realisasi-jadwal-pasta-report`
    Komponen : Doc_ID, Date, Shift, Type, Kode_Pasta, Name, Batch, Mesin, Total_Basis_KG, Realisasi_KG, Selisih_KG, Percentage, Mulai, Selesai, Waktu_Tunggu, Operator, Tgl_Jadwal, Lead_Time, Dateline, Status_Pencapaian, Notes
    Fungsi : Menyajikan laporan analitik rekapitulasi produksi pasta, analisis selisih kuantitas & persentase capaian, evaluasi ketepatan waktu pengerjaan (Lead Time vs Dateline), serta waktu tunggu produksi.
    Penjelasan UI : Tampilan awal berupa table analitik interaktif (Read-Only) menampilkan Doc_ID, Date, Shift, Type, Kode_Pasta, Name, Batch, Mesin, Total_Basis_KG, Realisasi_KG, Selisih_KG, Percentage (dengan warna indikator visual), Jam Operasional (Mulai - Selesai), Waktu Tunggu, Operator, Tgl Jadwal, Lead Time, Dateline, Status Pencapaian, dan Notes. Dilengkapi dengan filter interaktif (Rentang Tanggal, Shift, Type, Kode Pasta, Mesin, Operator, Status), tombol Export Excel/PDF, serta summary card total output pasta dan rata-rata lead time di bagian atas tabel.
    
- Monitoring Mesin Grinding `#`
  - Monitoring Mesin Grinding List `monitoring-mesin-grinding-list`
    Komponen : Doc_ID, Date, User_ID, Prod_Date, Shift, Machine, Type, Total_Product_Count, Total_Tonase, Status, Notes
    Fungsi : Menampilkan dan mengelola pencatatan pemantauan operasional mesin giling (grinding) secara detail, termasuk parameter teknis mesin (kecepatan gear pump & blade), siklus proses, jam kerja, dan hasil uji kehalusan (micron/u).
    Penjelasan UI : Tampilan awal berupa table data grid modern menampilkan Doc_ID, Date, Prod_Date, Shift, Machine, Type, Total_Product_Count, Total_Tonase, User_ID, Status, dan Notes beserta filter pencarian cepat, filter rentang tanggal, shift, mesin, dan type. Tombol [ + Tambah Monitoring Grinding ] akan memunculkan Pop-up Modal Form / Drawer Modern berstruktur:
      1. Seksi Info Dokumen (Top Card) :
         - Doc ID (Auto-generated, Read-only badge)
         - Date (DatePicker)
         - User ID (Read-only / User login aktif)
      2. Seksi Parameter & Auto-Generate (Filter Bar) :
         - Prod. Date (DatePicker)
         - Shift (Dropdown: Shift 1, 2, 3)
         - Machine (Dropdown pilihan mesin giling)
         - Type (Dropdown: Water Based, Solvent Based, dll.)
         - Tombol [ Load / Tarik Jadwal ] untuk mengisi tabel detail secara otomatis dari Rencana Jadwal/Produksi.
      3. Seksi Data Grid Detail (Dynamic Editable Table) :
         - Identitas Produk & Mesin: Date (DatePicker), Nama Product (Autocomplete Lookup), Batch No., Tonase (KG/Ton), No. Mesin (Dropdown/Read-only).
         - Operasional & Parameter Teknis: Mulai (TimePicker), Finish (TimePicker), Ke- (Siklus/Pass ke-), Jam (Waktu Pengamatan), Speed Gear Pump (RPM/Setting), Speed Blade (RPM/Setting).
         - Hasil Uji & Personel: Hasil (u) / Kehalusan Micron, Operator (Multi-select/Dropdown), dan Notes (Keterangan Baris).
         - Mendukung fitur tambah/hapus baris manual (Add/Remove Row).
      4. Seksi Catatan & Aksion (Footer Form) :
         - Field Textarea Notes (Catatan Tambahan Dokumen).
         - Tombol [ Batal ] dan [ Simpan Monitoring Grinding ].
    Untuk Edit memunculkan Pop-up Modal Form modern dengan data yang telah terisi untuk diperbarui. Hapus memunculkan alert konfirmasi modal.

  - Monitoring Mesin Grinding Report `monitoring-mesin-grinding-report`
    Komponen : Doc_ID, Date, Prod_Date, Shift, Machine, Type, Nama_Product, Batch_No, Tonase, No_Mesin, Mulai, Finish, Siklus_Ke, Jam_Pengamatan, Speed_Gear_Pump, Speed_Blade, Hasil_Micron, Operator, Notes
    Fungsi : Menyajikan laporan analitik rekapitulasi pencatatan monitoring mesin giling, evaluasi konsistensi kecepatan mesin (Speed Gear Pump & Blade), jumlah siklus giling (pass count), serta pencapaian tingkat kehalusan hasil gilingan (micron/u).
    Penjelasan UI : Tampilan awal berupa table analitik interaktif (Read-Only) menampilkan Doc_ID, Prod_Date, Shift, Machine, Type, Nama_Product, Batch_No, Tonase, No_Mesin, Jam Operasional (Mulai - Finish), Siklus Ke-, Jam Pengamatan, Speed Gear Pump, Speed Blade, Hasil Kehalusan (u), Operator, dan Notes. Dilengkapi dengan filter interaktif (Prod Date, Shift, Mesin, Type, Nama Product, Operator), tombol Export Excel/PDF, serta summary card total tonase digiling dan rata-rata tingkat kehalusan (u) di bagian atas tabel.

- Production Material Check Stock `production-material-check-stock`
  Komponen: Schedule ID, Product ID, Product Name, Total Qty, Current Stock, UOM, Warehouse, Shortage Qty, Stock Status
  Fungsi: Memeriksa dan menguji ketersediaan stok bahan baku di gudang terhadap kebutuhan rencana produksi sebelum jadwal dijalankan.
  Penjelasan UI: Tampilan awal berupa dashboard tabel simulasi ketersediaan stok yang membandingkan Total Qty kebutuhan jadwal dengan Current Stock. Kolom Stock Status dilengkapi indikator warna visual otomatis: Hijau (Sufficient / Cukup) dan Merah (Shortage / Kurang) beserta kalkulasi selisih kekurangan (Shortage Qty). Filter interaktif berdasarkan Schedule ID, Warehouse, dan Stock Status.

- Production Stock Level `production-stock-level`
  Komponen: Product ID, Name, Warehouse, Current Stock, Reserved Stock, Available Stock, UOM
  Fungsi: Memantau saldo tingkat stok bahan baku, barang setengah jadi (WIP), dan material penolong secara real-time di lokasi gudang produksi.
  Penjelasan UI: Tampilan berupa data grid interaktif menampilkan saldo fisik (Current Stock), stok yang terkunci jadwal produksi (Reserved Stock), dan stok bebas yang siap pakai (Available Stock). Dilengkapi filter lokasi gudang, pencarian produk, serta tombol penyegaran data (Refresh).

- STBJ `stbj`
  Komponen: STBJ No, Date, Production Id, Batch No, From Production Line, To Warehouse ID, Total Qty Pcs, Total Weight Kg, Received By, Notes, Status
  Fungsi: Mengelola penerbitan dokumen Surat Tanda Barang Jadi (STBJ) sebagai bukti serah terima resmi produk hasil produksi dari bagian pabrik ke gudang.
  Penjelasan UI: Tampilan awal berupa table data grid memuat daftar seluruh dokumen STBJ. Tombol Tambah memunculkan Modal Form berstruktur Header (STBJ No, Date, Production Ref, Batch No, From Line, To Warehouse) dan Detail Items Kuantitas & Berat. Dilengkapi tombol aksi cetak dokumen/barcode STBJ fisik dan konfirmasi verifikasi serah terima.

- Product Report
  - Product Stock `product-stock`
    Komponen: Product ID, Name, Category, Warehouse, Current Stock, Reserved Stock, Available Stock, UOM
    Fungsi: Menyajikan laporan saldo posisi fisik dan ketersediaan stok produk terkini per gudang.
    Penjelasan UI: Tabel analitik Read-Only dengan filter gudang, kategori produk, dan pencarian nama barang.

  - Product Stock Summary `product-stock-summary`
    Komponen: Product Group, Category, Total Item Count, Total Quantity, Total Stock Valuation (IDR), UOM
    Fungsi: Menyajikan laporan ringkasan akumulasi volume kuantitas dan total nilai finansial (modal) stok produk.
    Penjelasan UI: Tabel ringkasan berbasis grup produk dilengkapi summary card total akumulasi nilai aset stok di bagian atas tabel.

  - Product Stock Daily Summary `product-stock-daily-summary`
    Komponen: Date, Product ID, Name, Initial Stock, In Qty (Production/Receipt), Out Qty (Delivery/Sales), Final Stock, UOM
    Fungsi: Laporan harian rekapitulasi pergerakan mutasi stok (Stok Awal + Masuk - Keluar = Stok Akhir).
    Penjelasan UI: Tabel laporan pergerakan harian dilengkapi filter rentang tanggal dan nama barang.

  - Product Stock Quick View `product-stock-quick-view`
    Komponen: Product ID, Name, Warehouse Name, Available Qty, UOM, Last Updated
    Fungsi: Menyediakan fasilitas pencarian dan peninjauan cepat ketersediaan stok produk antar cabang/gudang.
    Penjelasan UI: Layar pencarian cepat (Quick Lookup) dengan pencarian teks responsif yang langsung menampilkan ketersediaan stok di seluruh lokasi gudang.

  - Product Price Info `product-price-info`
    Komponen: Product ID, Name, Category, Selling Price, Base Cost (COGS), Margin (%), Currency
    Fungsi: Menyajikan informasi daftar harga jual standar dan patokan nilai modal/HPP produk.
    Penjelasan UI: Tabel daftar harga Read-Only dengan filter kategori barang dan persentase margin.

  - Product Stock Track Repot `product-stock-track-report`
    Komponen: Trans Date, Product ID, Name, Reference Doc No, Transaction Type, In Qty, Out Qty, Balance Qty, User ID
    Fungsi: Menyajikan laporan jejak rekam mutasi (audit trail) transaksi pergerakan stok per produk.
    Penjelasan UI: Tabel kartu stok kronologis yang menampilkan riwayat setiap dokumen transaksi yang mengubah stok.

  - Product Stock Track Date Report `product-stock-track-date-report`
    Komponen: Period Date, Product ID, Name, Reference Doc No, Transaction Type, In Qty, Out Qty, Balance Qty, Warehouse
    Fungsi: Menyajikan laporan kartu stok mutasi pergerakan barang dalam batasan periode tanggal tertentu.
    Penjelasan UI: Tabel analitik mutasi stok berbasis rentang tanggal (From-To Date) dan filter lokasi gudang.

  - Product Stock Track with Price Report `product-stock-track-with-price-report`
    Komponen: Trans Date, Product ID, Name, Ref Doc No, Trans Type, In Qty, Out Qty, Balance Qty, Unit Cost, Total Valuation
    Fungsi: Menyajikan laporan kartu stok mutasi pergerakan barang yang dilengkapi dengan kalkulasi nilai finansial rupiahnya.
    Penjelasan UI: Tabel mutasi stok finansial yang mengalikan volume kuantitas mutasi dengan nilai modal/HPP barang.
 
  - Product Stock Minus Report  `product-stock-minus-report`
    Komponen: Product ID, Name, Warehous  e, Current Stock, UOM, Last Trans Date, Status
    Fungsi: Laporan audit khusus mendeteksi dan menampilkan daftar barang yang mengalami anomali stok bernilai negatif.
    Penjelasan UI: Tabel peringatan Read-Only yang secara otomatis memfilter hanya barang berstatus stok minus (< 0) dengan warna penanda merah.

  - Product Min Max Stock Check `product-min-max-stock-check`
    Komponen: Product ID, Name, Warehouse, Current Stock, Min Stock, Max Stock, Safety Stock, Reorder Qty, Status Alert
    Fungsi: Memantau kondisi stok terhadap batas Minimum-Maksimum untuk pencegahan kehabisan stok (stockout) atau kelebihan stok (overstock).
    Penjelasan UI: Tabel kriteria batas stok dilengkapi badge indikator visual status: Warning Below Min (Merah), Normal (Hijau), Over Max (Kuning).

  - Product COGS Monthly Report `product-cogs-monthly-report`
    Komponen: Month/Year Period, Product ID, Name, Average COGS Unit, Total Manufactured Qty, Total COGS Valuation
    Fungsi: Menyajikan laporan rekapitulasi rata-rata nilai Harga Pokok Penjualan/Produksi (COGS) bulanan per produk.
    Penjelasan UI: Tabel laporan bulanan terakumulasi dilengkapi filter periode bulan/tahun dan perbandingan tren COGS.

  - Product COGS Daily Report `product-cogs-daily-report`
    Komponen: Date, Production Ref, Product ID, Name, Daily COGS Unit, Batch Qty, Total Valuation
    Fungsi: Menyajikan laporan rincian fluktuasi pergerakan nilai HPP/COGS harian hasil produksi.
    Penjelasan UI: Tabel harian Read-Only yang menampilkan rincian HPP dari tiap dokumen batch produksi harian.

## Production Process
- Dashboard Production Process `production-process-dashboard`
  Komponen : Active_Batch_In_Progress_Count, Total_Base_Completed_Today, Total_CM_Completed_Today, QC_Pass_Rate_Percent, Rework_Adu_Count (SPKP/SPPBJ ADU), Active_Packaging_Lines_Count, Chart_Batch_Status_Distribution (Base -> CM -> QC -> Packing), Chart_Hourly_Process_Yield, Table_Active_Rework_Batches_Notification
  Fungsi : Menyajikan gambaran umum secara visual dan real-time mengenai status pengerjaan batch di lantai pabrik, rasio kelulusan QC, jumlah batch yang sedang mengalami perbaikan (Rework/Adu Base & CM), performa per jam tiap tahapan proses, serta indikator antrean proses pengemasan (filling/packing).
  Penjelasan UI : Tampilan awal berupa dashboard interaktif berisi ringkasan widget angka (card stat) untuk metrik utama proses, grafik donat/batang distribusi status batch yang sedang berjalan (In-Progress Base, CM, QC Pending, Packing), grafik garis tren throughput output per jam, serta tabel peringatan cepat untuk batch yang berstatus Rework/ADU. Halaman bersifat Read-Only dan dilengkapi filter periode tanggal, shift, mesin/lini produksi, dan tipe adonan.

- SPKP (Surat Perintah Kerja Produksi Base) `production-process-spkp` 
  Komponen Tampilan Awal : Production ID, Jadwal, No. SPKP, No. Batch, Date, Created by, Product Name, Proses BASE, Selesai BASE, Machine, Tipe Produk, Formulasi, FK, Basis, Required, Recanning.
  Fungsi : Mengelola dan mengeksekusi dokumen perintah kerja pembuatan adonan dasar (base) di lini produksi yang mencakup rincian penimbangan bahan baku, instruksi pengerjaan, realisasi STBJ, pencatatan hasil pengujian QC, hingga riwayat penyesuaian (adjustment).
  Penjelasan UI :
  Tampilan awal berupa table data grid yang menampilkan komponen halaman awal secara lengkap. Halaman ini tidak memiliki tombol Tambah. Terdapat filter Tanggal Awal, Tanggal Akhir, dan dropdown Tipe Produk dengan pilihan: Water Based, Solvent Based, Lain-Lain, Kemasan, TM, MP, Labeling, Pasta Printing, WB ADU.
  Pengguna mengklik baris data pada tabel untuk membuka tab baru Halaman Proses (Detail) yang terbagi menjadi dua area utama:
  Bagian Atas (Header Informasi) : Menampilkan Tgl. Jadwal, No. SPKP, Kelompok Produk, Nama Produk, Batch No, Tipe Product, Formulasi, Basis, Total Basis, Status, Notes, Production ID, dan Jadwal.
  Bagian Bawah (6 Tab Navigasi) :
  Tab Bahan Baku : Berisi table data komponen Name, Tonase, Urutan Proses, Batch No., Kebutuhan, UOM, Kemasan, Checklist, Realisasi, UOM, Change Batch, Warehouse. Dilengkapi dengan tombol aksi [ Simpan ], [ Leader Formulasi ], [ Complete ], dan [ Print RM ].
  Tab Production : Berisi table data komponen Urutan Proses dan Instruksi, Kemasan, Checklist, Tanggal Mulai, Jam Mulai, Tanggal Selesai, Jam Selesai. Dilengkapi dengan tombol aksi [ Save ], [ Complete ], [ Proses Base ], dan [ Selesai Base ].
  Tab Realisasi : Berisi table data komponen Product, Name, Warehouse, Qty, UOM, Batch No. Dilengkapi dengan tombol aksi [ Save ].
  Tab QC : Berisi table data komponen Pengujian, Standard, Hasil Pengujian 1, Hasil Pengujian 2, Hasil Pengujian 3.
  Tab Adjustment : Terdiri dari dua bagian. Bagian atas menampilkan informasi Date, Product Name, Batch Name, Mesin, Tonase, Notes, User Id. Bagian bawah berupa table data komponen Product ID, Nama Bahan, UOM, Warehouse, 1, FC1, 2, FC 2, 3, FC 3, 4, FC4, 5, FC 5, Pengembalian, Jumlah, Release Date.
  Tab Result : Menampung data atau kesimpulan hasil akhir dari serangkaian proses produksi tersebut.

- SPPBJ (Surat Perintah Pembuatan Barang Jadi / CM) `production-process-sppbj`
  Komponen Tampilan Awal : Production ID, Jadwal, No. SPKP, No. Batch, Date, Created by, Product Name, Proses CM, Selesai CM, Machine, Tipe Produk, Formulasi, FK, Basis, Required, Recanning, Bahan Baku, Production, Kemasan, Realisasi (STBJ), Tgl. Selesai, Adjustment, PPIC, WH, Notes, Base, Keputusan.
  Fungsi : Mengelola dan mengeksekusi dokumen kelanjutan proses produksi (Color Matching / Pencampuran Warna hingga pengemasan produk jadi), yang mencakup penimbangan bahan baku CM, instruksi produksi, alokasi dan permintaan kemasan, pengujian QC, hingga penyesuaian (adjustment).
  Penjelasan UI : Tampilan awal berupa table data grid yang menampilkan komponen halaman awal secara lengkap. Halaman ini tidak memiliki tombol Tambah (karena data biasanya di-generate otomatis dari penyelesaian tahapan SPKP sebelumnya). Terdapat filter pencarian Tanggal Awal, Tanggal Akhir, dan dropdown Tipe Produk dengan pilihan: Water Based, Solvent Based, Lain-Lain, Kemasan, TM, MP, Labeling, Pasta Printing, WB ADU.
  Pengguna mengklik baris data pada tabel untuk membuka Halaman Proses (Detail/Separate Page) yang terbagi menjadi dua area utama:
  Bagian Atas (Header Informasi) : Menampilkan Tgl. Jadwal, No. SPKP, Kelompok Produk, Nama Produk, Batch No, Tipe Product, Formulasi, Basis, Total Basis, Status, Notes, Production ID, dan Jadwal.
  Bagian Bawah (7 Tab Navigasi) :
    Tab Bahan Baku CM : Berisi table data komponen Name, Tonase, Batch No., Kebutuhan, UOM, Realisasi, UOM, Change Batch, Checklist, Warehouse. Dilengkapi dengan deretan tombol aksi: [ Save ], [ Leader Formulasi ], [ Complete ], dan [ Print CM ].
    Tab Production : Berisi table data komponen Uraian, Batch, %, Tonase (Kg), Realisasi (Kg). Dilengkapi dengan deretan tombol aksi: [ Save ], [ Identifikasi Product ], [ Laporan Hasil Kemas ], [ Complete ], [ Proses CM ], dan [ Selesai CM ].
    Tab Permintaan Kemasan : Berisi table data komponen Name, Kebutuhan, UOM, Realisasi, UOM, Date, Pengganti Reject, Kurang (OK), Sisa (OK), Warehouse, Reject, Warehouse Reject, Date. Dilengkapi dengan deretan tombol aksi: [ Save ], [ T Operator Print ], [ Leader Kemasan ], [ Produksi ], [ Complete ], dan [ Print Barcode ].
    Tab Realisasi : Berisi table data komponen Product, Name, Warehouse, Qty, UOM, Batch No.
    Tab QC : Berisi table data komponen Pengujian, Standard, Hasil Pengujian 1, Hasil Pengujian 2, Hasil Pengujian 3.
    Tab Adjustment : Terdiri dari dua bagian. Bagian atas menampilkan informasi Date, Product Name, Batch Name, Mesin, Tonase, Notes, User Id. Bagian bawah berupa table data komponen Product ID, Nama Bahan, UOM, Warehouse, 1, FC1, 2, FC 2, 3, FC 3, 4, FC4, 5, FC 5, Pengembalian, Jumlah, Release Date.
    Tab Result : Menampung data, rincian akhir, atau kesimpulan dari serangkaian proses barang jadi (Finished Goods) tersebut.

- SPKP ADU (Surat Perintah Kerja Produksi Adu / Adjustment Base) `production-process-spkpadu`
  Komponen Tampilan Awal : Production ID, Jadwal, No. SPKP ADU, No. Batch, Date, Created by, Product Name, Proses BASE ADU, Selesai BASE ADU, Machine, Tipe Produk, Formulasi, FK, Basis, Required, Recanning.
  Fungsi : Mengelola dokumen eksekusi perintah kerja perbaikan/penyesuaian (adjustment) pada adonan dasar (base) yang memerlukan proses ulang di lini produksi.
  Penjelasan UI :
  Tampilan awal berupa table data grid yang menampilkan komponen halaman awal secara lengkap. Halaman ini tidak memiliki tombol Tambah. Terdapat filter Tanggal Awal, Tanggal Akhir, dan dropdown Tipe Produk dengan pilihan: Water Based, Solvent Based, Lain-Lain, Kemasan, TM, MP, Labeling, Pasta Printing, WB ADU.
  Pengguna mengklik baris data pada tabel untuk membuka Halaman Proses (Detail Page) yang terbagi menjadi dua area utama:
  Bagian Atas (Header Informasi) : Menampilkan Tgl. Jadwal, No. SPKP ADU, Kelompok Produk, Nama Produk, Batch No, Tipe Product, Formulasi, Basis, Total Basis, Status, Notes, Production ID, dan Jadwal.
  Bagian Bawah (6 Tab Navigasi) :
  Tab Bahan Baku ADU : Berisi table data komponen Name, Tonase, Urutan Proses, Batch No., Kebutuhan, UOM, Kemasan, Checklist, Realisasi, UOM, Change Batch, Warehouse. (Dengan tombol aksi: Save, Leader Formulasi, Complete, Print RM).
  Tab Production ADU : Berisi table data komponen Urutan Proses dan Instruksi, Kemasan, Checklist, Tanggal Mulai, Jam Mulai, Tanggal Selesai, Jam Selesai. (Dengan tombol aksi: Save, Complete, Proses Base, Selesai Base).
  Tab Realisasi ADU : Berisi table data komponen Product, Name, Warehouse, Qty, UOM, Batch No. (Dengan tombol aksi: Save).
  Tab QC ADU : Berisi table data komponen Pengujian, Standard, Hasil Pengujian 1, Hasil Pengujian 2, Hasil Pengujian 3.
  Tab Adjustment ADU : Terdiri dari dua bagian (Header: Date, Product Name, Mesin, Tonase, dll. & Table: Product ID, Nama Bahan, UOM, 1, FC1, 2, FC2, dst., Pengembalian, Jumlah, Release Date).
  Tab Result ADU : Menampung data atau kesimpulan hasil akhir dari proses perbaikan base.

- SPPBJ ADU (Surat Perintah Pembuatan Barang Jadi Adu / Adjustment CM) `production-process-sppbjadu`
  Komponen Tampilan Awal: Production ID, Jadwal, No. SPKP ADU, No. Batch, Date, Created by, Product Name, Proses CM ADU, Selesai CM ADU, Machine, Tipe Produk, Formulasi, FK, Basis, Required, Recanning, Bahan Baku ADU, Production ADU, Kemasan ADU, Realisasi (STBJ), Tgl. Selesai, Adjustment ADU, PPIC, WH, Notes, Base, Keputusan.
  Fungsi : Mengelola dokumen perintah kerja perbaikan/penyesuaian warna (rework color matching) atau pengemasan ulang pada produk jadi yang tidak lolos standar QC.
  Penjelasan UI :
  Tampilan awal berupa table data grid yang menampilkan komponen halaman awal secara lengkap. Halaman ini tidak memiliki tombol Tambah. Terdapat filter Tanggal Awal, Tanggal Akhir, dan dropdown Tipe Produk.
  Pengguna mengklik baris data pada tabel untuk membuka Halaman Proses (Detail Page) yang terbagi menjadi dua area utama:
  Bagian Atas (Header Informasi) : Menampilkan Tgl. Jadwal, No. SPKP ADU, Kelompok Produk, Nama Produk, Batch No, Tipe Product, Formulasi, Basis, Total Basis, Status, Notes, Production ID, dan Jadwal.
  Bagian Bawah (7 Tab Navigasi) :
  Tab Bahan Baku CM ADU : Berisi table data komponen Name, Tonase, Batch No., Kebutuhan, UOM, Realisasi, UOM, Change Batch, Checklist, Warehouse. (Dengan tombol aksi: Save, Leader Formulasi, Complete, Print CM).
  Tab Production ADU : Berisi table data komponen Uraian, Batch, %, Tonase (Kg), Realisasi (Kg). (Dengan tombol aksi: Save, Identifikasi Product, Laporan Hasil Kemas, Complete, Proses CM, Selesai CM).
  Tab Permintaan Kemasan ADU : Berisi table data komponen Name, Kebutuhan, UOM, Realisasi, Date, Pengganti Reject, Kurang (OK), Sisa (OK), Warehouse, Reject, Warehouse Reject, Date. (Dengan tombol aksi: Save, T Operator Print, Leader Kemasan, Produksi, Complete, Print Barcode).
  Tab Realisasi ADU : Berisi table data komponen Product, Name, Warehouse, Qty, UOM, Batch No.
  Tab QC ADU : Berisi table data komponen Pengujian, Standard, Hasil Pengujian 1, Hasil Pengujian 2, Hasil Pengujian 3.
  Tab Adjustment ADU : Terdiri dari dua bagian (Header: Date, Mesin, Tonase, dll. & Table input parameter koreksi 1, FC1, 2, FC2, dst.).
  Tab Result ADU : Menampung data, rincian akhir, atau kesimpulan dari proses rework barang jadi tersebut.

- SPPI (Surat Perintah Penggunaan Insektisida / Bahan Penolong Khusus) `production-process-sppi`
  Komponen : Production ID, SPPI_No, Date, Created_By, No_Batch, Product_Name, Machine, Material_ID, Material_Name, Target_Dose_Qty, Actual_Dose_Qty, UOM, Mixing_Time, Operator, Notes, Status
  Fungsi : Mengelola instruksi dan pencatatan khusus penambahan bahan aditif/penolong berbahaya atau berpresisi tinggi (seperti anti-jamur, insektisida, biosida, atau aditif khusus) ke dalam adonan produksi.
  Penjelasan UI : Tampilan awal berupa table data grid menampilkan SPPI_No, Date, No_Batch, Product_Name, Material_Name, Target_Dose_Qty, Actual_Dose_Qty, Operator, dan Status. Tombol Tambah memunculkan Pop-up Modal Form berstruktur:
    1. Header Info : SPPI No (Auto), Date, Created By, Ref Batch No, Product Name, Machine.
    2. Detail Dosis & Bahan : Material ID/Name (Aditif/Biosida/Insektisida), Target Dose Qty, Actual Dose Qty (Penimbangan Presisi), UOM, Mixing Time (Durasi Pengadukan), Operator Pelaksana.
    3. Footer : Field Textarea Notes dan Tombol [ Simpan SPPI ].
  Edit memunculkan modal terisi untuk pembaruan data, Hapus memunculkan alert konfirmasi modal.

- SPPPK (Surat Perintah Persiapan & Penggunaan Kemasan) `production-process-spppk`
  Komponen : Production ID, SPPPK_No, Date, Created_By, No_Batch, Product_Name, Packaging_Line_ID, Package_Type (Kaleng/Galon/Pail), Target_Packing_Qty_Pcs, Target_Weight_Kg, Tare_Weight_Check, Actual_Packed_Pcs, Actual_Packed_Kg, Reject_Packaging_Pcs, Operator_Packing, Notes, Status
  Fungsi : Mengelola instruksi penarikan, pemeriksaan tare/kondisi wadah fisik, serta eksekusi pengisian (filling) produk cat yang sudah lulus QC ke dalam wadah kemasan akhir.
  Penjelasan UI : Tampilan awal berupa table data grid menampilkan SPPPK_No, Date, No_Batch, Product_Name, Packaging_Line_ID, Package_Type, Target_Packing_Qty_Pcs, Actual_Packed_Pcs, Status, dan Notes. Tombol Tambah memunculkan Pop-up Modal Form berstruktur:
    1. Header Info : SPPPK No (Auto), Date, Created By, Ref Production ID / Batch No (QC Approved), Product Name, Packaging Line ID.
    2. Detail Spesifikasi Kemasan : Package Type (Dropdown: Kaleng 0.1L, 0.9L, Galon, Pail, dll.), Target Packing Qty (Pcs & Kg), Cek Berat Kosong (Tare Weight Check).
    3. Realisasi Filling & Penimbangan : Actual Packed Pcs, Actual Packed Kg, Reject/Damaged Packaging Pcs, Operator Packing (Multi-select), Textarea Notes, serta Tombol [ Simpan SPPPK ].
  Edit memunculkan modal terisi untuk pembaruan data, Hapus memunculkan alert konfirmasi modal.

## Sales & Distribution `#`

- Dashboard Sales & Distribution `sales-dashboard`
  Komponen : Total_Sales_Omset_Today, Total_Active_SO, Total_Pending_Shipment, Total_AR_Outstanding, Total_Overdue_AR_Count, Chart_Daily_Sales_Trend, Chart_Top_Salesman_Performance, Chart_Sales_By_Category, Table_Recent_Sales_Orders, Table_Credit_Limit_Exceeded_Alert
  Fungsi : Menyajikan gambaran umum secara visual dan real-time mengenai performa penjualan, omset harian, piutang usaha (AR), status pengiriman, serta peringatan batas kredit pelanggan.
  Penjelasan UI : Tampilan awal berupa dashboard interaktif berisi ringkasan widget angka (card stat), grafik tren omset penjualan, diagram lingkaran kontribusi kategori produk, modul pemantauan kinerja wiraniaga, serta tabel notifikasi cepat pesanan terbaru dan pelanggan yang melebihi batas kredit. Halaman bersifat Read-Only dan dilengkapi filter periode tanggal, wilayah/area, dan gudang.

- Customer Balance Summary `customer-balance-summary`
  Komponen : Customer ID, Name, Currency, Beginning Balance, Total Invoice, Total Payment, Total Return, Ending Balance, Credit Limit, Available Credit
  Fungsi : Menyajikan ringkasan posisi saldo piutang pelanggan, sisa batas kredit, dan histori akumulasi mutasi secara real-time.
  Penjelasan UI : Tampilan awal berupa table analitik Read-Only dengan penanda visual warna merah untuk sisa kredit yang menipis atau melampaui batas (Available Credit < 0). Dilengkapi filter pelanggan, mata uang, dan tombol Export Excel/PDF.

- AR Warehouse Report `ar-warehouse-report`
  Komponen : Warehouse ID, Warehouse Name, Customer ID, Customer Name, Invoice No, Invoice Date, Due Date, Outstanding Amount, Age (Days)
  Fungsi : Laporan rincian piutang usaha yang dikelompokkan berdasarkan gudang pemenuhan pesanan.
  Penjelasan UI : Tampilan awal berupa table analitik Read-Only berstruktur hirarki/grouping per Gudang Pemenuhan. Dilengkapi filter gudang, tanggal jatuh tempo, dan rentang umur piutang.

- Customer Point `customer-point`
  - Point Setting
    Komponen : Point (num)
    Fungsi : Mengatur rasio dasar konversi transaksi menjadi poin loyalitas pelanggan.
    Penjelasan UI : Tampilan halaman pengaturan sederhana (Setting Form) berisi input nilai nominal konversi per 1 poin beserta tombol [ Simpan Pengaturan ].

  - Customer Point Promo Rule
    Komponen : Category ID, Name, 1 Point = ? Qty, UOM Id
    Fungsi : Mengatur aturan khusus perolehan poin berdasarkan kuantitas pembelian kategori produk tertentu.
    Penjelasan UI : Tampilan berupa table aturan promo poin. Tombol Tambah memunculkan Modal Form pemilihan Kategori Produk, Rasio Kuantitas per 1 Poin, dan Satuan (UOM).

  - Category Exception
    Komponen : Id, Category
    Fungsi : Menentukan pengecualian kategori produk yang tidak berhak mendapatkan poin loyalitas.
    Penjelasan UI : Tampilan berupa daftar kategori terpilih yang dikecualikan dari program poin. Tombol Tambah memunculkan Modal Form Lookup Kategori Produk.

  - Product Point Claim Setup
    Komponen : Id, Product, Point
    Fungsi : Mengatur katalog item produk beserta jumlah poin yang dibutuhkan untuk melakukan klaim/penukaran.
    Penjelasan UI : Tampilan berupa data grid katalog hadiah. Tombol Tambah memunculkan Modal Form pemilihan Produk Hadiah dan input Jumlah Poin Syarat Klaim.

  - Claim Product
    Komponen : Customer ID, Member ID, Name, Point Reguler, Point Promo, Point Type, Doc. ID, Date, Warehouse ID, User, Type Name/Id, Note, Total Point Claim, Detail Items Table (Product ID, Name, Description, Qty, UOM Id, Point, Total Point Claim)
    Fungsi : Transaksi penukaran poin milik pelanggan dengan produk atau reward tertentu.
    Penjelasan UI : Tampilan awal berupa table daftar transaksi klaim poin. Tombol Tambah memunculkan Modal Form berstruktur Header (Customer, Member ID, Sisa Poin) dan Data Grid Detail Items Hadiah (Dropdown Produk, Qty, Poin per Unit, Total Poin Klaim).

  - Claim Product Daily Report
    Komponen : Date, Claim Doc No, Customer ID, Customer Name, Product ID, Qty Claimed, Total Points Deducted, User
    Fungsi : Laporan harian transaksi klaim reward dan pengeluaran poin pelanggan.
    Penjelasan UI : Tampilan berupa table laporan harian Read-Only dilengkapi filter rentang tanggal, pelanggan, dan tombol Ekspor Data.

- Sales Order `sales-order`
  - Sales Order List `sales-order-list`
    Komponen : No., Date, Warehouse, Customer Id, Name, Area, WA, Note, Disc. %, Disc. Amt., Total, Currency, Status, Term, Sales, Contract No, Doc. Type
    Fungsi : Mengelola dokumen pemesanan barang dari pelanggan sebelum diproses ke tahap pengiriman.
    Penjelasan UI : Tampilan awal berupa table data grid utama seluruh pesanan penjualan dengan indikator status (Draft/Approved/In Progress/Closed/Cancelled). Tombol Tambah memunculkan Modal Form berstruktur Header (Customer, Date, Warehouse, Salesman, Term) dan Data Grid Items (Product, Qty, Price, Disc %, Total). Edit memunculkan modal terisi, Hapus memunculkan alert konfirmasi.

  - Sales Order Fulfilment `sales-order-fulfilment`
    Komponen : Cust. ID, Name, Area, Sales Order, SO Date, Warehouse, Note, Status, Product ID, Name, Description, SO Qty, SO UOM ID, SI Date, SI Qty, SI UOM ID, Qty Diff, Tonase
    Fungsi : Memantau tingkat pemenuhan kuantitas barang dari Sales Order menjadi Sales Invoice/pengiriman.
    Penjelasan UI : Tampilan awal berupa table analitik Read-Only membandingkan kuantitas pemesanan (SO Qty) vs kuantitas pengiriman/faktur (SI Qty) beserta indikator selisih (Qty Diff). Dilengkapi filter status pemenuhan (Unfulfilled / Partial / Fulfilled).

  - Daily Sales Order Report `daily-sales-order-report`
    Komponen : Date, SO No, Customer Name, Salesman, Total Amount, Status, Warehouse
    Fungsi : Laporan rekapitulasi harian pembuatan dan status dokumen Sales Order.
    Penjelasan UI : Tampilan awal berupa table analitik Read-Only dengan filter tanggal harian, wiraniaga, status, dan gudang.

  - Daily Sales Order Invoice Report `daily-sales-order-invoice-report`
    Komponen : Date, SO No, SI No, Customer Name, SO Amount, Invoiced Amount, Fulfilment Rate (%)
    Fungsi : Laporan perbandingan harian antara nilai pesanan (SO) dengan nilai yang telah berhasil ditagihkan (Invoice).
    Penjelasan UI : Tampilan awal berupa table analitik Read-Only dilengkapi kolom rasio pemenuhan (%) dan indikator warna pencapaian.

- Packing `packing`
  Komponen : Packing No, Date, SO No, Customer ID, Warehouse ID, Packing Staff, Total Box/Package, Weight, Status, Note
  Fungsi : Mengelola proses pengemasan barang di gudang berdasarkan pesanan penjualan sebelum diserahkan ke tim kurir/pengiriman.
  Penjelasan UI : Tampilan awal berupa table data grid status pengemasan. Tombol Tambah memunculkan Modal Form berstruktur Header (SO Ref, Packing Staff, Total Box/Karton, Berat Total) dan daftar verifikasi item barang yang dikemas.

- Sales Invoice `sales-invoice`
  - Sales Invoice List `sales-invoice-list`
    Komponen : No., Date, Due Date, Doc. Type, Printed Status, Purchase Note, Warehouse, Sales Order, No. Faktur, Customer Id, Name, Area, WA, Note, Curr., Total, Disc. %, Disc. Amt., Status, Term, User, Outstanding, Delivery Status
    Fungsi : Mengelola tagihan penjualan resmi kepada pelanggan atas barang yang telah dikirimkan.
    Penjelasan UI : Tampilan awal berupa table data grid komprehensif seluruh faktur penjualan. Tombol Tambah memunculkan Modal Form penarikan data SO/DO, kalkulasi otomatis pajak & diskon, serta pembuatan No. Faktur Pajak.

  - Shipment Priority `shipment-priority`
    Komponen : Priority No, Invoice No, SO No, Customer ID, Area, Total Weight/Volume, Promised Date, Status
    Fungsi : Mengatur urutan prioritas pengiriman barang berdasarkan kriteria pelanggan atau tanggal janji serah.
    Penjelasan UI : Tampilan awal berupa interface list interaktif yang dapat diatur urutannya secara drag-and-drop atau pengisian angka Priority No untuk menentukan urutan antrean muat barang.

  - Sales Promo Report `sales-promo-report`
    Komponen : Promo ID, Promo Name, Invoice No, Customer Name, Product ID, Discount Amount, Free Goods Qty
    Fungsi : Laporan efektivitas dan rekap penggunaan program promosi pada transaksi penjualan.
    Penjelasan UI : Tampilan awal berupa table analitik Read-Only penggunaan diskon promo dan barang gratis (free goods) dengan filter promo ID dan periode.

  - Sales Profit Report `sales-profit-report`
    Komponen : Invoice No, Date, Customer Name, Product ID, Qty, Selling Price, HPP/Cost, Gross Profit, Profit Margin (%)
    Fungsi : Laporan analisis keuntungan kotor penjualan berdasarkan selisih harga jual dan harga pokok penjualan (HPP).
    Penjelasan UI : Tampilan awal berupa table analitik finansial Read-Only yang menghitung selisih harga jual vs HPP per item faktur.

  - Sales Omset Report `sales-omset-report`
    Komponen : Period, Salesman, Area, Customer Group, Total Gross Sales, Total Discount, Total Net Omset
    Fungsi : Laporan rekapitulasi total pencapaian omset penjualan bersih per periode.
    Penjelasan UI : Tampilan berupa ringkasan eksekutif berbasis tabel dan grafik tren omset bersih dengan multi-filter pencapaian.

  - Sales Void Report `sales-void-report`
    Komponen : Void Date, Doc No (SO/SI), Customer Name, Original Amount, Void Reason, Authorized User
    Fungsi : Laporan riwayat pembatalan (void) dokumen transaksi penjualan beserta alasannya.
    Penjelasan UI : Tampilan berupa table audit trail Read-Only yang mencatat seluruh dokumen SO/SI yang dibatalkan beserta otorisatornya.

  - Sales Commision Report `sales-commision-report`
    Komponen : Salesman ID, Salesman Name, Period, Total Omset, Target, Commission Rate (%), Total Commission
    Fungsi : Laporan perhitungan komisi penjualan untuk wiraniaga berdasarkan pencapaian target.
    Penjelasan UI : Tampilan berupa table kalkulasi komisi wiraniaga dilengkapi indikator persentase pencapaian target.

  - Invoice Payment Report `invoice-payment-report`
    Komponen : Invoice No, Invoice Date, Customer Name, Total Invoice, Total Paid, Balance Due, Last Payment Date, Status
    Fungsi : Laporan riwayat dan status pelunasan faktur penjualan.
    Penjelasan UI : Tampilan berupa table status pembayaran faktur dengan indikator Lunas / Parsial / Belum Bayar.

  - Profit Loss Report `profit-loss-report`
    Komponen : Period, Total Sales Revenue, Sales Return, Cost of Goods Sold (HPP), Gross Margin, Operating Expenses, Net Sales Profit
    Fungsi : Laporan ringkasan laba rugi operasional yang dihasilkan dari aktivitas penjualan.
    Penjelasan UI : Tampilan ringkasan laporan keuangan Read-Only berstruktur pendapatan, HPP, margin kotor, hingga laba bersih operasional.

  - Sales Reports `sales-reports`
    Komponen Filter : Rentang Tanggal, Series & Brand, VAT / Non VAT, Sub-Report Type (Sales by Customer, Sales by Product, Sales by Supplier, Sales by Salesman, Sales by Category)
    Fungsi : Modul pelaporan penjualan multi-dimensi dengan berbagai kombinasi filter analisis.
    Penjelasan UI : Tampilan laporan dinamis dengan tab/dropdown pilihan jenis laporan (Customer, Product, Salesman, dll.) serta panel filter kombinasi di bagian atas.

- Tanda Terima Penagihan `tanda-terima-penagihan`
  Komponen : TTP No, TTP Date, Collector Name, Customer ID, Total Invoice Count, Total Amount, Due Date, Status, Note
  Fungsi : Mengelola dokumen penyerahan lembar faktur tagihan kepada penagih/kolektor untuk melakukan penagihan ke lokasi pelanggan.
  Penjelasan UI : Tampilan awal berupa table data grid TTP. Tombol Tambah memunculkan Modal Form pemilihan Kolektor dan tabel centang faktur-faktur yang diserahkan untuk ditagih.

- Customer Payment `customer-payment`
  - Customer Payment List `customer-payment-list`
    Komponen : Payment No., Date, Date Complete, Warehouse, No. TTP, Customer Id, Name, Account, Total, Status, Currency, Rate, Note, Def. Sales, Type Payment (Reguler/Down)
    Fungsi : Mengelola daftar seluruh transaksi penerimaan kas/bank dari pelanggan baik untuk pelunasan maupun uang muka.
    Penjelasan UI : Tampilan awal berupa table data grid transaksi penerimaan pembayaran. Tombol Tambah memunculkan Modal Form berstruktur Header (Customer, Payment Method, Account Bank, Amount) dan Table Alokasi Faktur yang dilunasi.

  - Cust. Outstanding List `cust-outstanding-list`
    Komponen : Invoice No, Customer Id, Customer Name, City, Date, Due Date, Age (Days), Curr, Total, Outstanding, Term, Invoiced, Warehouse, Sales, Note
    Fungsi : Memantau daftar faktur penjualan yang belum dilunasi beserta umur piutangnya.
    Penjelasan UI : Tampilan berupa table analitik pemantauan piutang dilengkapi pengelompokan umur piutang (AR Aging Bracket).

  - Daily Customer Payment Report `daily-customer-payment-report`
    Komponen : Date, Payment No, Customer Name, Payment Method, Total Paid, Account Name, User
    Fungsi : Laporan harian penerimaan pembayaran dari pelanggan.
    Penjelasan UI : Tampilan berupa table laporan harian kas/bank masuk dari penjualan.

  - Outstanding per Customer Report `outstanding-per-customer-report`
    Komponen : Customer ID, Customer Name, Total Invoices, Total Outstanding Amount, Credit Limit, Exceeded Amount
    Fungsi : Laporan total sisa piutang yang dikelompokkan per pelanggan.
    Penjelasan UI : Tampilan berupa table ringkasan piutang per pelanggan dilengkapi indikator sisa batas kredit.

  - Customer Payment Check `customer-payment-check`
    Komponen : Check/Giro No, Bank Name, Maturity Date, Customer ID, Amount, Status (Clearing/Bounced/Passed)
    Fungsi : Memantau dan memverifikasi status pembayaran menggunakan instrumen Cek atau Giro.
    Penjelasan UI : Tampilan berupa data grid pemantauan status warkat (Cek/Giro) dengan tombol aksi perubahan status (Cair / Tolak / Kliring).

  - Customer Outstanding per Date Report `customer-outstanding-per-date-report`
    Komponen : As of Date, Customer ID, Customer Name, Current, 1-30 Days, 31-60 Days, 61-90 Days, >90 Days, Total Outstanding
    Fungsi : Laporan analisis umur piutang (Aging AR Report) pada tanggal posisi tertentu.
    Penjelasan UI : Tampilan berupa table matriks umur piutang (Current hingga >90 Hari) pada posisi tanggal yang ditentukan.

- Sales Return `sales-return`
  - Sales Return List `sales-return-list`
    Komponen : No., Date, Warehouse, Customer Id, Name, Area, WA, Disc. %, Disc. Amt., Total, Currency, Status, Note, Term, Sales, SI Returned
    Fungsi : Mengelola penerimaan kembali barang yang dijual akibat kerusakan, retur komersial, atau kesalahan pengiriman.
    Penjelasan UI : Tampilan awal berupa table data grid retur penjualan. Tombol Tambah memunculkan Modal Form penarikan Faktur Asal (SI Returned Ref), alasan retur, dan rincian item barang yang dikembalikan.

  - Daily Sales Return Report `daily-sales-return-report`
    Komponen : Date, Return No, Customer Name, Product ID, Qty Returned, Total Amount, Reason, Warehouse ID
    Fungsi : Laporan harian pengembalian barang dan pemotongan tagihan piutang.
    Penjelasan UI : Tampilan berupa table analitik Read-Only rekapitulasi retur harian.

- Tanda Terima Invoice `tanda-terima-invoice`
  Komponen : TTI No, Date, Customer ID, Customer Name, Invoice List (No, Date, Amount), Received By (Customer PIC), Received Date, Return Status
  Fungsi : Mengelola dokumen bukti bahwa fisik faktur/tagihan telah diterima dengan baik oleh pelanggan untuk memicu perhitungan tanggal jatuh tempo.
  Penjelasan UI : Tampilan awal berupa table data grid TTI. Tombol Tambah memunculkan Modal Form input Tanggal Terima PIC Pelanggan dan lampiran bukti tanda terima fisik.

- Delivery Order `delivery-order`
  Komponen : DO No, Date, SO No, SI No, Warehouse ID, Customer ID, Driver Name, Vehicle No, Delivery Address, Status, Expeditor
  Fungsi : Mengelola dokumen surat jalan pengeluaran barang dari gudang untuk dikirimkan ke alamat pelanggan.
  Penjelasan UI : Tampilan awal berupa table data grid Surat Jalan (DO). Tombol Tambah memunculkan Modal Form penarikan SO/SI, nama pengemudi, nomor armada, dan ekspedisi.

- Shipment Preparation `shipment-preparation`
  Komponen : Prep No, Date, Warehouse ID, DO List, Total Weight, Total Volume, Fleet/Vehicle Type, Route Area, Status
  Fungsi : Mengonsolidasi beberapa dokumen surat jalan/DO ke dalam rencana pemuatan armada dan rute pengiriman.
  Penjelasan UI : Tampilan awal berupa table konsolidasi pengiriman. Tombol Tambah memunculkan Modal Form pemilihan rute area dan centang multi-DO untuk dialokasikan ke satu armada.

- Purchase Note `purchase-note`
  Komponen : Note No, Date, Customer ID, PO Customer No, Attachment, Description, Validation Status
  Fungsi : Mencatat dan memverifikasi nomor serta lampiran Surat Pesanan (PO) fisik resmi yang diterbitkan oleh pihak pelanggan.
  Penjelasan UI : Tampilan awal berupa table verifikasi PO Pelanggan. Tombol Tambah memunculkan Modal Form input No. PO Pelanggan, deskripsi, dan upload file scan PO fisik.

- Sales Commission `sales-commission`
  Komponen : Comm No, Date, Period, Salesman ID, Calculation Base (Omset/Pelunasan), Target Amount, Achieved Amount, Commission Rate, Total Commission Paid, Status
  Fungsi : Memproses perhitungan dan persetujuan pencairan komisi penjualan untuk staf penjualan.
  Penjelasan UI : Tampilan awal berupa table dokumen komisi. Tombol Tambah memunculkan Modal Form penarikan total omset/pelunasan wiraniaga periode tertentu, kalkulasi komisi otomatis, dan tombol persetujuan pencairan.

- Tax `tax`
  Komponen : Tax Doc No, Invoice No, Tax Code (PPN/PPh), Customer NPWP, DPP Amount, Tax Amount, Tax Invoice No (Faktur Pajak), Status Export/EFaktur
  Fungsi : Mengelola pencatatan kewajiban pajak penjualan (seperti PPN) dan integrasi pembuatan Seri Faktur Pajak.
  Penjelasan UI : Tampilan awal berupa data grid rekapitulasi Pajak Penjualan dilengkapi fitur filter status E-Faktur dan tombol ekspor data XML/CSV Faktur Pajak.
## Transit Area `#`

- Dashboard Transit Area `/transit-area-dashboard`
  Komponen : Total_Transit_Sales_Today, Total_Active_Depo_Count, Total_AR_Depo_Outstanding, Target_Achievement_Rate_Percent, Top_Depo_Performance_Ranking, Chart_Daily_Depo_Sales_Trend, Chart_Depo_Collection_Vs_Target, Table_Overdue_Depo_AR_Alert
  Fungsi : Menyajikan gambaran umum secara visual dan real-time mengenai performa penjualan harian, pencapaian target depo, posisi piutang (AR) transit, progres penagihan, serta pemeringkatan kinerja antar-depo.
  Penjelasan UI : Tampilan awal berupa dashboard interaktif berisi ringkasan widget angka (card stat), grafik tren penjualan harian per depo, diagram perbandingan target vs realisasi penagihan, serta tabel peringatan piutang berumur kritis (>90 hari). Halaman bersifat Read-Only dan dilengkapi filter periode tanggal, wilayah/area, serta pilihan Transit Area/Depo.

- Daily Sales Invoice Report `/daily-sales-invoice-report`
  Komponen : Date, Warehouse, Cust. ID, Name, Area, Sales Invoice, Delivery Order, Prod. ID, Name, UOM, Qty, Price, Disc. %, Disc. Amount, Total Potongan, Total, DPP, PPN, Due Date, Tonase, Sales, Brand, Note
  Fungsi : Menyajikan laporan harian rincian Faktur Penjualan per item barang lengkap dengan nilai DPP, PPN, tonase, dan salesman terkait pada Transit Area/Depo.
  Penjelasan UI : Tampilan berupa table analitik Read-Only berfitur pencarian multi-kolom dan pengelompokan per Faktur/Pelanggan. Dilengkapi filter tanggal harian, gudang/depo, brand, salesman, dan tombol Ekspor Excel/PDF.

- Daily Sales PO Closing Report `/daily-sales-po-closing-report`
  Komponen : Date, Warehouse, Cust. ID, Name, Area, Sales Invoice, Delivery Order, Prod. ID, Name, UOM, Qty, Price, Disc. %, Disc. Amount, Total Potongan, Total, DPP, PPN, Grand Total, Due Date, Tonase, Note
  Fungsi : Memantau harian transaksi penutupan pesanan (PO Closing) yang telah dipenuhi hingga penerbitan faktur penjualan dan pengiriman barang.
  Penjelasan UI : Tampilan berupa table laporan harian Read-Only dilengkapi penanda status penutupan transaksi (Closed PO). Dilengkapi filter tanggal, gudang/depo, area, serta tombol cetak rekapitulasi.

- Daily Sales Return Report `/daily-sales-return-report`
  Komponen : Date, Kode Area, Area, Cust. ID, Name, Sales Invoice, Prod. ID, Prod. Name, UOM, Qty, Price, Disc. %, Total Potongan, Grand Total, Total Invoice
  Fungsi : Menyajikan laporan harian pengembalian barang (retur) dari pelanggan beserta pemotongan nilai faktur tagihannya.
  Penjelasan UI : Tampilan berupa table analitik Read-Only rincian barang retur. Dilengkapi filter tanggal harian, area, pelanggan, dan subtotal pemotongan nilai faktur.

- Daily Sales by Brand Report `/daily-sales-by-brand-report`
  Komponen : Date, Warehouse, Area, Brand ID, Brand Name, Total Qty Sold, Gross Amount, Discount Amount, Net Sales Amount, Percentage Contribution (%)
  Fungsi : Menampilkan rekapitulasi pencapaian omset dan volume penjualan harian yang dikelompokkan berdasarkan merek/brand produk.
  Penjelasan UI : Tampilan berupa table rekapitulasi berbasis data grid dengan kolom persentase kontribusi (%) yang dilengkapi indikator grafik batang mini (progress bar). Dilengkapi filter tanggal, depo, dan brand.

- Daily Payment Recap Report `/daily-payment-recap-report`
  Komponen : No TTP, Date, Kode Area, Area, Cust. ID, Name, Sales Invoice, Bank, Cash, Discount, Lain-Lain, Retur, Total Bank In, Outstanding, Note, Tgl. TTP, Payment ID, Due Date, Invoice Total, Term, Diskon Promo (%)
  Fungsi : Menyajikan rekapitulasi harian pelunasan piutang pelanggan dari berbagai instrumen pembayaran (Bank, Kas, Diskon, Retur) terhadap faktur tagihan.
  Penjelasan UI : Tampilan berupa table matriks pembayaran Read-Only yang memisahkan kolom Kas, Bank, Diskon, dan Retur secara transparan. Dilengkapi filter rentang tanggal, TTP, area, dan pilihan instrumen pembayaran.

- Cheque Management `/cheque-management`
  Komponen : Date, Cust. ID, Name, No. BG, Bank, Valid Date, Amount, Valid, Note, Payment
  Fungsi : Mengelola dan memverifikasi status kelayakan instrumen Bilyet Giro (BG) dan Cek yang diterima dari pelanggan sebelum dikliringkan.
  Penjelasan UI : Tampilan awal berupa data grid berpenanda badge status warkat (Draft / Valid / Kliring / Bounced-Cair Tolak). Tombol Tambah memunculkan Modal Form input No. BG, Bank Penerbit, Tanggal Efektif, Nominal, dan ID Pelanggan. Tombol Aksi per baris menyediakan konfirmasi kelayakan warkat (Valid/Reject).

- RLHP (Rincian Laporan Hasil Penagihan) `/rlhp`
  Komponen : Doc. ID, Doc. Date, Payment From Date, Payment To Date, Depo, Tipe, Total Cash, Total Giro, Notes, User ID
  Fungsi : Mengonsolidasi pencatatan hasil penagihan harian oleh kolektor/kasir depo berdasarkan rincian penerimaan tunai dan giro.
  Penjelasan UI : Tampilan awal berupa table data grid dokumen RLHP. Tombol Tambah memunculkan Modal Form berstruktur Header (Depo, Tipe Penagihan, Periode Tagih, User/Kolektor) dan Data Grid Rincian Hasil Tagihan (Tunai & Bilyet Giro). Edit memunculkan modal terisi, Hapus memunculkan alert konfirmasi.

- AR per Customer Report `/ar-per-customer-report`
  Komponen : Warehouse, Area, Cust. ID, Name, Saldo Awal, Penjualan, PO Closing, Bank, Cash, Discount, Lain-Lain, Retur, Saldo Akhir, Sisa Piutang, Selisih, Salesman, < 45, > 45, > 90, > 120
  Fungsi : Laporan mutasi piutang lengkap per pelanggan beserta analisis umur piutang (Aging AR) dalam segmen rentang hari tertentu.
  Penjelasan UI : Tampilan berupa table mutasi saldo Read-Only (Saldo Awal + Penjualan - Pelunasan = Saldo Akhir) yang tersambung dengan kolom matriks Aging AR (<45 hari hingga >120 hari). Dilengkapi filter depo, area, salesman, dan opsi penyembunyian saldo nol.

- Customer AR Position Report `/customer-ar-position-report`
  Komponen : Warehouse, Area, Cust. ID, Name, Sales, Saldo Piutang, Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember, Saldo Piutang, Total Piutang
  Fungsi : Memantau posisi dan tren perkembangan saldo piutang pelanggan secara bulanan selama satu tahun berjalan.
  Penjelasan UI : Tampilan berupa table matriks tren bulanan (12 Bulan) Read-Only untuk evaluasi konsistensi pembayaran pelanggan. Dilengkapi filter tahun berjalan, depo, area, dan salesman.

- Invoice Customer AR List Report `/invoice-customer-ar-list-report`
  Komponen : Warehouse, Area, Cust. ID, Name, Sales, Saldo Piutang, Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember, Saldo Piutang, Total Piutang
  Fungsi : Menyajikan rincian daftar faktur outstanding milik pelanggan yang terdistribusi berdasarkan bulan penerbitan.
  Penjelasan UI : Tampilan berupa table rincian faktur terkelompok per pelanggan dan per bulan penerbitan. Dilengkapi filter depo, salesman, serta fitur expandable row untuk melihat nomor faktur spesifik.

- Salesman AR List PMB `/salesman-ar-list-pmb`
  Komponen : Salesman, Collection 53-90, Collection > 90, Total Collection, Ach. Coll. 0-52, Ach. Coll. 53-90, Ach. Coll. >90, Total Ach., Percentage
  Fungsi : Laporan evaluasi kinerja pencapaian penagihan piutang (Collection) oleh masing-masing salesman berdasarkan kategori umur piutang.
  Penjelasan UI : Tampilan berupa table kualifikasi kinerja penagihan salesman Read-Only dilengkapi persentase pencapaian (% Achievement) dan indikator warna sesuai batas target PMB.

- Invoice Expedition `/invoice-expedition`
  Komponen : Doc. ID, Date, Warehouse, Salesman, Notes, User ID
  Fungsi : Mengelola pengiriman dan serah terima dokumen fisik faktur penjualan dari depo pusat ke salesman/kolektor lapangan.
  Penjelasan UI : Tampilan awal berupa table data grid resi ekspedisi faktur internal. Tombol Tambah memunculkan Modal Form berstruktur Header (Salesman, Depo, Tanggal Kirim) dan Data Grid pemilihan daftar faktur fisik yang diserahterimakan.

- Shipping Invoice Expedition `/shipping-invoice-expedition`
  Komponen : Doc. ID, Date, Warehouse, Salesman, Notes, User ID
  Fungsi : Mengelola pengiriman berkas faktur dan surat jalan yang dilampirkan bersama armada pengiriman barang ke lokasi pelanggan.
  Penjelasan UI : Tampilan awal berupa table data grid pengiriman berkas pengiriman. Tombol Tambah memunculkan Modal Form pemilihan armada/pengemudi, daftar Surat Jalan (DO), dan faktur fisik terkait.

- Transit Area Target `/transit-area-target`
  Komponen : Warehouse, Target
  Fungsi : Menetapkan kuota target penjualan dan penagihan bulanan untuk masing-masing lokasi Transit Area/Depo.
  Penjelasan UI : Tampilan berupa data grid pengisian target tahunan/bulanan per Depo. Tombol Tambah / Edit memunculkan Modal Form berstruktur Lookup Depo/Warehouse, Periode Bulan/Tahun, serta Nilai Target Penjualan & Penagihan (RP/Qty).

- UBM Daily Control Progress Sales Report `/ubm-daily-control-progress-sales-report`
  Komponen : Transit Area, Target Bulanan, Toleransi, Belum Tercapai, Tahun Lalu, Bulan Lalu, Pencapaian TA, Target Hari Ini, Akumulasi, % Target, % Target TLR
  Fungsi : Laporan kontrol harian untuk memantau laju pencapaian target penjualan depo dibandingkan dengan periode lalu dan batas toleransi.
  Penjelasan UI : Tampilan berupa dashboard laporan kontrol harian Read-Only yang membandingkan run-rate harian, akumulasi omset, serta gap terhadap target bulanan dan batas toleransi (TLR).

- Transit Area New Brand `/transit-area-new-brand`
  Komponen : id, Brand
  Fungsi : Mengatur pendaftaran dan penetapan penanganan produk merek baru (New Brand) di Transit Area/Depo.
  Penjelasan UI : Tampilan berupa table pendaftaran merek baru. Tombol Tambah memunculkan Modal Form Lookup Brand dan penentuan depo penanggung jawab penanganan penetrasi produk.

- UBM New Product Sales Report `/ubm-new-product-sales-report`
  Komponen : Transit Area, Noo
  Fungsi : Memantau kinerja penetrasi produk baru dan penambahan Outlet Baru (NOO - New Open Outlet) pada Transit Area.
  Penjelasan UI : Tampilan berupa table analitik Read-Only pencapaian sebaran produk baru dan statistik akumulasi pembukaan toko/outlet baru (NOO) per depo.

- UBM Collection Progress Report `/ubm-collection-progress-report`
  Komponen : Transit Area, Collection 53-90, Collection > 90, Total Collection, Uncollected, Days Before, Target, Accumulation, Collection Tertagih (%), Rangking
  Fungsi : Memantau dan meranking progres pencapaian penagihan piutang overdue per Transit Area secara komparatif.
  Penjelasan UI : Tampilan berupa table klasemen pemeringkatan (Ranking) penagihan depo Read-Only yang dilengkapi indikator visual warna peringkat (Top 3 Green, Bottom Red).

- Daily Sales Achievement Report `/daily-sales-achievement-report`
  Komponen : Transit Area, Salesman, Target
  Fungsi : Menyajikan laporan harian persentase pencapaian target penjualan oleh tim sales di Transit Area.
  Penjelasan UI : Tampilan berupa table laporan harian pencapaian individu salesman terhadap target depo dilengkapi kolom rasio pencapaian (% Achievement).

- PMB (Penetapan & Monitoring Bonus) `/pmb`
  Komponen : Period, Transit Area, Salesman ID, Target Collection, Achieved Collection, Incentive Rate, Penalty Amount, Total PMB Bonus, Status
  Fungsi : Mengelola skema insentif, pemantauan target pencapaian penagihan, dan kalkulasi bonus bulanan salesman/depo (PMB).
  Penjelasan UI : Tampilan awal berupa table data grid pencairan insentif/bonus PMB. Tombol Tambah memunculkan Modal Form berstruktur Periode, Lookup Salesman/Depo, Kalkulasi Otomatis (Penagihan vs Target, Rate Insentif, Denda Overdue), Total Bonus Bersih, serta Tombol Persetujuan (Approve PMB).
  
## Marketing `#`

- Dashboard Marketing `marketing-dashboard`
  Komponen : Total_Prospect_Non_Customer, Total_Marketing_Visits_Today, Total_NOO_This_Month, Total_Incentive_Paid, Chart_Visit_Trend, Chart_NOO_Growth_By_Area, Chart_Commission_Distribution_By_Sales, Table_Top_Performers_Sales
  Fungsi : Menyajikan gambaran umum secara visual dan real-time mengenai aktivitas prospeksi lapangan, jumlah kunjungan marketing, pertumbuhan Outlet Baru (NOO), serta proyeksi dan realisasi komisi/insentif tim sales/BDH.
  Penjelasan UI : Tampilan awal berupa dashboard interaktif berisi ringkasan widget angka (card stat), grafik tren kunjungan lapangan, diagram pertumbuhan pelanggan baru per wilayah, serta tabel peringkat pencapaian tim marketing. Halaman bersifat Read-Only dan dilengkapi filter periode tanggal, area/rayon, serta nama sales/marketing.

- Non Customer `non-customer`
  Komponen : Non Customer ID, Name, Contact, Position, Address1, Address2, Kecamatan, Kabupaten, City, ZIP, Province, Country, Channel_Outlet, Kode_area, Phone, Mobile_Phone, Email, Employee_Id, Created_Date, Note, NPWP, Status
  Fungsi : Mengelola data calon pelanggan (prospek/lead) yang belum terdaftar secara resmi sebagai pelanggan aktif, mencakup profil lokasi, kontak PIC, serta status konversi prospek.
  Penjelasan UI : Tampilan awal berupa table data grid berfitur pencarian cepat dan filter status prospek (Prospect/In Follow-up/Converted/Rejected). Tombol [ + Tambah Non Customer ] memunculkan Pop-up Modal Form berstruktur Header Info, Alamat Lengkap, Kontak PIC, serta Sales Penanggung Jawab. Edit memunculkan modal terisi, Hapus memunculkan alert konfirmasi.

- Marketing Visit `marketing-visit`
  Komponen : Date, Hari, ID, Name, Tipe, NOO
  Fungsi : Mencatat dan memantau aktivitas kunjungan harian tim marketing/sales ke lokasi prospek atau pelanggan, serta mengidentifikasi hasil kunjungan berupa pembukaan toko/outlet baru (NOO).
  Penjelasan UI : Tampilan awal berupa table log kunjungan harian berpenanda indikator status NOO (Ya/Tidak). Tombol Tambah memunculkan Modal Form input Tanggal, Hari, Lookup Non Customer/Customer ID, Tipe Kunjungan (Canvas/Routine/Prospeksi), dan Checkbox NOO. Edit memunculkan modal terisi, Hapus memunculkan alert konfirmasi.

- New Customer Incentive `new-customer-incentive`
  Komponen : TA, Sales, Customer, Pemilik, Alamat, City, Insentif Sales, Insentif BDH, Bonus DOS, Total
  Fungsi : Menghitung dan mengelola alokasi insentif atas keberhasilan akuisisi pelanggan baru (NOO) bagi Sales, BDH (Business Development Head), dan DOS (Director of Sales).
  Penjelasan UI : Tampilan awal berupa table data grid rekapitulasi insentif NOO per area/depo. Tombol Tambah memunculkan Modal Form pemilihan Transit Area (TA), Sales, dan Customer terkonversi, yang secara otomatis menghitung proporsi Insentif Sales, BDH, dan DOS.

- Index Komisi Collection `index-komisi-collection`
  Komponen : Type, Min, Max, Index Commission
  Fungsi : Menetapkan matriks acuan nilai indeks komisi berdasarkan rentang pencapaian target penagihan piutang (Collection).
  Penjelasan UI : Tampilan berupa table aturan skema indeks komisi. Tombol Tambah / Edit memunculkan Modal Form input Tipe Kategori, Nilai Persentase Minimal (Min), Nilai Persentase Maksimal (Max), serta Bobot Index Commission. Hapus memunculkan alert konfirmasi.

- Marketing Komisi Collection `marketing-komisi-collection`
  Komponen : TA, Marketing, Target Usia Piutang > 90, Pencapaian, Persentase, Index Target >= 30, Komisi, Target Usia Piutang <= 90, Pencapaian, Persentase, Index Target >= 80, Komisi, Total Komisi
  Fungsi : Mengkalkulasi dan memproses pencairan komisi penagihan bagi tim marketing/sales berdasarkan performa penagihan piutang lancar (<=90 hari) dan piutang lama (>90 hari).
  Penjelasan UI : Tampilan awal berupa table kalkulasi komisi bulanan per salesman. Tombol Tambah / Hitung Komisi memunculkan Modal Form berstruktur Pilihan Periode, Transit Area (TA), Lookup Marketing, serta data grid kalkulasi otomatis yang membandingkan target vs pencapaian piutang, penetapan indeks, dan total nilai komisi bersih yang berhak diterima.

## QC 

- Monitoring Pengujian Kemasan `monitoring-pengujian-kemasan`
  Komponen : Product ID, Product Name, User QC, Dimensi Kemasan (P [mm], L [mm], T [mm], A [mm], ø B [mm], T [mm], S [mm]), Spesifikasi yang Diuji (Kebersihan, Kualitas, Layout, Drop Test, Seep Test, Ball Test, Dimensi Visual, Kesimpulan, Keputusan), Note
  Fungsi : Mengelola dan mencatat pengujian presisi dimensi fisik serta kekuatan teknis wadah kemasan (kaleng, galon, pail, dll.) guna memastikan kemasan memenuhi standar keamanan dan ketahanan sebelum digunakan di lini *filling/packing*.
  Penjelasan UI : Tampilan awal berupa table data grid daftar hasil pengujian kemasan berpenanda badge keputusan (Approve/Reject/Rework). Tombol [ + Tambah Pengujian Kemasan ] memunculkan Pop-up Modal Form berstruktur Header Info, Grid Dimensi Fisik (mm), Grid Pengujian Teknis, dan Footer Keputusan.

- Monitoring Berat Dalam Kemasan `monitoring-berat-dalam-kemasan`
  Komponen : Production ID, Date Test, Product ID, Product Name, Batch No., Kaleng 0.1 L Awal, Kaleng 0.1 L Tengah, Kaleng 0.1 L Akhir, Kaleng 0.2 L Awal, Kaleng 0.2 L Tengah, Kaleng 0.2 L Akhir, Kaleng 0.4 L Awal, Kaleng 0.4 L Tengah, Kaleng 0.4 L Akhir, Kaleng 0.45 L Awal, Kaleng 0.45 L Tengah, Kaleng 0.45 L Akhir, Kaleng 0.9 L Awal, Kaleng 0.9 L Tengah, Kaleng 0.9 L Akhir, Kaleng Awal, Kaleng Tengah, Kaleng Akhir, Galon Awal, Galon Tengah, Galon Akhir, Pail Awal, Pail Tengah, Pail Akhir, Liter Awal, Liter Tengah, Liter Akhir, Kaleng 1L Awal, Kaleng 1L Tengah, Kaleng 1L Akhir, User ID
  Fungsi : Memantau dan mencatat konsistensi berat isi produk cair/cat pada berbagai varian kemasan di tiga titik interval proses pengisian (*Awal*, *Tengah*, dan *Akhir* proses filling) untuk menjaga akurasi net volume dan mencegah kerugian *giveaway product*.
  Penjelasan UI : Tampilan awal berupa table data grid pemantauan berat per batch produksi. Tombol [ + Tambah Sampling Berat ] memunculkan Pop-up Modal Form berstruktur Header Info dan Matriks Input Penimbangan berdasarkan Varian Kemasan & Sampling Interval.

- Monitoring Pengujian Bahan Baku `monitoring-pengujian-bahan-baku`
  Komponen : Product ID, Product Name, Batch Number, Supplier, Tanggal Datang, Tanggal Uji, User QC, Spesifikasi yang Diuji (Solid Content, Viscosity, pH, Specific Gravity, Kelembapan, Berat, Panjang, Lebar), Appearance, Color Visual, Kebersihan, Test Gantung, Kualitas Cetak, Kerataan, Drop Test, Kesimpulan, Keputusan, Note
  Fungsi : Mengelola pengujian mutu bahan baku mentah (resin, aditif, solvent, pigmen) yang diterima dari supplier sebelum dialokasikan ke gudang utama atau lini produksi.
  Penjelasan UI : Tampilan awal berupa table data grid hasil lab bahan baku berpenanda status keputusan. Tombol [ + Tambah Pengujian Bahan Baku ] memunculkan Pop-up Modal Form berstruktur Header Info, Parameter Kimia & Fisika, Parameter Visual & Teknis, dan Footer Keputusan.

- Monitoring SPKP (Monitoring Quality Control Base / Giling) `monitoring-spkp`
  Komponen : Product, Type Production, ID Product, Name, Batch No., Tgl. Mulai, Tgl. Selesai, User ID, Appearance, Fineness (μ), Viskositas (ku), Colour, Hiding Power, SG (gr/ml), pH, Solid Content (%), Viscositas (detik), Viscositas NK2 (detik), Gloss (%), Miss Print, Teks, Tampilan, Adhesi, Layout, Kebersihan Kemasan, Kualitas Cetakan, Colour Strenght (%), Ball Test, Matching Test, Drop Test, Cycle Time, Berat, Dimensi Kemasan (Tinggi, Atas, Panjang, ∅ luar, ∅ Ring Dalam), Seep Test, Tinggi, Panjang, Lebar, Panjang/Lebar bibir kuas, Stapler Test, Berat 5'' & 6'', Panjang/Lebar bibir kuas 5'' & 6'', Tinggi 5'' & 6'', Kualitas Cetakan, Stapler Test (4-5 bar), Panjang 5'' & 6'', Lebar 5'' & 6'', Kesimpulan, Keputusan
  Fungsi : Memantau, menguji, dan memverifikasi kualitas adonan dasar (Base/Giling) secara menyeluruh—mulai dari parameter fisikokimia adonan cair (viskositas, kehalusan micron, SG, solid content) hingga standar kelayakan wadah pendukungnya—sebelum diizinkan lanjut ke tahap pewarnaan (CM).
  Penjelasan UI : Tampilan awal berupa table data grid monitoring hasil uji laboratorium adonan Base berpenanda badge status (Approve Base / Reject Base / Rework ADU). Tombol [ + Tambah Uji SPKP ] memunculkan Pop-up Modal Form berstruktur Tab Navigasi:
    1. Tab General & Waktu : Lookup Product/Batch No., Type Production, User ID, Timestamp Mulai/Selesai.
    2. Tab Parameter Kimia/Fisika Adonan : Fineness (μ), Viskositas (ku/NK2/detik), SG, pH, Solid Content (%), Gloss, Hiding Power, Colour Strength.
    3. Tab Pengujian Visual & Fisik Kemasan : Layout, Adhesi, Drop Test, Seep Test, Ball Test, Dimensi Kemasan, serta Pengujian Aset Pendukung (Kuas/Stapler Test).
    4. Footer : Textarea Catatan Laboratorium dan Keputusan (Approve/Reject/Rework ADU).
  Edit memunculkan modal terisi untuk pembaruan data, Hapus memunculkan alert konfirmasi modal.

- Monitoring SPPBJ (Monitoring Quality Control CM / Finished Goods) `monitoring-sppbj`
  Komponen : Product, Type Production, ID Product, Name, Batch No., Tgl. Mulai, Tgl. Selesai, User ID, Appearance, Fineness (μ), Viskositas (ku), Colour, Hiding Power, SG (gr/ml), pH, Solid Content (%), Viscositas (detik), Viscositas NK2 (detik), Gloss (%), Miss Print, Teks, Tampilan, Adhesi, Layout, Kebersihan Kemasan, Kualitas Cetakan, Colour Strenght (%), Ball Test, Matching Test, Drop Test, Cycle Time, Berat, Dimensi Kemasan (Tinggi, Atas, Panjang, ∅ luar, ∅ Ring Dalam), Seep Test, Tinggi, Panjang, Lebar, Panjang/Lebar bibir kuas, Stapler Test, Berat 5'' & 6'', Panjang/Lebar bibir kuas 5'' & 6'', Tinggi 5'' & 6'', Kualitas Cetakan, Stapler Test (4-5 bar), Panjang 5'' & 6'', Lebar 5'' & 6'', Kesimpulan, Keputusan
  Fungsi : Memantau dan menguji kepatuhan kualitas akhir produk hasil proses pewarnaan (*Color Matching* - CM) dan pengemasan, memastikan akurasi pencocokan warna (*Matching Test*), daya tutup, daya rekat (*Adhesi*), dan kekuatan wadah fisik sebelum menerbitkan keputusan kelulusan produk siap rilis ke gudang barang jadi (*Release Production*).
  Penjelasan UI : Tampilan awal berupa table data grid monitoring hasil uji lab CM/Barang Jadi berpenanda badge status (QC Approved / Reject CM / Rework ADU CM). Tombol [ + Tambah Uji SPPBJ ] memunculkan Pop-up Modal Form berstruktur Tab Navigasi:
    1. Tab General & Batch Ref : Lookup Ref SPPBJ/Batch No., Product Name, User ID, Timestamp Mulai/Selesai Uji.
    2. Tab Pengujian Warna & Karakteristik Cat : Matching Test (Delta E/Pencocokan Warna Standar), Colour Strength, Hiding Power, Viskositas, Gloss, Fineness, Adhesi Film Cat.
    3. Tab Quality Control Kemasan & Pengisian : Cek Teks/Miss Print, Kualitas Cetakan, Kebersihan, Seep Test, Drop Test, Dimensi & Integritas Kemasan.
    4. Footer : Textarea Ringkasan QC dan Radio Button Keputusan Final (Approve / Rework CM / Reject).
  Edit memunculkan modal terisi untuk pembaruan data, Hapus memunculkan alert konfirmasi modal.

## Riset

- Data dan Metode Aplikasi
  Komponen: Data dan metode Aplikasi, Ketentuan
  Fungsi: Mengelola dan mendefinisikan standar metode serta parameter teknis pengaplikasian produk hasil riset (misalnya cara aplikasi, alat yang digunakan, hingga kondisi lingkungan yang dipersyaratkan).
  Penjelasan UI: Tampilan berupa tabel daftar data dan metode aplikasi beserta ketentuannya. Terdapat tombol Tambah yang memunculkan pop-up modal form berisi kolom input untuk "Data dan metode Aplikasi" serta "Ketentuan". Aksi Edit memunculkan modal yang sama dengan data terisi, dan Hapus memunculkan alert konfirmasi.

- Instruksi Penyaringan
  Komponen: Instruksi Penyaringan
  Fungsi: Menyimpan standar prosedur atau instruksi khusus terkait proses penyaringan (filtering) bahan atau produk selama tahap riset uji coba dan produksi massal.
  Penjelasan UI: Tampilan berupa tabel daftar instruksi penyaringan. Tombol Tambah memunculkan pop-up modal form dengan field input teks tunggal untuk instruksi. Edit dan Hapus memiliki standar alert dan form yang sama.

- Jenis Saringan
  Komponen: Jenis Saringan
  Fungsi: Mendata tipe atau ukuran saringan (mesh/filter) yang wajib digunakan sesuai dengan standar produk yang sedang diteliti.
  Penjelasan UI: Tampilan tabel sederhana berisi daftar jenis saringan. Tombol Tambah memunculkan pop-up modal form untuk menginputkan nama/jenis saringan.

- Cost
  Komponen: Nama Biaya, Biaya
  Fungsi: Mencatat dan mengatur komponen biaya tambahan (overhead, operasional lab, pengujian eksternal, dll.) di luar bahan baku utama yang timbul selama proses riset.
  Penjelasan UI: Tampilan tabel menampilkan daftar Nama Biaya dan nominal Biayanya. Tombol Tambah memunculkan pop-up modal form untuk menginput Nama Biaya dan nilai Biaya (currency).

- Template
  Komponen: ID, Date, Product ID, Name, FA, Rev, Status, User, Notes (Table Halaman Utama). Product ID, Name, Notes, Template ID, Date, User ID, FA, Rev, Status (Untuk modal tambah bagian atas). Kode bahan, nama bahan, formula% (Untuk Table bagian bawah ada tombol tambah baris). No. LHR, Template ID, Product ID, Nama Formula, FA, Rev, Price Method, Report ID, Created Dat, Status, User ID, Posting Date (Untuk modal tambah laporan bagian atas).
  Fungsi: Mengelola template dasar formulasi produk (BOM Riset) beserta versi revisinya sebagai acuan standar sebelum diuji coba menjadi Laporan Hasil Riset (LHR).
  Penjelasan UI: Tampilan awal berupa tabel daftar template. Tombol Tambah akan membuka halaman/modal form yang terbagi menjadi dua area. Bagian atas memuat form informasi header (Product ID, Name, FA, Rev, dll.). Bagian bawah memuat tabel dinamis untuk menginput komponen formula (Kode Bahan, Nama Bahan, Formula %) yang dilengkapi dengan tombol "Tambah Baris" untuk memasukkan multi-bahan baku.

- Riset Report
  Komponen: Doc ID, No LHR, Price Method, Product ID, Name, Created Date, Posting Date, Status, FA, Rev, Grand Total, Notes, User ID (Untuk Halaman awal). Kode Bahan, Nama Bahan, Formula %, Harga COGS, Formula (KG), Faktor Konversi, Total Formula, Total Harga (Untuk table bagian bawah).
  Fungsi: Mencatat rincian laporan analisis biaya dan komposisi finansial dari sebuah formula riset, serta menampilkan kalkulasi otomatis harga pokok produksi (COGS) dari formulasi yang sedang diuji.
  Penjelasan UI: Tampilan awal berupa tabel rekap dokumen laporan riset. Saat melihat detail atau menambah data, antarmuka menampilkan Header informasi LHR di bagian atas, dan tabel kalkulasi bahan terperinci di bagian bawah. Di bawah tabel tersebut terdapat area field Notes untuk catatan tambahan. 

- Riset Result Report
  Komponen: Doc ID, Date, Last Status Update, No.LHR, Status, Riset ID, Product, Name, FA, Revisi, Substart, Pemakaian, Hapus STD Lama, Instruksi Penyaringan, Jenis Saringan, User, Notes (Untuk table halaman awal). Riset ID, No. LHR, Product ID, Nama Formula, FA, Rev, Substart, Report ID, Created Date, Status, User ID, Pemakaian, Hapus STD Lama, Last Status Update (Untuk modal tambah bagian atas). Tab Formulasi (Kode Bahan, Nama Bahan, Urutan Proses, Jumlah%, Adjustmen). Tab Hasil Pengujian (Pengujian, Spek). Tab Data Aplikasi (data dan metode aplikasi, Ketentuan).
  Fungsi: Mendokumentasikan hasil pengujian komprehensif dari suatu riset, mencakup komposisi final, parameter kelulusan uji laboratorium, hingga metode pengaplikasiannya, sebagai penentu rilis formula standar baru.
  Penjelasan UI: Tampilan awal berupa tabel daftar hasil riset (Result Report). Tombol Tambah/Detail akan membuka Halaman Proses (Terpisah) dengan Header Informasi LHR di bagian atas. Bagian bawah menggunakan sistem 3 Tab Navigasi: Tab Formulasi (Tabel input urutan proses & adjustment), Tab Hasil Pengujian (Tabel input spesifikasi uji lab), dan Tab Data Aplikasi (Tabel instruksi metode). Di bagian footer/paling bawah halaman terdapat field dropdown dan teks untuk Instruksi Penyaringan, Jenis Saringan, dan Notes.

## System Menu
- Dashboard `/`
- Setting `setting`
  - User `user`
  - Role Permision `role-permision`
  - Role `role-menu`
  - Config App `configuration`
  - Menu `menu`
