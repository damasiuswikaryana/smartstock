<?php

namespace App\Services;

use App\Models\StockInMaster;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TandaTerimaInService
{
    public function getData(int $id)
    {
        $data           = StockInMaster::with('child')->where('id', $id)->first();
        return [
            'data'              => $data,
            'generated_at'      => now(),
        ];
    }

    public function generatePdf(int $id)
    {
        $data   = $this->getData($id);
        //-----
        $pathIcon   = public_path('assets/images/icons/icon-96x96.jpg');
        $typeIcon   = pathinfo($pathIcon, PATHINFO_EXTENSION);
        $data64Icon = file_get_contents($pathIcon);
        $base64Icon = 'data:image/' . $typeIcon . ';base64,' . base64_encode($data64Icon);

        return PDF::setOptions([
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => true
        ])
            ->setPaper('a4', 'portrait')
            ->loadView('pages.stock.in.tandaTerima', [
                'data'              => $data['data'],
                'icon'              => $base64Icon,
            ]);
    }
}
