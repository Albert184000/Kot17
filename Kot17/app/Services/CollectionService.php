<?php
// app/Services/CollectionService.php

namespace App\Services;

use App\Models\Member;
use App\Models\DailyCollection;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CollectionService
{
    public function collectPayment($memberId, $collectorId, $date = null)
    {
        $date = $date ?? today();
        $member = Member::findOrFail($memberId);

        return DB::transaction(function () use ($member, $collectorId, $date) {
            // Create or update daily collection
            $collection = DailyCollection::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'collection_date' => $date,
                ],
                [
                    'collected_by' => $collectorId,
                    'amount' => $member->daily_rate,
                    'status' => 'collected',
                    'collected_at_time' => now()->format('H:i:s'),
                ]
            );

            // Create transaction record
            Transaction::create([
                'type' => 'income',
                'category' => 'daily_collection',
                'amount' => $member->daily_rate,
                'description' => "Daily collection from {$member->user->name}",
                'transaction_date' => $date,
                'created_by' => $collectorId,
                'reference_type' => DailyCollection::class,
                'reference_id' => $collection->id,
            ]);

            return $collection;
        });
    }

    public function getActiveMembersForCollection($date = null)
    {
        $date = $date ?? today();

        return Member::active()
            ->with(['user', 'dailyCollections' => function ($query) use ($date) {
                $query->whereDate('collection_date', $date);
            }])
            ->get()
            ->map(function ($member) use ($date) {
                return [
                    'member' => $member,
                    'has_paid' => $member->dailyCollections->isNotEmpty() && 
                                  $member->dailyCollections->first()->status === 'collected',
                    'collection' => $member->dailyCollections->first(),
                ];
            });
    }

    public function getDailyTotal($date = null)
    {
        $date = $date ?? today();

        return DailyCollection::byDate($date)
            ->collected()
            ->sum('amount');
    }

    public function getCollectionStats($startDate, $endDate)
    {
        return [
            'total_collected' => DailyCollection::whereBetween('collection_date', [$startDate, $endDate])
                ->collected()
                ->sum('amount'),
            'total_days' => DailyCollection::whereBetween('collection_date', [$startDate, $endDate])
                ->distinct('collection_date')
                ->count('collection_date'),
            'unique_members' => DailyCollection::whereBetween('collection_date', [$startDate, $endDate])
                ->distinct('member_id')
                ->count('member_id'),
        ];
    }
}