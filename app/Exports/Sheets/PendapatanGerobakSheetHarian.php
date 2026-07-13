<?php
namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendapatanGerobakSheetHarian implements FromView, WithEvents, WithColumnWidths, WithTitle
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
            'B' => 31,
            'C' => 18,
            'D' => 18,
            'E' => 23,
            'F' => 18,
        ];
        
        return $columnWidth;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet          = $event->sheet->getDelegate();
                $highestColumn  = $sheet->getHighestColumn();
                $highestRow     = $sheet->getHighestRow();
                $range          = 'A1:' . $highestColumn . $highestRow;
                $sheet->getStyle($range)->applyFromArray([
                    'font' => [
                        'size' => 14,
                    ],
                ]);
            },
        ];
    }

    public function view(): View
    {
        return view('pages.report.expor_data.harian.sheet_gerobak', $this->data);
    }
    
    public function title(): string
    {
        return 'Pendapatan Gerobak';
    }
}