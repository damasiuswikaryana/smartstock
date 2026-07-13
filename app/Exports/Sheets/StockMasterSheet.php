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

class StockMasterSheet implements FromView, WithEvents, WithColumnWidths, WithTitle
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
            'B' => 28,
            'C' => 50,
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
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'size' => 20,
                    ],
                ]);
            },
        ];
    }

    public function view(): View
    {
        return view('pages.stock_opnam.master.expor_data.sheet_stock_master', $this->data);
    }
    
    public function title(): string
    {
        return 'Master Stock';
    }
}