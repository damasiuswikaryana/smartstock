<?php

namespace App\Http\Controllers\Admin;

use App\Models\BankAccount;
use App\Models\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;

class AdmBankAccountController extends Controller
{
    public function index(Request $request)
    {
        $banks  = Bank::all();
        $data   = BankAccount::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('bank-account.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })->addColumn('id_bank', function ($row) {
                    return $row->bank->bank_name;
                })->rawColumns(['action', 'updated_at', 'id_bank'])
                ->make(true);
        }
        return view('pages.bank_account.index', [
            'banks' => $banks,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            DB::beginTransaction();
            BankAccount::create([
                'id_bank'               => $input['id_bank'],
                'bank_account_name'     => $input['bank_account_name'],
                'bank_account_number'   => $input['bank_account_number'],
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
        $banks  = Bank::all();
        $data   = BankAccount::where('id', $id)->first();
        try {
            return view('pages.bank_account.edit', compact('data', 'banks'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data   = BankAccount::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->id_bank              = $input['id_bank'];
            $data->bank_account_name    = $input['bank_account_name'];
            $data->bank_account_number  = $input['bank_account_number'];
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
            $data = BankAccount::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
