<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PlannedProjectsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Project::query()
            ->with('governmentEntity')
            ->where('status', 'planned')
            // ->whereNotNull('budget')
            // ->where('budget', '>', 0)
            ->get()
            ->map(function ($p) {
                return [
                    'name' => $p->name,
                    'entity' => $p->governmentEntity->name ?? '—',
                    'start_date' => $p->start_date ? $p->start_date->format('Y-m-d') : '—',
                    'budget' => number_format($p->budget, 2),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'اسم المشروع',
            'الجهة الحكومية',
            'تاريخ البدء',
            'الميزانية',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FFE0E0E0'], // رمادي خفيف
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ]);

        // Auto width for columns
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}
