<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    $user = User::create([
        'firstname' => $validated['firstname'],
        'lastname'  => $validated['lastname'],
        'email'     => $validated['email'],
        'phone'     => $validated['phone'] ?? null,
        'category'  => $validated['category'],
        'class'     => $validated['category'] === 'student' ? $validated['class'] : null,
        'term'      => $settings->term ?? null,
        'session'   => $settings->session ?? null,
        'status'    => 'active',
    ]);

    $mergeData = [
        'firstname' => $user->firstname,
        'lastname'  => $user->lastname,
        'class'     => $user->class ?? 'N/A',
        'term'      => $user->term ?? 'N/A',
        'category'      => $user->category ?? 'N/A',
        'session'   => $user->session ?? 'N/A',
        'date'      => Carbon::now()->format('Y-m-d'),
    ];

    try {
        // --- 1. Notify school admin ---
        Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->timeout(30)
            ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                'template_key' => 'new-registration',
                'from' => [
                    'address' => 'development@leverpay.io',
                    'name'    => 'School Admin'
                ],
                'to' => [
                    ['email_address' => ['address' => 'tenak09@gmail.com']]
                ],
                'merge_info' => $mergeData
            ]);
    } catch (\Exception $e) {
        Log::error('Failed to send admin registration email: ' . $e->getMessage());
    }

    try {
        // --- 2. Notify newly registered user ---
        Http::withoutVerifying()
            ->withHeaders([
                'authorization' => 'Zoho-enczapikey ' . env('ZEPTOMAIL_API_KEY'),
                'accept'        => 'application/json',
                'content-type'  => 'application/json',
            ])->timeout(30)
            ->post(env('ZEPTOMAIL_URL') . '/v1.1/email/template', [
                'template_key' => 'onboarding-notice',
                'from' => [
                    'address' => 'development@leverpay.io',
                    'name'    => 'School Management Team'
                ],
                'to' => [
                    ['email_address' => ['address' => $user->email]]
                ],
                'merge_info' => $mergeData
            ]);
    } catch (\Exception $e) {
        Log::error('Failed to send onboarding email: ' . $e->getMessage());
    }

    //return redirect()->route('register.form')->with('success', 'Registration successful. Emails sent.');
    return back()->with('success', 'Registration successful. Emails sent.');

}
}

