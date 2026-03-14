<?php

namespace App\Http\Controllers;

use App\Models\ItemDetail;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $revenue = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'income')
            ->selectRaw('SUM(journal_details.credit - journal_details.debit) as total')
            ->value('total');

        $expense = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'expense')
            ->selectRaw('SUM(journal_details.debit - journal_details.credit) as total')
            ->value('total');

        $cashBalance = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'asset')
            ->selectRaw('SUM(journal_details.debit - journal_details.credit) as total')
            ->value('total');

        $netProfit = $revenue - $expense;

        $totalStock = ItemDetail::whereHas('condition', fn($q) => $q->where('ready', true))
            ->orWhereDoesntHave('condition')
            ->count();

        $totalStockToday = ItemDetail::where(function ($q) {
            $q->whereHas('condition', fn($q2) => $q2->where('ready', true))
                ->orWhereDoesntHave('condition');
        })
            ->whereDate('created_at', today())
            ->count();

        $salesPerDay = SalesInvoice::selectRaw('EXTRACT(DAY FROM transaction_date) as day, SUM(grand_total) as total')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->whereIn('status', [2, 3, 4])
            ->groupBy('day')
            ->pluck('total', 'day');

        $daysInMonth = now()->daysInMonth;

        $labels = [];
        $data = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {

            $labels[] = $i;

            $data[] = $salesPerDay[$i] ?? 0;
        }

        $salesInvoiceNew = SalesInvoice::whereBetween('transaction_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])
            ->whereIn('status', [2, 3, 4])
            ->latest('transaction_date')
            ->limit(10)
            ->get();
        $newPhones = ItemDetail::where('type', 'new')->count();

        $secondPhones = ItemDetail::where('type', 'second')->count();



        // $cashBalance = JournalDetail::whereIn('coa_id', [1, 2])
        //     ->sum(DB::raw('debit - credit'));

        // $totalStock = ItemDetail::count();
        return view('dashboard.index', [
            'totalSales' => SalesInvoice::whereIn('status', [2, 3, 4])->sum('grand_total'),
            'netProfit' => $netProfit,
            'cashBalance' => $cashBalance,
            'totalStock' => $totalStock,
            'percentageRevenue' => $this->percentageTotalRevenue(),
            'percentageExpense' => $this->percentageTotalExpence(),
            'percentageCashBalance' => $this->percentageTotalCashBalance(),
            'percentageNetProfit' => $this->percentageNetProfit(),
            'percentageSales' => $this->percentageTotalSales(),
            'totalStockToday' => $totalStockToday,
            'salesLabels' => $labels,
            'salesData' => $data,
            'sales_invoice_new' => $salesInvoiceNew,
            'newPhones' => $newPhones,
            'secondPhones' => $secondPhones
        ]);
    }


    protected function percentageTotalRevenue()
    {
        $todayRevenue = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'income')
            ->whereDate('journal_details.created_at', today())
            ->sum(DB::raw('journal_details.credit - journal_details.debit'));

        $yesterdayRevenue = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'income')
            ->whereDate('journal_details.created_at', today()->subDay())
            ->sum(DB::raw('journal_details.credit - journal_details.debit'));

        $revenuePercent = $yesterdayRevenue
            ? (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100
            : 0;

        return $revenuePercent;
    }

    protected function percentageTotalSales()
    {
        $todaySales = SalesInvoice::whereIn('status', [2, 3, 4])
            ->whereDate('transaction_date', today())
            ->sum('grand_total');

        $yesterdaySales = SalesInvoice::whereIn('status', [2, 3, 4])
            ->whereDate('transaction_date', today()->subDay())
            ->sum('grand_total');

        return $yesterdaySales
            ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 2)
            : 0;
    }

    protected function percentageTotalExpence()
    {
        $todayExpense = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'expense')
            ->whereDate('journal_details.created_at', today())
            ->sum(DB::raw('journal_details.debit - journal_details.credit'));

        $yesterdayExpense = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'expense')
            ->whereDate('journal_details.created_at', today()->subDay())
            ->sum(DB::raw('journal_details.debit - journal_details.credit'));

        $expensePercent = $yesterdayExpense
            ? (($todayExpense - $yesterdayExpense) / $yesterdayExpense) * 100
            : 0;

        return $expensePercent;
    }

    protected function percentageTotalCashBalance()
    {
        $todayCash = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'asset')
            ->whereDate('journal_details.created_at', today())
            ->sum(DB::raw('journal_details.debit - journal_details.credit'));

        $yesterdayCash = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'asset')
            ->whereDate('journal_details.created_at', today()->subDay())
            ->sum(DB::raw('journal_details.debit - journal_details.credit'));

        $cashPercent = $yesterdayCash
            ? (($todayCash - $yesterdayCash) / $yesterdayCash) * 100
            : 0;
        return $cashPercent;
    }

    protected function percentageNetProfit()
    {
        $todayRevenue = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'income')
            ->whereDate('journal_details.created_at', today())
            ->sum(DB::raw('credit - debit'));

        $todayExpense = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'expense')
            ->whereDate('journal_details.created_at', today())
            ->sum(DB::raw('debit - credit'));

        $yesterdayRevenue = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'income')
            ->whereDate('journal_details.created_at', today()->subDay())
            ->sum(DB::raw('credit - debit'));

        $yesterdayExpense = DB::table('journal_details')
            ->join('coas', 'coas.code', '=', 'journal_details.coa')
            ->where('coas.type', 'expense')
            ->whereDate('journal_details.created_at', today()->subDay())
            ->sum(DB::raw('debit - credit'));

        $todayProfit = $todayRevenue - $todayExpense;
        $yesterdayProfit = $yesterdayRevenue - $yesterdayExpense;

        return $yesterdayProfit
            ? round((($todayProfit - $yesterdayProfit) / $yesterdayProfit) * 100, 2)
            : 0;
    }
}
