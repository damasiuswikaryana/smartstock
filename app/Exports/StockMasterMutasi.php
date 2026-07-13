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

use App\Exports\Sheets\StockMutasiSheet;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StockMasterMutasi implements WithMultipleSheets
{
    use Exportable;
    
    protected $awal;
    protected $akhir;
    public function __construct(string $awal, string $akhir)
    {
        $this->awal         = $awal;
        $this->akhir        = $akhir;
    }
    
    public function sheets(): array
    {
        $data = $this->getData();
        return [
            new StockMutasiSheet($data),
        ];
    }
    
    protected function getData()
    {
        $awal           = $this->awal;
        $akhir          = $this->akhir;
        $bulanTahun     = Carbon::parse($awal)->format('F Y');
        $start          = Carbon::parse($awal)->startOfDay();
        $end            = Carbon::parse($akhir)->endOfDay(); 
        
        $riwayatMaster  = StockMutation::where(function ($query) {
                            $query->where('source_type', 'Master')->orWhere('target_type', 'Master');
                          })
                          ->whereBetween('created_at', [$start, $end])
                          ->with(['item', 'satuan'])->orderBy('created_at', 'desc')->get();

        return [
            'riwayatMaster'     => $riwayatMaster,
            'awal'              => $awal,
            'akhir'             => $akhir,
        ];
    }
}
