<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductionCommissionController extends Controller
{
    protected DummyStore $commissionStore;
    protected DummyStore $paymentStore;

    public function __construct()
    {
        $this->commissionStore = new DummyStore('production-commission');
        $this->paymentStore = new DummyStore('production-commission-payment');
        $this->initDummyData();
        View::share('activeMenu', 'production-commission');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->commissionStore->all())) return;

        $employees = [
            ['id'=>'EMP-001','name'=>'Andi Kurniawan','position'=>'Operator LINE-A1'],
            ['id'=>'EMP-002','name'=>'Siti Rahayu','position'=>'Operator LINE-A2'],
            ['id'=>'EMP-003','name'=>'Budi Santoso','position'=>'Operator LINE-B1'],
            ['id'=>'EMP-004','name'=>'Ahmad Hidayat','position'=>'Operator LINE-B2'],
        ];

        $commissions = [
            ['employee_id'=>'EMP-001','employee_name'=>'Andi Kurniawan','date'=>'2026-07-28','production_id'=>'PRD-LST-0001','paid'=>'Yes','payment_date'=>'2026-07-30','status'=>'COMPLETED','machine'=>'LINE-A1','commission_rate'=>500,'qty'=>200,'amount'=>100000,'notes'=>'Produksi wall paint 20L'],
            ['employee_id'=>'EMP-001','employee_name'=>'Andi Kurniawan','date'=>'2026-07-28','production_id'=>'PRD-LST-0002','paid'=>'Yes','payment_date'=>'2026-07-30','status'=>'COMPLETED','machine'=>'LINE-A1','commission_rate'=>500,'qty'=>150,'amount'=>75000,'notes'=>''],
            ['employee_id'=>'EMP-002','employee_name'=>'Siti Rahayu','date'=>'2026-07-29','production_id'=>'PRD-LST-0003','paid'=>'No','payment_date'=>'','status'=>'IN_PROGRESS','machine'=>'LINE-A2','commission_rate'=>450,'qty'=>300,'amount'=>135000,'notes'=>'Primer grey'],
            ['employee_id'=>'EMP-002','employee_name'=>'Siti Rahayu','date'=>'2026-07-27','production_id'=>'PRD-LST-0006','paid'=>'Yes','payment_date'=>'2026-07-29','status'=>'COMPLETED','machine'=>'LINE-A2','commission_rate'=>450,'qty'=>220,'amount'=>99000,'notes'=>'Primer putih'],
            ['employee_id'=>'EMP-003','employee_name'=>'Budi Santoso','date'=>'2026-07-29','production_id'=>'PRD-LST-0004','paid'=>'No','payment_date'=>'','status'=>'PLANNED','machine'=>'LINE-B1','commission_rate'=>550,'qty'=>180,'amount'=>99000,'notes'=>'Top coat clear'],
            ['employee_id'=>'EMP-003','employee_name'=>'Budi Santoso','date'=>'2026-07-26','production_id'=>'PRD-LST-0007','paid'=>'Yes','payment_date'=>'2026-07-28','status'=>'COMPLETED','machine'=>'LINE-B1','commission_rate'=>550,'qty'=>400,'amount'=>220000,'notes'=>'Cat ekonomis'],
            ['employee_id'=>'EMP-004','employee_name'=>'Ahmad Hidayat','date'=>'2026-07-30','production_id'=>'PRD-LST-0005','paid'=>'No','payment_date'=>'','status'=>'QC_PENDING','machine'=>'LINE-B2','commission_rate'=>480,'qty'=>250,'amount'=>120000,'notes'=>'Wall paint cream'],
            ['employee_id'=>'EMP-004','employee_name'=>'Ahmad Hidayat','date'=>'2026-07-31','production_id'=>'PRD-LST-0008','paid'=>'No','payment_date'=>'','status'=>'DRAFT','machine'=>'LINE-B2','commission_rate'=>480,'qty'=>0,'amount'=>0,'notes'=>'Belum produksi'],
        ];

        foreach ($commissions as $item) { $this->commissionStore->create($item); }

        $payments = [
            [
                'id'          => 'PAY-2026-001','date' => '2026-07-30','account' => 'BCA 1234567890','total' => 175000,'notes' => 'Pembayaran komisi Andi Kurniawan',
                'details' => [
                    ['production_id' => 'PRD-LST-0001', 'commission' => 500, 'qty' => 200, 'amount' => 100000, 'total_detail' => 100000],
                    ['production_id' => 'PRD-LST-0002', 'commission' => 500, 'qty' => 150, 'amount' => 75000, 'total_detail' => 75000],
                ],
            ],
            [
                'id'          => 'PAY-2026-002','date' => '2026-07-29','account' => 'BCA 1234567890','total' => 99000,'notes' => 'Pembayaran komisi Siti Rahayu',
                'details' => [
                    ['production_id' => 'PRD-LST-0006', 'commission' => 450, 'qty' => 220, 'amount' => 99000, 'total_detail' => 99000],
                ],
            ],
            [
                'id'          => 'PAY-2026-003','date' => '2026-07-28','account' => 'BRI 0987654321','total' => 220000,'notes' => 'Pembayaran komisi Budi Santoso',
                'details' => [
                    ['production_id' => 'PRD-LST-0007', 'commission' => 550, 'qty' => 400, 'amount' => 220000, 'total_detail' => 220000],
                ],
            ],
        ];

        foreach ($payments as $item) { $this->paymentStore->create($item); }
    }

    public function index()
    {
        return view('production-planning.production-commission.index');
    }

    public function commissionTable(Request $request)
    {
        $data = $this->commissionStore->all();

        if ($request->filled('filter_employee') && $request->filter_employee !== 'all') {
            $e = $request->filter_employee;
            $data = array_filter($data, fn($i) => ($i['employee_id'] ?? '') === $e);
        }
        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }
        if ($request->filled('filter_complete_only') && $request->filter_complete_only === '1') {
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === 'COMPLETED');
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('payment_date_fmt', fn($r) => $r['payment_date'] ? \Carbon\Carbon::parse($r['payment_date'])->format('d/m/Y') : '-')
            ->addColumn('total', fn($r) => $r['amount'])
            ->addColumn('paid_badge', fn($r) => $r['paid'] === 'Yes'
                ? '<span class="badge bg-success">Paid</span>'
                : '<span class="badge bg-secondary">Unpaid</span>')
            ->addColumn('status_badge', function ($r) {
                $map = ['DRAFT'=>'secondary','PLANNED'=>'info','IN_PROGRESS'=>'primary','QC_PENDING'=>'warning text-dark','COMPLETED'=>'success'];
                $c = $map[$r['status']] ?? 'secondary';
                return '<span class="badge bg-'.$c.'">'.$r['status'].'</span>';
            })
            ->addColumn('checkbox', fn($r) => $r['paid'] === 'No' ? '<input type="checkbox" class="form-check-input row-check" data-id="'.$r['id'].'">' : '')
            ->rawColumns(['paid_badge', 'status_badge', 'checkbox'])->make(true);
    }

    public function paymentTable(Request $request)
    {
        $data = $this->paymentStore->all();
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->rawColumns([])->make(true);
    }

    public function paymentShow($id)
    {
        $d = $this->paymentStore->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function paySelected(Request $request)
    {
        $request->validate([
            'ids'       => ['required','string'],
            'account'   => ['required','string','max:100'],
            'notes'     => ['nullable','string'],
        ]);

        $ids = json_decode($request->ids, true);
        if (empty($ids)) return response()->json(['message' => 'Tidak ada komisi dipilih.'], 422);

        $commissions = $this->commissionStore->all();
        $selected = array_filter($commissions, fn($c) => in_array($c['id'], $ids));

        $totalPaid = 0;
        $details = [];
        $payDate = date('Y-m-d');
        $paymentId = 'PAY-'.date('Y').'-'.str_pad($this->paymentStore->count() + 1, 3, '0', STR_PAD_LEFT);

        foreach ($selected as $s) {
            $this->commissionStore->update($s['id'], ['paid' => 'Yes', 'payment_date' => $payDate]);
            $totalPaid += $s['amount'];
            $details[] = ['production_id' => $s['production_id'], 'commission' => $s['commission_rate'], 'qty' => $s['qty'], 'amount' => $s['amount'], 'total_detail' => $s['amount']];
        }

        $this->paymentStore->create([
            'id' => $paymentId, 'date' => $payDate, 'account' => $request->account, 'total' => $totalPaid,
            'notes' => $request->notes ?? 'Pembayaran '.count($selected).' komisi', 'details' => $details,
        ]);

        return response()->json(['message' => 'Pembayaran berhasil diproses. Total: Rp '.number_format($totalPaid,0,',','.'), 'payment_id' => $paymentId]);
    }

    public function employees()
    {
        $data = $this->commissionStore->all();
        $employees = [];
        foreach ($data as $d) {
            $employees[$d['employee_id']] = $d['employee_name'];
        }
        return response()->json($employees);
    }
}