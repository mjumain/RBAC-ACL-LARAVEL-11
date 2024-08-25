<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionEditRequest;
use App\Http\Requests\PermissionRequest;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Throwable;
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
    public function store(PermissionRequest $request)
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
            }
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

    public function create()
    {
        return Response::HTTP_OK;
    }

    public function update(PermissionEditRequest $request)
    {
        try {
            $datas = Permission::findOrFail($request->id);
            $datas->name = $request->name;
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
            $datas = Permission::findOrFail($id);
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
            $datas = Permission::find($id);
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
