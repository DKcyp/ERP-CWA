<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class DashboardMaterialController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', 'material-dashboard');
    }

    public function index()
    {
        return view('material-management.dashboard.index');
    }

    public function data()
    {
        $stats = [
            'total_pr_pending'    => 12,
            'total_po_active'     => 28,
            'total_stbj_today'    => 5,
            'total_ap_outstanding'=> 47,
            'stock_alert_count'   => 8,
        ];

        $pipeline = [
            ['stage' => 'PR (Pengajuan)',    'count' => 45, 'completed' => 33, 'color' => 'primary'],
            ['stage' => 'PO (Pemesanan)',    'count' => 38, 'completed' => 28, 'color' => 'info'],
            ['stage' => 'STBJ (Penerimaan)', 'count' => 30, 'completed' => 22, 'color' => 'warning'],
            ['stage' => 'Invoice (Tagihan)', 'count' => 25, 'completed' => 18, 'color' => 'success'],
        ];

        $chart_po_vs_stbj = [
            'labels'   => ['Jan','Feb','Mar','Apr','May','Jun'],
            'po_data'  => [35, 42, 38, 50, 45, 52],
            'stbj_data'=> [30, 35, 32, 42, 40, 48],
        ];

        $chart_monthly = [
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun'],
            'data'   => [12500000, 15200000, 13800000, 18500000, 16200000, 21000000],
        ];

        $pending_docs = [
            ['no' => 1, 'type' => 'PR', 'number' => 'PR-2026-0045', 'supplier' => 'PT Sumber Jaya', 'date' => '2026-07-28', 'status' => 'Pending Approval', 'status_color' => 'warning'],
            ['no' => 2, 'type' => 'PR', 'number' => 'PR-2026-0044', 'supplier' => 'CV Maju Bersama', 'date' => '2026-07-27', 'status' => 'Pending Approval', 'status_color' => 'warning'],
            ['no' => 3, 'type' => 'PO', 'number' => 'PO-2026-0028', 'supplier' => 'PT Multi Makmur', 'date' => '2026-07-26', 'status' => 'Awaiting STBJ', 'status_color' => 'info'],
            ['no' => 4, 'type' => 'PO', 'number' => 'PO-2026-0027', 'supplier' => 'PT Sumber Jaya', 'date' => '2026-07-25', 'status' => 'Awaiting STBJ', 'status_color' => 'info'],
            ['no' => 5, 'type' => 'STBJ', 'number' => 'STBJ-2026-0018', 'supplier' => 'CV Maju Bersama', 'date' => '2026-07-28', 'status' => 'Awaiting Invoice', 'status_color' => 'primary'],
            ['no' => 6, 'type' => 'AP', 'number' => 'INV-2026-0032', 'supplier' => 'PT Multi Makmur', 'date' => '2026-07-24', 'status' => 'Overdue 5 days', 'status_color' => 'danger'],
            ['no' => 7, 'type' => 'AP', 'number' => 'INV-2026-0030', 'supplier' => 'PT Sumber Jaya', 'date' => '2026-07-22', 'status' => 'Overdue 7 days', 'status_color' => 'danger'],
            ['no' => 8, 'type' => 'STOCK', 'number' => 'WH-MAIN-A01', 'supplier' => 'Gudang Utama', 'date' => '2026-07-28', 'status' => 'Low Stock Alert', 'status_color' => 'danger'],
        ];

        return response()->json([
            'stats'          => $stats,
            'pipeline'       => $pipeline,
            'chart_po_vs_stbj' => $chart_po_vs_stbj,
            'chart_monthly'  => $chart_monthly,
            'pending_docs'   => $pending_docs,
        ]);
    }
}