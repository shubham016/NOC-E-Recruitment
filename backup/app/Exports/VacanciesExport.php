<?php

namespace App\Exports;

use App\Models\JobPosting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Collection;

class VacanciesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected Collection $jobs;

    public function __construct(Collection $jobs)
    {
        $this->jobs = $jobs;
    }

    public function title(): string
    {
        return 'Vacancy List';
    }

    public function collection(): Collection
    {
        return $this->jobs->map(function ($job, $index) {
            $category = match ($job->category) {
                'internal_appraisal' => 'Internal Appraisal',
                'internal'           => 'Internal' . ($job->internal_type ? '/' . ucfirst($job->internal_type) : ''),
                'inclusive'          => 'Inclusive' . ($job->inclusive_type ? '/' . ucfirst($job->inclusive_type) : ''),
                default              => ucfirst($job->category),
            };

            return [
                'sn'                => $index + 1,
                'advertisement_no'  => $job->advertisement_no,
                'position_level'    => $job->position_level,
                'service_group'     => $job->service_group ?: $job->department,
                'category'          => $category,
                'number_of_posts'   => $job->number_of_posts,
                'qualification'     => $job->minimum_qualification,
                'applications'      => $job->applications_count ?? 0,
                'application_fee'   => $job->application_fee ? 'NPR ' . number_format($job->application_fee, 2) : '-',
                'double_dastur_fee' => $job->double_dastur_fee ? 'NPR ' . number_format($job->double_dastur_fee, 2) : '-',
                'deadline'          => $job->deadline ? $job->deadline->format('Y-m-d') : '-',
                'status'            => ucfirst($job->status),
                'posted_on'         => $job->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'S.N.',
            'Advertisement No.',
            'Position / Level',
            'Service / Group',
            'Category',
            'Demand',
            'Minimum Qualification',
            'Applications',
            'Application Fee',
            'Double Dastur Fee',
            'Deadline',
            'Status',
            'Posted On',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold + background
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'C9A84C'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 18,
            'C' => 25,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 35,
            'H' => 14,
            'I' => 16,
            'J' => 18,
            'K' => 14,
            'L' => 12,
            'M' => 14,
        ];
    }
}
