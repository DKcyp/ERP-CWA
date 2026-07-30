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
                        'New Purchase Request' => '#',
                        'Purchase Request List' => '#',
                        'Purchase Request Fulfilment Report' => '#',
                    ],
                    'Purchase Order' => [
                        'New Purchase Order' => '#',
                        'Purchase Order List' => '#',
                        'Purchase Fulfillment Report' => '#',
                        'Daily Purchase Order Report' => '#',
                    ],
                    'Purchase Invoice' => [
                        'New Purchase Invoice' => '#',
                        'Purchase Invoice List' => '#',
                        'Daily Purchase Invoice Report' => '#',
                        'Monthly Purchase by Supplier Report' => '#',
                    ],
                    'STBJ' => '#',
                    'Supplier Payment' => [
                        'New Supplier Payment' => '#',
                        'New Supplier Down Payment' => '#',
                        'Supplier Payment List' => '#',
                        'Supp. Outstanding List' => '#',
                        'Daily Supplier Payment Report' => '#',
                        'Daily Supplier Payment List' => '#',
                    ],
                    'Purchase Return' => [
                        'New Purchase Return' => '#',
                        'Purchase Return List' => '#',
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
                    'Customer Master' => '#',
                    'Customer Group' => '#',
                    'Customer Area' => '#',
                    'WA Name' => '#',
                    'Customer Tools' => '#',
                    'Customer Centre' => '#',
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
                        'Sales Order List' => '#',
                        'Sales Order Fulfilment' => '#',
                        'Daily Sales Order Report' => '#',
                        'Daily Sales Order Invoice Report' => '#',
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
                        'Sales Reports' => [
                            'Sales by Customer' => '#',
                            'Sales by Product' => '#',
                            'Sales by Supplier' => '#',
                            'Sales by Salesman' => '#',
                            'Sales by Category' => '#',
                        ],
                    ],
                    'Tanda Terima Penagihan' => '#',
                    'Customer Payment' => [
                        'Customer Payment List' => '#',
                        'Cust. Outstanding List' => '#',
                        'Daily Customer Payment Report' => '#',
                        'Outstanding per Customer Report' => '#',
                        'Customer Payment Check' => '#',
                        'Customer Outstanding per Date Report' => '#',
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
            'Transit Area' => [
                'icon' => 'bi bi-signpost-2-fill',
                'items' => [
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
            'Master Data' => [
                'icon' => 'bi bi-database-fill',
                'items' => [
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
                        'url' => '#',
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
                                'url' => '#',
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
                                    'url' => Str::slug($grandChildName),
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
                                'url' => Str::slug($childName),
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
                        'url' => Str::slug($name),
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
