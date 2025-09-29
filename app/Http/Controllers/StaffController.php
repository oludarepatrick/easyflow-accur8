<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\StaffBankDetail;
use App\Models\StaffSalary;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\School;
use App\Mail\ReceiptMail;

class StaffController extends Controller
{
    /**
     * Display a listing of staff.
     */
    public function index()
    {
        $staff = User::where('category', 'Staff')
                     ->where('status', 'active') // active only
                     ->get();

        return view('admin.staff.index', compact('staff'));
    }

    /**
     * Show a single staff member.
     */
    public function show($id)
    {
        $staff = User::findOrFail($id);
        $bank = StaffBankDetail::where('staff_id', $id)->first();
        $salaries = StaffSalary::where('staff_id', $id)->get();
        return view('admin.staff.show', compact('staff', 'bank', 'salaries'));
    }

    /**
     * Deactivate staff.
     */
    public function deactivate($id)
    {
        $staff = User::findOrFail($id);
        $staff->is_active = 0;
        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Staff deactivated successfully.');
    }

    /**
     * Delete staff completely.
     */
    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
    }

    // Update profile (firstname, lastname, class, phone etc.)
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'class'     => 'nullable|string|max:50',
            'phone'     => 'nullable|string|max:11',
            'email'     => 'nullable|string|max:50',
        ]);

        $staff = User::where('category','staff')->findOrFail($id);
        $staff->firstname = $request->firstname;
        $staff->lastname  = $request->lastname;
        $staff->class     = $request->class;
        $staff->phone     = $request->phone;
        $staff->email     = $request->email;
        $staff->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // Update or create bank details for staff
    public function updateBank(Request $request, $id)
    {
        $request->validate([
            'bank_name'    => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_no'   => 'nullable|string|max:10',
        ]);

        $staff = User::where('category','staff')->findOrFail($id);

        StaffBankDetail::updateOrCreate(
            ['staff_id' => $staff->id],
            [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_no' => $request->account_no,
            ]
        );

        return redirect()->back()->with('success', 'Bank details saved successfully.');
    }


   // Add (store) salary record
public function addSalary(Request $request, $id)
{
        $request->validate([
        'month' => 'required|integer|min:1|max:12',
        'year' => 'required|integer|min:2000|max:2100',
        'status' => 'required|in:pending,paid',
        'basic' => 'required|numeric|min:0',
        'bonus' => 'nullable|numeric|min:0',
        'loan_repayment' => 'nullable|numeric|min:0',
        'health' => 'nullable|numeric|min:0',
        'lesson_amount' => 'nullable|numeric|min:0',
        'tax_deduction' => 'nullable|numeric|min:0',
        'social_deduction' => 'nullable|numeric|min:0',
        'date_paid' => 'nullable|date',
    ]);

    $staff = User::where('category', 'staff')->findOrFail($id);

    // Calculate Net Pay
    $netPay = ($request->basic ?? 0) 
            + ($request->bonus ?? 0) 
            + ($request->health ?? 0) 
            + ($request->lesson_amount ?? 0);

    // Total deductions
    $totalDeductions = ($request->loan_repayment ?? 0) 
                    + ($request->tax_deduction ?? 0) 
                    + ($request->social_deduction ?? 0);

    // Gross Pay
    $gross = $netPay - $totalDeductions;

    $salary = StaffSalary::create([
        'staff_id' => $staff->id,
        'month' => $request->month,
        'year'  => $request->year,
        'status'=> $request->status,
        'basic' => $request->basic,
        'bonus' => $request->bonus,
        'loan_repayment' => $request->loan_repayment,
        'health' => $request->health,
        'lesson_amount' => $request->lesson_amount,
        'tax_deduction' => $request->tax_deduction,
        'social_deduction' => $request->social_deduction,
        'net_pay' => $netPay,
        'gross' => $gross,
        'date_paid' => $request->date_paid ? Carbon::parse($request->date_paid)->format('Y-m-d') : null,
    ]);


    // ✅ If status is paid and send_email is checked → send payslip
    if ($request->status === 'paid' && $request->has('send_email')) {

        // 1. Generate Payslip PDF
        $pdf = \PDF::loadView('admin.staff.payslip', [
            'staff' => $staff,
            'salary' => $salary,
        ])->output();

        // 2. Send via ZeptoMail
        $response = Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->timeout(30)
            ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                "template_key" => "salary-notification", // ✅ your ZeptoMail template
                "from" => [
                    "address" => "development@leverpay.io",
                    "name"    => "School Payroll"
                ],
                "to" => [
                    ["email_address" => ["address" => $staff->email]]
                ],
                "merge_info" => [
                    "firstname"    => $staff->firstname,
                    "month"        => date("F", mktime(0,0,0,$salary->month,1)),
                    "year"         => $salary->year,
                    "loan_repayment"      => number_format($salary->loan_repayment, 2),
                    "net_pay"      => number_format($salary->net_pay, 2),
                    "gross"        => number_format($salary->gross, 2),
                ],
                "attachments" => [
                    [
                        "name"      => "payslip-{$salary->id}.pdf",
                        "mime_type" => "application/pdf",
                        "content"   => base64_encode($pdf)
                    ]
                ]
            ]);

        if ($response->failed()) {
            return back()->with('error', 'Salary saved but email failed: ' . $response->body());
        }
    }

    return redirect()->back()->with('success', 'Salary record added.');
}

// 📌 Download Payslip
    public function downloadPayslip($id)
    {
        $salary = StaffSalary::findOrFail($id);
        $staff = $salary->staff;
        $school = School::first();

        $pdf = Pdf::loadView('admin.staff.payslip', compact('salary', 'staff', 'school'))->output();

        return response()->streamDownload(fn () => print($pdf), "payslip-{$salary->id}.pdf");
    }

    // 📌 Email Payslip
    public function emailPayslip($id)
    {
        $salary = StaffSalary::findOrFail($id);
        $staff = $salary->staff;
        $school = School::first();

        // Generate PDF
        $pdf = Pdf::loadView('admin.staff.payslip', compact('salary', 'staff', 'school'))->output();

        $response = Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                "template_key" => "salary-notification",
                "from" => [
                    "address" => "development@leverpay.io",
                    "name"    => $school->name ?? "School Payroll"
                ],
                "to" => [
                    ["email_address" => ["address" => $staff->email]]
                ],
                "merge_info" => [
                    "firstname"  => $staff->firstname,
                    "month"      => date("F", mktime(0,0,0,$salary->month,1)),
                    "year"       => $salary->year,
                    "loan_repayment"=> $salary->loan_repayment,
                    "net_pay"    => number_format($salary->net_pay, 2),
                    "gross"      => number_format($salary->gross, 2),
                ],
                "attachments" => [
                    [
                        "name"      => "payslip-{$salary->id}.pdf",
                        "mime_type" => "application/pdf",
                        "content"   => base64_encode($pdf)
                    ]
                ]
            ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to send payslip: ' . $response->body());
        }

        return back()->with('success', 'Payslip emailed successfully.');
    }

    // 📌 Mark as Paid + Email Payslip
    public function markAsPaid($id)
    {
        $salary = StaffSalary::findOrFail($id);

        if ($salary->status === 'pending') {
            $salary->update([
                'status' => 'paid',
                'date_paid' => Carbon::now()->format('Y-m-d'),
            ]);

            // Send payslip after marking paid
            return $this->emailPayslip($salary->id);
        }

        return back()->with('info', 'Salary is already marked as paid.');
    }

    public function salaryStatement(Request $request)
    {
        $month = $request->input('month');
        $year  = $request->input('year');

        $query = StaffSalary::with('staff') // relationship with User
            ->when($month, fn($q) => $q->where('month', $month))
            ->when($year, fn($q) => $q->where('year', $year))
            ->orderBy('month', 'asc');

        $salaries = $query->get();

        return view('admin.staff.salary_statement', compact('salaries', 'month', 'year'));
    }

    public function downloadSalaryStatement(Request $request)
    {
        $month = $request->input('month');
        $year  = $request->input('year');

        $salaries = StaffSalary::with('staff')
            ->when($month, fn($q) => $q->where('month', $month))
            ->when($year, fn($q) => $q->where('year', $year))
            ->orderBy('month', 'asc')
            ->get();

        $pdf = PDF::loadView('admin.staff.salary_statement_pdf', compact('salaries', 'month', 'year'));

        return $pdf->download("staff_salary_statement_{$month}_{$year}.pdf");
    }

    public function emailSalaryStatement(Request $request)
{
    $month = $request->input('month');
    $year  = $request->input('year');

    $salaries = StaffSalary::with('staff')
        ->when($month, fn($q) => $q->where('month', $month))
        ->when($year, fn($q) => $q->where('year', $year))
        ->get();

    if ($salaries->isEmpty()) {
        return back()->with('error', 'No salary records found for this period.');
    }

    $pdf = \PDF::loadView('admin.staff.salary_statement_pdf', compact('salaries', 'month', 'year'))->output();

    // Total deductions
    $totalDeductions = ($request->loan_repayment ?? 0) 
                    + ($request->tax_deduction ?? 0) 
                    + ($request->social_deduction ?? 0);

    $total = $salaries->sum('gross'); // total gross in this period
    $totalnet = $salaries->sum('net_pay'); // total netpay in this period
    //$totalDeductions = $salaries->sum('totalDeductions'); // total gross in this period
    $school = \App\Models\School::first();

    // Send using ZeptoMail template API
    $response = Http::withoutVerifying()
        ->withHeaders([
            'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
            'accept'        => 'application/json',
            'content-type'  => 'application/json',
        ])->timeout(30)
        ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
            "template_key" => "email-staff-statement",
            "from" => [
                "address" => "development@leverpay.io", // must be verified
                "name"    => $school->schoolname ?? 'School'
            ],
            "to" => [
                ["email_address" => ["address" => $request->email]]
            ],
            "merge_info" => [
                "firstname" => $request->firstname ?? '',
                "month"      => $request->input('month') ?? '',
                "year"   => $request->input('year') ?? '',
                "totalnet"     => number_format($totalnet, 2),
                "total"     => number_format($total, 2),
            ],
            "attachments" => [
                [
                    "name"      => "staff-statement-{$month}-{$year}.pdf",
                    "mime_type" => "application/pdf",
                    "content"   => base64_encode($pdf)
                ]
            ]
        ]);

    if ($response->failed()) {
        return back()->with('error', 'Failed to send statement: ' . $response->body());
    }

    return back()->with('success', 'Salary statement emailed successfully.');
}

public function deleteSalary($id)
{
    $salary = \App\Models\StaffSalary::findOrFail($id);
    $salary->delete();

    return redirect()->back()->with('success', 'Salary record deleted successfully.');
}
    
}
