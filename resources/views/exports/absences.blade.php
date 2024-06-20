@php
    use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
    $days = range(1, $date->daysInMonth);
@endphp
<p>
    Ведомость пропусков группы {{ $course->groupName }} за {{ $date->monthName }} {{ $date->year }}
</p>
<table>
    <thead>
        <tr>
            <th rowspan="3" style="vertical-align: middle;text-align: center;font-weight:bold;word-break:break-all;border: 1px solid black">№<br>п/п</th>
            <th rowspan="3" style="vertical-align: middle;text-align: center;font-weight:bold;border: 1px solid black">Ф.И.О. учащегося</th>
            <th colspan="{{ $date->daysInMonth * 2 }}" style="text-align: center;font-weight:bold;border: 1px solid black">Дата</th>
            <th colspan="2" style="text-align: center;font-weight:bold;border: 1px solid black">Из них</th>
        </tr>
        <tr>
            @for($i = 1; $i <= $date->daysInMonth; $i++)
                <th colspan="2" style="text-align: center;font-weight:bold;border: 1px solid black">{{ $i }}</th>
            @endfor
            <th rowspan="2" style="text-align: center;font-weight:bold;border: 1px solid black">Уваж</th>
            <th rowspan="2" style="text-align: center;font-weight:bold;border: 1px solid black">Неуваж</th>
        </tr>
        <tr>
            @for($i = 1; $i <= $date->daysInMonth; $i++)
                <th style="text-align: center;font-weight:bold;border: 1px solid black">у</th>
                <th style="text-align: center;font-weight:bold;border: 1px solid black">н</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($students as $index => $student)
            <tr>
                <td style="border: 1px solid black">{{ $index + 1 }}</td>
                <td style="border: 1px solid black">{{ $student->initials }}</td>
                @for($i = 1; $i <= $date->daysInMonth; $i++)
                    @php
                        $copiedDate = $date->copy();
                        $copiedDate->day = $i;
                        $absence = $absences->where('student_id', $student->id)->where('date', $copiedDate->format('Y-m-d'))->first();
                    @endphp
                    <td style="text-align: center;border: 1px solid black">{{ $absence?->reasonable_count ?: '' }}</td>
                    <td style="text-align: center;border: 1px solid black">{{ $absence?->unreasonable_count ?: '' }}</td>
                @endfor
                <td style="text-align: center;border: 1px solid black" data-format="0;-0;;@">=SUM({{implode(', ', array_map(fn($day) => Coordinate::stringFromColumnIndex($day * 2 + 1) . $index + 5, $days))}})</td>
                <td style="text-align: center;border: 1px solid black" data-format="0;-0;;@">=SUM({{implode(', ', array_map(fn($day) => Coordinate::stringFromColumnIndex($day * 2 + 2) . $index + 5, $days))}})</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="{{ $date->daysInMonth * 2 + 2 }}" style="text-align: center;font-weight:bold;border: 1px solid black">Итого</th>
            <th style="text-align: center;border: 1px solid black" data-format="0;-0;;@">=SUM({{ Coordinate::stringFromColumnIndex($date->daysInMonth * 2 + 3) . 5 . ':' . Coordinate::stringFromColumnIndex($date->daysInMonth * 2 + 3) . $students->count() + 4 }})</th>
            <th style="text-align: center;border: 1px solid black" data-format="0;-0;;@">=SUM({{ Coordinate::stringFromColumnIndex($date->daysInMonth * 2 + 4) . 5 . ':' . Coordinate::stringFromColumnIndex($date->daysInMonth * 2 + 4) . $students->count() + 4 }})</th>
        </tr>
    </tbody>
</table>
