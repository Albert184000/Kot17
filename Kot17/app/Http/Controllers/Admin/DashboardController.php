<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ (Optional) if you only want active users, use ->where('is_active', true)
        $users = User::query()->get();

        // ✅ Admin (can be null)
        $admin = $users->firstWhere('role', 'admin');

        // ✅ Clean + normalize monk_rank safely (handles null, unicode spaces, zero-width)
        $clean = function ($text) {
            $text = (string)($text ?? '');
            // remove unicode spaces + invisible chars + normal spaces
            $text = preg_replace('/[\p{Z}\p{Cf}\s]+/u', '', $text);
            return trim($text);
        };

        $norm = function ($text) use ($clean) {
            // Str::lower supports utf-8 reasonably
            return Str::lower($clean($text));
        };

        // ✅ Rank lists (NO SAMANERI)
        $MAHA = array_map($norm, [
            'maha_thera', 'maha-thera',
            'ព្រះមហាថេរ', 'មហាថេរ',
        ]);

        $BHIKKHU = array_map($norm, [
            'bhikkhu',
            'monk',
            'senior_monk', 'senior-monk',
            'ព្រះភិក្ខុ', 'ភិក្ខុ',
        ]);

        $NOVICE = array_map($norm, [
            'samanera',
            'novice', 'novice_monk', 'novice-monk',
            'junior_monk', 'junior-monk',
            'samoner',
            'សាមណេរ',
            'ព្រះសាមណេរ',
        ]);

        // ✅ forbid "samaneri" in any form
        $isSamaneri = function ($rankNorm) {
            return Str::contains($rankNorm, ['សាមណេរី', 'samaneri']);
        };

        // ✅ Detect novice (male only) — no samaneri
        $isNovice = function ($rankNorm) use ($NOVICE, $isSamaneri) {
            if ($isSamaneri($rankNorm)) return false;

            if (in_array($rankNorm, $NOVICE, true)) return true;

            // fallback (NO សាមណេរី)
            return Str::contains($rankNorm, ['សាមណេ', 'novice', 'samon', 'saman']);
        };

        // ✅ Decide monk? (helps for students filtering)
        $isMonkRank = function ($rankNorm) use ($MAHA, $BHIKKHU, $isNovice, $isSamaneri) {
            if ($isSamaneri($rankNorm)) return false;

            return in_array($rankNorm, $MAHA, true)
                || in_array($rankNorm, $BHIKKHU, true)
                || $isNovice($rankNorm);
        };

        // ✅ Work only from people who are monk OR rank indicates monk
        // (Some old data might not have person_type, so keep rank-based backup)
        $monksPool = $users->filter(function ($u) use ($norm, $isMonkRank) {
            $rank = $norm($u->monk_rank);
            return ($u->person_type === 'monk') || $isMonkRank($rank);
        });

        // ✅ 1) MAHA (highest)
        $mahaTheras = $monksPool
            ->filter(fn($u) => in_array($norm($u->monk_rank), $MAHA, true))
            ->sortByDesc(fn($u) => (int)($u->vassa ?? 0))
            ->values();

        // ✅ 2) NOVICE (lowest) — do this before bhikkhu to avoid overlap via fallback
        $juniors = $monksPool
            ->filter(fn($u) => $isNovice($norm($u->monk_rank)))
            ->sortByDesc(fn($u) => (int)($u->vassa ?? 0))
            ->values();

        // ✅ 3) BHIKKHU (middle) — exclude maha + exclude novice
        $seniorMonks = $monksPool
            ->filter(function ($u) use ($norm, $BHIKKHU, $MAHA, $isNovice) {
                $rank = $norm($u->monk_rank);
                return in_array($rank, $BHIKKHU, true)
                    && !in_array($rank, $MAHA, true)
                    && !$isNovice($rank);
            })
            ->sortByDesc(fn($u) => (int)($u->vassa ?? 0))
            ->values();

        // ✅ Officers
        $treasurer  = $users->firstWhere('role', 'treasurer');
        $collectors = $users->where('role', 'collector')->values();
        $utilities  = $users->where('role', 'utility')->values();

        // ✅ Students (Lay only + not officers + not monks)
        // If you want "member" only: add ->where('role','member') logic
        $notStudentRoles = ['admin', 'treasurer', 'collector', 'utility'];

        $students = $users->filter(function ($u) use ($norm, $isMonkRank, $notStudentRoles) {
            $rank = $norm($u->monk_rank);

            $isMonk = ($u->person_type === 'monk') || $isMonkRank($rank);

            // ✅ student should be lay
            $isLay = ($u->person_type === 'lay') || (!$isMonk);

            return $isLay && !$isMonk && !in_array($u->role, $notStudentRoles, true);
        })->values();

        $totalOrg = $users->count();

        return view('admin.dashboard', compact(
            'admin',
            'mahaTheras',
            'seniorMonks',
            'juniors',
            'treasurer',
            'collectors',
            'utilities',
            'students',
            'totalOrg'
        ));
    }
}
