<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentPayments;
use App\Models\StudentReceipts;
use Carbon\Carbon;

class ClerkController extends Controller
{
    /**
     * Display the clerk dashboard with key metrics.
     */
    public function index()
    {
        // ✅ Active Students
        $activeStudents = User::where('category', 'student')
            ->where('status', 'active')
            ->count();

        // ✅ Today’s Collections (payments made today)
        $todaysCollections = StudentPayments::whereDate('payment_date', Carbon::today())
            ->sum('amount_paid');

        // ✅ Outstanding Balance (sum of all amount_due from receipts)
        $outstandingBalance = StudentReceipts::sum('amount_due');

        // ✅ Recent Payments (latest 5 payments)
        $recentInvoices = StudentPayments::with('student')
            ->latest('payment_date')
            ->take(5)
            ->get();

        return view('dashboard.clerk', [
            'activeStudents'     => $activeStudents,
            'todaysCollections'  => $todaysCollections,
            'outstandingBalance' => $outstandingBalance,
            'recentInvoices'     => $recentInvoices,
        ]);
    }
}
