<table>
    <thead>
        <tr>
            <th>اسم المشروع</th>
            <th>الجهة الحكومية</th>
            <th>تاريخ البدء</th>
            <th>الميزانية</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->governmentEntity->name ?? '—' }}</td>
                <td>{{ $p->start_date ? $p->start_date->format('Y-m-d') : '—' }}</td>
                <td>{{ number_format($p->budget, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
