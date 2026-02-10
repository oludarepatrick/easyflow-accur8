<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Salary;
use App\Models\StaffSalary;
use App\Models\StudentReceipts;
use App\Models\StudentPayments;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with key metrics.
     */
    public function index()
{
    // ✅ Total active students (users with category = student)
    $activeStudents = User::where('category', 'student')
        ->where('status', 'active')
        ->count();

    // ✅ Total active staff (users with category = staff)
    $activeStaff = User::where('category', 'staff')
        ->where('status', 'active')
        ->count();

    // ✅ Total revenue (sum of all paid invoices)
    // use total_amount since "amount" doesn’t exist anymore
    $totalRevenue = StudentPayments::sum('amount_paid');

    /*$termRevenue = StudentReceipts::select('term', DB::raw('SUM(amount_paid) as total'))
    ->where('schooltype', 'primary')
    ->where('category', 'student')
    ->groupBy('term')
    ->pluck('total', 'term');*/

    // ✅ Total expected revenue for the entire term
    // Based on number of students per class × fee set in school_fees.total
    $totalExpectedPry = DB::table('users')
        ->join('school_fees', 'users.class', '=', 'school_fees.class')
        ->where('users.category', 'student')
        ->where('schooltype', 'primary')
        ->where('users.status', 'active')
        ->sum('school_fees.total');

    $totalExpectedSec = DB::table('users')
        ->join('school_fees', 'users.class', '=', 'school_fees.class')
        ->where('users.category', 'student')
        ->where('schooltype', 'secondary')
        ->where('users.status', 'active')
        ->sum('school_fees.total');

    $totalRevenuePry = StudentPayments::whereHas('user', function ($query) {
    $query->where('schooltype', 'primary')
            ->where('category', 'student');
    })->sum('amount_paid');

    $totalRevenueSec = StudentPayments::whereHas('user', function ($query) {
    $query->where('schooltype', 'secondary')
            ->where('category', 'student');
    })->sum('amount_paid');

    // ✅ Total salary paid (sum of net_pay for paid salaries)
    $totalSalary = StaffSalary::where('status', 'paid')
        ->sum('net_pay');

    // ✅ 3 most recent invoices (include student relationship)
    
        $recentInvoices = StudentReceipts::with(['student'])
        ->latest('updated_at') // or 'created_at' if that's your timestamp
        ->take(3)
        ->get();

    $termRevenue = StudentReceipts::select('term', DB::raw('SUM(amount_paid) as total'))
    ->groupBy('term')
    ->pluck('total', 'term');


    // Normalize terms to your expected keys
    $chartData = [
        'First Term'  => $termRevenue['First Term'] ?? 0,
        'Second Term' => $termRevenue['Second Term'] ?? 0,
        'Third Term'  => $termRevenue['Third Term'] ?? 0,
    ];

    // ✅ Outstanding balance (Expected - Paid)
    $outstandingBalancePry = max($totalExpectedPry - $totalRevenuePry, 0);
    $outstandingBalanceSec = max($totalExpectedSec - $totalRevenueSec, 0);
    $outstandingBalance = $outstandingBalancePry + $outstandingBalanceSec;
    return view('dashboard.admin', [
        'activeStudents' => $activeStudents,
        'activeStaff'    => $activeStaff,
        'totalRevenue'   => $totalRevenue,
        'termRevenue'    => $termRevenue,
        'totalRevenuePry'   => $totalRevenuePry,
        'totalRevenueSec'   => $totalRevenueSec,
        'totalSalary'    => $totalSalary,
        'recentInvoices' => $recentInvoices,
        'chartData'      => $chartData,
        'outstandingBalancePry' => $outstandingBalancePry,
    ]);


     
    }
}

