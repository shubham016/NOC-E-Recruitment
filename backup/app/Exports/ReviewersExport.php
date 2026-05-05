<?php

namespace App\Exports;

use App\Models\Reviewer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class ReviewersExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected Collection $reviewers;

    public function __construct(Collection $reviewers)
    {
        $this->reviewers = $reviewers;
    }

    public function title(): string { return 'Reviewers'; }

    public function collection(): Collection
    {
        return $this->reviewers->map(function ($r, $index) {
            return [
                'sn'             => $index + 1,
                'name'           => $r->name,
                'email'          => $r->email,
                'status'         => ucfirst($r->status ?? 'active'),
                'total_assigned' => $r->application_forms_count ?? 0,
                'reviewed'       => $r->reviewed_count ?? 0,
                'pending'        => $r->pending_count ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.N.', 'Name', 'Email', 'Status',
            'Total Assigned', 'Reviewed', 'Pending Review',
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
        return ['A' => 6, 'B' => 25, 'C' => 30, 'D' => 12, 'E' => 16, 'F' => 12, 'G' => 16];
    }
}
