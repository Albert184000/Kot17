<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User; // បន្ថែម User model
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index() 
{
    $userId = auth()->id();
    
    // ១. ទាញទិន្នន័យសម្រាប់ស្ថិតិផ្ទាល់ខ្លួន (ការពារ Error $totalIn, $paymentsCount)
    $totalIn = \App\Models\Donation::where('user_id', $userId)->sum('amount') ?? 0;
    $paymentsCount = \App\Models\Donation::where('user_id', $userId)->count();
    $recentDonations = \App\Models\Donation::where('user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

    // ២. ទាញទិន្នន័យរចនាសម្ពន្ធ
    $admin = User::where('role', 'admin')->first();
    $treasurer = User::where('role', 'treasurer')->first();
    $collectors = User::where('role', 'collector')->get();
    $allMembers = User::whereIn('role', ['monk', 'member', 'student'])->get();

    // ៣. ស្ថិតិវត្តមានថ្ងៃនេះ
    $todayReports = \App\Models\Donation::whereDate('created_at', today())->count();
    $countMonks = User::where('role', 'monk')->count();
    $countStudents = User::where('role', 'student')->count();
    $countMembers = User::where('role', 'member')->count();
    $totalPeople = $countMonks + $countStudents + $countMembers;

    // ៤. បោះទៅ View (ប្រាកដថាឈ្មោះក្នុង compact ត្រូវនឹង variable ខាងលើ)
    return view('member.dashboard', compact(
        'totalIn', 
        'paymentsCount', 
        'recentDonations', 
        'admin', 
        'treasurer', 
        'collectors', 
        'allMembers',
        'countMonks', 
        'countStudents', 
        'countMembers', 
        'totalPeople',
        'todayReports'
    ));
}

    public function skipMeal(Request $request)
    {
        // ១. ត្រួតពិនិត្យម៉ោង (អនុញ្ញាតតែម៉ោង 00:00 ដល់ 12:00)
        $currentHour = now()->format('H'); 

        if ($currentHour >= 12) {
            return back()->with('error', '⚠️ ការរាយការណ៍ត្រូវបានបិទ! អ្នកអាចរាយការណ៍បានតែនៅចន្លោះម៉ោង ១២:០០ យប់ ដល់ ១២:០០ ថ្ងៃត្រង់ប៉ុណ្ណោះ។');
        }

        // ២. ចាប់យកទិន្នន័យពី Form
        $reason = $request->input('reason', 'មិនបានបញ្ជាក់មូលហេតុ'); 
        $statusType = $request->input('status');
        $user = auth()->user();
        $userName = $user->name;
        $role = strtolower($user->role);

        // ៣. លក្ខខណ្ឌមិនផ្ញើសារសម្រាប់កូនសិស្ស (ប្រសិនបើកំណត់ថា student មិនបាច់ report)
        if ($role === 'student') {
            return back()->with('info', 'កូនសិស្សមិនចាំបាច់រាយការណ៍ឡើយ។');
        }

        // ៤. កំណត់ខ្លឹមសារសារតាមប្រភេទ Status
        if ($statusType === 'late') {
            $header = "⏳ **និមន្តមកឆាន់យឺត** ⏳";
            $detail = "ខ្ញុំកុណានឹង **និមន្តមកឆាន់ដែរ** ប៉ុន្តែអាចនឹងមកយឺតបន្តិច ពីព្រោះ៖";
            $footer = "✅ ស្ថានភាព៖ **មកយឺត (សូមមេត្តាទុកចង្ហាន់ឱ្យផង)**";
        } else {
            $header = "🔔 **អវត្តមានភត្ត** 🔔";
            $detail = "ខ្ញុំកុណានឹង **មិនបាននិមន្តមកឆាន់** នៅកុដិទេថ្ងៃនេះ ពីព្រោះ៖";
            $footer = "✅ ស្ថានភាព៖ **និមន្តទៅខាងក្រៅ (មិនឆាន់)**";
        }

        // ៥. រៀបចំ Template សារសម្រាប់ Telegram
        $botToken = "8417479652:AAHBhZhajfmSPvkpUUdf79MblK1bTkvI8mY";
        $chatId = "828036461";
        $divider = "───────────────────";
        
        $message = "📍 " . $header . "\n" .
                   $divider . "\n\n" .
                   "👤 **ព្រះតេជគុណ:** " . $userName . "\n" .
                   "🙏 **សេចក្តីរាយការណ៍:**\n" .
                   "_" . $detail . "_\n\n" .
                   "📝 **មូលហេតុ:** `" . $reason . "`\n" .
                   "⏰ **ពេលវេលា:** " . now()->format('d-M-Y | H:i') . "\n\n" .
                   $divider . "\n" .
                   $footer;

        try {
            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown'
            ]);
            
            return back()->with('success', 'សេចក្តីរាយការណ៍ត្រូវបានបញ្ជូនទៅ Telegram រួចរាល់! 🙏');
        } catch (\Exception $e) {
            return back()->with('error', 'បណ្តាញមានបញ្ហា មិនអាចផ្ញើសារបានទេ។');
        }
    }
}