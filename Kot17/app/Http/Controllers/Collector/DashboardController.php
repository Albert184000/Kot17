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
        $realMembers = User::where('role', '!=', 'member')->get(); 

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

        // Message Body
        $message = "<b>🍚របាយការណ៍ប្រមូលចង្ហាន់ស្អែក🍴</b>\n";
        $message .= "📝 <b>ថ្ងៃស្អែកយើងមាន៖</b> " . $description . "\n";
        $message .= "───────────────────\n";
        $message .= "💸 <b>១. ក្នុងម្នាក់បង់៖</b> " . number_format($amountPerPerson, $format) . $symbol . "\n";
        $message .= "👥 <b>២. សមាជិកបង់រួច៖</b> " . $totalPresent . " នាក់\n";
        $message .= "💰 <b>៣. ដូច្នេះកូនសិស្សទៅផ្សារស្អែក៖</b> <u>" . number_format($totalCollected, $format) . $symbol . "</u>\n";
        $message .= "───────────────────\n";
        $message .= "📝 <b>ទិន្នន័យដែលយើងប្រមូលសមាជិករួមមាន៖</b>\n" . (count($presentList) > 0 ? implode("\n", $presentList) : "មិនទាន់មាន");
        
        if (count($absentList) > 0) {
            $message .= "\n\n⚠️ <b>មិនទាន់បង់៖</b>\n" . implode("\n", $absentList);
        }
        
        $message .= "\n───────────────────\n";
        $message .= "📅 <b>ថ្ងៃទី៖</b> " . now()->format('d-M-Y | H:i') . "\n";
        $message .= "👤 <b>អ្នករាយការណ៍៖</b> " . auth()->user()->name;

        // Get Multiple Images from Request
        $images = $request->file('report_images'); // Note the 's' plural

        if ($this->sendToTelegram($message, $images)) {
            return back()->with('success', 'របាយការណ៍ និងរូបភាពត្រូវបានផ្ញើ!');
        }
        return back()->with('error', 'ការផ្ញើបរាជ័យ!');
    }

    private function sendToTelegram($message, $images = null)
    {
        $botToken = "8417479652:AAHBhZhajfmSPvkpUUdf79MblK1bTkvI8mY"; 
        $chatId = "-1003525236378";

        try {
            // Case 1: Multiple Images
            if ($images && count($images) > 0) {
                $media = [];
                $requestPayload = Http::withoutVerifying();

                foreach ($images as $index => $image) {
                    $name = "photo_" . $index;
                    // Attach the binary file
                    $requestPayload->attach($name, file_get_contents($image), $image->getClientOriginalName());
                    
                    // Create the media array entry
                    $item = [
                        'type' => 'photo',
                        'media' => "attach://{$name}",
                    ];

                    // Only the first image in a group can carry the caption
                    if ($index === 0) {
                        $item['caption'] = $message;
                        $item['parse_mode'] = 'HTML';
                    }
                    $media[] = $item;
                }

                $response = $requestPayload->post("https://api.telegram.org/bot{$botToken}/sendMediaGroup", [
                    'chat_id' => $chatId,
                    'media' => json_encode($media),
                ]);
            } 
            // Case 2: Just Text
            else {
                $response = Http::withoutVerifying()
                    ->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $message,
                        'parse_mode' => 'HTML',
                    ]);
            }

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}