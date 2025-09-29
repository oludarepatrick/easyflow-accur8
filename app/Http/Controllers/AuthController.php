<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffSalary;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            switch ($admin->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Welcome Admin!');
                case 'clerk':
                    return redirect()->route('clerk.dashboard')
                        ->with('success', 'Welcome Clerk!');
                default:
                    Auth::guard('admin')->logout();
                    return redirect()->route('login')
                        ->with('error', 'Unauthorized role.');
            }
        }

        return back()->with('error', 'Invalid login credentials.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}

