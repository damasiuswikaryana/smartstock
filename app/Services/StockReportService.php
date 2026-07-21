<?php

namespace App\Services;

use App\Models\Stock;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class StockReportService
{
    public function getData($whid, $cat, $entitas)
    {
        $stocks = Stock::query()
            ->with([
                'lokasi',
                'entitas',
                'item_varian.itemMaster.category'
            ])
            ->when($entitas != 'all', function ($q) use ($entitas) {
                $q->where('entitas_id', $entitas);
            })
            ->when($cat != 'all', function ($q) use ($cat) {
                $q->whereHas('item_varian.itemMaster', function ($q) use ($cat) {
                    $q->where('category_id', $cat);
                });
            })
            ->when($whid != 'all', function ($q) use ($whid) {
                $q->where('lokasi_id', $whid);
            })
            ->orderBy('lokasi_id')
            ->get();

        $stocks = $stocks->groupBy(function ($item) {
            return $item->lokasi->nama;
        })->map(function ($werehouse) {
            return $werehouse->groupBy(function ($item) {
                return optional($item->item_varian->itemMaster->category)->title;
            });
        });

        return [
            'stocks'        => $stocks,
            'warehouse'     => $whid,
            'generated_at'  => now(),
        ];
    }

    public function generatePdf($warehouse = 'all', $category = 'all', $entitas = 'all')
    {
        $data = $this->getData($warehouse, $category, $entitas);
        return PDF::setOptions([
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => true
        ])
            ->setPaper('a4', 'portrait')
            ->loadView('pages.stock.current.pdf_report', [
                'stocks'    => $data['stocks'],
            ]);
    }

    public function savePdf($pdf)
    {
        Storage::makeDirectory('reports/stock');
        $waktu    = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename = 'Stock Report - Semua Gudang - ' . $waktu . '.pdf';
        Storage::put(
            'reports/stock/' . $filename,
            $pdf->output()
        );
        return storage_path(
            'app/reports/stock/' . $filename
        );
    }
}
