<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير النشاطات التفصيلي</title>

    {{-- ✅ تحميل خط Cairo من Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', DejaVu Sans, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 13px;
            background-color: #fff;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 20px;
            font-size: 22px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 6px;
        }

        th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: center;
        }

        td {
            text-align: center;
            vertical-align: middle;
            line-height: 1.6;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        /* ✅ تحسين النص العربي */
        * {
            unicode-bidi: plaintext;
        }
    </style>
</head>

<body>

    <h1>📄 سجل النشاطات التفصيلي</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>المستخدم</th>
                <th>العملية</th>
                <th>الكيان</th>
                <th>الوصف</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                @php
                    $actions = [
                        'created' => 'إضافة',
                        'updated' => 'تعديل',
                        'deleted' => 'حذف',
                    ];
                    $models = [
                        'government_entities' => 'الجهات الحكومية',
                        'perspectives' => 'المناظير',
                        'pillars' => 'المحاور',
                        'projects' => 'المشاريع',
                        'standards' => 'المعايير',
                    ];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->user?->name ?? 'غير معروف' }}</td>
                    <td>{{ $actions[$log->action] ?? 'غير معروف' }}</td>
                    <td>{{ $models[$log->model] ?? $log->model }}</td>
                    <td style="text-align:right;">{{ $log->description }}</td>
                    <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">لا توجد نشاطات خلال هذه الفترة.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        تم إنشاء هذا التقرير بواسطة نظام تحليل الأداء — © {{ date('Y') }}
    </div>

</body>
</html>
