<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Satuan;
use App\Models\Outlet;
use App\Models\StockOpnamItem;
use App\Models\StockOpnamMaster;
use App\Models\StockOpnamOutlet;
use App\Models\StockMutation;
use App\Models\StockOpnam;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\StockOpnamHarianSheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StockOpnamHarian implements WithMultipleSheets
{
    use Exportable;
    
    protected $tanggal;
    public function __construct(string $tanggal) {
        $this->tanggal = $tanggal;
    }
    
    public function sheets(): array
    {
        $data = $this->getData();
        return [
            new StockOpnamHarianSheet($data),
        ];
    }
    
    protected function getData()
    {
        $tanggal        = $this->tanggal;
        $idOutlet       = Auth::user()->loc_id;
        $data           = StockOpnamItem::all();
        $stockOpnam     = StockOpnam::where('id_outlet', $idOutlet)->whereDate('created_at', $tanggal)->get();
        $outlet         = Outlet::findOrFail($idOutlet);
        
        foreach ($data as $item) {
            $item->opnam = null;
            foreach ($stockOpnam as $opnam) {
                if ($item->id == $opnam->item_id) {
                    $item->opnam = $opnam;
                }
            }
        }
        
        return [
            'data'      => $data,
            'tanggal'   => $tanggal,
            'outlet'    => $outlet,
        ];
    }
}
