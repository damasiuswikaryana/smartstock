<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Mail\MonthlyStockReportMail;
use App\Services\StockReportService;
use Illuminate\Support\Facades\Mail;

class SendMonthlyStockReport extends Command
{
    protected $signature    = 'stock:monthly-report';
    protected $description  = 'Generate monthly stock report and send via email';

    public function __construct(protected StockReportService $reportService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $pdf    = $this->reportService->generatePdf();
        $path   = $this->reportService->savePdf($pdf);

        Mail::to(config('inventory.report_email'))->send(new MonthlyStockReportMail($path));

        $this->info('Monthly stock report sent successfully.');
        return Command::SUCCESS;
    }
}
