<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $todayTotalUSD = Donation::whereDate('created_at', today())->where('currency', 'USD')->sum('amount');
        $todayTotalKHR = Donation::whereDate('created_at', today())->where('currency', 'KHR')->sum('amount');
        $recentCollections = Donation::orderBy('created_at', 'desc')->limit(5)->get();

        // ✅ ទាញយកសមាជិកពិតប្រាកដពី Database
        $realMembers = User::where('role', 'MEMBER')->get(); 

        return view('collector.dashboard', compact(
            'todayTotalUSD', 
            'todayTotalKHR', 
            'recentCollections', 
            'realMembers'
        ));
    }

    public function sendDailySummary(Request $request)
    {
        $amountPerPerson = (float) $request->input('per_person_amount', 0);
        $currency = $request->input('per_person_currency', 'USD');
        $description = $request->input('final_description', 'ចង្ហាន់ថ្ងៃត្រង់');
        
        $attendance = $request->input('attendance', []);
        $presentList = [];
        $absentList = [];

        foreach ($attendance as $name => $status) {
            if ($status == 'present') {
                $presentList[] = "✅ " . $name;
            } else {
                $absentList[] = "❌ " . $name;
            }
        }

        $totalPresent = count($presentList);
        $totalCollected = $totalPresent * $amountPerPerson;

        $symbol = ($currency == 'USD') ? '$' : ' ៛';
        $format = ($currency == 'USD') ? 2 : 0;

        $message = "<b>🟢 របាយការណ៍ចង្ហាន់ថ្ងៃត្រង់ 🍀</b>\n";
        $message .= "📝 <b>កម្មវិធី៖</b> " . $description . "\n";
        $message .= "📅 <b>ថ្ងៃទី៖</b> " . now()->format('d-M-Y | H:i') . "\n";
        $message .= "───────────────────\n";
        $message .= "💸 <b>១. ក្នុងម្នាក់បង់៖</b> " . number_format($amountPerPerson, $format) . $symbol . "\n";
        $message .= "👥 <b>២. សមាជិកបង់រួច៖</b> " . $totalPresent . " នាក់\n";
        $message .= "💰 <b>៣. សរុបថវិកាប្រមូលបាន៖</b> <u>" . number_format($totalCollected, $format) . $symbol . "</u>\n";
        $message .= "───────────────────\n";
        $message .= "📝 <b>បញ្ជីវត្តមាន៖</b>\n" . (count($presentList) > 0 ? implode("\n", $presentList) : "មិនទាន់មាន");
        
        if (count($absentList) > 0) {
            $message .= "\n\n⚠️ <b>មិនទាន់បង់៖</b>\n" . implode("\n", $absentList);
        }
        
        $message .= "\n───────────────────\n";
        $message .= "👤 <b>អ្នករាយការណ៍៖</b> " . auth()->user()->name . "\n";

        if ($this->sendToTelegram($message)) {
            return back()->with('success', 'របាយការណ៍ត្រូវបានផ្ញើជោគជ័យ!');
        }
        return back()->with('error', 'ការផ្ញើបរាជ័យ!');
    }

    private function sendToTelegram($message)
    {
        $botToken = "8417479652:AAHBhZhajfmSPvkpUUdf79MblK1bTkvI8mY"; 
        $chatId = "-1003525236378";

        try {
            $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}