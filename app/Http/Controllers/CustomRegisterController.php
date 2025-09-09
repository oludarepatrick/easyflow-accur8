<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CustomRegisterController extends Controller
{
     public function showForm()
    {
        return view('auth.register'); // <-- your Blade
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string',
            'category'  => 'required|in:student,staff',
            'class'     => 'required_if:category,student|nullable|string|max:50',
        ]);

        // Fetch school term & session from settings table
        $settings = School::first();

        User::create([
            'firstname'   => $validated['firstname'],
            'lastname'    => $validated['lastname'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'category'    => $validated['category'],
            'class'       => $validated['category'] === 'student' ? $validated['class'] : null,
            'term'        => $settings->term ?? null,
            'session'     => $settings->session ?? null,
            'status'      => 'active',
        ]);

         return redirect()->route('register.form')->with('success', 'Registration successful.');
    }
}

