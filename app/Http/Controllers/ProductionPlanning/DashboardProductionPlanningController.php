<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class DashboardProductionPlanningController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', 'production-planning-dashboard');
    }

    public function index()
    {
        return view('production-planning.dashboard.index');
    }

    public function data()
    {
        $stats = [
            'spk_active'              => 14,
            'target_tonase_today'     => 85.5,
            'realisasi_tonase_today'  => 72.3,
            'schedule_compliance'     => 87.5,
            'machine_grinding_util'   => 78.2,
            'overall_yield'           => 94.1,
            'material_shortage_count' => 5,
        ];

        $pipeline = [
            ['stage' => 'Draft',       'count' => 6,  'color' => 'secondary'],
            ['stage' => 'Planned',     'count' => 8,  'color' => 'info'],
            ['stage' => 'In Progress', 'count' => 12, 'color' => 'primary'],
            ['stage' => 'QC Pending',  'count' => 4,  'color' => 'warning'],
            ['stage' => 'Completed',   'count' => 25, 'color' => 'success'],
        ];

        $chart_hourly = [
            'labels' => ['06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'],
            'data'   => [3.2, 5.8, 7.5, 8.1, 7.8, 6.2, 2.5, 6.8, 8.2, 7.5, 6.0, 2.7],
        ];

        $chart_base_vs_cm_vs_packing = [
            'labels'   => ['Base Paint','Color Matching','Packing'],
            'plan'     => [35.0, 28.5, 22.0],
            'realisasi'=> [32.5, 26.0, 13.8],
        ];

        $urgent_spk = [
            ['no' => 1, 'spk_no' => 'SPK-2026-0018', 'customer' => 'PT Cat Nusantara', 'product' => 'Wall Paint White 20L', 'target_tonase' => 12.5, 'due_date' => '2026-07-30', 'status' => 'Material Shortage', 'status_color' => 'danger'],
            ['no' => 2, 'spk_no' => 'SPK-2026-0015', 'customer' => 'CV Dinding Indah', 'product' => 'Primer Grey 5L', 'target_tonase' => 8.0, 'due_date' => '2026-07-30', 'status' => 'Pending QC', 'status_color' => 'warning'],
            ['no' => 3, 'spk_no' => 'SPK-2026-0020', 'customer' => 'Toko Bangunan Jaya', 'product' => 'Top Coat Clear 15L', 'target_tonase' => 15.0, 'due_date' => '2026-07-31', 'status' => 'Not Started', 'status_color' => 'secondary'],
            ['no' => 4, 'spk_no' => 'SPK-2026-0012', 'customer' => 'PT Maju Jaya', 'product' => 'Wall Paint Cream 20L', 'target_tonase' => 10.0, 'due_date' => '2026-07-29', 'status' => 'Overdue', 'status_color' => 'danger'],
            ['no' => 5, 'spk_no' => 'SPK-2026-0019', 'customer' => 'Indogloss Paint', 'product' => 'Primer Putih 5L', 'target_tonase' => 6.5, 'due_date' => '2026-07-31', 'status' => 'Material Shortage', 'status_color' => 'danger'],
            ['no' => 6, 'spk_no' => 'SPK-2026-0016', 'customer' => 'PT Cat Nusantara', 'product' => 'Wall Paint Blue 10L', 'target_tonase' => 7.0, 'due_date' => '2026-07-30', 'status' => 'Pending Approval', 'status_color' => 'info'],
        ];

        return response()->json([
            'stats'                   => $stats,
            'pipeline'                => $pipeline,
            'chart_hourly'            => $chart_hourly,
            'chart_base_vs_cm_vs_packing' => $chart_base_vs_cm_vs_packing,
            'urgent_spk'              => $urgent_spk,
        ]);
    }
}