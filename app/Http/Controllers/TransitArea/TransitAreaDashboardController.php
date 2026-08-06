<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class TransitAreaDashboardController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', 'transit-area-dashboard');
    }

    public function index()
    {
        return view('transit-area.dashboard');
    }

    public function data(Request $request)
    {
        $depos = ['Depo Bandung','Depo Jakarta','Depo Semarang','Depo Surabaya','Depo Bogor','Depo Tangerang','Depo Bekasi','Depo Cirebon'];

        $transitSalesToday = rand(650000000, 1200000000);
        $activeDepoCount = count($depos);
        $arOutstanding = rand(1500000000, 4000000000);
        $targetAchievement = rand(72, 108);

        $dailyTrend = [];
        for ($d = 29; $d >= 0; $d--) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $entry = ['date' => date('d M', strtotime($date))];
            foreach (array_slice($depos, 0, 4) as $dep) {
                $entry[strtolower(str_replace(' ', '_', $dep))] = rand(80000000, 250000000);
            }
            $dailyTrend[] = $entry;
        }

        $depoRanking = [];
        foreach ($depos as $i => $dep) {
            $target = rand(500000000, 1500000000);
            $realized = (int)($target * (rand(65, 120) / 100));
            $depoRanking[] = [
                'name' => $dep,
                'target' => $target,
                'realized' => $realized,
                'percent' => min(120, round(($realized / $target) * 100)),
                'ar_outstanding' => rand(50000000, 500000000),
                'collection_rate' => rand(60, 98),
            ];
        }
        usort($depoRanking, fn($a, $b) => $b['percent'] <=> $a['percent']);

        $overdueAR = [];
        $customers = ['PT Maju Jaya','CV Berkah','PT Sinar Terang','CV Pelangi','PT Sentosa','CV Abadi','PT Sejahtera','CV Mitra'];
        $overdueCount = rand(4, 8);
        for ($i = 0; $i < $overdueCount; $i++) {
            $days = rand(91, 180);
            $amount = rand(50000000, 500000000);
            $overdueAR[] = [
                'no' => $i + 1,
                'customer' => $customers[array_rand($customers)],
                'depo' => $depos[array_rand($depos)],
                'invoice_no' => 'INV-'.date('ymd', strtotime('-'.$days.' days')).'-'.str_pad(rand(1,200),4,'0',STR_PAD_LEFT),
                'amount' => $amount,
                'days_overdue' => $days,
                'aging' => $days > 150 ? 'CRITICAL' : ($days > 120 ? 'SEVERE' : 'WARNING'),
            ];
        }

        $collectionVsTarget = [];
        foreach (array_slice($depos, 0, 5) as $dep) {
            $target = rand(200000000, 600000000);
            $collected = (int)($target * (rand(55, 105) / 100));
            $collectionVsTarget[] = [
                'name' => $dep,
                'target' => $target,
                'collected' => $collected,
            ];
        }

        return response()->json([
            'total_transit_sales_today' => $transitSalesToday,
            'total_active_depo_count' => $activeDepoCount,
            'total_ar_depo_outstanding' => $arOutstanding,
            'target_achievement_rate' => $targetAchievement,
            'depo_performance_ranking' => $depoRanking,
            'daily_depo_sales_trend' => $dailyTrend,
            'collection_vs_target' => $collectionVsTarget,
            'overdue_depo_ar_alert' => $overdueAR,
        ]);
    }
}
