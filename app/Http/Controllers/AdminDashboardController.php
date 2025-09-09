<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Salary;
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
    $totalRevenue = Invoice::where('status', 'paid')
        ->sum('total_amount');

    // ✅ Total salary paid (sum of net_pay for paid salaries)
    $totalSalary = Salary::where('status', 'paid')
        ->sum('net_pay');

    // ✅ 3 most recent invoices (include student relationship)
    $recentInvoices = Invoice::with('student')
        ->where('status', 'paid')
        ->latest()
        ->take(3)
        ->get();

    // ✅ Revenue comparison across terms
    $termRevenue = Invoice::select('term', DB::raw('SUM(total_amount) as total'))
        ->where('status', 'paid')
        ->groupBy('term')
        ->pluck('total', 'term');

    // ✅ Make sure all 3 terms are represented
    $chartData = [
        'First Term'  => $termRevenue['first'] ?? 0,
        'Second Term' => $termRevenue['second'] ?? 0,
        'Third Term'  => $termRevenue['third'] ?? 0,
    ];

    return view('dashboard.admin', [
        'activeStudents' => $activeStudents,
        'activeStaff'    => $activeStaff,
        'totalRevenue'   => $totalRevenue,
        'totalSalary'    => $totalSalary,
        'recentInvoices' => $recentInvoices,
        'chartData'      => $chartData,
    ]);


     
    }
}

