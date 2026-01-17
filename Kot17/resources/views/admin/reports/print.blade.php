@extends('layouts.admin')

@section('content')
@php
    $rows = $rows ?? [
        [
            'no' => '9',
            'name' => 'សមតុល្យ 9',
            'key' => '9',
            'children' => [
                ['no' => '9.1', 'name' => 'ចំណូលសរុប', 'key' => '9.1'],
                ['no' => '9.2', 'name' => 'ចំណាយសរុប', 'key' => '9.2'],
                ['no' => '9.3', 'name' => 'ចំណាយកាត់ទុក', 'key' => '9.3'],
            ],
        ],
        [
            'no' => '10',
            'name' => 'សមតុល្យ 10',
            'key' => '10',
            'children' => [
                ['no' => '10.1', 'name' => 'ចំណូលសរុប', 'key' => '10.1'],
                ['no' => '10.2', 'name' => 'ចំណាយសរុប', 'key' => '10.2'],
                ['no' => '10.3', 'name' => 'ចំណាយកាត់ទុក', 'key' => '10.3'],
            ],
        ],
    ];

    // Expected values structure:
    // $values = [
    //   '9.1' => ['prev'=>['riel'=>0,'usd'=>0], 'in'=>..., 'out'=>..., 'bal'=>...],
    //   'total' => [...]
    // ];
    $values = $values ?? [];

    $fmt = function($amount, $cur){
        $n = (float) ($amount ?? 0);
        if ($cur === 'usd') return number_format($n, 2);
        return number_format($n, 0); // riel
    };

    $get = function($k, $section, $cur) use ($values, $fmt) {
        $v = data_get($values, "{$k}.{$section}.{$cur}", 0);
        return $fmt($v, $cur);
    };
@endphp

<div class="print-page mx-auto bg-white">
    {{-- TOP BAR (no print) --}}
    <div class="no-print flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-black text-slate-800">បោះពុម្ពរបាយការណ៍</h1>
            <p class="text-xs text-gray-500">{{ $label ?? '' }} — {{ $startDate ?? '' }} ដល់ {{ $endDate ?? '' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.index', request()->query()) }}"
               class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-bold hover:bg-gray-50">
               ត្រឡប់
            </a>
            <button onclick="window.print()"
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-bold hover:bg-blue-500">
                បោះពុម្ព
            </button>
        </div>
    </div>

    {{-- TITLE --}}
    <div class="text-center mb-3">
        <div class="text-lg font-black">តារាងសមតុល្យ និងចំណូលចំណាយ</div>
        <div class="text-xs text-gray-700 mt-1">
            {{ $label ?? '' }} | {{ $startDate ?? '' }} ដល់ {{ $endDate ?? '' }}
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-black border-collapse report-table">
            <thead>
                <tr>
                    <th rowspan="2" class="th w-[60px]">ល.រ</th>
                    <th rowspan="2" class="th">បរិយាយ</th>

                    {{-- ✅ Correct finance meaning --}}
                    <th colspan="2" class="th">សមតុល្យដើម</th>
                    <th colspan="2" class="th">ប្រាក់ចំណូល</th>
                    <th colspan="2" class="th">ប្រាក់ចំណាយ</th>
                    <th colspan="2" class="th">សមតុល្យចុង</th>
                </tr>
                <tr>
                    <th class="th w-[95px]">រៀល</th>
                    <th class="th w-[95px]">ដុល្លារ</th>

                    <th class="th w-[95px]">រៀល</th>
                    <th class="th w-[95px]">ដុល្លារ</th>

                    <th class="th w-[95px]">រៀល</th>
                    <th class="th w-[95px]">ដុល្លារ</th>

                    <th class="th w-[95px]">រៀល</th>
                    <th class="th w-[95px]">ដុល្លារ</th>
                </tr>
            </thead>

            <tbody>
                @foreach($rows as $g)
                    <tr class="group-row">
                        <td class="td text-center font-black">{{ $g['no'] }}</td>
                        <td class="td font-black">{{ $g['name'] }}</td>

                        {{-- ✅ prev / in / out / bal --}}
                        <td class="td text-right">{{ $get($g['key'], 'prev', 'riel') }}</td>
                        <td class="td text-right">{{ $get($g['key'], 'prev', 'usd') }}</td>

                        <td class="td text-right">{{ $get($g['key'], 'in', 'riel') }}</td>
                        <td class="td text-right">{{ $get($g['key'], 'in', 'usd') }}</td>

                        <td class="td text-right">{{ $get($g['key'], 'out', 'riel') }}</td>
                        <td class="td text-right">{{ $get($g['key'], 'out', 'usd') }}</td>

                        <td class="td text-right">{{ $get($g['key'], 'bal', 'riel') }}</td>
                        <td class="td text-right">{{ $get($g['key'], 'bal', 'usd') }}</td>
                    </tr>

                    @foreach($g['children'] as $c)
                        <tr>
                            <td class="td text-center">{{ $c['no'] }}</td>
                            <td class="td pl-6">{{ $c['name'] }}</td>

                            <td class="td text-right">{{ $get($c['key'], 'prev', 'riel') }}</td>
                            <td class="td text-right">{{ $get($c['key'], 'prev', 'usd') }}</td>

                            <td class="td text-right">{{ $get($c['key'], 'in', 'riel') }}</td>
                            <td class="td text-right">{{ $get($c['key'], 'in', 'usd') }}</td>

                            <td class="td text-right">{{ $get($c['key'], 'out', 'riel') }}</td>
                            <td class="td text-right">{{ $get($c['key'], 'out', 'usd') }}</td>

                            <td class="td text-right">{{ $get($c['key'], 'bal', 'riel') }}</td>
                            <td class="td text-right">{{ $get($c['key'], 'bal', 'usd') }}</td>
                        </tr>
                    @endforeach
                @endforeach

                <tr class="total-row">
                    <td class="td font-black text-center" colspan="2">សរុប</td>

                    <td class="td text-right font-black">{{ $get('total', 'prev', 'riel') }}</td>
                    <td class="td text-right font-black">{{ $get('total', 'prev', 'usd') }}</td>

                    <td class="td text-right font-black">{{ $get('total', 'in', 'riel') }}</td>
                    <td class="td text-right font-black">{{ $get('total', 'in', 'usd') }}</td>

                    <td class="td text-right font-black">{{ $get('total', 'out', 'riel') }}</td>
                    <td class="td text-right font-black">{{ $get('total', 'out', 'usd') }}</td>

                    <td class="td text-right font-black">{{ $get('total', 'bal', 'riel') }}</td>
                    <td class="td text-right font-black">{{ $get('total', 'bal', 'usd') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ✅ signatures (optional, pro) --}}
    <div class="grid grid-cols-3 gap-6 mt-6 text-center text-xs">
        <div>
            <div class="font-bold mb-10">រៀបចំដោយ</div>
            <div class="border-t border-gray-400 pt-1">{{ $preparedBy ?? '..........................' }}</div>
        </div>
        <div>
            <div class="font-bold mb-10">ត្រួតពិនិត្យដោយ</div>
            <div class="border-t border-gray-400 pt-1">{{ $reviewedBy ?? '..........................' }}</div>
        </div>
        <div>
            <div class="font-bold mb-10">អនុម័តដោយ</div>
            <div class="border-t border-gray-400 pt-1">{{ $approvedBy ?? '..........................' }}</div>
        </div>
    </div>
</div>

<style>
.print-page{ max-width: 1100px; padding: 16px; }
.report-table .th{
    border:1px solid #000;
    padding:8px 10px;
    font-size:12px;
    font-weight:800;
    text-align:center;
    vertical-align:middle;
    background:#fff;
    white-space:nowrap;
}
.report-table .td{
    border:1px solid #000;
    padding:8px 10px;
    font-size:12px;
    vertical-align:middle;
}
.group-row td{ font-weight:900; background:#f7f7f7; }
.total-row td{ font-weight:900; background:#f2f2f2; }

@media print{
    @page{ size:A4 landscape; margin:10mm; }
    .no-print, nav, aside, header{ display:none !important; }
    body{ background:#fff !important; }
    .print-page{ max-width:100%; padding:0; }
    .group-row td{ background:#f7f7f7 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .total-row td{ background:#f2f2f2 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endsection
