<?php

namespace App\Exports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;

class CandidatesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected Collection $candidates;

    public function __construct(Collection $candidates)
    {
        $this->candidates = $candidates;
    }

    public function title(): string { return 'Candidates'; }

    public function collection(): Collection
    {
        return $this->candidates->map(function ($c, $index) {
            return [
                'sn'                 => $index + 1,
                'name'               => trim($c->first_name . ' ' . $c->middle_name . ' ' . $c->last_name),
                'username'           => $c->username,
                'email'              => $c->email,
                'mobile'             => $c->mobile_number,
                'gender'             => $c->gender ? ucfirst($c->gender) : '-',
                'city'               => $c->city ?? '-',
                'state'              => $c->state ?? '-',
                'status'             => ucfirst($c->status ?? 'active'),
                'registered_on'      => $c->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.N.', 'Full Name', 'Username', 'Email', 'Mobile',
            'Gender', 'City', 'State', 'Status', 'Registered On',
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
            'A' => 6, 'B' => 28, 'C' => 20, 'D' => 30, 'E' => 16,
            'F' => 10, 'G' => 16, 'H' => 16, 'I' => 12, 'J' => 14,
        ];
    }
}
