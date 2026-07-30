<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionBaseReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-base-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-base-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0001','base_name'=>'White Titanium Base','batch_no'=>'BN-0421','machine_id'=>'LINE-A1','target_base_kg'=>105,'actual_base_kg'=>103,'operator'=>'Andi Kurniawan','notes'=>''],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0002','base_name'=>'White Titanium Base','batch_no'=>'BN-0422','machine_id'=>'LINE-A1','target_base_kg'=>80,'actual_base_kg'=>78,'operator'=>'Andi Kurniawan','notes'=>''],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0009','base_name'=>'Grey Primer Base','batch_no'=>'BN-0429','machine_id'=>'LINE-A2','target_base_kg'=>50,'actual_base_kg'=>51,'operator'=>'Siti Rahayu','notes'=>'Sedikit over'],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0003','base_name'=>'Grey Primer Base','batch_no'=>'BN-0423','machine_id'=>'LINE-A2','target_base_kg'=>155,'actual_base_kg'=>150,'operator'=>'Siti Rahayu','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0010','base_name'=>'Cream Color Base','batch_no'=>'BN-0430','machine_id'=>'LINE-B1','target_base_kg'=>90,'actual_base_kg'=>88,'operator'=>'Budi Santoso','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0004','base_name'=>'Clear Solvent Base','batch_no'=>'BN-0424','machine_id'=>'LINE-B1','target_base_kg'=>90,'actual_base_kg'=>92,'operator'=>'Budi Santoso','notes'=>'Over target'],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0005','base_name'=>'Cream Color Base','batch_no'=>'BN-0425','machine_id'=>'LINE-A1','target_base_kg'=>130,'actual_base_kg'=>126,'operator'=>'Andi Kurniawan','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0011','base_name'=>'White Titanium Base','batch_no'=>'BN-0431','machine_id'=>'LINE-A2','target_base_kg'=>60,'actual_base_kg'=>59,'operator'=>'Siti Rahayu','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0012','base_name'=>'Clear Solvent Base','batch_no'=>'BN-0432','machine_id'=>'LINE-B2','target_base_kg'=>75,'actual_base_kg'=>78,'operator'=>'Ahmad Hidayat','notes'=>'Over'],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0006','base_name'=>'White Titanium Base','batch_no'=>'BN-0426','machine_id'=>'LINE-A2','target_base_kg'=>115,'actual_base_kg'=>110,'operator'=>'Siti Rahayu','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0013','base_name'=>'Blue Color Base','batch_no'=>'BN-0433','machine_id'=>'LINE-B1','target_base_kg'=>70,'actual_base_kg'=>72,'operator'=>'Budi Santoso','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0014','base_name'=>'White Titanium Base','batch_no'=>'BN-0434','machine_id'=>'LINE-B2','target_base_kg'=>100,'actual_base_kg'=>94,'operator'=>'Ahmad Hidayat','notes'=>'Deviasi tinggi, cek material'],
        ];

        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-base-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['base_name'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['operator'] ?? '', $q) !== false
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

        if ($request->filled('filter_machine') && $request->filter_machine !== 'all') {
            $m = $request->filter_machine;
            $data = array_filter($data, fn($i) => ($i['machine_id'] ?? '') === $m);
        }

        if ($request->filled('filter_base') && $request->filter_base !== 'all') {
            $b = $request->filter_base;
            $data = array_filter($data, fn($i) => ($i['base_name'] ?? '') === $b);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('variance_kg', fn($r) => ($r['actual_base_kg'] ?? 0) - ($r['target_base_kg'] ?? 0))
            ->addColumn('variance_badge', function ($r) {
                $var = ($r['actual_base_kg'] ?? 0) - ($r['target_base_kg'] ?? 0);
                $pct = $r['target_base_kg'] > 0 ? round(($var / $r['target_base_kg']) * 100, 1) : 0;
                $label = ($var >= 0 ? '+' : '').$var.' Kg ('.$pct.'%)';
                if ($var == 0) return '<span class="badge bg-secondary">'.$label.'</span>';
                if (abs($pct) <= 3) return '<span class="badge bg-success">'.$label.'</span>';
                if (abs($pct) <= 5) return '<span class="badge bg-warning text-dark">'.$label.'</span>';
                return '<span class="badge bg-danger">'.$label.'</span>';
            })
            ->rawColumns(['variance_badge'])->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-base-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Production ID','Base Name','Batch No','Machine ID','Target Base (Kg)','Actual Base (Kg)','Variance (Kg)','Operator','Notes']);
            foreach ($data as $row) {
                $var = ($row['actual_base_kg'] ?? 0) - ($row['target_base_kg'] ?? 0);
                fputcsv($file, [$row['date'],$row['production_id'],$row['base_name'],$row['batch_no'],$row['machine_id'],$row['target_base_kg'],$row['actual_base_kg'],$var,$row['operator'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}