<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdmCategoryController extends Controller
{
    public function index(Request $request)
    {
        $data_product   = Category::all();
        $data = Category::orderBy('title')->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('category.ubah', $row->id) . '" class="avtar avtar-s btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-s btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.category.index', [
            'data_product' => $data_product,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            Category::create($input);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function show(int $id)
    {
        $data = Category::where('id', $id)->first();
        try {
            return view('pages.category.detail', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $data_product   = Category::all();
        $data           = Category::where('id', $id)->first();
        try {
            return view('pages.category.edit', compact('data', 'data_product'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data = Category::where('id', $id)->first();
        $input = $request->all();
        try {
            DB::beginTransaction();
            $data->title        = $input['title'];
            $data->description  = $input['description'];
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
            $data = Category::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
