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

use App\Exports\Sheets\StockMutasiOutletSheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StockOutletMutasi implements WithMultipleSheets
{
    use Exportable;
    
    protected $id_outlet;
    protected $awal;
    protected $akhir;
    
    public function __construct(string $id_outlet, string $awal, string $akhir, )
    {
        $this->id_outlet    = $id_outlet;
        $this->awal         = $awal;
        $this->akhir        = $akhir;
    }
    
    public function sheets(): array
    {
        $data = $this->getData();
        return [
            new StockMutasiOutletSheet($data),
        ];
    }
    
    protected function getData()
    {
        $idOutlet       = $this->id_outlet;
        $awal           = $this->awal;
        $akhir          = $this->akhir;
        $start          = Carbon::parse($awal)->startOfDay();
        $end            = Carbon::parse($akhir)->endOfDay(); 
        $outlet         = Outlet::findOrFail($idOutlet);
        
        $riwayatOutlet  =   StockMutation::where(function ($query) use ($idOutlet) {
                                $query->where(function ($q) use ($idOutlet) {
                                    $q->where('source_type', 'Outlet')->where('source_id', $idOutlet);
                                })->orWhere(function ($q) use ($idOutlet) {
                                    $q->where('target_type', 'Outlet')->where('target_id', $idOutlet);
                                });
                            })
                            ->whereBetween('created_at', [$start, $end])
                            ->with(['item', 'satuan'])->orderBy('created_at', 'desc')->get();

        return [
            'riwayatOutlet'     => $riwayatOutlet,
            'awal'              => $awal,
            'akhir'             => $akhir,
            'outlet'            => $outlet,
        ];
    }
}
