<?php

namespace App\Services;

use App\Models\Project;
use App\Models\StockMutation;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MutationContractFullfillmentService
{
    public function getData(int $id)
    {
        $data           = Project::with('items')->where('id', $id)->first();
        $gudang_koneksi = $data->werehouse_id;

        $data_in        = StockMutation::where('pekerjaan_id', $id)
            ->where('tipe', 'Masuk')
            ->where('target_id', $gudang_koneksi)
            ->get();

        $data_out       = StockMutation::where('pekerjaan_id', $id)
            ->where('tipe', 'Keluar')
            ->where('source_id', $gudang_koneksi)
            ->get();

        $data_trf       = StockMutation::where('pekerjaan_id', $id)
            ->where('tipe', 'Transfer')
            ->where(function ($query) use ($gudang_koneksi) {
                $query->where('source_id', $gudang_koneksi)
                    ->orWhere('target_id', $gudang_koneksi);
            })
            ->get();

        return [
            'data'          => $data,
            'masuk'         => $data_in,
            'keluar'        => $data_out,
            'transfer'      => $data_trf,
            'generated_at'  => now(),
        ];
    }

    public function generatePdf(int $id)
    {
        $data = $this->getData($id);
        return PDF::setOptions([
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => true
        ])
            ->setPaper('a4', 'landscape')
            ->loadView('pages.fullfillment.pdf_report', [
                'data'          => $data['data'],
                'data_masuk'    => $data['masuk'],
                'data_keluar'   => $data['keluar'],
                'data_transfer' => $data['transfer'],
                'generated_at'  => $data['generated_at'],
            ]);
    }
}
