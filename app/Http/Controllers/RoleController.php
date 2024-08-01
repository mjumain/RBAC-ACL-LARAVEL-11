<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Role::orderBy('name', 'ASC')->get();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-warning btn-sm btn-icon editdata" data-id="' . $row->id . '"><i class="far fa-edit"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm btn-icon deleteData"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('roles.index');
    }
    public function store(Request $request)
    {
        try {
            $guard_name = empty($request->guard_name) ? 'web' : $request->guard_name;
            $datas = new Role;
            $datas->name = $request->name;
            $datas->guard_name = $guard_name;
            $datas->save();

            $datas->givePermissionTo(!blank($request->permissions) ? $request->permissions : array());

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $datas = Role::findOrFail($request->id);
            $datas->name = $request->name;
            $datas->guard_name = $request->guard_name;
            $datas->update();

            $datas->syncPermissions(!blank($request->permissions) ? $request->permissions : array());


            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal diperbarui',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $datapermissions = [];
        $permissions = DB::table('role_has_permissions')->select('permission_id')->where('role_id', $id)->get();
        foreach ($permissions as $key => $value) {
            array_push($datapermissions, $value->permission_id);
        }
        $datas["role"] = Role::find($id);
        $datas["role_has_permissions"] = $datapermissions;
        $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
        return response()->json([
            'success' => true,
            'data' => $datas
        ]);
    }
    public function destroy($id)
    {
        try {
            $datas = Role::find($id);
            $datas->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
