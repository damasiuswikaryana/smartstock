<?php
namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengeluaranSheetBulanan implements FromView, WithColumnWidths, WithTitle
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
            'B' => 10,
            'C' => 17,
            'D' => 35,
            'E' => 16,
            'F' => 25,
            'G' => 13,
        ];
        
        return $columnWidth;
    }

    public function view(): View
    {
        return view('pages.report.expor_data.bulanan.sheet_pengeluaran', $this->data);
    }
    
    public function title(): string
    {
        return 'Pengeluaran';
    }
}