<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo tăng ca</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 2px; }
        .company { text-align: center; font-size: 13px; margin-bottom: 2px; }
        .meta { text-align: center; font-size: 11px; color: #555; margin-bottom: 16px; }
        h2 { font-size: 13px; margin-top: 20px; margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background-color: #4a5568; color: #fff; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) td { background-color: #f7fafc; }
    </style>
</head>
<body>
    <h1>Báo cáo tăng ca</h1>
    <div class="company">OTL System</div>
    <div class="meta">
        Từ ngày: {{ $fromDate }} &mdash; Đến ngày: {{ $toDate }}<br>
        Xuất lúc: {{ now()->format('d/m/Y H:i') }}
    </div>

    <h2>Chi tiết tăng ca</h2>
    <table>
        <thead>
            <tr>
                <th>Mã NV</th>
                <th>Tên NV</th>
                <th>Phòng Ban</th>
                <th>Ngày OT</th>
                <th>Giờ Duyệt</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($otRequests as $row)
            <tr>
                <td>{{ $row->employee->employee_code ?? '' }}</td>
                <td>{{ $row->employee->name ?? '' }}</td>
                <td>{{ $row->employee->department ?? '' }}</td>
                <td>{{ $row->ot_date }}</td>
                <td>{{ $row->approved_hours }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;">Không có dữ liệu</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Tổng hợp</h2>
    <table>
        <thead>
            <tr>
                <th>Tên NV</th>
                <th>Tổng ngày</th>
                <th>Tổng giờ</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($summary as $item)
            <tr>
                <td>{{ $item->name ?? '' }}</td>
                <td>{{ $item->total_days ?? 0 }}</td>
                <td>{{ $item->total_hours ?? 0 }}</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;">Không có dữ liệu</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
