<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockMutation;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StockMutationService
{
    public function getData($tipe, $source, $target, $category)
    {
        $lokasi  = Auth::user()->loc_id;
        if (
            Auth::user()->roles[0]->name == "masteradmin"
            || Auth::user()->roles[0]->name == "pengadaan"
            || Auth::user()->roles[0]->name == "gudang"
            || Auth::user()->roles[0]->name == "keuangan"
            || Auth::user()->roles[0]->name == "adminkeuangan"
            || Auth::user()->roles[0]->name == "director"
        ) {
            $data       = StockMutation::query();
        } else {
            $data       = StockMutation::where('source_id', $lokasi)->orWhere('target_id', $lokasi);
        }

        if ($tipe != 'all') {
            $data       = $data->where('tipe', $tipe);
        }
        if ($source != 'all') {
            if ($source != "External") {
                $data       = $data->where('source_id', $source);
            } else {
                $data       = $data->where('source_type', $source);
            }
        }
        if ($target != 'all') {
            if ($target != "External") {
                $data       = $data->where('target_id', $target);
            } else {
                $data       = $data->where('target_type', $target);
            }
        }
        if ($category != 'all') {
            $data->whereHas('item_varian.itemMaster', function ($q) use ($category) {
                $q->where('category_id', $category);
            });
        }
        // get data
        $data = $data->get();

        return [
            'data'          => $data,
            'generated_at'  => now(),
        ];
    }

    public function generatePdf($tipe = 'all', $source = 'all', $target = 'all', $category = 'all')
    {
        $data = $this->getData($tipe, $source, $target, $category);
        return PDF::setOptions([
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => true
        ])
            ->setPaper('a4', 'landscape')
            ->loadView('pages.stock.mutation.pdf_report', [
                'datas'    => $data['data'],
            ]);
    }

    public function savePdf($pdf)
    {
        Storage::makeDirectory('reports/mutation');
        $waktu    = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename = 'Stock Mutation - Semua Gudang - ' . $waktu . '.pdf';
        Storage::put(
            'reports/mutation/' . $filename,
            $pdf->output()
        );
        return storage_path(
            'app/reports/mutation/' . $filename
        );
    }
}
