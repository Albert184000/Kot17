<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <tr><th colspan="7">របាយការណ៍ហិរញ្ញវត្ថុ ខែ {{ $month }}/{{ $year }}</th></tr>
    <tr style="background: #eee;">
        <th>ថ្ងៃទី</th>
        <th>ចំណូល (រៀល)</th><th>ចំណូល ($)</th>
        <th>ចំណាយ (រៀល)</th><th>ចំណាយ ($)</th>
        <th>សមតុល្យ (រៀល)</th><th>សមតុល្យ ($)</th>
    </tr>
    @foreach($dailyReport as $row)
    <tr>
        <td>{{ $row['day'] }}</td>
        <td>{{ $row['in_khr'] }}</td><td>{{ $row['in_usd'] }}</td>
        <td>{{ $row['out_khr'] }}</td><td>{{ $row['out_usd'] }}</td>
        <td>{{ $row['bal_khr'] }}</td><td>{{ $row['bal_usd'] }}</td>
    </tr>
    @endforeach
</table>