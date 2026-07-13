<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Satuan;
use App\Models\Outlet;
use App\Models\StockOpnamItem;
use App\Models\StockOpnamMaster;
use App\Models\StockOpnamOutlet;
use App\Models\StockMutation;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\StockMasterSheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StockMaster implements WithMultipleSheets
{
    use Exportable;
    
    protected $awal;
    protected $akhir;
    public function __construct() {}
    
    public function sheets(): array
    {
        $data = $this->getData();
        return [
            new StockMasterSheet($data),
        ];
    }
    
    protected function getData()
    {
        $data           = StockOpnamItem::all();
        $current        = StockOpnamMaster::all();
        
        foreach ($data as $d) {
            $d->stock_master = collect([]);
            foreach ($current as $c) {
                if ($c->item_id == $d->id) {
                    $d->stock_master->push($c);
                }
            }
        }

        return [
            'data' => $data,
        ];
    }
}
