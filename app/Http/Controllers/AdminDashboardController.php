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

