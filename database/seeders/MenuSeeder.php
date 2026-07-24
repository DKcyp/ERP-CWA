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
            ]
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
