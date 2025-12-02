<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentPayments;
use App\Models\StudentReceipts;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsStatementExport;
use Illuminate\Support\Facades\Http;

class StatementController extends Controller
{
    public function index(Request $request)
{
    // Filters
    $term = $request->input('term');
    $session = $request->input('session');
    $from = $request->input('from');
    $to = $request->input('to');

    $query = StudentPayments::with(['receipt.student'])
        ->whereHas('user', function ($q) {
            $q->where('schooltype', 'primary')
              ->where('category', 'student');
        });

    // filter by session/term via receipts
    if ($term) {
        $query->whereHas('receipt', fn($q) => $q->where('term', $term));
    }
    if ($session) {
        $query->whereHas('receipt', fn($q) => $q->where('session', $session));
    }

    // date range (payment_date)
    if ($from) {
        $query->whereDate('payment_date', '>=', Carbon::parse($from)->startOfDay());
    }
    if ($to) {
        $query->whereDate('payment_date', '<=', Carbon::parse($to)->endOfDay());
    }

    $payments = $query->orderBy('payment_date', 'desc')->paginate(25)->withQueryString();

    // totals
    $total = $query->sum('amount_paid');

    // For filter selects
    $sessions = \App\Models\SchoolFee::select('session')->distinct()->pluck('session');
    $terms = \App\Models\SchoolFee::select('term')->distinct()->pluck('term');
    $school = School::first();

    return view('admin.statements.payments', compact('payments','total','sessions','terms','school','term','session','from','to'));
}


    // Secondary Payments Statement
    public function sec_index(Request $request)
    {
        // Filters
        $term = $request->input('term');
        $session = $request->input('session');
        $from = $request->input('from');
        $to = $request->input('to');

        $query = StudentPayments::with(['receipt.student'])
            ->whereHas('user', function ($q) {
                $q->where('schooltype', 'secondary')
                ->where('category', 'student');
            });

        // filter by session/term via receipts
        if ($term) {
            $query->whereHas('receipt', fn($q) => $q->where('term', $term));
        }
        if ($session) {
            $query->whereHas('receipt', fn($q) => $q->where('session', $session));
        }

        // date range (payment_date)
        if ($from) {
            $query->whereDate('payment_date', '>=', Carbon::parse($from)->startOfDay());
        }
        if ($to) {
            $query->whereDate('payment_date', '<=', Carbon::parse($to)->endOfDay());
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(25)->withQueryString();

        // totals
        $total = $query->sum('amount_paid');

        // For filter selects
        $sessions = \App\Models\SchoolFee::select('session')->distinct()->pluck('session');
        $terms = \App\Models\SchoolFee::select('term')->distinct()->pluck('term');
        $school = School::first();

        return view('admin.statements.sec_payments', compact('payments','total','sessions','terms','school','term','session','from','to'));
    }


    public function exportPdf(Request $request)
    {
        // gather same filters as index
        $payments = $this->paymentsQuery($request)
            ->whereHas('user', function ($q) {
                $q->where('schooltype', 'primary')
                ->where('category', 'student');
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        $school = School::first();
        $total = $payments->sum('amount_paid');

        $pdf = PDF::loadView('admin.statements.pdf', compact('payments', 'school', 'total'))
                ->setPaper('a4', 'landscape');

        $filename = 'payments-statement-' . now()->format('YmdHis') . '.pdf';
        return $pdf->download($filename);
    }

    public function sec_exportPdf(Request $request)
    {
        // gather same filters as index
        $payments = $this->paymentsQuery($request)
            ->whereHas('user', function ($q) {
                $q->where('schooltype', 'secondary')
                ->where('category', 'student');
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        $school = School::first();
        $total = $payments->sum('amount_paid');

        $pdf = PDF::loadView('admin.statements.pdf', compact('payments', 'school', 'total'))
                ->setPaper('a4', 'landscape');

        $filename = 'payments-statement-' . now()->format('YmdHis') . '.pdf';
        return $pdf->download($filename);
    }


    

    public function emailStatement(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'firstname' => 'nullable|string',
        ]);

        $payments = $this->paymentsQuery($request)
            ->whereHas('user', function ($q) {
                $q->where('schooltype', 'primary')
                ->where('category', 'student');
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        $school = School::first();
        $total = $payments->sum('amount_paid');

        $pdf = PDF::loadView('admin.statements.pdf', compact('payments', 'school', 'total'))
                ->setPaper('a4', 'landscape')
                ->output();

        // Send using ZeptoMail template API (similar to your existing code)
        $response = Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->timeout(30)
            ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                "template_key" => "email-statement", // create this template in ZeptoMail
                "from" => [
                    "address" => "development@schooldrive.com.ng",
                    "name"    => $school->name ?? 'School'
                ],
                "to" => [
                    ["email_address" => ["address" => $request->email]]
                ],
                "merge_info" => [
                    "firstname" => $request->firstname ?? '',
                    "term" => $request->input('term') ?? '',
                    "session" => $request->input('session') ?? '',
                    "total" => number_format($total, 2),
                ],
                "attachments" => [
                    [
                        "name" => "payments-statement.pdf",
                        "mime_type" => "application/pdf",
                        "content" => base64_encode($pdf)
                    ]
                ]
            ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to send statement: ' . $response->body());
        }

        return back()->with('success', 'Statement emailed successfully to ' . $request->email);
    }

    public function sec_emailStatement(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'firstname' => 'nullable|string',
        ]);

        $payments = $this->paymentsQuery($request)
            ->whereHas('user', function ($q) {
                $q->where('schooltype', 'secondary')
                ->where('category', 'student');
            })
            ->orderBy('payment_date', 'desc')
            ->get();

        $school = School::first();
        $total = $payments->sum('amount_paid');

        $pdf = PDF::loadView('admin.statements.pdf', compact('payments', 'school', 'total'))
                ->setPaper('a4', 'landscape')
                ->output();

        // Send using ZeptoMail template API (similar to your existing code)
        $response = Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->timeout(30)
            ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                "template_key" => "email-statement", // create this template in ZeptoMail
                "from" => [
                    "address" => "development@schooldrive.com.ng",
                    "name"    => $school->name ?? 'School'
                ],
                "to" => [
                    ["email_address" => ["address" => $request->email]]
                ],
                "merge_info" => [
                    "firstname" => $request->firstname ?? '',
                    "term" => $request->input('term') ?? '',
                    "session" => $request->input('session') ?? '',
                    "total" => number_format($total, 2),
                ],
                "attachments" => [
                    [
                        "name" => "payments-statement.pdf",
                        "mime_type" => "application/pdf",
                        "content" => base64_encode($pdf)
                    ]
                ]
            ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to send statement: ' . $response->body());
        }

        return back()->with('success', 'Statement emailed successfully to ' . $request->email);
    }


    protected function paymentsQuery(Request $request)
    {
        $term = $request->input('term');
        $session = $request->input('session');
        $from = $request->input('from');
        $to = $request->input('to');

        $query = StudentPayments::with(['receipt.student']);

        if ($term) {
            $query->whereHas('receipt', fn($q) => $q->where('term', $term));
        }
        if ($session) {
            $query->whereHas('receipt', fn($q) => $q->where('session', $session));
        }

        if ($from) {
            $query->whereDate('payment_date', '>=', Carbon::parse($from)->startOfDay());
        }
        if ($to) {
            $query->whereDate('payment_date', '<=', Carbon::parse($to)->endOfDay());
        }

        return $query;
    }

   public function owingReport(Request $request)
    {
        $term    = $request->input('term');
        $session = $request->input('session');

        // 🔎 Filter receipts (students who still owe)
        $query = StudentReceipts::with('student', 'payments')
            ->where('amount_due', '>', 0)
            ->whereHas('student', function ($q) {
                $q->where('schooltype', 'primary')
                ->where('category', 'student');
            });

        if ($term) {
            $query->where('term', $term);
        }

        if ($session) {
            $query->where('session', $session);
        }

        $receipts = $query->get();

        // 🔢 Summary for chart
        $totalPaid = $receipts->sum('amount_paid');
        $totalDebt = $receipts->sum('amount_due');

        if ($request->has('download') && $request->download === 'pdf') {
            $school = \App\Models\School::first();

            // Fix invalid filename by removing slashes
            $cleanSession = str_replace(['/', '\\'], '-', $session);
            $cleanTerm = str_replace(['/', '\\'], '-', $term);

            $pdf = \PDF::loadView(
                'admin.statements.owing-pdf',
                compact('receipts', 'school', 'term', 'session', 'totalPaid', 'totalDebt')
            );

            return $pdf->download("owing_report_{$cleanTerm}_{$cleanSession}.pdf");
        }


        $sessions = \App\Models\SchoolFee::select('session')->distinct()->pluck('session');
        $terms = \App\Models\SchoolFee::select('term')->distinct()->pluck('term');
        $school = School::first();

        return view(
            'admin.statements.owing-report',
            compact('receipts', 'terms', 'sessions', 'totalPaid', 'totalDebt')
        );
    }

    public function sec_owingReport(Request $request)
{
    $term    = $request->input('term');
    $session = $request->input('session');

    // 🔎 Filter receipts (students who still owe)
    $query = StudentReceipts::with('student', 'payments')
        ->where('amount_due', '>', 0)
        ->whereHas('student', function ($q) {
            $q->where('schooltype', 'secondary')
              ->where('category', 'student');
        });

    if ($term) {
        $query->where('term', $term);
    }

    if ($session) {
        $query->where('session', $session);
    }

    $receipts = $query->get();

    // 🔢 Summary for chart
    $totalPaid = $receipts->sum('amount_paid');
    $totalDebt = $receipts->sum('amount_due');

    if ($request->has('download') && $request->download === 'pdf') {
        $school = \App\Models\School::first();

        // Fix invalid filename by removing slashes
        $cleanSession = str_replace(['/', '\\'], '-', $session);
        $cleanTerm = str_replace(['/', '\\'], '-', $term);

        $pdf = \PDF::loadView(
            'admin.statements.owing-pdf',
            compact('receipts', 'school', 'term', 'session', 'totalPaid', 'totalDebt')
        );

        return $pdf->download("owing_report_{$cleanTerm}_{$cleanSession}.pdf");
    }


    $sessions = \App\Models\SchoolFee::select('session')->distinct()->pluck('session');
    $terms = \App\Models\SchoolFee::select('term')->distinct()->pluck('term');
    $school = School::first();

    return view(
        'admin.statements.owing-report',
        compact('receipts', 'terms', 'sessions', 'totalPaid', 'totalDebt')
    );
}


    public function owingStudentsPdf(Request $request)
    {
        $data = $this->owingStudents($request)->getData(); // reuse logic
        $pdf = \PDF::loadView('backend.reports.pdf.owing_students', (array)$data);
        return $pdf->download('owing-students-report.pdf');
    }
}
