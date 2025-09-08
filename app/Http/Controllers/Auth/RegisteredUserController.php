<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\School;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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

    return redirect()->route('register')->with('success', 'Registration successful.');
}


}
