<?php

namespace App\Services;

use App\Models\Po;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PoDownloladService
{
    public function getData(int $id)
    {
        $data           = Po::with('child')->where('id', $id)->first();
        $totals         = $data->calculateTotals();
        $logo           = $data->entitas->entitas_logo;

        return [
            'po'                => $data,
            'totals'            => $totals,
            'logo'              => $logo,
            'generated_at'      => now(),
        ];
    }

    public function generatePdf(int $id)
    {
        $data   = $this->getData($id);

        // logo entitas boleh kosong (belum diupload); jangan sampai download PO jadi error
        $logo          = $data['logo'] ? $this->imageBase64(storage_path('app/public/entitas/' . $data['logo'])) : null;
        $base64Checked = $this->imageBase64(storage_path('app/public/entitas/checked.jpg'));
        $base64Icon    = $this->imageBase64(public_path('assets/images/icons/icon-96x96.jpg'));

        return PDF::setOptions([
            'isHtml5ParserEnabled'  => true,
            'isRemoteEnabled'       => true
        ])
            ->setPaper('a4', 'portrait')
            ->loadView('pages.po.pdf_po', [
                'data'              => $data['po'],
                'totals'            => $data['totals'],
                'logo'              => $logo,
                'checked'           => $base64Checked,
                'icon'              => $base64Icon,
            ]);
    }

    /**
     * Ubah file gambar menjadi data URI base64. Null jika file tidak ada.
     */
    private function imageBase64(string $path): ?string
    {
        if (!is_file($path)) {
            return null;
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
    }
}
