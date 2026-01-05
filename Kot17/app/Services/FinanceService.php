<?php
// app/Services/FinanceService.php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public function getBalance()
    {
        $income = Transaction::income()->sum('amount');
        $expense = Transaction::expense()->sum('amount');

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'current_balance' => $income - $expense,
        ];
    }

    public function getBalanceByDateRange($startDate, $endDate)
    {
        $income = Transaction::income()
            ->byDateRange($startDate, $endDate)
            ->sum('amount');

        $expense = Transaction::expense()
            ->byDateRange($startDate, $endDate)
            ->sum('amount');

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'net_balance' => $income - $expense,
        ];
    }

    public function getMonthlyReport($year, $month)
    {
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $transactions = Transaction::byDateRange($startDate, $endDate)
            ->with('creator')
            ->orderBy('transaction_date', 'desc')
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        $incomeByCategory = $transactions->where('type', 'income')
            ->groupBy('category')
            ->map(fn($items) => $items->sum('amount'));

        $expenseByCategory = $transactions->where('type', 'expense')
            ->groupBy('category')
            ->map(fn($items) => $items->sum('amount'));

        return [
            'transactions' => $transactions,
            'summary' => [
                'total_income' => $income,
                'total_expense' => $expense,
                'net_balance' => $income - $expense,
                'income_by_category' => $incomeByCategory,
                'expense_by_category' => $expenseByCategory,
            ],
        ];
    }
}