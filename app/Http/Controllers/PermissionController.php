<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Permission::orderBy('name', 'ASC')->get();
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
        return view('permissions.index');
    }
    public function store(Request $request)
    {
        try {
            if (!empty($request->permission) > 0) {
                $guard_name = empty($request->guard_name) ? 'web' : $request->guard_name;
                foreach ($request->permission as $key => $value) {
                    $model = new Permission;
                    $model->name = $request->name . '-' . $value;
                    $model->guard_name = $guard_name;
                    $model->save();
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil ditambah'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gagal ditambah',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal ditambah',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function create()
    {
        return response()->json([
            'success' => true,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $datas = Permission::findOrFail($request->id);
            $datas->name = $request->name;
            $datas->update();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal ditambah',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $datas = Permission::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $datas
        ]);
    }
    public function destroy($id)
    {
        try {
            $datas = Permission::find($id);
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
