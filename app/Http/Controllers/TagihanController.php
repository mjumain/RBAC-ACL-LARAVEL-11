<?php

namespace App\Http\Controllers;

use App\Imports\PreviewImport;
use App\Models\Tagihan;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datas = Tagihan::all();
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" class="btn btn-warning btn-sm btn-icon editdata" data-id="' . $row->id . '"><i class="far fa-edit"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm btn-icon deleteData"><i class="fas fa-trash-alt"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-sm btn-icon refreshData"><i class="fas fa-sync-alt"></i></a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-success btn-sm btn-icon flagData"><i class="far fa-flag"></i></a>';
                    return $btn;
                })
                ->editColumn('status_pembayaran', function ($data_pembayaran) {
                    if (empty($data_pembayaran->status_pembayaran)) {
                        if ($data_pembayaran->waktu_kadaluarsa >= Carbon::now()) {
                            return '<span class="badge badge-primary">Menunggu Pembayaran</span>';
                        } else {
                            return '<span class="badge badge-danger">Pembayaran Kadaluarsa</span>';
                        }
                    } else {
                        return '<span class="badge badge-success">Pembayaran Berhasil</span>';
                    }
                })
                ->editColumn('rincian', function ($data_pembayaran) {
                    $rincian = '';
                    foreach (json_decode($data_pembayaran->rincian) as $key => $value) {
                        $rincian = $rincian . $value->kode_rincian . '<span class="float-right">=</span>' . $value->nominal . '</br>';
                    };
                    return $rincian;
                })
                ->editColumn('info', function ($data_pembayaran) {
                    $rincian = '<table class="table table-default">';
                    foreach (json_decode($data_pembayaran->info) as $key => $value) {
                        $rincian = $rincian . '<tr><td>';
                        $rincian = $rincian . $value->label_key . '</td><td>=</td><td>' . $value->label_value;
                        $rincian = $rincian . '</td></tr>';
                    };
                    $rincian = $rincian . '</tabel>';
                    return $rincian;
                })
                ->rawColumns(['action', 'status_pembayaran', 'rincian', 'info'])
                ->make(true);
        }
        return view('tagihan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->aksi == 'create') {
            return Response::HTTP_OK;
        } elseif ($request->aksi == 'import') {
            return response()->json([
                'data' => $request->all(),
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (isset($request->aksi)) {
                if ($request->aksi == 'preview') {
                    $request->validate([
                        'berkas' => 'required|mimes:xlsx,xls,csv',
                    ]);
                    $file = $request->file('berkas');
                    $nama_file = $file->hashName();
                    $path = $file->storeAs('public/excel/', $nama_file);

                    $data_array = Excel::toArray(new class implements ToArray, WithHeadingRow {
                        public function array(array $array)
                        {
                            return $array;
                        }
                    }, $path);

                    $sheetData = $data_array[0];

                    $data = [];

                    foreach ($sheetData as $row) {
                        $info = [];
                        $rincian = [];
                        $error = '';
                        $total_rincian = 0;

                        $filteredInfo = array_filter(array_keys($row), function ($header) {
                            return strpos($header, 'info_') === 0;
                        });

                        foreach ($filteredInfo as $header) {
                            $info[] = [
                                'label_key' => strtoupper(Str::after($header, 'info_')),
                                'label_value' => $row[$header],
                            ];
                        }

                        $filteredRincian = array_filter(array_keys($row), function ($header) {
                            return strpos($header, 'rincian_') === 0;
                        });

                        foreach ($filteredRincian as $header) {
                            $rincian[] = [
                                'kode_rincian' => $header,
                                'deskripsi' => strtoupper(Str::after($header, 'rincian_')),
                                'nominal' => $row[$header]
                            ];
                            $total_rincian = $total_rincian + $row[$header];
                        }

                        if (strtolower(substr($row['nomor_pokok_mahasiswa'] ?? '', 0, 1)) == 's') {
                            $nomor_pembayaran = str_replace('s', '', strtolower($row['nomor_pokok_mahasiswa'] ?? ''));
                        } else {
                            $nomor_pembayaran = str_replace('1031', '', $row['nomor_pokok_mahasiswa'] ?? '');
                        }

                        if (isset($row['waktu_kadaluarsa'])) {
                            $waktu_kadaluarsa = Date::excelToDateTimeObject(intval($row['waktu_kadaluarsa']))->format('Y-m-d 23:59:59');
                        } else {
                            $waktu_kadaluarsa = Carbon::now()->format('Y-m-d 23:59:59');
                        }

                        // Validasi Data
                        if (!isset($row['nomor_pokok_mahasiswa'])) {
                            $error = $error . 'Nomor Pokok Mahsiswa Tidak Ada </br>';
                        }
                        if (isset($row['total_nominal'])) {
                            if ($row['total_nominal'] != $total_rincian) {
                                $error = $error . 'Total Rincian dan Total Nominal Tidak Sama </br>';
                            }
                        }

                        $data[] = [
                            'nomor_pembayaran' => $nomor_pembayaran ?? '',
                            'nim' => $row['nomor_pokok_mahasiswa'] ?? '',
                            'nama' => $row['nama'] ?? '',
                            'info' => json_encode($info) ?? array(),
                            'rincian' => json_encode($rincian) ?? array(),
                            'waktu_berlaku' => Carbon::now()->format('Y-m-d h:i:s'),
                            'waktu_kadaluarsa' => $waktu_kadaluarsa,
                            'total_nominal' => $row['total_nominal'] ?? 0,
                            'keterangan' => ($error == '') ? 'Siap import' : $error,
                        ];
                    }

                    return response()->json([
                        'success' => true,
                        'data' => $data
                    ], 200);
                } elseif ($request->aksi == 'import') {

                    if (is_string($request->input('input'))) {
                        $json = json_decode($request->input('input'), true);
                    }

                    $filteredData = array_map(function ($item) {
                        $timestamp = microtime(true);
                        $randomPart = rand(1000, 9999);
                        $inv = intval($timestamp * 10000) + $randomPart;

                        $item['id_pelanggan'] = $item['nim'];
                        $item['nomor_invoice'] = 'INV' . $inv;
                        unset($item['nim']);
                        unset($item['keterangan']);
                        return $item;
                    }, $json);

                    foreach ($filteredData as $key => $value) {
                        $data[] = $value;
                    }

                    Tagihan::insert($data);
                    return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil ditambah'
                    ], 201);
                }
            } else {
                foreach ($request->label_key as $key => $value) {
                    foreach ($request->label_value as $oo => $data) {
                        if ($key == $oo) {
                            $info[] = [
                                'label_key' => $value,
                                'label_value' => $data,
                            ];
                        }
                    }
                };

                $total_nominal = 0;
                foreach ($request->kode_rincian as $key => $value) {
                    foreach ($request->nominal as $oo => $data) {
                        if ($key == $oo) {
                            $rincian[] = [
                                'kode_rincian' => $value,
                                'deskripsi' => $value,
                                'nominal' => $data,
                            ];
                            $total_nominal = $total_nominal + $data;
                        }
                    }
                };

                if (strtoupper(substr($request->nim, 0, 1)) === 'S') {
                    $nomor_pembayaran = Str::replaceArray('s', [''], strtolower($request->nim));
                } else {
                    $nomor_pembayaran = $request->nim;
                }

                $timestamp = microtime(true);
                $randomPart = rand(1000, 9999);
                $inv = intval($timestamp * 10000) + $randomPart;

                $data = new Tagihan();
                $data->nomor_invoice = 'INV' . $inv;
                $data->nomor_pembayaran = $nomor_pembayaran;
                $data->waktu_berlaku = Carbon::now()->format('Y-m-d H:i:s');
                $data->waktu_kadaluarsa = Carbon::now()->format('Y-m-d H:i:s');
                $data->nama = $request->nama;
                $data->id_pelanggan = $request->nim;
                $data->info = json_encode($info);
                $data->rincian = json_encode($rincian);
                $data->total_nominal = $total_nominal;
                $data->save();
            };
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambah'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Validasi file
        $request->validate([
            'berkas' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Ambil file dari request
        $file = $request->file('berkas');

        $nama_file = $file->hashName();

        //temporary file
        $path = $file->storeAs('public/excel/', $nama_file);

        // import data
        $data = Excel::import(new PreviewImport(), storage_path('app/public/excel/' . $nama_file));

        // Kirimkan data yang diambil sebagai JSON untuk ditampilkan
        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $datas['tagihan'] = Tagihan::findOrFail($id);
            $datas['aksi'] = ['aksi' => 'update'];
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            if ($request->aksi === 'perpanjang') {
                $datas = Tagihan::findOrFail($request->id);
                if ($datas->status_pembayaran === "Terbayar") {
                    return response()->json([
                        'message' => 'Perpanjangan Gagal',
                        'error' => 'Tagihan yang telah terbayar tidak dapat di perpanjang',
                    ], 500);
                }
                $datas->waktu_kadaluarsa = Carbon::now()->addDays(intval($request->hari));
                $datas->update();
            } elseif ($request->aksi === 'update') {

                foreach ($request->label_key as $key => $value) {
                    foreach ($request->label_value as $oo => $data) {
                        if ($key == $oo) {
                            $info[] = [
                                'label_key' => $value,
                                'label_value' => $data,
                            ];
                        }
                    }
                };

                $total_nominal = 0;
                foreach ($request->kode_rincian as $key => $value) {
                    foreach ($request->nominal as $oo => $data) {
                        if ($key == $oo) {
                            $rincian[] = [
                                'kode_rincian' => $value,
                                'deskripsi' => $value,
                                'nominal' => $data,
                            ];
                            $total_nominal = $total_nominal + $data;
                        }
                    }
                };

                $data = Tagihan::FindOrFail($request->id);
                $data->nama = $request->nama;
                $data->id_pelanggan = $request->nim;
                $data->info = json_encode($info);
                $data->rincian = json_encode($rincian);
                $data->update();
            } elseif ($request->aksi === 'flagging') {
                $data = Tagihan::FindOrFail($request->id);
                $data->status_pembayaran = 'Terbayar';
                $data->waktu_pembayaran = Carbon::now()->format('Y-m-d H:i:s');
                $data->update();
            }
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $datas = Tagihan::find($id);
            $datas->delete();
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
