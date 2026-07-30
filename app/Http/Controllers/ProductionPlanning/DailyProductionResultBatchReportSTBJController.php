<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionResultBatchReportSTBJController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-result-batch-report-stbj');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-result-batch-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $warehouses = ['Gudang Jadi Bandung','Gudang Jadi Jakarta','Gudang Bahan Bandung'];
        $statuses = ['Verified','Pending','Received'];
        $users = ['oper-001','oper-002','oper-003','oper-004','oper-005'];
        $data = [];
        $id = 1;

        $batches = [
            ['product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0421','pcs'=>200,'kg'=>195],
            ['product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0422','pcs'=>150,'kg'=>152],
            ['product_name'=>'Primer Grey 5L','batch_no'=>'BN-0423','pcs'=>300,'kg'=>295],
            ['product_name'=>'Top Coat Clear 15L','batch_no'=>'BN-0424','pcs'=>180,'kg'=>168],
            ['product_name'=>'Wall Paint Cream 20L','batch_no'=>'BN-0425','pcs'=>250,'kg'=>248],
            ['product_name'=>'Primer Putih 5L','batch_no'=>'BN-0426','pcs'=>220,'kg'=>218],
            ['product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0427','pcs'=>250,'kg'=>248],
            ['product_name'=>'Top Coat Glossy 15L','batch_no'=>'BN-0428','pcs'=>250,'kg'=>245],
            ['product_name'=>'Primer Grey 5L','batch_no'=>'BN-0429','pcs'=>200,'kg'=>198],
            ['product_name'=>'Wall Paint Cream 20L','batch_no'=>'BN-0430','pcs'=>180,'kg'=>178],
            ['product_name'=>'Cat Ekonomis 5L','batch_no'=>'BN-0431','pcs'=>120,'kg'=>115],
            ['product_name'=>'Wall Paint Blue 10L','batch_no'=>'BN-0432','pcs'=>130,'kg'=>132],
        ];

        for ($d = 0; $d < 4; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-28 +{$d} days"));
            for ($i = 0; $i < 3; $i++) {
                $b = $batches[($d * 3 + $i) % count($batches)];
                $data[] = [
                    'date' => $date,
                    'stbj_no' => 'STBJ-'.date('Ymd', strtotime($date)).'-'.str_pad($id, 3, '0', STR_PAD_LEFT),
                    'production_id' => 'PRD-LST-'.str_pad(rand(1, 14), 4, '0', STR_PAD_LEFT),
                    'batch_no' => $b['batch_no'],
                    'product_name' => $b['product_name'],
                    'warehouse_target' => $warehouses[array_rand($warehouses)],
                    'total_qty_received_pcs' => $b['pcs'] + rand(-5, 5),
                    'total_weight_kg' => $b['kg'] + rand(-3, 3),
                    'user_id' => $users[array_rand($users)],
                    'status' => $statuses[array_rand($statuses)],
                ];
                $id++;
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-result-batch-report-stbj.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['stbj_no'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['production_id'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }

        if ($request->filled('filter_warehouse') && $request->filter_warehouse !== 'all') {
            $wh = $request->filter_warehouse;
            $data = array_filter($data, fn($i) => ($i['warehouse_target'] ?? '') === $wh);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('status_badge', function ($r) {
                $s = $r['status'] ?? '';
                if ($s === 'Verified') return '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>';
                if ($s === 'Received') return '<span class="badge bg-info"><i class="bi bi-inbox me-1"></i>Received</span>';
                return '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>';
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-result-batch-report-stbj-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','STBJ No','Production ID','Batch No','Product Name','Warehouse Target','Received (Pcs)','Weight (Kg)','User ID','Status']);
            foreach ($data as $row) {
                fputcsv($file, [$row['date'],$row['stbj_no'],$row['production_id'],$row['batch_no'],$row['product_name'],$row['warehouse_target'],$row['total_qty_received_pcs'],$row['total_weight_kg'],$row['user_id'],$row['status']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}