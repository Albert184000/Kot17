<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = auth()->user();
        $today      = today();

        /**
         * 1️⃣ My report (today, amount = 0)
         */
        $myReport = Donation::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('amount', 0)
            ->first();

        /**
         * 2️⃣ Organization roles
         */
        $admin      = User::where('role', 'admin')->first();
        $treasurer  = User::where('role', 'treasurer')->first();
        $collectors = User::where('role', 'collector')->orderBy('name')->get();
        $members    = User::where('role', 'member')->orderBy('name')->get();

        /**
         * 3️⃣ Donation stats (for current user)
         */
        $totalIn = Donation::where('user_id', $user->id)->sum('amount');
        $paymentsCount = Donation::where('user_id', $user->id)->count();

        $recentDonations = Donation::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        /**
         * 4️⃣ Attendance (ALL users)
         */
        $allMembers = User::whereIn('role', ['admin','treasurer','collector','member','monk','student'])
            ->orderBy('name')
            ->get();

        $totalPeople = $allMembers->count();

        /**
         * 5️⃣ Today offline reports (ONE QUERY)
         */
        $todayOfflineReports = Donation::whereDate('created_at', $today)
            ->where('amount', 0)
            ->select(['id','user_id','reason','status','created_at'])
            ->get()
            ->keyBy('user_id');

        $todayReports = $todayOfflineReports->count();
        $offlineUserIds = $todayOfflineReports->keys()->all();

        return view('member.dashboard', compact(
            'user',
            'myReport',

            'admin',
            'treasurer',
            'collectors',
            'members',

            'totalIn',
            'paymentsCount',
            'recentDonations',

            'allMembers',
            'totalPeople',
            'todayReports',
            'todayOfflineReports',
            'offlineUserIds'
        ));
    }

    /**
     * Skip / Late report
     */
    public function skipMeal(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'status' => 'required|in:skip,late',
        ]);

        $user = auth()->user();

        // ❌ Prevent duplicate report in same day
        $alreadyReported = Donation::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->where('amount', 0)
            ->exists();

        if ($alreadyReported) {
            return back()->with('success', 'លោកបានរាយការណ៍រួចហើយសម្រាប់ថ្ងៃនេះ។');
        }

        // ✅ Save report
        Donation::create([
            'user_id' => $user->id,
            'amount'  => 0,
            'status'  => $request->status,
            'reason'  => $request->reason,
        ]);

        // 📩 Telegram
        $this->sendTelegramNotification($user, $request->status, $request->reason);

        return back()->with(
            'success',
            $request->status === 'late'
                ? 'ស្ថានភាព៖ មកឆាន់ (យឺត)'
                : 'ស្ថានភាព៖ មិននៅកុដិ (Offline)'
        );
    }

    /**
     * Telegram notify
     */
    private function sendTelegramNotification($user, $status, $reason)
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) return;

        $statusEmoji = $status === 'late' ? '⏳' : '✅';
        $statusText  = $status === 'late'
            ? 'និមន្តមក (យឺត)'
            : 'និមន្តទៅខាងក្រៅ (មិនឆាន់)';

        $message = "🔔 *សេចក្ដីរាយការណ៍ភត្ត*\n";
        $message .= "━━━━━━━━━━━━━━━\n";
        $message .= "🧘 *ឈ្មោះ:* {$user->name}\n";
        $message .= "📝 *មូលហេតុ:* {$reason}\n";
        $message .= "⏰ *ពេលវេលា:* ".now()->format('d-M-Y | H:i')."\n";
        $message .= "━━━━━━━━━━━━━━━\n";
        $message .= "{$statusEmoji} *ស្ថានភាព:* {$statusText}";

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'Markdown'
            ]);
        } catch (\Exception $e) {
            // silent fail
        }
    }

    /**
     * Cancel report
     */
    public function cancelSkip($id)
    {
        $report = Donation::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('amount', 0)
            ->firstOrFail();

        $report->delete();

        return back()->with('success', 'បច្ចុប្បន្នភាព៖ លោកកំពុងនៅកុដិ (Online) វិញហើយ');
    }
}
