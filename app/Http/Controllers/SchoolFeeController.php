<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentReceipt;
use App\Models\StudentPayment;
use App\Models\SchoolFee;

class SchoolFeeController extends Controller
{
    public function index()
{
    $fees = SchoolFee::all();
    return view('students.fees', compact('fees'));
}

public function store(Request $request)
{
    SchoolFee::create([
        'class'   => $request->class,
        'term'    => $request->term,
        'session' => $request->session,
        'tuition' => $request->tuition,
        'uniform' => $request->uniform,
        'exam_fee'=> $request->exam_fee,
        'total'   => $request->tuition + $request->uniform + $request->exam_fee,
    ]);

    return back()->with('success', 'Fee setup saved!');
}

public function edit($id)
{
    $fee = SchoolFee::findOrFail($id);
    return view('students.fee.edit', compact('fee'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'class' => 'required|string',
        'term' => 'required|string',
        'session' => 'required|string',
        'tuition' => 'required|numeric',
        'uniform' => 'nullable|numeric',
        'exam_fee' => 'nullable|numeric',
    ]);

    $fee = SchoolFee::findOrFail($id);

    $total = ($request->tuition ?? 0) + ($request->uniform ?? 0) + ($request->exam_fee ?? 0);

    $fee->update([
        'class' => $request->class,
        'term' => $request->term,
        'session' => $request->session,
        'tuition' => $request->tuition,
        'uniform' => $request->uniform,
        'exam_fee' => $request->exam_fee,
        'total' => $total,
    ]);

    return redirect()->route('admin.fees.index')->with('success', 'Fee structure updated successfully.');
}

public function destroy($id)
{
    $fee = SchoolFee::findOrFail($id);
    $fee->delete();

    return redirect()->route('admin.fees.index')->with('success', 'Fee structure deleted successfully.');
}


}
