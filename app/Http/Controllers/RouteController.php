<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Spatie\Permission\Models\Permission;
use Throwable;
use Yajra\DataTables\DataTables;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Route::orderBy('permission_name', 'ASC')->get();
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
        try {
            $datas["routes"] = FacadesRoute::getRoutes()->get();
            $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
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
            $datas = new Route();
            $datas->route = $request->route;
            $datas->permission_name = $request->permission_name;
            $datas->save();
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
            $datas = Route::findOrFail($request->id);
            $datas->route = $request->route;
            $datas->permission_name = $request->permission_name;
            $datas->update();
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
        ], 200);
    }

    public function edit($id)
    {
        try {
            $datas["route"] = Route::findorfail($id);
            $datas["routes"] = FacadesRoute::getRoutes()->get();
            $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
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
            $datas = Route::find($id);
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
