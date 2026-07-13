<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Satuan;
use App\Models\Outlet;
use App\Models\Gerobak;
use App\Models\StockOpnamItem;
use App\Models\Product;
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

use App\Exports\Sheets\StockSpoiledSheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StockSpoiled implements WithMultipleSheets
{
    use Exportable;
    
    protected $awal;
    protected $akhir;
    public function __construct() {}
    
    public function sheets(): array
    {
        $data = $this->getData();
        return [
            new StockSpoiledSheet($data),
        ];
    }
    
    protected function getData()
    {
        $awal           = $this->awal;
        $akhir          = $this->akhir;
        $start          = Carbon::parse($awal)->startOfDay();
        $end            = Carbon::parse($akhir)->endOfDay(); 
        
        $idOutlet       = Auth::user()->loc_id;
        $item           = StockOpnamItem::all();
        $product        = Product::all();
        $satuan         = Satuan::all();
        $outlet         = Outlet::all();
        $gerobak        = Gerobak::all();
        
        $data           = StockMutation::where('tipe', 'Spoiled')
                            ->where('target_type', 'External')
                            ->where(function ($q) {
                                $q->where('source_type', 'Outlet')->orWhere('source_type', 'Gerobak');
                            })
                            ->whereBetween('created_at', [$start, $end])
                            ->with(['item', 'product', 'satuan'])->orderBy('created_at', 'desc')->get();

        return [
            'data'  => $data,
            'awal'  => $awal,
            'akhir' => $akhir,
        ];
    }
}
