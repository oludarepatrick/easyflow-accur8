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
        'date_paid' => 'nullable|date',
    ]);

    $staff = User::where('category','staff')->findOrFail($id);

    // Calculate Net Pay & Gross
    $netPay = ($request->basic ?? 0) 
            + ($request->bonus ?? 0) 
            + ($request->health ?? 0) 
            + ($request->lesson_amount ?? 0);

    $gross = $netPay - ($request->loan_repayment ?? 0);

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

    
}
