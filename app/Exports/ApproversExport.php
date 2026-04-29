<?php

namespace App\Exports;

use App\Models\Approver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class ApproversExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected Collection $approvers;

    public function __construct(Collection $approvers)
    {
        $this->approvers = $approvers;
    }

    public function title(): string { return 'Approvers'; }

    public function collection(): Collection
    {
        return $this->approvers->map(function ($a, $index) {
            return [
                'sn'       => $index + 1,
                'name'     => $a->name,
                'email'    => $a->email,
                'status'   => ucfirst($a->status ?? 'active'),
                'approved' => $a->approved_count ?? 0,
                'rejected' => $a->rejected_count ?? 0,
                'total'    => ($a->approved_count ?? 0) + ($a->rejected_count ?? 0),
            ];
        });
    }

    public function headings(): array
    {
        return ['S.N.', 'Name', 'Email', 'Status', 'Approved', 'Rejected', 'Total Actioned'];
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
        return ['A' => 6, 'B' => 25, 'C' => 30, 'D' => 12, 'E' => 12, 'F' => 12, 'G' => 16];
    }
}
