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

use App\Exports\Sheets\StockOutletSheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StockOutlet implements WithMultipleSheets
{
    use Exportable;
    
    protected $id_outlet;
    
    public function __construct(string $id_outlet) {
        $this->id_outlet = $id_outlet;
    }
    
    public function sheets(): array
    {
        $data = $this->getData();
        return [
            new StockOutletSheet($data),
        ];
    }
    
    protected function getData()
    {
        $idOutlet       = $this->id_outlet;
        $data           = StockOpnamItem::all();
        $current        = StockOpnamOutlet::where('id_outlet', $idOutlet)->get();
        $outlet         = Outlet::findOrFail($idOutlet);
        
        foreach ($data as $d) {
            $d->stock_outlet = collect([]);
            foreach ($current as $c) {
                if ($c->item_id == $d->id) {
                    $d->stock_outlet->push($c);
                }
            }
        }

        return [
            'data'      => $data,
            'outlet'    => $outlet,
        ];
    }
}
