@php
    // Financial logic
    $monthNetKHR = $totalDonationsKHR - $totalExpensesKHR;
    $monthNetUSD = $totalDonationsUSD - $totalExpensesUSD;
    
    $d = (int)date('j'); 
    $m = (int)date('n'); 
    $y = (int)date('Y');

    if (!function_exists('toKhmerNum')) {
        function toKhmerNum($num) { 
            return str_replace(['0','1','2','3','4','5','6','7','8','9'], ['០','១','២','៣','៤','៥','៦','៧','៨','៩'], $num); 
        }
    }

    if (!function_exists('khmerAmountWords')) {
        function khmerAmountWords($number) {
            $khWords = ['', 'មួយ', 'ពីរ', 'បី', 'បួន', 'ប្រាំ', 'ប្រាំមួយ', 'ប្រាំពីរ', 'ប្រាំបី', 'ប្រាំបួន'];
            $khUnits = ['', 'ដប់', 'រយ', 'ពាន់', 'ម៉ឺន', 'សែន', 'លាន'];
            $number = (int)abs($number); 
            if ($number == 0) return 'សូន្យ';
            $str = (string)$number;
            $len = strlen($str);
            $res = '';
            for ($i = 0; $i < $len; $i++) {
                $digit = $str[$i];
                $pos = $len - $i - 1;
                if ($digit != 0) {
                    if ($pos == 1 && $digit == 1) { $res .= 'ដប់'; } 
                    else { $res .= $khWords[$digit] . $khUnits[$pos]; }
                }
            }
            return $res;
        }
    }

    $solarMonths = [1=>'មករា', 2=>'កុម្ភៈ', 3=>'មីនា', 4=>'មេសា', 5=>'ឧសភា', 6=>'មិថុនា', 7=>'កក្កដា', 8=>'សីហា', 9=>'កញ្ញា', 10=>'តុលា', 11=>'វិច្ឆិកា', 12=>'ធ្នូ'];
    $khmerDays = ['Sunday'=>'អាទិត្យ', 'Monday'=>'ច័ន្ទ', 'Tuesday'=>'អង្គារ', 'Wednesday'=>'ពុធ', 'Thursday'=>'ព្រហស្បតិ៍', 'Friday'=>'សុក្រ', 'Saturday'=>'សៅរ៍'];

    // Lunar logic
    $refDate = strtotime('2026-01-16');
    $currDate = strtotime(date('Y-m-d'));
    $diff = round(($currDate - $refDate) / 86400);
    $lunarDayCycle = (28 + $diff) % 30; 
    if ($lunarDayCycle <= 0) $lunarDayCycle += 30;
    $lunarStatus = ($lunarDayCycle <= 15) ? toKhmerNum($lunarDayCycle) . " កើត" : toKhmerNum($lunarDayCycle - 15) . " រោច";
    $lunarMonths = [1=>'មិគសិរ', 2=>'បុស្ស', 3=>'មាឃ', 4=>'ផល្គុន', 5=>'ចេត្រ', 6=>'ពិសាខ', 7=>'ជេស្ឋ', 8=>'អាសាឍ', 9=>'ស្រាពណ៍', 10=>'ភទ្របទ', 11=>'អស្សុជ', 12=>'កក្ដិក'];
    $currentLunarMonth = ($m == 1 && $lunarDayCycle > 3) ? $lunarMonths[2] : $lunarMonths[$m];
    $khZodiac = ($m < 4 || ($m == 4 && $d < 14)) ? "ម្សាញ់" : "មមី"; 
    $saks = [1=>'ឯកស័ក', 2=>'ទោស័ក', 3=>'ត្រីស័ក', 4=>'ចត្វាស័ក', 5=>'បញ្ចស័ក', 6=>'ឆស័ក', 7=>'សប្តស័ក', 8=>'អដ្ឋស័ក', 9=>'នព្វស័ក', 10=>'សំរឹទ្ធិស័ក'];
    $khSak = ($m < 4 || ($m == 4 && $d < 14)) ? $saks[7] : $saks[8];
    $beYear = ($m < 5 || ($m == 5 && $d < 12)) ? 2569 : 2570;

    $lunarFull = "ថ្ងៃ" . $khmerDays[date('l')] . " " . $lunarStatus . " ខែ" . $currentLunarMonth . " ឆ្នាំ" . $khZodiac . " " . $khSak . " ព.ស. " . toKhmerNum($beYear);
    $solarFull = "រាជធានីភ្នំពេញ, ថ្ងៃទី " . toKhmerNum($d) . " ខែ " . $solarMonths[$m] . " ឆ្នាំ " . toKhmerNum($y);
@endphp

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Khmer&family=Moul&family=Battambang&display=swap');
        body { background-color: #525659; margin: 0; font-family: 'Battambang', cursive; }
        .toolbar { background: #333; padding: 10px; display: flex; justify-content: center; position: sticky; top: 0; z-index: 1000; }
        .btn { padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; background: #007bff; font-family: 'Battambang'; }
        .container { display: flex; justify-content: center; padding: 20px; }
        .paper { background: white; width: 210mm; min-height: 297mm; padding: 1.5cm 2cm; box-shadow: 0 0 20px rgba(0,0,0,0.4); }
        .muol { font-family: 'Moul', serif; }
        .official-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .header-left { width: 33%; text-align: center; }
        .header-center { width: 34%; text-align: center; }
        .report-logo { width: 85px; margin-bottom: 5px; }
        .title-block { text-align: center; margin: 20px 0 30px 0; }
        .text-body { text-align: justify; line-height: 1.8; font-size: 16px; }
        .indent { text-indent: 50px; }
        .data-section { margin: 15px 0 20px 40px; }
        
        .signature-container { margin-top: 40px; display: flex; justify-content: space-between; align-items: flex-start; }
        .sig-box { width: 45%; text-align: center; }
        .sig-date { font-size: 16px; margin-bottom: 8px; }
        .sig-leader { margin-top: 40px; } /* រុញមេកុដិឱ្យចុះក្រោម */

        @media print {
            .toolbar { display: none !important; }
            .paper { box-shadow: none; margin: 0; }
            body { background: white; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button class="btn" onclick="window.print()">🖨️ បោះពុម្ពរបាយការណ៍ (Print A4)</button>
</div>

<div class="container">
    <div class="paper">
        <div class="official-header">
            <div class="header-left">
                <img src="{{ asset('assets/images/logo_kot17.png') }}" alt="Logo" class="report-logo">
                <div class="muol" style="font-size: 13px;">ហិរញ្ញិកកុដិលេខ ១៧</div>
                <div style="font-size: 12px;">លេខ: ....................</div>
            </div>
            <div class="header-center">
                <div class="muol" style="font-size: 14px;">ព្រះរាជាណាចក្រកម្ពុជា</div>
                <div class="muol" style="font-size: 14px;">ជាតិ សាសនា ព្រះមហាក្សត្រ</div>
                <div style="letter-spacing: 2px;">-------</div>
            </div>
            <div style="width: 33%;"></div>
        </div>

        <div class="title-block">
            <div class="muol" style="font-size: 22px;">របាយការណ៍ខែ {{ $solarMonths[$m] }} ឆ្នាំ {{ toKhmerNum($y) }}</div>
        </div>

        <div class="text-body">
            <p><strong>កម្មវត្ថុ៖</strong> ស្តីពីរបាយការណ៍ហិរញ្ញវត្ថុ ប្រាក់ចំណូល និងប្រាក់ចំណាយក្នុងខែ។</p>
            <p class="indent">សេចក្តីដូចបានចែងក្នុងកម្មវត្ថុខាងលើ ខ្ញុំព្រះករុណាមានកិត្តិយសសូមប្រគេនព្រះមេកុដិ គណៈកម្មការ និងសមាជិកទាំងអស់ជ្រាបថា៖ ស្ថានភាពហិរញ្ញវត្ថុក្នុងខែនេះមានដូចខាងក្រោម៖</p>

            <div class="data-section">
    <div style="margin-bottom: 15px;">
        <span class="muol" style="font-size: 17px;">ក/ ប្រាក់ចំណូល (Incomes)</span>
        <ul style="list-style-type: disc; padding-left: 35px; margin-top: 5px;">
            <li>ប្រាក់រៀលសរុប៖ <strong>{{ toKhmerNum(number_format($totalDonationsKHR)) }}</strong> រៀល។</li>
            <li>ប្រាក់ដុល្លារសរុប៖ <strong>{{ toKhmerNum(number_format($totalDonationsUSD, 2)) }}</strong> ដុល្លារ។</li>
        </ul>
    </div>

    <div style="margin-bottom: 15px;">
        <span class="muol" style="font-size: 17px;">ខ/ ប្រាក់ចំណាយ (Expenses)</span>
        <ul style="list-style-type: disc; padding-left: 35px; margin-top: 5px;">
            <li>ប្រាក់រៀលសរុប៖ <strong>{{ toKhmerNum(number_format($totalExpensesKHR)) }}</strong> រៀល។</li>
            <li>ប្រាក់ដុល្លារសរុប៖ <strong>{{ toKhmerNum(number_format($totalExpensesUSD, 2)) }}</strong> ដុល្លារ។</li>
        </ul>
    </div>

    <div style="margin-bottom: 15px;">
        <span class="muol" style="font-size: 17px;">គ/ សមតុល្យ៖ ប្រាក់ចំណូល ដកប្រាក់ចំណាយ</span>
        <ul style="list-style-type: disc; padding-left: 35px; margin-top: 10px;">
            <li>ប្រាក់រៀល៖ {{ toKhmerNum(number_format($totalDonationsKHR)) }} - {{ toKhmerNum(number_format($totalExpensesKHR)) }} = 
                <span style="color: {{ $monthNetKHR >= 0 ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $monthNetKHR < 0 ? '-' : '' }}{{ toKhmerNum(number_format(abs($monthNetKHR))) }} ({{ khmerAmountWords($monthNetKHR) }}រៀលគត់)
                </span> រៀល។
            </li>
            <li>ប្រាក់ដុល្លារ៖ {{ toKhmerNum(number_format($totalDonationsUSD, 2)) }} - {{ toKhmerNum(number_format($totalExpensesUSD, 2)) }} = 
                <span style="color: {{ $monthNetUSD >= 0 ? 'green' : 'red' }}; font-weight: bold;">
                    {{ $monthNetUSD < 0 ? '-' : '' }}{{ toKhmerNum(number_format(abs($monthNetUSD), 2)) }} ({{ khmerAmountWords($monthNetUSD) }})
                </span> ដុល្លារ។
            </li>
        </ul>

        @if($monthNetKHR < 0 || $monthNetUSD < 0)
            <div style="margin-top: 10px; padding-left: 15px; font-weight: bold;">
                ដូច្នេះ ប្រាក់ចំណាយខែនេះមានលើសចំនួន៖ 
                <span style="color: red;">
                    {{ $monthNetKHR < 0 ? toKhmerNum(number_format(abs($monthNetKHR))) . ' រៀល' : '' }}
                    {{ $monthNetKHR < 0 && $monthNetUSD < 0 ? ' និង ' : '' }}
                    {{ $monthNetUSD < 0 ? toKhmerNum(number_format(abs($monthNetUSD), 2)) . ' ដុល្លារ' : '' }}
                </span>
            </div>
        @endif
    </div>
</div>

            <p class="indent" style="margin-top: 25px;">សេចក្តីដូចបានចែងខាងលើនេះ សូមប្រគេនព្រះមេកុដិ និងសមាជិកទាំងអស់មេត្តាជ្រាប។ សូមអរគុណ!</p>
        </div>

        <div class="signature-container">
            <div class="sig-box sig-leader">
                <div class="sig-date">{{ $lunarFull }}<br>{{ $solarFull }}</div>
                <div class="muol" style="font-size: 14px;">បានពិនិត្យ និងឯកភាព</div>
                <div class="muol" style="font-size: 14px;">ព្រះមេកុដិ</div>
                <div style="height: 60px;"></div>
            </div>
            <div class="sig-box">
                <div class="sig-date">{{ $lunarFull }}<br>{{ $solarFull }}</div>
                <div class="muol" style="font-size: 14px;">ហិរញ្ញិក</div>
                <div style="height: 60px;"></div>
            </div>
        </div>
    </div>
</div>

</body>
</html>