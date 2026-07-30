<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PreSpkController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('pre-spk-list');
        $this->initDummyData();
        View::share('activeMenu', 'pre-spk-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'doc_id'      => 'PRESPK-2026-0001',
                'date'        => '2026-07-01',
                'customer_id' => 'CUS-001',
                'customer_name' => 'PT Maju Jaya Abadi',
                'user_id'     => 'USR-001',
                'notes'       => 'Pesanan kue kering untuk event Agustusan',
                'status'      => 'DRAFT',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 200, 'tonase' => 40],
                    ['product_id' => 'PRD-002', 'product_name' => 'Kue Kering Cokelat', 'qty' => 150, 'tonase' => 30],
                ],
            ],
            [
                'doc_id'      => 'PRESPK-2026-0002',
                'date'        => '2026-07-05',
                'customer_id' => 'CUS-002',
                'customer_name' => 'CV Sentosa Makmur',
                'user_id'     => 'USR-002',
                'notes'       => 'Roti untuk distribusi retail',
                'status'      => 'APPROVED',
                'items' => [
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 500, 'tonase' => 100],
                    ['product_id' => 'PRD-004', 'product_name' => 'Roti Cokelat', 'qty' => 300, 'tonase' => 60],
                    ['product_id' => 'PRD-005', 'product_name' => 'Roti Keju', 'qty' => 200, 'tonase' => 40],
                ],
            ],
            [
                'doc_id'      => 'PRESPK-2026-0003',
                'date'        => '2026-07-10',
                'customer_id' => 'CUS-003',
                'customer_name' => 'Toko Berkah',
                'user_id'     => 'USR-001',
                'notes'       => 'Pesanan bulanan toko',
                'status'      => 'PENDING',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 100, 'tonase' => 20],
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 250, 'tonase' => 50],
                ],
            ],
            [
                'doc_id'      => 'PRESPK-2026-0004',
                'date'        => '2026-07-15',
                'customer_id' => 'CUS-004',
                'customer_name' => 'PT Harapan Bangsa',
                'user_id'     => 'USR-003',
                'notes'       => 'Pesanan snack box untuk seminar nasional',
                'status'      => 'REJECTED',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 1000, 'tonase' => 200],
                    ['product_id' => 'PRD-002', 'product_name' => 'Kue Kering Cokelat', 'qty' => 800, 'tonase' => 160],
                ],
            ],
            [
                'doc_id'      => 'PRESPK-2026-0005',
                'date'        => '2026-07-20',
                'customer_id' => 'CUS-001',
                'customer_name' => 'PT Maju Jaya Abadi',
                'user_id'     => 'USR-002',
                'notes'       => 'Restock rutin mingguan',
                'status'      => 'DRAFT',
                'items' => [
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 400, 'tonase' => 80],
                    ['product_id' => 'PRD-004', 'product_name' => 'Roti Cokelat', 'qty' => 250, 'tonase' => 50],
                ],
            ],
            [
                'doc_id'      => 'PRESPK-2026-0006',
                'date'        => '2026-07-25',
                'customer_id' => 'CUS-005',
                'customer_name' => 'Indomaret cabang utara',
                'user_id'     => 'USR-001',
                'notes'       => 'Pengisian shelf outlet',
                'status'      => 'APPROVED',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 300, 'tonase' => 60],
                    ['product_id' => 'PRD-002', 'product_name' => 'Kue Kering Cokelat', 'qty' => 200, 'tonase' => 40],
                    ['product_id' => 'PRD-005', 'product_name' => 'Roti Keju', 'qty' => 150, 'tonase' => 30],
                ],
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.pre-spk-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $status = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('total_qty', fn($r) => array_sum(array_column($r['items'] ?? [], 'qty')))
            ->addColumn('total_tonase', fn($r) => array_sum(array_column($r['items'] ?? [], 'tonase')))
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PENDING'   => ['class' => 'bg-warning text-dark',  'label' => 'Pending'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'REJECTED'  => ['class' => 'bg-danger',             'label' => 'Rejected'],
                ];
                $s = $r['status'] ?? 'DRAFT';
                return '<span class="badge '.($map[$s]['class'] ?? 'bg-secondary').'">'.($map[$s]['label'] ?? $s).'</span>';
            })
            ->addColumn('action', function ($row) {
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>';
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doc_id'        => ['required','string','max:50'],
            'date'          => ['required','date'],
            'customer_id'   => ['required','string','max:50'],
            'customer_name' => ['required','string','max:200'],
            'user_id'       => ['nullable','string','max:50'],
            'notes'         => ['nullable','string'],
            'status'        => ['required','string','in:DRAFT,PENDING,APPROVED,REJECTED'],
            'items'         => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->create($request->only('doc_id','date','customer_id','customer_name','user_id','notes','status') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'doc_id'        => ['required','string','max:50'],
            'date'          => ['required','date'],
            'customer_id'   => ['required','string','max:50'],
            'customer_name' => ['required','string','max:200'],
            'user_id'       => ['nullable','string','max:50'],
            'notes'         => ['nullable','string'],
            'status'        => ['required','string','in:DRAFT,PENDING,APPROVED,REJECTED'],
            'items'         => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->update($id, $request->only('doc_id','date','customer_id','customer_name','user_id','notes','status') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}