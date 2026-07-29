<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use View;

class PurchaseRequestFulfilmentController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-request-fulfilment');
        $this->initDummyData();
        View::share('activeMenu', 'purchase-request-fulfilment-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            ['pr_no'=>'PR-2026-0001','pr_date'=>'2026-07-01','department'=>'Produksi','material_id'=>'MAT-001','material_name'=>'Tepung Terigu Protein Tinggi','qty_requested'=>100,'qty_ordered_total'=>100,'linked_po_numbers'=>'PO-2026-0001, PO-2026-0005','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0001','pr_date'=>'2026-07-01','department'=>'Produksi','material_id'=>'MAT-002','material_name'=>'Gula Pasir Kristal','qty_requested'=>50,'qty_ordered_total'=>50,'linked_po_numbers'=>'PO-2026-0001','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0001','pr_date'=>'2026-07-01','department'=>'Produksi','material_id'=>'MAT-003','material_name'=>'Mentega Wisman','qty_requested'=>30,'qty_ordered_total'=>20,'linked_po_numbers'=>'PO-2026-0005','status'=>'Partial'],
            ['pr_no'=>'PR-2026-0002','pr_date'=>'2026-07-05','department'=>'Gudang','material_id'=>'MAT-004','material_name'=>'Kardus Box 30x20x15','qty_requested'=>200,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0002','pr_date'=>'2026-07-05','department'=>'Gudang','material_id'=>'MAT-005','material_name'=>'Bubble Wrap 1m','qty_requested'=>50,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0002','pr_date'=>'2026-07-05','department'=>'Gudang','material_id'=>'MAT-006','material_name'=>'Solasi Bening 2 inch','qty_requested'=>100,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0002','pr_date'=>'2026-07-05','department'=>'Gudang','material_id'=>'MAT-007','material_name'=>'Stiker Label Produk','qty_requested'=>500,'qty_ordered_total'=>300,'linked_po_numbers'=>'PO-2026-0008','status'=>'Partial'],
            ['pr_no'=>'PR-2026-0002','pr_date'=>'2026-07-05','department'=>'Gudang','material_id'=>'MAT-008','material_name'=>'Plastik OPP 30cm','qty_requested'=>30,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0003','pr_date'=>'2026-07-10','department'=>'Produksi','material_id'=>'MAT-009','material_name'=>'Pewarna Makanan Merah','qty_requested'=>10,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0003','pr_date'=>'2026-07-10','department'=>'Produksi','material_id'=>'MAT-010','material_name'=>'Perasa Vanila','qty_requested'=>5,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0004','pr_date'=>'2026-07-15','department'=>'Engineering','material_id'=>'MAT-011','material_name'=>'Bearing 6205-2RS','qty_requested'=>10,'qty_ordered_total'=>10,'linked_po_numbers'=>'PO-2026-0003','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0004','pr_date'=>'2026-07-15','department'=>'Engineering','material_id'=>'MAT-012','material_name'=>'V-Belt B68','qty_requested'=>5,'qty_ordered_total'=>5,'linked_po_numbers'=>'PO-2026-0003','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0004','pr_date'=>'2026-07-15','department'=>'Engineering','material_id'=>'MAT-013','material_name'=>'O-Ring 50mm','qty_requested'=>20,'qty_ordered_total'=>20,'linked_po_numbers'=>'PO-2026-0006','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0004','pr_date'=>'2026-07-15','department'=>'Engineering','material_id'=>'MAT-014','material_name'=>'Grease EP2 18kg','qty_requested'=>2,'qty_ordered_total'=>2,'linked_po_numbers'=>'PO-2026-0006','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0005','pr_date'=>'2026-07-20','department'=>'Produksi','material_id'=>'MAT-015','material_name'=>'Cokelat Bubuk Van Houten','qty_requested'=>25,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0005','pr_date'=>'2026-07-20','department'=>'Produksi','material_id'=>'MAT-016','material_name'=>'Susu Bubuk Full Cream','qty_requested'=>40,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0005','pr_date'=>'2026-07-20','department'=>'Produksi','material_id'=>'MAT-017','material_name'=>'Telur Ayam','qty_requested'=>10,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0005','pr_date'=>'2026-07-20','department'=>'Produksi','material_id'=>'MAT-018','material_name'=>'Tepung Maizena','qty_requested'=>15,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0006','pr_date'=>'2026-07-22','department'=>'Gudang','material_id'=>'MAT-019','material_name'=>'NaOH (Sodium Hydroxide)','qty_requested'=>20,'qty_ordered_total'=>10,'linked_po_numbers'=>'PO-2026-0009','status'=>'Partial'],
            ['pr_no'=>'PR-2026-0006','pr_date'=>'2026-07-22','department'=>'Gudang','material_id'=>'MAT-020','material_name'=>'Citric Acid','qty_requested'=>10,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0006','pr_date'=>'2026-07-22','department'=>'Gudang','material_id'=>'MAT-021','material_name'=>'Pembersih Lantai 5L','qty_requested'=>30,'qty_ordered_total'=>30,'linked_po_numbers'=>'PO-2026-0009','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0006','pr_date'=>'2026-07-22','department'=>'Gudang','material_id'=>'MAT-022','material_name'=>'Hand Sanitizer 500ml','qty_requested'=>50,'qty_ordered_total'=>50,'linked_po_numbers'=>'PO-2026-0009','status'=>'Completed'],
            ['pr_no'=>'PR-2026-0006','pr_date'=>'2026-07-22','department'=>'Gudang','material_id'=>'MAT-023','material_name'=>'Sarung Tangan Karet','qty_requested'=>100,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
            ['pr_no'=>'PR-2026-0006','pr_date'=>'2026-07-22','department'=>'Gudang','material_id'=>'MAT-024','material_name'=>'Apron Plastik','qty_requested'=>20,'qty_ordered_total'=>0,'linked_po_numbers'=>'-','status'=>'Unfulfilled'],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('material-management.purchase-request-fulfilment-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['pr_no'] ?? '', $q) !== false ||
                stripos($i['department'] ?? '', $q) !== false ||
                stripos($i['material_name'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $status = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('qty_outstanding', fn($r) => ($r['qty_requested'] ?? 0) - ($r['qty_ordered_total'] ?? 0))
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'Unfulfilled' => 'danger',
                    'Partial'     => 'warning text-dark',
                    'Completed'   => 'success',
                ];
                $s = $r['status'] ?? 'Unfulfilled';
                $c = $map[$s] ?? 'secondary';
                return '<span class="badge bg-'.$c.'">'.$s.'</span>';
            })
            ->rawColumns(['status_badge'])->make(true);
    }
}