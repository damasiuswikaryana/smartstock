<?php

namespace App\Services;

use App\Models\Ptw;
use App\Models\PoChild;
use App\Models\User;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PtwDownloladService
{
    public function getData(int $id)
    {
        $data           = Ptw::with(['child.poMaster', 'child.varian'])->where('id', $id)->first();
        foreach ($data->child as $child) {
            $child->qty_po = PoChild::where('po_id', $child->po_id)
                ->where('item_varian_id', $child->item_varian_id)
                ->value('qty');
        }
        $gudang         = User::role('gudang')->first();

        return [
            'ptw'               => $data,
            'generated_at'      => now(),
            'gudang'            => $gudang,

        ];
    }

    public function generatePdf(int $id)
    {
        $data   = $this->getData($id);
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
            ->loadView('pages.ptw.pdf_ptw', [
                'data'              => $data['ptw'],
                'checked'           => $base64Checked,
                'icon'              => $base64Icon,
                'gudang'            => $data['gudang'],
            ]);
    }
}
