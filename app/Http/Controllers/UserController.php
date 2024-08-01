<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
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
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
    }
    public function create()
    {
        $datas["roles"] = Role::orderBy('name', 'ASC')->get();
        return response()->json([
            'success' => true,
            'data' => $datas
        ]);
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

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $request->role
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
            $datas = User::findOrFail($request->id);
            $datas->name = $request->name;
            $datas->email = $request->email;
            $datas->password = Hash::make('password');
            $datas->email_verified_at = !blank($request->verified) ? now() : null;
            $datas->update();

            $datas->syncRoles(!blank($request->role) ? $request->role : array());

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
        $user = User::find($id);
        $datas['role'] = $user->getRoleNames()->toArray();
        $datas["roles"] = Role::orderBy('name', 'ASC')->get();
        $datas["user"] = User::findOrfail($id);
        return response()->json([
            'success' => true,
            'data' => $datas
        ]);
    }
    public function destroy($id)
    {
        try {
            $datas = User::find($id);
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
