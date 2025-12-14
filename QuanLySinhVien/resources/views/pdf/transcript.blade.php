<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điểm Sinh Viên</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        
        .info-value {
            flex: 1;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        table td.text-left {
            text-align: left;
        }
        
        .summary {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        
        .summary-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .summary-label {
            font-weight: bold;
            width: 250px;
        }
        
        .summary-value {
            flex: 1;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .signature-note {
            font-style: italic;
            font-size: 11px;
            margin-bottom: 60px;
        }
        
        .signature-name {
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
        
        .passed {
            color: #10b981;
            font-weight: bold;
        }
        
        .failed {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Trường Đại học ABC</h1>
        <h2>Bảng Điểm Sinh Viên</h2>
        <p>Năm học: {{ date('Y') }} - {{ date('Y') + 1 }}</p>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Họ và tên:</span>
            <span class="info-value">{{ $student->full_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mã sinh viên:</span>
            <span class="info-value">{{ $student->student_code }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Ngày sinh:</span>
            <span class="info-value">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Lớp:</span>
            <span class="info-value">{{ $student->class->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Khoa:</span>
            <span class="info-value">{{ $student->class->department->name ?? 'N/A' }}</span>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">STT</th>
                <th style="width: 100px;">Mã môn</th>
                <th style="width: 200px;">Tên môn học</th>
                <th style="width: 60px;">Chuyên cần</th>
                <th style="width: 60px;">Giữa kỳ</th>
                <th style="width: 60px;">Cuối kỳ</th>
                <th style="width: 60px;">Tổng điểm</th>
                <th style="width: 80px;">Kết quả</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSum = 0;
                $subjectCount = 0;
                $passedCount = 0;
                $failedCount = 0;
            @endphp
            
            @forelse ($scores as $index => $score)
                @php
                    $totalSum += $score->total;
                    $subjectCount++;
                    if ($score->total >= 5) {
                        $passedCount++;
                    } else {
                        $failedCount++;
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $score->subject->code ?? 'N/A' }}</td>
                    <td class="text-left">{{ $score->subject->name ?? 'N/A' }}</td>
                    <td>{{ number_format($score->cc, 1) }}</td>
                    <td>{{ number_format($score->gk, 1) }}</td>
                    <td>{{ number_format($score->ck, 1) }}</td>
                    <td>{{ number_format($score->total, 1) }}</td>
                    <td class="{{ $score->total >= 5 ? 'passed' : 'failed' }}">
                        {{ $score->total >= 5 ? 'Đạt' : 'Trượt' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Chưa có điểm</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @if ($subjectCount > 0)
        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Tổng số môn học:</span>
                <span class="summary-value">{{ $subjectCount }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Số môn đạt:</span>
                <span class="summary-value passed">{{ $passedCount }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Số môn trượt:</span>
                <span class="summary-value failed">{{ $failedCount }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Điểm trung bình:</span>
                <span class="summary-value">{{ number_format($totalSum / $subjectCount, 2) }}</span>
            </div>
        </div>
    @endif
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-title">Sinh viên</div>
            <div class="signature-note">(Ký và ghi rõ họ tên)</div>
            <div class="signature-name">{{ $student->full_name }}</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">Phòng Đào tạo</div>
            <div class="signature-note">(Ký và đóng dấu)</div>
            <div class="signature-name">&nbsp;</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Ngày in: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Hệ thống Quản lý Sinh viên - Trường Đại học ABC</p>
    </div>
</body>
</html>
