<?php

namespace App\Http\Controllers\Admin;

use App\Models\BankAccount;
use App\Models\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;

class AdmProjectRequirementController extends Controller
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
        return view('pages.requirements.index', [
            'banks' => $banks,
        ]);
    }

    public function store(Request $request) {}

    public function edit(int $id) {}

    public function update(Request $request, int $id) {}

    public function destroy(int $id) {}
}
