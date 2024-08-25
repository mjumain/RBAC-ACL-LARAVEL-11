<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Throwable;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Menu::orderBy('name', 'ASC')->get();
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
        return view('menus.index');
    }
    public function create()
    {
        try {
            $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
            $datas["routes"] = FacadesRoute::getRoutes()->get();
            $datas["menus"] = Menu::where('parent_id', 0)->orderBy('name', 'ASC')->get();
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
            $datas = new Menu();
            $datas->name = $request->name;
            $datas->route = $request->route;
            $datas->icon = $request->icon;
            $datas->parent_id = $request->parent_id;
            $datas->posision = 0;
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
            $datas = Menu::findOrFail($request->id);
            $datas->name = $request->name;
            $datas->route = $request->route;
            $datas->icon = $request->icon;
            $datas->parent_id = $request->parent_id;
            $datas->posision = 0;
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
        ]);
    }

    public function edit($id)
    {
        try {
            $datas["permissions"] = Permission::orderBy('name', 'ASC')->get();
            $datas["routes"] = FacadesRoute::getRoutes()->get();
            $datas["menus"] = Menu::where('parent_id', 0)->orderBy('name', 'ASC')->get();
            $datas["menu"] = Menu::findOrfail($id);
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
            $datas = Menu::find($id);
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
