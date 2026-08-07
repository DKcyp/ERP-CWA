<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class MarketingDashboardController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', 'marketing-dashboard');
    }

    public function index()
    {
        return view('marketing-dashboard');
    }

    public function data(Request $request)
    {
        $areas = ['Area Bandung','Area Jakarta','Area Semarang','Area Surabaya','Area Bogor','Area Tangerang','Area Bekasi','Area Cirebon'];
        $salesNames = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno','Lina Maulida','Andi Wijaya','Rina Susanti','Fajar Nugroho','Maya Putri'];

        $prospectNonCustomer = rand(45, 120);
        $visitToday = rand(25, 80);
        $nooThisMonth = rand(8, 35);
        $incentivePaid = rand(5000000, 25000000);

        $visitTrend = [];
        for ($d = 29; $d >= 0; $d--) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $visitTrend[] = [
                'date' => date('d M', strtotime($date)),
                'visits' => rand(15, 65),
                'new_leads' => rand(2, 15),
            ];
        }

        $nooGrowth = [];
        foreach (array_slice($areas, 0, 6) as $area) {
            $nooGrowth[] = [
                'area' => $area,
                'target' => rand(5, 20),
                'achieved' => rand(3, 18),
            ];
        }

        $commissionDist = [];
        foreach (array_slice($salesNames, 0, 6) as $name) {
            $commissionDist[] = [
                'name' => $name,
                'amount' => rand(1000000, 8000000),
            ];
        }

        $topPerformers = [];
        foreach ($salesNames as $i => $name) {
            $visits = rand(40, 120);
            $targets = rand(8, 30);
            $achieved = rand(5, 28);
            $topPerformers[] = [
                'rank' => $i + 1,
                'name' => $name,
                'area' => $areas[array_rand($areas)],
                'total_visits' => $visits,
                'noo_target' => $targets,
                'noo_achieved' => $achieved,
                'achievement_pct' => min(120, round(($achieved / max(1, $targets)) * 100)),
                'commission_earned' => rand(1500000, 12000000),
            ];
        }
        usort($topPerformers, fn($a, $b) => $b['achievement_pct'] <=> $a['achievement_pct']);
        foreach ($topPerformers as $i => &$p) { $p['rank'] = $i + 1; }

        return response()->json([
            'total_prospect_non_customer' => $prospectNonCustomer,
            'total_marketing_visits_today' => $visitToday,
            'total_noo_this_month' => $nooThisMonth,
            'total_incentive_paid' => $incentivePaid,
            'visit_trend' => $visitTrend,
            'noo_growth_by_area' => $nooGrowth,
            'commission_distribution' => $commissionDist,
            'top_performers' => $topPerformers,
        ]);
    }
}
