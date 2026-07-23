<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;            // ikuti pola file lamamu
use Yajra\DataTables\Datatables;             // kalau error, pakai Facade: Yajra\DataTables\Facades\DataTables

class UserController extends Controller
{
    /**
     * DataTables source.
     */
     public function table(Request $request)
    {
        if ($request->ajax()) {
            // UPDATE: Tambahkan relasi sales
            $query = User::with(['getRoles', 'getArea'])->select('*');

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('department', function ($row) {
                    return $row->getArea ? $row->getArea->nama : ($row->department ?: '-');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex justify-content-center gap-1">
                            <button type="button" onclick="onEdit(`'.$row->id.'`)" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <button type="button" onclick="onDelete(`'.$row->id.'`)" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        abort(404);
    }

    /**
     * Index view.
     */
    public function index(): Renderable
    {
        return view('user.index');
    }

    public function create() {}

    /**
     * Create/Update user.
     * - role_id: ULID (string 26) atau null
     * - password: wajib saat create, opsional saat update (kalau diisi harus sama)
     */
    public function store(Request $request)
        {
                $id = $request->input('id');

                // Normalize numeric user id: ensure we don't pass ULID/string values
                if ($id === null || $id === '') {
                    $id = null;
                } elseif (ctype_digit((string) $id)) {
                    $id = (int) $id;
                } else {
                    // non-numeric id (e.g. ULID) is not valid for users.id (bigint)
                    $id = null;
                }

            // Normalisasi role_id
            $rawRole  = $request->input('role_id');
            $normRole = ($rawRole === null || $rawRole === '') ? null : (string) $rawRole;
            $request->merge(['role_id' => $normRole]);

            // RULES
            $rules = [
                'name'       => ['required', 'string', 'max:100'],
                'username'   => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($id)],
                'role_id'    => ['nullable', 'string', 'size:26', 'exists:roles,id'],
                'department' => ['nullable', 'string', 'max:150', 'exists:area,id'],
            ];

            // Validasi sales_id hanya jika role adalah Sales
            $roleId = $request->input('role_id');
            $departmentId = $request->input('department');
            $isSalesRole = false;
            
            if ($roleId) {
                $role = Role::find($roleId);
                $isSalesRole = $role && (
                    stripos($role->role_name, 'sales') !== false || 
                    stripos($role->role_code, 'sales') !== false
                );
                
                if ($isSalesRole) {
                    // PERBAIKAN: Ubah dari 'exists:sales,sales_id' menjadi 'exists:sales,id'
                    $rules['sales_id'] = ['nullable', 'string', 'size:26', 'exists:sales,id'];
                }
            }

            // Password validation
            $pass  = (string) $request->input('password', '');
            $pass2 = (string) $request->input('password2', '');

            if ($id) {
                if ($pass !== '' || $pass2 !== '') {
                    $rules['password']  = ['required', 'string', 'min:6'];
                    $rules['password2'] = ['required', 'same:password'];
                }
            } else {
                $rules['password']  = ['required', 'string'];
                $rules['password2'] = ['required', 'same:password'];
            }

            $validated = $request->validate($rules);

            // Payload
            $payload = [
                'name'       => $validated['name'],
                'username'   => $validated['username'],
                'role_id'    => $validated['role_id'] ?? null,
                'department' => $validated['department'] ?? null,
            ];

            // Tambahkan sales_id ke payload hanya jika role adalah Sales
            if ($isSalesRole && isset($validated['sales_id'])) {
                $payload['sales_id'] = $validated['sales_id'];
            } else {
                // Jika bukan role Sales, set sales_id menjadi null
                $payload['sales_id'] = null;
            }

            if ($pass !== '') {
                $payload['password'] = bcrypt($pass);
            }

            User::updateOrCreate(['id' => $id], $payload);

            return response()->json(['status' => 'success', 'message' => 'Data Save Successfully.']);
        }

    /**
     * Show detail user untuk form edit (AJAX).
     */
        public function show(Request $request)
    {
        try {
            $reqId = $request->id;
            if (!ctype_digit((string) $reqId)) {
                return response()->json(['code' => 404, 'success' => false, 'message' => 'Invalid id'], 404);
            }

            $data = User::with(['getRoles', 'getArea'])->findOrFail((int) $reqId);

            // Prepare role data for select2
            $roleData = null;
            if ($data->role_id && $data->getRoles) {
                $roleData = [
                    'id' => $data->role_id,
                    'text' => $data->getRoles->role_name
                ];
            }

            return response()->json([
                'code'    => 200,
                'success' => true,
                'message' => "Successfully get data!",
                'data'    => $data,
                'role'    => $roleData,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code'    => 404,
                'success' => false,
                'message' => $th->getMessage(),
            ], 404);
        }
    }


    public function edit($id) {}

    /**
     * Hapus user.
     */
    public function destroy(Request $request)
    {
        $reqId = $request->id;
        if (!ctype_digit((string) $reqId)) {
            return response()->json(['code' => 404, 'success' => false, 'message' => 'Invalid id'], 404);
        }

        $data = User::findOrFail((int) $reqId);
        $data->delete();

        return response()->json([
            'code'    => 200,
            'success' => true,
            'message' => "Deleted data successfully",
        ]);
    }

    /**
     * Sumber Select2 untuk Roles (id = ULID, text = role_code).
     */
     public function getRoles(Request $request)
    {
        $q = (string) $request->q;

        $roles = Role::select('id', 'role_name AS text')
            ->when($q !== '', function ($w) use ($q) {
                $w->where('role_code', 'like', "%{$q}%");
            })
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'code'    => 200,
            'message' => "Get data successfully",
            'data'    => $roles,
        ]);
    }

    /**
     * Sumber Select2 untuk Department (id = id, text = nama).
     */
       public function getDepartment(Request $request)
    {
        $q = (string) $request->q;

        $areas = Area::select('id', 'nama AS text') // Model Area menggunakan tabel 'area'
            ->when($q !== '', function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%");
            })
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'code'    => 200,
            'message' => "Get data successfully",
            'data'    => $areas,
        ]);
    }

}