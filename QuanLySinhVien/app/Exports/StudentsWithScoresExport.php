<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StudentsWithScoresExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $students = Student::with(['classRelation', 'scores.subject'])
            ->orderBy('student_id')
            ->get();
        
        $data = collect();
        
        foreach ($students as $student) {
            if ($student->scores->isEmpty()) {
                // Nếu sinh viên chưa có điểm, vẫn hiển thị thông tin sinh viên
                $data->push([
                    'student_id' => $student->student_id,
                    'full_name' => $student->full_name,
                    'birth_date' => $student->birth_date ? $student->birth_date->format('d/m/Y') : '',
                    'class_id' => $student->classRelation->class_id ?? '',
                    'subject_id' => '',
                    'subject_name' => '',
                    'cc' => '',
                    'gk' => '',
                    'ck' => '',
                    'total' => '',
                ]);
            } else {
                // Tạo một dòng cho mỗi điểm số
                foreach ($student->scores as $score) {
                    $data->push([
                        'student_id' => $student->student_id,
                        'full_name' => $student->full_name,
                        'birth_date' => $student->birth_date ? $student->birth_date->format('d/m/Y') : '',
                        'class_id' => $student->classRelation->class_id ?? '',
                        'subject_id' => $score->subject->subject_id ?? '',
                        'subject_name' => $score->subject->name ?? '',
                        'cc' => $score->cc ?? '',
                        'gk' => $score->gk ?? '',
                        'ck' => $score->ck ?? '',
                        'total' => $score->total ?? '',
                    ]);
                }
            }
        }
        
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Mã sinh viên',
            'Họ và tên',
            'Ngày sinh',
            'Lớp',
            'Mã môn học',
            'Tên môn học',
            'CC',
            'GK',
            'CK',
            'Tổng kết',
        ];
    }

    /**
     * @param array $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['student_id'],
            $row['full_name'],
            $row['birth_date'],
            $row['class_id'],
            $row['subject_id'],
            $row['subject_name'],
            $row['cc'],
            $row['gk'],
            $row['ck'],
            $row['total'],
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Danh sách sinh viên kèm điểm';
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Mã sinh viên
            'B' => 25, // Họ và tên
            'C' => 12, // Ngày sinh
            'D' => 15, // Lớp
            'E' => 15, // Mã môn học
            'F' => 30, // Tên môn học
            'G' => 10, // CC
            'H' => 10, // GK
            'I' => 10, // CK
            'J' => 12, // Tổng kết
        ];
    }
}
