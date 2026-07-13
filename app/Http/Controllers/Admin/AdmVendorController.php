<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdmVendorController extends Controller
{
    public function index(Request $request)
    {
        $bank   = Bank::all();
        $data   = Vendor::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('vendor.ubah', $row->id) . '" class="avtar avtar-s btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-s btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('bank', function ($row) {
                    return $row->bank->bank_name;
                })
                ->addColumn('is_dp', function ($row) {
                    if ($row->is_dp) {
                        return '<span class="badge bg-light-dark">Available</span>';
                    } else {
                        return '<span class="badge bg-light-danger">Not Available</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'is_dp', 'bank'])
                ->make(true);
        }
        return view('pages.vendor.index', compact('bank'));
    }

    public function store(Request $request)
    {
        $input  = $request->all();
        try {
            DB::beginTransaction();
            Vendor::create([
                'kode'                 => $input['kode'],
                'nama'                 => $input['nama'],
                'alamat'               => $input['alamat'],
                'kabupaten'            => $input['kabupaten'],
                'provinsi'             => $input['provinsi'],
                'negara'               => $input['negara'],
                'kode_pos'             => $input['kode_pos'],
                'website'              => $input['website'],
                'pic_name'             => $input['pic_name'],
                'pic_jabatan'          => $input['pic_jabatan'],
                'phone'                => $input['phone'],
                'email'                => $input['email'],
                'npwp'                 => $input['npwp'],
                'bank_id'              => $input['bank_id'],
                'bank_account_number'  => $input['bank_account_number'],
                'bank_account_name'    => $input['bank_account_name'],
                'termin_pembayaran'    => $input['termin_pembayaran'],
                'is_dp'                => $input['is_dp'],
                'catatan'              => $input['catatan'],
                'status'               => $input['status'],
            ]);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $bank   = Bank::all();
        $data   = Vendor::where('id', $id)->first();
        try {
            return view('pages.vendor.edit', compact('data', 'bank'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data   = Vendor::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->kode                 = $input['kode'];
            $data->nama                 = $input['nama'];
            $data->alamat               = $input['alamat'];
            $data->kabupaten            = $input['kabupaten'];
            $data->provinsi             = $input['provinsi'];
            $data->negara               = $input['negara'];
            $data->kode_pos             = $input['kode_pos'];
            $data->website              = $input['website'];
            $data->pic_name             = $input['pic_name'];
            $data->pic_jabatan          = $input['pic_jabatan'];
            $data->phone                = $input['phone'];
            $data->email                = $input['email'];
            $data->npwp                 = $input['npwp'];
            $data->bank_id              = $input['bank_id'];
            $data->bank_account_number  = $input['bank_account_number'];
            $data->bank_account_name    = $input['bank_account_name'];
            $data->termin_pembayaran    = $input['termin_pembayaran'];
            $data->is_dp                = $input['is_dp'];
            $data->catatan              = $input['catatan'];
            $data->status               = $input['status'];

            $data->save();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $data = Vendor::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
