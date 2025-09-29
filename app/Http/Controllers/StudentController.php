<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Fetch current term and session from the school table
        $school = School::first(); // assuming one active school row
        $currentTerm = $school->term ?? null;
        $currentSession = $school->session ?? null;

        // Build query: only active students in current term & session
        $query = User::where('category', 'student')
                    ->where('status', 'active') // active
                    ->where('term', $currentTerm)
                    ->where('session', $currentSession);

        // Apply search if keyword exists
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(10); // paginate results

        return view('students.index', compact('students', 'currentTerm', 'currentSession'));
    }

    public function destroy($id)
    {
        $student = User::where('category', 'student')->findOrFail($id);

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    // Show edit form
   
    public function edit($id)
    {
        $student = User::where('category', 'student')->findOrFail($id);

        // Pull active session and term from School table
        $school = School::first();
        $currentSession = $school->session ?? null;
        $currentTerm    = $school->term ?? null;

        // Predefined dropdowns
        $classes = ['CRECHE','PREP','KG 1','KG 2','NURSERY 1','NURSERY 2','GRADE 1','GRADE 2','GRADE 3','GRADE 4','GRADE 5','GRADE 6',
                    'JSS 1','JSS 2','JSS 3','SSS 1','SSS 2','SSS 3'];
        $terms   = ['First Term','Second Term','Third Term'];
        $sessions = [];  
        for ($year = 2020; $year <= date('Y')+1; $year++) {
            $sessions[] = $year . '/' . ($year+1);
        }

        return view('students.edit', compact('student','classes','terms','sessions','currentSession','currentTerm'));
    }


    // Update student
    public function update(Request $request, $id)
    {
        $student = User::where('category', 'student')->findOrFail($id);

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $student->id,
            'phone'     => 'nullable|string|max:20',
            'class'     => 'required|string',
            'session'   => 'required|string',
            'term'      => 'required|string',
            'status'    => 'required|in:active,inactive',
        ]);

        $student->update($request->only([
            'firstname','lastname','email','phone','class','session','term','status'
        ]));

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
}

}
