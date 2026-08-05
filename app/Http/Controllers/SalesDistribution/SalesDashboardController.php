<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class SalesDashboardController extends Controller
{
    public function __construct()
    {
        View::share('activeMenu', 'sales-dashboard');
    }

    public function index()
    {
        return view('Sales-distribution.sales-dashboard');
    }

    public function data(Request $request)
    {
        $salesmen = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno','Lina Maulida','Andi Wijaya','Rina Susanti'];
        $categories = ['Wall Paint','Primer','Top Coat','Thinner','Specialty','Wood Stain','Practical'];
        $warehouses = ['WH-UTAMA','WH-BANDUNG','WH-JAKARTA','WH-SEMARANG'];

        $omsetToday = rand(85000000, 180000000);
        $activeSO = rand(120, 280);
        $pendingShipment = rand(15, 45);
        $arOutstanding = rand(250000000, 650000000);
        $overdueAR = rand(3, 12);

        $dailyTrend = [];
        for ($d = 29; $d >= 0; $d--) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $dailyTrend[] = [
                'date' => date('d M', strtotime($date)),
                'omset' => rand(60000000, 180000000),
                'orders' => rand(15, 65),
            ];
        }

        $salesmanPerf = [];
        foreach (array_slice($salesmen, 0, 5) as $s) {
            $target = rand(50000000, 120000000);
            $achieved = $target * (rand(60, 115) / 100);
            $salesmanPerf[] = [
                'name' => $s,
                'target' => $target,
                'achieved' => (int)$achieved,
                'percent' => min(115, round(($achieved / $target) * 100)),
                'so_count' => rand(8, 35),
            ];
        }

        $categoryData = [];
        foreach ($categories as $c) {
            $categoryData[] = [
                'name' => $c,
                'value' => rand(50000000, 250000000),
            ];
        }

        $recentSO = [];
        for ($i = 0; $i < 10; $i++) {
            $status = ['Confirmed','Processing','Shipped','Delivered','Pending'][rand(0, 4)];
            $statusColor = match($status) {
                'Confirmed' => 'success',
                'Processing' => 'info',
                'Shipped' => 'primary',
                'Delivered' => 'secondary',
                'Pending' => 'warning',
                default => 'secondary',
            };
            $recentSO[] = [
                'no' => $i + 1,
                'so_no' => 'SO-'.date('ymd', strtotime('-'.rand(0,3).' days')).'-'.str_pad(rand(1, 200), 4, '0', STR_PAD_LEFT),
                'customer' => ['PT Maju Jaya','CV Berkah','PT Sinar Terang','CV Pelangi','PT Sentosa','CV Abadi'][rand(0, 5)],
                'salesman' => $salesmen[array_rand($salesmen)],
                'date' => date('Y-m-d', strtotime('-'.rand(0, 5).' days')),
                'amount' => rand(2000000, 35000000),
                'status' => $status,
                'status_color' => $statusColor,
            ];
        }

        $creditAlerts = [];
        $creditCustomers = ['PT Maju Jaya','CV Berkah','PT Sinar Terang','CV Pelangi','PT Sentosa','CV Abadi','PT Sejahtera','CV Mitra'];
        $alertCount = rand(3, 6);
        for ($i = 0; $i < $alertCount; $i++) {
            $limit = rand(50000000, 200000000);
            $outstanding = $limit * (rand(105, 145) / 100);
            $creditAlerts[] = [
                'no' => $i + 1,
                'customer' => $creditCustomers[array_rand($creditCustomers)],
                'credit_limit' => $limit,
                'outstanding' => (int)$outstanding,
                'exceeded' => (int)($outstanding - $limit),
                'days_overdue' => rand(1, 30),
            ];
        }

        return response()->json([
            'total_sales_omset_today' => $omsetToday,
            'total_active_so' => $activeSO,
            'total_pending_shipment' => $pendingShipment,
            'total_ar_outstanding' => $arOutstanding,
            'total_overdue_ar_count' => $overdueAR,
            'daily_sales_trend' => $dailyTrend,
            'top_salesman_performance' => $salesmanPerf,
            'sales_by_category' => $categoryData,
            'recent_sales_orders' => $recentSO,
            'credit_limit_exceeded_alert' => $creditAlerts,
        ]);
    }
}
