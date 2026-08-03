<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class ProductionProcessDashboardController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', 'production-process');
    }

    public function index()
    {
        return view('production-planning.production-process-dashboard');
    }

    public function data(Request $request)
    {
        $shifts = ['Shift 1 (06-14)','Shift 2 (14-22)','Shift 3 (22-06)'];
        $lines = ['LINE-A1','LINE-A2','LINE-B1','LINE-B2','LINE-C1'];
        $types = ['Base Putih','Base Krem','CM Putih','CM Krem','CM Special'];

        $activeBatches = rand(12, 22);
        $baseCompleted = rand(8, 18);
        $cmCompleted = rand(6, 14);
        $qcPass = rand(88, 99);
        $reworkCount = rand(2, 6);
        $packagingLines = rand(3, 5);

        $batchStatus = [
            ['label' => 'In-Progress Base', 'count' => rand(5, 10), 'color' => '#4e73df'],
            ['label' => 'In-Progress CM', 'count' => rand(3, 8), 'color' => '#1cc88a'],
            ['label' => 'QC Pending', 'count' => rand(2, 6), 'color' => '#f6c23e'],
            ['label' => 'Packing', 'count' => rand(2, 5), 'color' => '#36b9cc'],
        ];

        $hourlyYield = [];
        for ($h = 6; $h <= 22; $h++) {
            $hourlyYield[] = [
                'hour' => str_pad($h, 2, '0', STR_PAD_LEFT).':00',
                'base' => rand(80, 150),
                'cm' => rand(60, 120),
                'packing' => rand(40, 100),
            ];
        }

        $reworkBatches = [];
        for ($i = 0; $i < $reworkCount; $i++) {
            $type = $types[array_rand($types)];
            $line = $lines[array_rand($lines)];
            $shift = $shifts[array_rand($shifts)];
            $isBase = stripos($type, 'Base') !== false;
            $reworkBatches[] = [
                'batch_no' => 'BN-'.str_pad(rand(401, 500), 4, '0', STR_PAD_LEFT),
                'product_id' => 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT),
                'type' => $type,
                'line' => $line,
                'shift' => $shift,
                'rework_type' => $isBase ? 'ADU Base' : 'ADU CM',
                'reason' => ['Warna tidak sesuai','Viskositas out of spec','Kontaminasi','Gel akibat curing','Finishing kurang halus'][array_rand(['Warna tidak sesuai','Viskositas out of spec','Kontaminasi','Gel akibat curing','Finishing kurang halus'])],
                'started_at' => date('H:i', strtotime("-".rand(1,4)." hours")),
                'status' => ['In Progress','Pending QC','Completed'][rand(0, 2)],
            ];
        }

        return response()->json([
            'active_batch_in_progress_count' => $activeBatches,
            'total_base_completed_today' => $baseCompleted,
            'total_cm_completed_today' => $cmCompleted,
            'qc_pass_rate_percent' => $qcPass,
            'rework_adu_count' => $reworkCount,
            'active_packaging_lines_count' => $packagingLines,
            'batch_status_distribution' => $batchStatus,
            'hourly_process_yield' => $hourlyYield,
            'active_rework_batches_notification' => $reworkBatches,
        ]);
    }
}
