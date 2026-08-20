<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $roles = Role::all();

        $structure = [
            'Material Management' => [
                'icon' => 'bi bi-box-seam-fill',
                'items' => [
                    'Supplier Master' => '#',
                    'Supplier Group' => '#',
                    'Supplier Center' => '#',
                    'Supplier Balance Summary' => '#',
                    'Purchase Request' => [
                        'New Purchase Request' => 'purchase-request-list',
                        'Purchase Request List' => 'purchase-request-list',
                        'Purchase Request Fulfilment Report' => 'purchase-request-fulfilment-report',
                    ],
                    'Purchase Order' => [
                        'New Purchase Order' => 'purchase-order-list',
                        'Purchase Order List' => 'purchase-order-list',
                        'Purchase Fulfillment Report' => 'purchase-fulfillment-report',
                        'Daily Purchase Order Report' => 'daily-purchase-order-report',
                    ],
                    'Purchase Invoice' => [
                        'New Purchase Invoice' => 'purchase-invoice-list',
                        'Purchase Invoice List' => 'purchase-invoice-list',
                        'Daily Purchase Invoice Report' => 'daily-purchase-invoice-report',
                        'Monthly Purchase by Supplier Report' => 'monthly-purchase-by-supplier-report',
                    ],
                    'STBJ' => '#',
                    'Supplier Payment' => [
                        'New Supplier Payment' => 'supplier-payment-list',
                        'New Supplier Down Payment' => 'supplier-payment-list',
                        'Supplier Payment List' => 'supplier-payment-list',
                        'Supp. Outstanding List' => 'supp-outstanding-list',
                        'Daily Supplier Payment Report' => 'daily-supplier-payment-report',
                        'Daily Supplier Payment List' => 'daily-supplier-payment-list',
                    ],
                    'Purchase Return' => [
                        'New Purchase Return' => 'purchase-return-list',
                        'Purchase Return List' => 'purchase-return-list',
                    ],
                    'SJBB' => '#',
                    'Stock Adjustment' => [
                        'Stock Adjustment Use' => '#',
                        'New Stock Adjustment (Standard)' => '#',
                        'New Stock Adjustment (Internal Use)' => '#',
                        'Stock Adjustment List' => '#',
                        'Daily Stock Adjustment Report' => '#',
                        'Daily Stock Adjustment Track Report' => '#',
                        'Daily Stock Adjustment Cost Report' => '#',
                    ],
                    'Stock Transfer' => [
                        'New Stock Transfer' => '#',
                        'Stock Transfer List' => '#',
                        'Stock Transfer Shipment Preparation' => '#',
                        'Stock Transfer Shipment Preparation List' => '#',
                        'New Stock Transfer Request' => '#',
                        'Stock Transfer Request List' => '#',
                        'Daily Stock Transfer Report' => '#',
                        'Stock Transfer Fulfilment' => '#',
                    ],
                    'Stock Convertion' => '#',
                    'Material Template' => '#',
                ]
            ],
            'Sales & Distribution' => [
                'icon' => 'bi bi-cart-check-fill',
                'items' => [
                    'Dashboard Sales & Distribution' => 'sales-dashboard',
                    'Sales Report' => 'sales-report',
                    'Customer Balance Summary' => '#',
                    'AR Warehouse Report' => '#',
                    'Customer Point' => [
                        'Point Setting' => '#',
                        'Customer Point Promo Rule' => '#',
                        'Category Exception' => '#',
                        'Product Point Claim Setup' => '#',
                        'Claim Product' => '#',
                        'Claim Product Daily Report' => '#',
                    ],
                    'Sales Order' => [
                        'Sales Order List' => 'sales-order-list',
                        'Sales Order Fulfilment' => 'sales-order-fulfilment',
                        'Daily Sales Order Report' => 'daily-sales-order-report',
                        'Daily Sales Order Invoice Report' => 'daily-sales-order-invoice-report',
                    ],
                    'Packing' => '#',
                    'Sales Invoice' => [
                        'Sales Invoice List' => '#',
                        'Shipment Priority' => '#',
                        'Customer Payment' => '#',
                        'Sales Promo Report' => '#',
                        'Sales Profit Report' => '#',
                        'Sales Omset Report' => '#',
                        'Sales Void Report' => '#',
                        'Sales Commision Report' => '#',
                        'Invoice Payment Report' => '#',
                        'Profit Loss report' => '#',
                    ],
                    'Tanda Terima Penagihan' => '#',
                    'Customer Payment' => [
                        'Customer Payment List' => 'customer-payment-list',
                        'Cust. Outstanding List' => 'cust-outstanding-list',
                        'Daily Customer Payment Report' => 'daily-customer-payment-report',
                        'Outstanding per Customer Report' => 'outstanding-per-customer-report',
                        'Customer Payment Check' => 'customer-payment-check',
                        'Customer Outstanding per Date Report' => 'customer-outstanding-per-date-report',
                    ],
                    'Sales Return' => [
                        'Sales Return List' => '#',
                        'Daily Sales Return Report' => '#',
                    ],
                    'Tanda Terima Invoice' => '#',
                    'Delivery Order' => '#',
                    'Shipment Preparation' => '#',
                    'Purchase Note' => '#',
                    'Sales Commission' => '#',
                    'Tax' => '#',
                ]
            ],
            'System Menu' => [
                'icon' => 'bi bi-gear-fill',
                'items' => [
                    'Dashboard' => '/',
                    'Setting' => [
                        'User' => 'user',
                        'Role Permision' => 'role-permision',
                        'Role' => 'role-menu',
                        'Config App' => 'configuration',
                        'Menu' => 'menu',
                    ],
                ]
            ],
            'Transit Area' => [
                'icon' => 'bi bi-signpost-2-fill',
                'items' => [
                    'Dashboard Transit Area' => 'transit-area-dashboard',
                    'Daily Sales Invoice Report' => '#',
                    'Daily Sales PO Closing Report' => '#',
                    'Daily Sales Return Report' => '#',
                    'Daily Sales by Brand Report' => '#',
                    'Daily Payment Recap Report' => '#',
                    'Cheque Management' => '#',
                    'RLHP (Rincian Laporan Hasil Penagihan)' => '#',
                    'AR per Customer Report' => '#',
                    'Customer AR Position Report' => '#',
                    'Invoice Customer AR List Report' => '#',
                    'Salesman AR List PMB' => '#',
                    'Invoice Expedition' => '#',
                    'Shipping Invoice Expedition' => '#',
                    'Transit Area Target' => '#',
                    'UBM Daily Control Progress Sales Report' => '#',
                    'Transit Area New Brand' => '#',
                    'UBM New Product Sales Report' => '#',
                    'UBM Collection Progress Report' => '#',
                    'Daily Sales Achievement Report' => '#',
                    'PMB (Penetapan & Monitoring Bonus)' => '#',
                ]
            ],
            'Production Process' => [
                'icon' => 'bi bi-box-seam',
                'items' => [
                    'Dashboard Production Process' => 'production-process-dashboard',
                    'SPKP (Surat Perintah Kerja Produksi Base)' => 'production-process-spkp',
                    'SPPBJ (Surat Perintah Pembuatan Barang Jadi / CM)' => 'production-process-sppbj',
                    'SPKP ADU (Surat Perintah Kerja Produksi Adu / Adjustment Base)' => 'production-process-spkpadu',
                    'SPPBJ ADU (Surat Perintah Pembuatan Barang Jadi Adu / Adjustment CM)' => 'production-process-sppbjadu',
                    'SPPI (Surat Perintah Penggunaan Insektisida / Bahan Penolong Khusus)' => 'production-process-sppi',
                    'SPPPK (Surat Perintah Persiapan & Penggunaan Kemasan)' => 'production-process-spppk',
                ]
            ],
            'Marketing' => [
                'icon' => 'bi bi-megaphone-fill',
                'items' => [
                    'Dashboard Marketing' => 'marketing-dashboard',
                    'Non Customer' => 'non-customer',
                    'Marketing Visit' => 'marketing-visit',
                    'New Customer Incentive' => 'new-customer-incentive',
                    'Index Komisi Collection' => 'index-komisi-collection',
                    'Marketing Komisi Collection' => 'marketing-komisi-collection',
                ]
            ],
            'QC' => [
                'icon' => 'bi bi-clipboard-check',
                'items' => [
                    'Monitoring Pengujian Kemasan' => 'monitoring-pengujian-kemasan',
                    'Monitoring Berat Dalam Kemasan' => 'monitoring-berat-dalam-kemasan',
                    'Monitoring Pengujian Bahan Baku' => 'monitoring-pengujian-bahan-baku',
                    'Monitoring SPKP' => 'monitoring-spkp',
                    'Monitoring SPPBJ' => 'monitoring-sppbj',
                ]
            ],
            'Riset' => [
                'icon' => 'bi bi-lightbulb-fill',
                'items' => [
                    'Data dan Metode Aplikasi' => 'riset-data-metode',
                    'Instruksi Penyaringan' => 'riset-intruksi-penyaringan',
                    'Jenis Saringan' => 'riset-jenis-saringan',
                    'Cost' => 'riset-cost',
                    'Template' => 'riset-template',
                    'Riset Report' => 'riset-report',
                    'Riset Result Report' => 'riset-result-report',
                ]
            ],
            'Master Data' => [
                'icon' => 'bi bi-database-fill',
                'items' => [
                    'Customer Master' => 'customer-master',
                    'Customer Group' => 'customer-group',
                    'Customer Area' => 'customer-area',
                    'WA Name' => 'wa-name',
                    'Customer Tools' => 'customer-tools',
                    'Customer Centre' => 'customer-centre',
                    'Product' => [
                        'Product' => '#',
                        'Brand' => '#',
                        'Group' => '#',
                        'Category' => '#',
                        'Series' => '#',
                        'Hierarchie' => '#',
                        'Quality' => '#',
                        'Unit of Measures' => '#',
                        'Discount' => '#',
                        'Price List' => '#',
                        'Sales Discount' => '#',
                        'Purchase Discount' => '#',
                        'Product Cash Back' => '#',
                        'Supplier Product' => '#',
                        'Product Price Log' => '#',
                        'UOM General Convertion' => '#',
                    ],
                    'Warehouse' => '#',
                    'Currency' => '#',
                    'Rate' => '#',
                    'Payment Term' => '#',
                    'Notes' => '#',
                    'Promo Buy N Get M' => '#',
                    'Employee' => '#',
                    'Commission' => '#',
                    'Department' => '#',
                    'Forwarder' => '#',
                    'Edition' => '#',
                    'Bank' => '#',
                    'Document' => '#',
                    'Supplier' => [
                        'Supplier Master' => '#',
                        'Supplier Group' => '#',
                        'Supplier Center' => '#',
                        'Supplier Balance Summary' => '#',
                    ],
                ]
            ],
            'Production Planning' => [
                'icon' => 'bi bi-gear-wide-connected',
                'items' => [
                    'Dashboard Production Planning' => 'production-planning-dashboard',
                    'Pre Production' => [
                        'Pre SPK List' => 'pre-spk-list',
                        'SPK List' => 'spk-list',
                        'Production Scheduling' => 'production-scheduling',
                        'Daily Schedule Report' => 'daily-schedule-report',
                    ],
                    'Kemasan' => [
                        'SPK Kemasan' => 'spk-kemasan',
                        'Jadwal Kemasan' => 'jadwal-kemasan',
                    ],
                    'Production' => [
                        'Production List' => 'production-list',
                        'Release Production' => 'release-production',
                        'Production Commission' => 'production-commission',
                    ],
                    'Production Report' => [
                        'Daily Production Report' => 'daily-production-report',
                        'Daily Production Base Report' => 'daily-production-base-report',
                        'Daily Production Result Report' => 'daily-production-result-report',
                        'Daily Production Result Batch Report (STBJ)' => 'daily-production-result-batch-report',
                        'Daily Production Commission' => 'daily-production-commission-report',
                        'Daily Production Material Cost Report' => 'daily-production-material-cost-report',
                        'Daily Production Result COGS Report' => 'daily-production-result-cogs-report',
                        'Daily Production Packaging Report' => 'daily-production-packaging-report',
                        'Daily Production Material Cost Recap Report' => 'daily-production-material-cost-recap-report',
                    ],
                    'Realisasi Jadwal Base' => [
                        'Realisasi Jadwal Base List' => 'realisasi-jadwal-base-list',
                        'Realisasi Jadwal Base Report' => 'realisasi-jadwal-base-report',
                    ],
                    'Realisasi Jadwal CM' => [
                        'Realisasi Jadwal CM List' => 'realisasi-jadwal-cm-list',
                        'Realisasi Jadwal CM Report' => 'realisasi-jadwal-cm-report',
                    ],
                    'Realisasi Jadwal Canning dan Packing' => [
                        'Realisasi Jadwal Canning dan Packing List' => 'realisasi-jadwal-canning-packing-list',
                        'Realisasi Jadwal Canning dan Packing Report' => 'realisasi-jadwal-canning-packing-report',
                    ],
                    'Realisasi Jadwal Base per Mesin' => [
                        'Realisasi Jadwal Base per Mesin List' => 'realisasi-jadwal-base-per-mesin-list',
                        'Realisasi Jadwal Base per Mesin Report' => 'realisasi-jadwal-base-per-mesin-report',
                    ],
                    'Realisasi Jadwal Pasta' => [
                        'Realisasi Jadwal Pasta List' => 'realisasi-jadwal-pasta-list',
                        'Realisasi Jadwal Pasta Report' => 'realisasi-jadwal-pasta-report',
                    ],
                    'Monitoring Mesin Grinding' => [
                        'Monitoring Mesin Grinding List' => 'monitoring-mesin-grinding-list',
                        'Monitoring Mesin Grinding Report' => 'monitoring-mesin-grinding-report',
                    ],
                    'Production Material Check Stock' => 'production-material-check-stock',
                    'Production Stock Level' => 'production-stock-level',
                    'STBJ' => 'stbj-production',
                    'Product Report' => [
                        'Product Stock' => '#',
                        'Product Stock Summary' => '#',
                        'Product Stock Daily Summary' => '#',
                        'Product Stock Quick View' => '#',
                        'Product Price Info' => '#',
                        'Product Stock Track Report' => '#',
                        'Product Stock Track Date Report' => '#',
                        'Product Stock Track with Price Report' => '#',
                        'Product Stock Minus Report' => '#',
                        'Product Min Max Stock Check' => '#',
                        'Product COGS Monthly Report' => '#',
                        'Product COGS Daily Report' => '#',
                    ],
                ]
            ],
        ];

        $sortIndex = 1;

        foreach ($structure as $topName => $topData) {
            $topMenu = Menu::updateOrCreate([
                'code' => Str::slug($topName),
            ], [
                'name' => $topName,
                'url' => '#',
                'icon' => $topData['icon'],
                'main_menu' => null,
                'menu_hassub' => 1,
                'sort' => $sortIndex++,
                'active' => 1,
            ]);

            $this->assignRoles($topMenu, $roles);

            $subSort = 1;
            foreach ($topData['items'] as $name => $val) {
                if (is_array($val)) {
                    // Level 2 with children
                    $menuL2 = Menu::updateOrCreate([
                        'code' => Str::slug($name),
                    ], [
                        'name' => $name,
                        'url' => $this->resolveMenuUrl($name, '#'),
                        'icon' => 'bi bi-folder2-open',
                        'main_menu' => $topMenu->id,
                        'menu_hassub' => 1,
                        'sort' => $subSort++,
                        'active' => 1,
                    ]);
                    $this->assignRoles($menuL2, $roles);

                    $l3Sort = 1;
                    foreach ($val as $childName => $childUrl) {
                        if (is_array($childUrl)) {
                            $menuL3 = Menu::updateOrCreate([
                                'code' => Str::slug($childName),
                            ], [
                                'name' => $childName,
                                'url' => $this->resolveMenuUrl($childName, '#'),
                                'icon' => 'bi bi-folder2-open',
                                'main_menu' => $menuL2->id,
                                'menu_hassub' => 1,
                                'sort' => $l3Sort++,
                                'active' => 1,
                            ]);
                            $this->assignRoles($menuL3, $roles);

                            $l4Sort = 1;
                            foreach ($childUrl as $grandChildName => $grandChildUrl) {
                                $menuL4 = Menu::updateOrCreate([
                                    'code' => Str::slug($grandChildName),
                                ], [
                                    'name' => $grandChildName,
                                    'url' => is_string($grandChildUrl) ? $grandChildUrl : '#',
                                    'icon' => 'bi bi-file-earmark-text',
                                    'main_menu' => $menuL3->id,
                                    'menu_hassub' => 0,
                                    'sort' => $l4Sort++,
                                    'active' => 1,
                                ]);
                                $this->assignRoles($menuL4, $roles);
                            }
                        } else {
                            $menuL3 = Menu::updateOrCreate([
                                'code' => Str::slug($childName),
                            ], [
                                'name' => $childName,
                                'url' => is_string($childUrl) ? $childUrl : '#',
                                'icon' => 'bi bi-file-earmark-text',
                                'main_menu' => $menuL2->id,
                                'menu_hassub' => 0,
                                'sort' => $l3Sort++,
                                'active' => 1,
                            ]);
                            $this->assignRoles($menuL3, $roles);
                        }
                    }
                } else {
                    // Level 2 leaf item
                    $menuL2 = Menu::updateOrCreate([
                        'code' => Str::slug($name),
                    ], [
                        'name' => $name,
                        'url' => $this->resolveMenuUrl($name, is_string($val) ? $val : '#'),
                        'icon' => 'bi bi-file-earmark',
                        'main_menu' => $topMenu->id,
                        'menu_hassub' => 0,
                        'sort' => $subSort++,
                        'active' => 1,
                    ]);
                    $this->assignRoles($menuL2, $roles);
                }
            }
        }
    }

    private function resolveMenuUrl(string $name, string $fallback = '#'): string
    {
        $map = [
            'Dashboard Sales & Distribution' => 'sales-dashboard',
            'Sales Report' => 'sales-report',
            'Customer Balance Summary' => 'customer-balance-summary',
            'AR Warehouse Report' => 'ar-warehouse-report',
            'Sales Order List' => 'sales-order-list',
            'Sales Order Fulfilment' => 'sales-order-fulfilment',
            'Daily Sales Order Report' => 'daily-sales-order-report',
            'Daily Sales Order Invoice Report' => 'daily-sales-order-invoice-report',
            'Packing' => 'packing',
            'Sales Invoice List' => 'sales-invoice-list',
            'Shipment Priority' => 'shipment-priority',
            'Customer Payment' => 'customer-payment',
            'Sales Promo Report' => 'sales-promo-report',
            'Sales Profit Report' => 'sales-profit-report',
            'Sales Omset Report' => 'sales-omset-report',
            'Sales Void Report' => 'sales-void-report',
            'Sales Commision Report' => 'sales-commision-report',
            'Invoice Payment Report' => 'invoice-payment-report',
            'Profit Loss report' => 'profit-loss-report',
            'Sales Return List' => 'sales-return-list',
            'Daily Sales Return Report' => 'daily-sales-return-report',
            'Tanda Terima Invoice' => 'tanda-terima-invoice',
            'Delivery Order' => 'delivery-order',
            'Shipment Preparation' => 'shipment-preparation',
            'Purchase Note' => 'purchase-note',
            'Sales Commission' => 'sales-commission',
            'Tax' => 'tax',
            'Dashboard Transit Area' => 'transit-area-dashboard',
            'Daily Sales Invoice Report' => 'daily-sales-invoice-report',
            'Daily Sales PO Closing Report' => 'daily-sales-po-closing-report',
            'Daily Sales Return Report' => 'daily-sales-return-report',
            'Daily Sales by Brand Report' => 'daily-sales-by-brand-report',
            'Daily Payment Recap Report' => 'daily-payment-recap-report',
            'Cheque Management' => 'cheque-management',
            'RLHP (Rincian Laporan Hasil Penagihan)' => 'rlhp-rincian-laporan-hasil-penagihan',
            'Dashboard Production Process' => 'production-process-dashboard',
            'SPKP (Surat Perintah Kerja Produksi Base)' => 'production-process-spkp',
            'SPPBJ (Surat Perintah Pembuatan Barang Jadi / CM)' => 'production-process-sppbj',
            'SPKP ADU (Surat Perintah Kerja Produksi Adu / Adjustment Base)' => 'production-process-spkpadu',
            'SPPBJ ADU (Surat Perintah Pembuatan Barang Jadi Adu / Adjustment CM)' => 'production-process-sppbjadu',
            'SPPI (Surat Perintah Penggunaan Insektisida / Bahan Penolong Khusus)' => 'production-process-sppi',
            'SPPPK (Surat Perintah Persiapan & Penggunaan Kemasan)' => 'production-process-spppk',
            'Dashboard Production Planning' => 'production-planning-dashboard',
            'Dashboard' => '/',
            'Setting' => '#',
            'User' => 'user',
            'Role Permision' => 'role-permision',
            'Role' => 'role-menu',
            'Config App' => 'configuration',
            'Menu' => 'menu',
            'Pre SPK List' => 'pre-spk-list',
            'SPK List' => 'spk-list',
            'Production Scheduling' => 'production-scheduling',
            'Daily Schedule Report' => 'daily-schedule-report',
            'SPK Kemasan' => 'spk-kemasan',
            'Jadwal Kemasan' => 'jadwal-kemasan',
            'Production List' => 'production-list',
            'Release Production' => 'release-production',
            'Production Commission' => 'production-commission',
            'Daily Production Report' => 'daily-production-report',
            'Daily Production Commission' => 'daily-production-commission-report',
            'Daily Production Base Report' => 'daily-production-base-report',
            'Daily Production Result Report' => 'daily-production-result-report',
            'Daily Production Result Batch Report (STBJ)' => 'daily-production-result-batch-report',
            'Daily Production Commission Report' => 'daily-production-commission-report',
            'Daily Production Material Cost Report' => 'daily-production-material-cost-report',
            'Daily Production Result COGS Report' => 'daily-production-result-cogs-report',
            'Daily Production Packaging Report' => 'daily-production-packaging-report',
            'Daily Production Material Cost Recap Report' => 'daily-production-material-cost-recap-report',
            'Realisasi Jadwal Base List' => 'realisasi-jadwal-base-list',
            'Realisasi Jadwal Base Report' => 'realisasi-jadwal-base-report',
            'Realisasi Jadwal CM List' => 'realisasi-jadwal-cm-list',
            'Realisasi Jadwal CM Report' => 'realisasi-jadwal-cm-report',
            'Realisasi Jadwal Canning dan Packing List' => 'realisasi-jadwal-canning-packing-list',
            'Realisasi Jadwal Canning dan Packing Report' => 'realisasi-jadwal-canning-packing-report',
            'Realisasi Jadwal Base per Mesin List' => 'realisasi-jadwal-base-per-mesin-list',
            'Realisasi Jadwal Base per Mesin Report' => 'realisasi-jadwal-base-per-mesin-report',
            'Realisasi Jadwal Pasta List' => 'realisasi-jadwal-pasta-list',
            'Realisasi Jadwal Pasta Report' => 'realisasi-jadwal-pasta-report',
            'Monitoring Mesin Grinding List' => 'monitoring-mesin-grinding-list',
            'Monitoring Mesin Grinding Report' => 'monitoring-mesin-grinding-report',
            'Production Material Check Stock' => 'production-material-check-stock',
            'Production Stock Level' => 'production-stock-level',
            'STBJ' => 'stbj-production',
            'Product Stock' => 'product-stock',
            'Product Stock Summary' => 'product-stock-summary',
            'Product Stock Daily Summary' => 'product-stock-daily-summary',
            'Product Stock Quick View' => 'product-stock-quick-view',
            'Product Price Info' => 'product-price-info',
            'Product Stock Track Report' => 'product-stock-track-report',
            'Product Stock Track Date Report' => 'product-stock-track-date-report',
            'Product Stock Track with Price Report' => 'product-stock-track-with-price-report',
            'Product Stock Minus Report' => 'product-stock-minus-report',
            'Product Min Max Stock Check' => 'product-min-max-stock-check',
            'Product COGS Monthly Report' => 'product-cogs-monthly-report',
            'Product COGS Daily Report' => 'product-cogs-daily-report',
            'Supplier Master' => 'supplier-master',
            'Supplier Group' => 'supplier-group',
            'Supplier Center' => 'supplier-center',
            'Supplier Balance Summary' => 'supplier-balance-summary',
            'Purchase Request' => 'purchase-request-list',
            'Purchase Order' => 'purchase-order-list',
            'Purchase Invoice' => 'purchase-invoice-list',
            'New Purchase Request' => 'purchase-request-list',
            'Purchase Request List' => 'purchase-request-list',
            'Purchase Request Fulfilment Report' => 'purchase-request-fulfilment-report',
            'New Purchase Order' => 'purchase-order-list',
            'Purchase Order List' => 'purchase-order-list',
            'Purchase Fulfillment Report' => 'purchase-fulfillment-report',
            'Daily Purchase Order Report' => 'daily-purchase-order-report',
            'New Purchase Invoice' => 'purchase-invoice-list',
            'Purchase Invoice List' => 'purchase-invoice-list',
            'Daily Purchase Invoice Report' => 'daily-purchase-invoice-report',
            'Monthly Purchase by Supplier Report' => 'monthly-purchase-by-supplier-report',
            'Supplier Payment' => 'supplier-payment-list',
            'New Supplier Payment' => 'supplier-payment-list',
            'New Supplier Down Payment' => 'supplier-payment-list',
            'Supplier Payment List' => 'supplier-payment-list',
            'Supp. Outstanding List' => 'supp-outstanding-list',
            'Daily Supplier Payment Report' => 'daily-supplier-payment-report',
            'Daily Supplier Payment List' => 'daily-supplier-payment-list',
            'Purchase Return' => 'purchase-return-list',
            'New Purchase Return' => 'purchase-return-list',
            'Purchase Return List' => 'purchase-return-list',
            'SJBB' => 'sjbb',
            'Stock Adjustment' => 'stock-adjustment-list',
            'Stock Adjustment Use' => 'stock-adjustment-use',
            'New Stock Adjustment (Standard)' => 'stock-adjustment-list',
            'New Stock Adjustment (Internal Use)' => 'stock-adjustment-list',
            'Stock Adjustment List' => 'stock-adjustment-list',
            'Daily Stock Adjustment Report' => 'daily-stock-adjustment-report',
            'Daily Stock Adjustment Track Report' => 'daily-stock-adjustment-track-report',
            'Daily Stock Adjustment Cost Report' => 'daily-stock-adjustment-cost-report',
            'Stock Transfer' => 'stock-transfer-list',
            'New Stock Transfer' => 'stock-transfer-list',
            'Stock Transfer List' => 'stock-transfer-list',
            'Stock Transfer Shipment Preparation' => 'stock-transfer-list',
            'Stock Transfer Shipment Preparation List' => 'stock-transfer-list',
            'New Stock Transfer Request' => 'stock-transfer-list',
            'Stock Transfer Request List' => 'stock-transfer-list',
            'Daily Stock Transfer Report' => 'daily-stock-transfer-report',
            'Stock Transfer Fulfilment' => 'stock-transfer-fulfilment',
            'Stock Convertion' => 'stock-convertion',
            'Material Template' => 'material-template',
            'Customer Master' => 'customer-master',
            'Customer Group' => 'customer-group',
            'Customer Area' => 'customer-area',
            'WA Name' => 'wa-name',
            'Customer Tools' => 'customer-tools',
            'Customer Centre' => 'customer-centre',
            'Product' => 'product',
            'Brand' => 'brand',
            'Group' => 'group',
            'Category' => 'category',
            'Series' => 'series',
            'Hierarchie' => 'hierarchy',
            'Quality' => 'quality',
            'Unit of Measures' => 'uom',
            'Discount' => 'discount',
            'Price List' => 'price-list',
            'Sales Discount' => 'sales-discount',
            'Purchase Discount' => 'purchase-discount',
            'Product Cash Back' => 'product-cash-back',
            'Supplier Product' => 'supplier-product',
            'Product Price Log' => 'product-price-log',
            'UOM General Convertion' => 'uom-general-convertion',
            'Warehouse' => 'warehouse',
            'Currency' => 'currency',
            'Rate' => 'rate',
            'Payment Term' => 'payment-term',
            'Notes' => 'notes',
            'Promo Buy N Get M' => 'promo-buy-n-get-m',
            'Employee' => 'employee',
            'Commission' => 'commission',
            'Department' => 'department',
            'Forwarder' => 'forwarder',
            'Edition' => 'edition',
            'Bank' => 'bank',
            'Document' => 'document',
            'Customer Point' => 'customer-point',
            'Point Setting' => 'point-setting',
            'Customer Point Promo Rule' => 'customer-point-promo-rule',
            'Category Exception' => 'category-exception',
            'Product Point Claim Setup' => 'product-point-claim-setup',
            'Claim Product' => 'claim-product',
            'Claim Product Daily Report' => 'claim-product-daily-report',
            'Sales Order' => 'sales-order-list',
            'Customer Payment List' => 'customer-payment-list',
            'Cust. Outstanding List' => 'cust-outstanding-list',
            'Daily Customer Payment Report' => 'daily-customer-payment-report',
            'Outstanding per Customer Report' => 'outstanding-per-customer-report',
            'Customer Payment Check' => 'customer-payment-check',
            'Customer Outstanding per Date Report' => 'customer-outstanding-per-date-report',
            'AR per Customer Report' => 'ar-per-customer-report',
            'Customer AR Position Report' => 'customer-ar-position-report',
            'Invoice Customer AR List Report' => 'invoice-customer-ar-list-report',
            'Salesman AR List PMB' => 'salesman-ar-list-pmb',
            'Invoice Expedition' => 'invoice-expedition',
            'Shipping Invoice Expedition' => 'shipping-invoice-expedition',
            'Transit Area Target' => 'transit-area-target',
            'UBM Daily Control Progress Sales Report' => 'ubm-daily-control-progress-sales-report',
            'Transit Area New Brand' => 'transit-area-new-brand',
            'UBM New Product Sales Report' => 'ubm-new-product-sales-report',
            'UBM Collection Progress Report' => 'ubm-collection-progress-report',
            'Daily Sales Achievement Report' => 'daily-sales-achievement-report',
            'PMB (Penetapan & Monitoring Bonus)' => 'pmb-penetapan-monitoring-bonus',
            'Monitoring Pengujian Kemasan' => 'monitoring-pengujian-kemasan',
            'Monitoring Berat Dalam Kemasan' => 'monitoring-berat-dalam-kemasan',
            'Monitoring Pengujian Bahan Baku' => 'monitoring-pengujian-bahan-baku',
            'Monitoring SPKP' => 'monitoring-spkp',
            'Monitoring SPPBJ' => 'monitoring-sppbj',
            'Data dan Metode Aplikasi' => 'riset-data-metode',
            'Instruksi Penyaringan' => 'riset-intruksi-penyaringan',
            'Jenis Saringan' => 'riset-jenis-saringan',
            'Cost' => 'riset-cost',
            'Template' => 'riset-template',
            'Riset Report' => 'riset-report',
            'Riset Result Report' => 'riset-result-report',
        ];

        return $map[$name] ?? $fallback;
    }

    private function assignRoles($menu, $roles)
    {
        foreach ($roles as $role) {
            RoleMenu::create([
                'role_id' => $role->id,
                'menu_id' => $menu->id,
            ]);
        }
    }
}
