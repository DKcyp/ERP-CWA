<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class NonCustomerController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('non-customer');
        $this->initDummyData();
        View::share('activeMenu', 'non-customer');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $channels = ['Toko Cat','Bangunan','Toko Besi','Toko Cat Online','Agen Paint','Contractor','Toko Material','Paint Shop'];
        $statuses = ['Prospect','In Follow-up','Converted','Rejected'];
        $provinces = ['Jawa Barat','Jawa Tengah','Jawa Timur','DKI Jakarta','Banten','Yogyakarta'];
        $cities = ['Bandung','Jakarta','Semarang','Surabaya','Bogor','Tangerang','Bekasi','Cirebon','Depok','Malang'];
        $kecamatans = ['Bandung Wetan','Menteng','Gedung Sari','Tegalrejo','Gubeng','Kemanggisan','Depok Timur','Cimahi Utara','Batu Ampar'];
        $positions = ['Owner','Manager','Staff Purchasing','Staff Marketing','Director','Supervisor','Kepala Toko'];

        $names = ['PT Maju Jaya','CV Berkah','Toko Sinar','UD Makmur','PT Sentosa','CV Pelangi','Toko Abadi','UD Sejahtera','PT Bintang','CV Cahaya'];
        $contacts = ['Budi Santoso','Rina Wati','Ahmad Fauzi','Dewi Sari','Hendra Wijaya','Siti Aminah','Rudi Hartono','Lina Marlena','Fajar Pratama','Maya Anggraeni'];
        $streets = ['Sudirman','Thamrin','Dago','Asia Afrika','Brimob','Soekarno-Hatta','Diponegoro','Ahmad Yani'];
        $sources = ['Referral','Sales Visit','Online','Event','Exhibition'];

        for ($i = 0; $i < 50; $i++) {
            $city = $cities[array_rand($cities)];
            $phone = '022-'.rand(1000000,9999999);
            $mobile = '08'.rand(10000000000,99999999999);
            $name = $names[array_rand($names)];
            $contact = $contacts[array_rand($contacts)];
            $street = $streets[array_rand($streets)];
            $this->store->create([
                'non_customer_id' => 'NC-'.str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'name' => $name.' '.($i + 1),
                'contact_person' => $contact,
                'position' => $positions[array_rand($positions)],
                'address1' => 'Jl. '.$street.' No. '.rand(1,200),
                'address2' => 'RT '.rand(1,20).'/RW '.rand(1,10),
                'kecamatan' => $kecamatans[array_rand($kecamatans)],
                'kabupaten' => $city,
                'city' => $city,
                'zip' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'province' => $provinces[array_rand($provinces)],
                'country' => 'Indonesia',
                'channel_outlet' => $channels[array_rand($channels)],
                'kode_area' => '0'.rand(21,39),
                'phone' => $phone,
                'mobile_phone' => $mobile,
                'email' => strtolower(str_replace(' ','_',str_replace('.','_',$contact)).'@gmail.com'),
                'employee_id' => 'EMP-'.str_pad(rand(1,20), 3, '0', STR_PAD_LEFT),
                'created_date' => date('Y-m-d', strtotime('-'.rand(1,180).' days')),
                'note' => 'Prospek dari '.$sources[array_rand($sources)],
                'npwp' => rand(10,99).'.'.rand(100,999).'.'.rand(100,999).'.'.'0-'.rand(10,99).'.'.rand(100,999),
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }

    public function index()
    {
        return view('non-customer');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['non_customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['contact_person'] ?? '', $q) !== false ||
                stripos($i['city'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all')
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $request->filter_status);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('created_date_fmt', fn($r) => $r['created_date'] ? \Carbon\Carbon::parse($r['created_date'])->format('d/m/Y') : '-')
            ->addColumn('status_badge', function ($r) {
                return match($r['status'] ?? '') {
                    'Prospect' => '<span class="badge bg-info"><i class="bi bi-person-plus me-1"></i>Prospect</span>',
                    'In Follow-up' => '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>In Follow-up</span>',
                    'Converted' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Converted</span>',
                    'Rejected' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>',
                    default => '<span class="badge bg-secondary">'.$r['status'].'</span>',
                };
            })
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="detailRecord(\''.$r['id'].'\')" title="Detail"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['created_date_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'contact_person' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:50',
        ]);

        $data = $request->only(['non_customer_id','name','contact_person','position','address1','address2','kecamatan','kabupaten','city','zip','province','country','channel_outlet','kode_area','phone','mobile_phone','email','employee_id','created_date','note','npwp','status']);
        if (empty($data['non_customer_id'])) {
            $data['non_customer_id'] = 'NC-'.str_pad(count($this->store->all()) + 1, 5, '0', STR_PAD_LEFT);
        }
        $data['created_date'] = $data['created_date'] ?? date('Y-m-d');
        $data['status'] = $data['status'] ?? 'Prospect';

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'Non-Customer berhasil disimpan.']);
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

        $request->validate(['name' => 'required|string|max:200']);
        $data = $request->only(['non_customer_id','name','contact_person','position','address1','address2','kecamatan','kabupaten','city','zip','province','country','channel_outlet','kode_area','phone','mobile_phone','email','employee_id','created_date','note','npwp','status']);
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'Non-Customer berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Non-Customer berhasil dihapus.']);
    }
}
