<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use App\Models\UtilityBill;
use App\Models\UtilityRoomReading;
use App\Exports\UtilityBillExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UtilitiesController extends Controller
{
    public function index(Request $request)
    {
        $type  = $request->get('type', 'electricity');
        $month = $request->get('month', now()->format('Y-m'));

        abort_unless(in_array($type, ['water','electricity']), 404);

        $bill = UtilityBill::with('readings')
            ->where('type', $type)
            ->where('month', $month)
            ->first();

        return view('admin.utilities.bill', compact('type','month','bill'));
    }

    public function show(UtilityBill $bill)
    {
        $bill->load('readings');
        $type  = $bill->type;
        $month = $bill->month;

        return view('admin.utilities.bill', compact('type','month','bill'));
    }

    public function store(Request $request)
    {
        return $this->saveBill($request, null);
    }

    public function update(Request $request, UtilityBill $bill)
    {
        return $this->saveBill($request, $bill);
    }

    public function export(UtilityBill $bill)
    {
        return Excel::download(
            new UtilityBillExport($bill), 
            "utility-{$bill->type}-{$bill->month}.xlsx"
        );
    }

    public function print(UtilityBill $bill)
    {
        $bill->load('readings');
        return view('admin.utilities.print', compact('bill'));
    }

    private function saveBill(Request $request, ?UtilityBill $existing)
    {
        $data = $request->validate([
            'type'            => ['required','in:water,electricity'],
            'month'           => ['required','date_format:Y-m'],
            'price_per_unit'  => ['required','numeric','min:0'],
            'common_units'    => ['nullable','numeric','min:0'],
            'donation_amount' => ['nullable','numeric','min:0'],
            'common_mode'     => ['required','in:usage,equal'],
            'note'            => ['nullable','string','max:255'],
            'rows'                    => ['required','array','min:1'],
            'rows.*.room_id'          => ['nullable','integer','min:0'],
            'rows.*.room_name'        => ['required','string','max:255'],
            'rows.*.people_count'     => ['nullable','integer','min:1','max:10'],
            'rows.*.meter_no'         => ['nullable','string','max:255'],
            'rows.*.old_reading'      => ['nullable','numeric','min:0'],
            'rows.*.new_reading'      => ['nullable','numeric','min:0'],
            'rows.*.paid_amount'      => ['nullable','numeric','min:0'],
            'rows.*.status'           => ['nullable','string','max:30'],
            'rows.*.note'             => ['nullable','string','max:255'],
        ]);

        $type = $data['type'];
        $month = $data['month'];
        $ppu = (float)$data['price_per_unit'];
        $commonUnits = (float)($data['common_units'] ?? 0);
        $donation = (float)($data['donation_amount'] ?? 0);
        $mode = $data['common_mode'];

        DB::transaction(function () use ($existing, $type, $month, $ppu, $commonUnits, $donation, $mode, $data) {
            if ($existing) {
                $bill = $existing;
            } else {
                $bill = UtilityBill::firstOrNew(['type' => $type, 'month' => $month]);
            }

            $bill->fill([
                'type'           => $type,
                'month'          => $month,
                'price_per_unit' => $ppu,
                'common_units'   => $commonUnits,
                'donation_amount'=> $donation,
                'common_mode'    => $mode,
                'note'           => $data['note'] ?? null,
            ]);
            $bill->save();

            $bill->readings()->delete();

            $rows = collect($data['rows'])->map(function ($r) {
                $old = (float)($r['old_reading'] ?? 0);
                $new = (float)($r['new_reading'] ?? 0);
                $usage = ($new >= $old) ? ($new - $old) : 0;
                $peopleCount = max(1, (int)($r['people_count'] ?? 1));

                return [
                    'room_id'      => isset($r['room_id']) ? (int)$r['room_id'] : null,
                    'room_name'    => trim($r['room_name']),
                    'people_count' => $peopleCount,
                    'meter_no'     => trim($r['meter_no'] ?? ''),
                    'old_reading'  => $old,
                    'new_reading'  => $new,
                    'usage_units'  => round($usage, 2),
                    'paid_amount'  => (float)($r['paid_amount'] ?? 0),
                    'status'       => trim($r['status'] ?? 'ok'),
                    'note'         => trim($r['note'] ?? ''),
                ];
            });

            $sumUsage = (float)$rows->sum('usage_units');
            $count = max(1, $rows->count());

            $rows = $rows->map(function ($r) use ($sumUsage, $commonUnits, $mode, $count) {
                $share = 0;
                if ($commonUnits > 0) {
                    if ($mode === 'equal' || $sumUsage <= 0) {
                        $share = $commonUnits / $count;
                    } else {
                        $share = $commonUnits * ($r['usage_units'] / $sumUsage);
                    }
                }
                $r['common_share_units'] = round($share, 2);
                $r['total_units'] = round($r['usage_units'] + $r['common_share_units'], 2);
                return $r;
            });

            $rows = $rows->map(function ($r) use ($ppu) {
                $r['amount_before_donation'] = round($r['total_units'] * $ppu, 0);
                return $r;
            });

            $sumBefore = (float)$rows->sum('amount_before_donation');
            $donateClamp = min(max(0, (float)$donation), $sumBefore);

            $rows = $rows->map(function ($r) use ($sumBefore, $donateClamp) {
                $ratio = ($sumBefore > 0) ? ($r['amount_before_donation'] / $sumBefore) : 0;
                $donShare = $donateClamp * $ratio;
                $final = max(0, $r['amount_before_donation'] - $donShare);

                $r['donation_share'] = round($donShare, 0);
                $r['amount_final'] = round($final, 0);
                $r['amount_per_person'] = $r['people_count'] > 0 
                    ? round($r['amount_final'] / $r['people_count'], 0) 
                    : $r['amount_final'];

                $paid = max(0, (float)$r['paid_amount']);
                $bal  = max(0, (float)$r['amount_final'] - $paid);
                $r['balance_amount'] = round($bal, 0);
                return $r;
            });

            foreach ($rows as $r) {
                UtilityRoomReading::create([
                    'utility_bill_id'         => $bill->id,
                    'room_id'                 => $r['room_id'],
                    'room_name'               => $r['room_name'],
                    'people_count'            => $r['people_count'],
                    'meter_no'                => $r['meter_no'] ?: null,
                    'old_reading'             => $r['old_reading'],
                    'new_reading'             => $r['new_reading'],
                    'usage_units'             => $r['usage_units'],
                    'common_share_units'      => $r['common_share_units'],
                    'total_units'             => $r['total_units'],
                    'amount_before_donation'  => $r['amount_before_donation'],
                    'donation_share'          => $r['donation_share'],
                    'amount_final'            => $r['amount_final'],
                    'amount_per_person'       => $r['amount_per_person'],
                    'paid_amount'             => $r['paid_amount'],
                    'balance_amount'          => $r['balance_amount'],
                    'status'                  => $r['status'] ?: 'ok',
                    'note'                    => $r['note'] ?: null,
                ]);
            }

            $bill->sum_usage_units            = round($sumUsage, 2);
            $bill->sum_units_with_common      = round($sumUsage + $commonUnits, 2);
            $bill->sum_amount_before_donation = round($sumBefore, 0);
            $bill->sum_amount_final           = round($sumBefore - $donateClamp, 0);
            $bill->save();
        });

        return redirect()->route('admin.utilities.index', [
            'type'  => $type,
            'month' => $month,
        ])->with('success', 'Saved successfully');
    }
}
