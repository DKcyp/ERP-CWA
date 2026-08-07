<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MarketingKomisiCollectionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('marketing-komisi-collection');
        $this->initDummyData();
        View::share('activeMenu', 'marketing-komisi-collection');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $tas = ['TA Bandung','TA Jakarta','TA Semarang','TA Surabaya','TA Bogor'];
        $marketing = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno','Lina Maulida','Andi Wijaya','Rina Susanti'];

        for ($m = 0; $m < 6; $m++) {
            $period = date('Y-m', strtotime("2026-01 +{$m} months"));
            $count = rand(4, 7);
            for ($i = 0; $i < $count; $i++) {
                $target90 = rand(20000000, 80000000);
                $achv90 = (int)($target90 * (rand(60, 110) / 100));
                $pct90 = $target90 > 0 ? round(($achv90 / $target90) * 100, 2) : 0;
                $index90 = $pct90 >= 80 ? 1.0 : ($pct90 >= 60 ? 0.7 : 0.3);
                $komisi90 = (int)($achv90 * 0.005 * $index90);

                $targetLt90 = rand(30000000, 100000000);
                $achvLt90 = (int)($targetLt90 * (rand(55, 115) / 100));
                $pctLt90 = $targetLt90 > 0 ? round(($achvLt90 / $targetLt90) * 100, 2) : 0;
                $indexLt90 = $pctLt90 >= 30 ? 1.5 : ($pctLt90 >= 20 ? 1.0 : 0.5);
                $komisiLt90 = (int)($achvLt90 * 0.003 * $indexLt90);

                $this->store->create([
                    'period' => $period,
                    'ta' => $tas[array_rand($tas)],
                    'marketing' => $marketing[array_rand($marketing)],
                    'target_usia_piutang_gt90' => $target90,
                    'pencapaian_gt90' => $achv90,
                    'persentase_gt90' => $pct90,
                    'index_target_gt90' => $index90,
                    'komisi_gt90' => $komisi90,
                    'target_usia_piutang_lte90' => $targetLt90,
                    'pencapaian_lte90' => $achvLt90,
                    'persentase_lte90' => $pctLt90,
                    'index_target_lte90' => $indexLt90,
                    'komisi_lte90' => $komisiLt90,
                    'total_komisi' => $komisi90 + $komisiLt90,
                ]);
            }
        }
    }

    public function index()
    {
        return view('marketing-komisi-collection');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['ta'] ?? '', $q) !== false ||
                stripos($i['marketing'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_period') && $request->filter_period !== 'all')
            $data = array_filter($data, fn($i) => ($i['period'] ?? '') === $request->filter_period);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('period_fmt', fn($r) => $r['period'] ? \Carbon\Carbon::parse($r['period'].'-01')->format('M Y') : '-')
            ->addColumn('target_gt90_fmt', fn($r) => 'Rp '.number_format($r['target_usia_piutang_gt90'] ?? 0, 0, ',', '.'))
            ->addColumn('achv_gt90_fmt', fn($r) => 'Rp '.number_format($r['pencapaian_gt90'] ?? 0, 0, ',', '.'))
            ->addColumn('pct_gt90_fmt', function ($r) {
                $v = $r['persentase_gt90'] ?? 0;
                $c = $v >= 80 ? 'text-success' : ($v >= 60 ? 'text-warning' : 'text-danger');
                return '<span class="'.$c.' fw-bold">'.$v.'%</span>';
            })
            ->addColumn('index_gt90_fmt', function ($r) {
                $v = $r['index_target_gt90'] ?? 0;
                $c = $v >= 1.0 ? 'bg-success' : ($v >= 0.7 ? 'bg-warning text-dark' : 'bg-danger');
                return '<span class="badge '.$c.'">'.$v.'</span>';
            })
            ->addColumn('komisi_gt90_fmt', fn($r) => 'Rp '.number_format($r['komisi_gt90'] ?? 0, 0, ',', '.'))
            ->addColumn('target_lte90_fmt', fn($r) => 'Rp '.number_format($r['target_usia_piutang_lte90'] ?? 0, 0, ',', '.'))
            ->addColumn('achv_lte90_fmt', fn($r) => 'Rp '.number_format($r['pencapaian_lte90'] ?? 0, 0, ',', '.'))
            ->addColumn('pct_lte90_fmt', function ($r) {
                $v = $r['persentase_lte90'] ?? 0;
                $c = $v >= 80 ? 'text-success' : ($v >= 30 ? 'text-warning' : 'text-danger');
                return '<span class="'.$c.' fw-bold">'.$v.'%</span>';
            })
            ->addColumn('index_lte90_fmt', function ($r) {
                $v = $r['index_target_lte90'] ?? 0;
                $c = $v >= 1.0 ? 'bg-success' : ($v >= 0.7 ? 'bg-warning text-dark' : 'bg-danger');
                return '<span class="badge '.$c.'">'.$v.'</span>';
            })
            ->addColumn('komisi_lte90_fmt', fn($r) => 'Rp '.number_format($r['komisi_lte90'] ?? 0, 0, ',', '.'))
            ->addColumn('total_komisi_fmt', fn($r) => '<strong class="text-success">Rp '.number_format($r['total_komisi'] ?? 0, 0, ',', '.').'</strong>')
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="detailRecord(\''.$r['id'].'\')" title="Detail"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['period_fmt','target_gt90_fmt','achv_gt90_fmt','pct_gt90_fmt','index_gt90_fmt','komisi_gt90_fmt','target_lte90_fmt','achv_lte90_fmt','pct_lte90_fmt','index_lte90_fmt','komisi_lte90_fmt','total_komisi_fmt','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|string|max:10',
            'ta' => 'required|string|max:100',
            'marketing' => 'required|string|max:100',
        ]);

        $data = $request->only(['period','ta','marketing']);
        $data += $this->calculate($request);

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'Komisi berhasil disimpan.']);
    }

    public function show($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $request->validate(['period' => 'required', 'ta' => 'required', 'marketing' => 'required']);
        $data = $request->only(['period','ta','marketing']);
        $data += $this->calculate($request);
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'Komisi berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Komisi berhasil dihapus.']);
    }

    protected function calculate($request): array
    {
        $targetGt90 = (int)($request->target_usia_piutang_gt90 ?? 0);
        $achvGt90 = (int)($request->pencapaian_gt90 ?? 0);
        $pctGt90 = $targetGt90 > 0 ? round(($achvGt90 / $targetGt90) * 100, 2) : 0;
        $indexGt90 = $pctGt90 >= 80 ? 1.0 : ($pctGt90 >= 60 ? 0.7 : 0.3);
        $komisiGt90 = (int)($achvGt90 * 0.005 * $indexGt90);

        $targetLte90 = (int)($request->target_usia_piutang_lte90 ?? 0);
        $achvLte90 = (int)($request->pencapaian_lte90 ?? 0);
        $pctLte90 = $targetLte90 > 0 ? round(($achvLte90 / $targetLte90) * 100, 2) : 0;
        $indexLte90 = $pctLte90 >= 30 ? 1.5 : ($pctLte90 >= 20 ? 1.0 : 0.5);
        $komisiLte90 = (int)($achvLte90 * 0.003 * $indexLte90);

        return [
            'pencapaian_gt90' => $achvGt90,
            'persentase_gt90' => $pctGt90,
            'index_target_gt90' => $indexGt90,
            'komisi_gt90' => $komisiGt90,
            'pencapaian_lte90' => $achvLte90,
            'persentase_lte90' => $pctLte90,
            'index_target_lte90' => $indexLte90,
            'komisi_lte90' => $komisiLte90,
            'total_komisi' => $komisiGt90 + $komisiLte90,
        ];
    }
}
