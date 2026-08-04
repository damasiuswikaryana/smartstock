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
        $subtotal       = $data->child->sum(function ($child) {
            return $child->unit_price * $child->qty;
        });
        $tax            = $data->tax;
        $tax_amount     = $tax / 100 * $subtotal;
        $total_after_tax = $subtotal + $tax_amount;
        $disc           = $data->disc;
        $total_after_disc = $total_after_tax - $disc;
        $dp             = $data->dp;
        $total_after_dp = $total_after_disc + $dp;
        $logo           = $data->entitas->entitas_logo;

        return [
            'po'                => $data,
            'subtotal'          => $subtotal,
            'tax_amount'        => $tax_amount,
            'total_after_tax'   => $total_after_tax,
            'total_after_disc'  => $total_after_disc,
            'total_after_dp'    => $total_after_dp,
            'logo'              => $logo,
            'generated_at'      => now(),
        ];
    }

    public function generatePdf(int $id)
    {
        $data   = $this->getData($id);
        //-----
        $logo   = $data['logo'];
        $path   = storage_path('app/public/entitas/' . $logo);
        $type   = pathinfo($path, PATHINFO_EXTENSION);
        $data64 = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data64);
        //-----
        $pathChecked   = storage_path('app/public/entitas/checked.jpg');
        $typeChecked   = pathinfo($pathChecked, PATHINFO_EXTENSION);
        $data64Checked = file_get_contents($pathChecked);
        $base64Checked = 'data:image/' . $typeChecked . ';base64,' . base64_encode($data64Checked);
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
            ->loadView('pages.po.pdf_po', [
                'data'              => $data['po'],
                'subtotal'          => $data['subtotal'],
                'tax_amount'        => $data['tax_amount'],
                'total_after_tax'   => $data['total_after_tax'],
                'total_after_disc'  => $data['total_after_disc'],
                'total_after_dp'    => $data['total_after_dp'],
                'logo'              => $base64,
                'checked'           => $base64Checked,
                'icon'              => $base64Icon,
            ]);
    }
}
