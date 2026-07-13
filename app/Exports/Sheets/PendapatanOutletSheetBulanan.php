<?php
namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendapatanOutletSheetBulanan implements FromView, WithColumnWidths, WithTitle
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function columnWidths(): array
    {
        $columnWidth = [
            'A' => 4,
            'B' => 17,
            'C' => 16,
            'D' => 11,
            'E' => 16,
            'F' => 16,
            'G' => 25,
        ];
        
        return $columnWidth;
    }

    public function view(): View
    {
        return view('pages.report.expor_data.bulanan.sheet_outlet', $this->data);
    }
    
    public function title(): string
    {
        return 'Pendapatan Outlet';
    }
}