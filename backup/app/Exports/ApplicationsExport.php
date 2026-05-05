<?php

namespace App\Exports;

use App\Models\ApplicationForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class ApplicationsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected Collection $applications;

    public function __construct(Collection $applications)
    {
        $this->applications = $applications;
    }

    public function title(): string { return 'Applications'; }

    public function collection(): Collection
    {
        return $this->applications->map(function ($app, $index) {
            return [
                'sn'               => $index + 1,
                'advertisement_no' => $app->advertisement_no,
                'name'             => $app->name_english,
                'email'            => $app->email,
                'phone'            => $app->phone,
                'position'         => $app->position,
                'applied_category' => $app->applied_category ? ucfirst($app->applied_category) : '-',
                'status'           => ucfirst(str_replace('_', ' ', $app->status ?? '')),
                'reviewer'         => $app->reviewer?->name ?? '-',
                'reviewed_at'      => $app->reviewed_at ? $app->reviewed_at->format('Y-m-d') : '-',
                'approver'         => $app->approver?->name ?? '-',
                'approved_at'      => $app->approved_at ? $app->approved_at->format('Y-m-d') : '-',
                'applied_on'       => $app->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.N.', 'Advertisement No.', 'Applicant Name', 'Email', 'Phone',
            'Position', 'Applied Category', 'Status',
            'Reviewer', 'Reviewed At', 'Approver', 'Approved/Rejected At', 'Applied On',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C9A84C']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6, 'B' => 18, 'C' => 25, 'D' => 28, 'E' => 16,
            'F' => 22, 'G' => 18, 'H' => 14,
            'I' => 20, 'J' => 14, 'K' => 20, 'L' => 20, 'M' => 14,
        ];
    }
}
