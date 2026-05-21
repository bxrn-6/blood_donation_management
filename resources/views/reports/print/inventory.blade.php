<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report - Print</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1 { font-size: 18px; color: #2980b9; margin-bottom: 4px; }
        p.subtitle { color: #666; margin-bottom: 16px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #2980b9; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background: #fafafa; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <h1>🧪 Blood Inventory Report</h1>
    <p class="subtitle">Generated: {{ now()->format('F d, Y h:i A') }}</p>

    <button onclick="window.print()" style="margin-bottom:12px;padding:6px 14px;background:#2980b9;color:#fff;border:none;border-radius:4px;cursor:pointer;">Print / Save PDF</button>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Blood Type</th>
                <th>Quantity (ml)</th>
                <th>Status</th>
                <th>Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->blood_type }}</td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:20px;color:#999;">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
