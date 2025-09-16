This receipt creation logic is not efficient. there's the scenario: assuming the total_expected for Grade 3 is 90000, student pay 65000 initially covering tuition 50000, exam_fee 5000, uniform 10000. the method should save it. 

Let's say student now make a next payment another date 15000. the system create receipt for the new payment, deducting what is newly paid from outstanding balance showing a new balance due. This should continue till all payment is fully made.

So every time we want to download or send receipt via mail to the student, it should capture all the payment history for that term and session.

Here's the current method:
public function store(Request $request, $studentId)
{
    $student = User::findOrFail($studentId);

    // Pull active session and term from School table
    $school = School::first();
    $currentSession = $school->session ?? null;
    $currentTerm    = $school->term ?? null;

    // Get expected fee for this student's class/term/session
    $expectedFee = SchoolFee::where('class', $student->class)
        ->where('term', $currentTerm)
        ->where('session', $currentSession)
        ->first();

    $totalExpected = $expectedFee ? $expectedFee->total : 0;

    // Create receipt
    $receipt = StudentReceipts::create([
        'student_id'     => $student->id,
        'term'           => $currentTerm,
        'session'        => $currentSession,
        'tuition'        => $request->tuition,
        'uniform'        => $request->uniform,
        'exam_fee'       => $request->exam_fee,
        'discount'       => $request->discount ?? 0,
        'total_expected' => $totalExpected,
        'amount_paid'    => $request->amount_paid,
        'amount_due'     => $totalExpected - ($request->discount ?? 0) - $request->amount_paid,
    ]);

    // Save first payment
    StudentPayments::create([
        'receipt_id'     => $receipt->id,
        'amount_paid'    => $request->amount_paid,
        'payment_method' => $request->payment_method,
        'payment_date'   => now(),
    ]);

    return redirect()->route('students.index')->with('success', 'Receipt created successfully!');
}

I also need a method that can enable admin and clerk to send reminder email to the student. It should capture the payment history and the balance due