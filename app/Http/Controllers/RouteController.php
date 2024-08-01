<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Route::orderBy('permission_name','ASC')->get();
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
        return view('routes.index');
    }
    public function create()
    {
        $datas["routes"] = FacadesRoute::getRoutes()->get();
        $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
        return response()->json([
            'success' => true,
            'data' => $datas
        ]);
    }
    public function store(Request $request)
    {
        try {
            $datas = new Route();
            $datas->route = $request->route;
            $datas->permission_name = $request->permission_name;
            $datas->save();

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
            $datas = Route::findOrFail($request->id);
            $datas->route = $request->route;
            $datas->permission_name = $request->permission_name;
            $datas->update();

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
        $datas["route"] = Route::findorfail($id);
        $datas["routes"] = FacadesRoute::getRoutes()->get();
        $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
        return response()->json([
            'success' => true,
            'data' => $datas
        ]);
    }
    public function destroy($id)
    {
        try {
            $datas = Route::find($id);
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
