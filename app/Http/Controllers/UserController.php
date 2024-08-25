<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Throwable;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::orderBy('name', 'ASC')->get();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-warning btn-sm btn-icon editdata" data-id="' . $row->id . '"><i class="far fa-edit"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm btn-icon deleteData"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->addColumn('role', function ($row) {
                    $role = '';
                    foreach ($row->roles as $value) {
                        $role = $role . '<span class="badge badge-primary">' . strtolower($value["name"]) . '</span> ';
                    }
                    return $role;
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }
        return view('users.index');
    }
    public function create()
    {
        try {
            $datas["roles"] = Role::orderBy('name', 'ASC')->get();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan Tidak Diketahui',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'data' => $datas
        ], 200);
    }
    public function store(Request $request)
    {
        try {
            $datas = new User();
            $datas->name = $request->name;
            $datas->email = $request->email;
            $datas->password = Hash::make('password');
            $datas->email_verified_at = !blank($request->verified) ? now() : null;
            $datas->save();

            $datas->assignRole(!blank($request->role) ? $request->role : array());
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan Tidak Diketahui',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambah'
        ], 201);
    }

    public function update(Request $request)
    {
        try {
            $datas = User::findOrFail($request->id);
            $datas->name = $request->name;
            $datas->email = $request->email;
            $datas->password = Hash::make('password');
            $datas->email_verified_at = !blank($request->verified) ? now() : null;
            $datas->update();

            $datas->syncRoles(!blank($request->role) ? $request->role : array());
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan Tidak Diketahui',
                'error' => $e->getMessage(),
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }

    public function edit($id)
    {
        try {
            $user = User::find($id);
            $datas['role'] = $user->getRoleNames()->toArray();
            $datas["roles"] = Role::orderBy('name', 'ASC')->get();
            $datas["user"] = User::findOrfail($id);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan Tidak Diketahui',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $datas
        ], 200);
    }
    public function destroy($id)
    {
        try {
            $datas = User::find($id);
            $datas->delete();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Kesalahan Tidak Diketahui',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
