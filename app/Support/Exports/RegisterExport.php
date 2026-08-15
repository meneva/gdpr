<?php

namespace App\Support\Exports;

use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * One shared exporter used by every module's controller — a register is
 * just headers + rows, whether it's SARs, breaches, DPIAs, suppliers, or
 * processing activities. Keeping the export mechanism in one place means
 * a future format (XLSX, say) only needs adding here once.
 */
class RegisterExport
{
    public static function csv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            foreach ($rows as $row) {
                fputcsv($out, $row);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public static function pdf(string $title, array $headers, iterable $rows, string $filename)
    {
        $pdf = Pdf::loadView('exports.pdf', [
            'title' => $title,
            'headers' => $headers,
            'rows' => $rows,
            'companyName' => optional(Company::find(session('current_company_id')))->name,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
