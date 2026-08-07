<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class NewCustomerIncentiveController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('new-customer-incentive');
        $this->initDummyData();
        View::share('activeMenu', 'new-customer-incentive');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $tas = ['TA Bandung','TA Jakarta','TA Semarang','TA Surabaya','TA Bogor'];
        $sales = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno','Lina Maulida','Andi Wijaya','Rina Susanti'];
        $customers = ['PT Maju Jaya','CV Berkah','Toko Sinar','UD Makmur','PT Sentosa','CV Pelangi','Toko Abadi','UD Sejahtera','PT Bintang','CV Cahaya'];
        $cities = ['Bandung','Jakarta','Semarang','Surabaya','Bogor','Tangerang','Bekasi','Cirebon'];
        $owners = ['Budi Santoso','Rina Wati','Ahmad Fauzi','Dewi Sari','Hendra Wijaya','Siti Aminah','Rudi Hartono','Lina Marlena'];

        for ($i = 0; $i < 35; $i++) {
            $insentifSales = rand(500000, 5000000);
            $insentifBDH = (int)($insentifSales * 0.3);
            $bonusDOS = (int)($insentifSales * 0.15);

            $this->store->create([
                'ta' => $tas[array_rand($tas)],
                'sales' => $sales[array_rand($sales)],
                'customer' => $customers[array_rand($customers)].' '.($i + 1),
                'pemilik' => $owners[array_rand($owners)],
                'alamat' => 'Jl. '.['Sudirman','Thamrin','Dago','Soekarno-Hatta'][rand(0,3)].' No. '.rand(1,200),
                'city' => $cities[array_rand($cities)],
                'insentif_sales' => $insentifSales,
                'insentif_bdh' => $insentifBDH,
                'bonus_dos' => $bonusDOS,
                'total' => $insentifSales + $insentifBDH + $bonusDOS,
            ]);
        }
    }

    public function index()
    {
        return view('new-customer-incentive');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['ta'] ?? '', $q) !== false ||
                stripos($i['sales'] ?? '', $q) !== false ||
                stripos($i['customer'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_ta') && $request->filter_ta !== 'all')
            $data = array_filter($data, fn($i) => ($i['ta'] ?? '') === $request->filter_ta);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('insentif_sales_fmt', fn($r) => 'Rp '.number_format($r['insentif_sales'] ?? 0, 0, ',', '.'))
            ->addColumn('insentif_bdh_fmt', fn($r) => 'Rp '.number_format($r['insentif_bdh'] ?? 0, 0, ',', '.'))
            ->addColumn('bonus_dos_fmt', fn($r) => 'Rp '.number_format($r['bonus_dos'] ?? 0, 0, ',', '.'))
            ->addColumn('total_fmt', fn($r) => '<strong class="text-success">Rp '.number_format($r['total'] ?? 0, 0, ',', '.').'</strong>')
            ->addColumn('ta_badge', function ($r) {
                return '<span class="badge bg-info border">'.($r['ta'] ?? '-').'</span>';
            })
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="detailRecord(\''.$r['id'].'\')" title="Detail"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['insentif_sales_fmt','insentif_bdh_fmt','bonus_dos_fmt','total_fmt','ta_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ta' => 'required|string|max:100',
            'sales' => 'required|string|max:100',
            'customer' => 'required|string|max:200',
        ]);

        $data = $request->only(['ta','sales','customer','pemilik','alamat','city','insentif_sales','insentif_bdh','bonus_dos']);
        $is = (int)($data['insentif_sales'] ?? 0);
        $data['insentif_bdh'] = $data['insentif_bdh'] ?? (int)($is * 0.3);
        $data['bonus_dos'] = $data['bonus_dos'] ?? (int)($is * 0.15);
        $data['total'] = $is + (int)$data['insentif_bdh'] + (int)$data['bonus_dos'];

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'Incentive berhasil disimpan.']);
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

        $request->validate(['ta' => 'required', 'sales' => 'required', 'customer' => 'required']);
        $data = $request->only(['ta','sales','customer','pemilik','alamat','city','insentif_sales','insentif_bdh','bonus_dos']);
        $is = (int)($data['insentif_sales'] ?? 0);
        $data['insentif_bdh'] = $data['insentif_bdh'] ?? (int)($is * 0.3);
        $data['bonus_dos'] = $data['bonus_dos'] ?? (int)($is * 0.15);
        $data['total'] = $is + (int)$data['insentif_bdh'] + (int)$data['bonus_dos'];
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'Incentive berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Incentive berhasil dihapus.']);
    }
}
