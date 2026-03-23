<?php

use App\Models\LeaveRequest;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo nghỉ phép</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 6px;
            font-size: 20px;
        }
        .header p {
            margin: 2px 0;
        }
        .section-title {
            margin: 18px 0 8px;
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .footer {
            margin-top: 16px;
            font-size: 11px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Báo cáo nghỉ phép</h1>
        <p>OTL System</p>
        <p>Từ ngày: {{ $fromDate }} | Đến ngày: {{ $toDate }}</p>
    </div>

    <div class="section-title">Chi tiết nghỉ phép</div>
    <table>
        <thead>
            <tr>
                <th>Mã NV</th>
                <th>Tên NV</th>
                <th>Phòng Ban</th>
                <th>Loại nghỉ</th>
                <th>Từ ngày</th>
                <th>Đến ngày</th>
                <th>Số ngày</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveRequests as $row)
                <tr>
                    <td>{{ $row->employee->employee_code ?? '' }}</td>
                    <td>{{ $row->employee->name ?? '' }}</td>
                    <td>{{ $row->employee->department ?? '' }}</td>
                    <td>{{ LeaveRequest::LEAVE_TYPES[$row->leave_type] ?? $row->leave_type }}</td>
                    <td>{{ $row->from_date }}</td>
                    <td>{{ $row->to_date }}</td>
                    <td>{{ $row->days }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Tổng hợp theo nhân viên</div>
    <table>
        <thead>
            <tr>
                <th>Tên NV</th>
                <th>Phòng Ban</th>
                <th>Tổng lần nghỉ</th>
                <th>Tổng ngày nghỉ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summary as $row)
                <tr>
                    <td>{{ $row['employee']->name ?? '' }}</td>
                    <td>{{ $row['employee']->department ?? '' }}</td>
                    <td>{{ $row['total_times'] }}</td>
                    <td>{{ $row['total_days'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated at: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
