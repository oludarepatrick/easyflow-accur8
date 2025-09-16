<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomRegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentReceiptController;
use App\Http\Controllers\SchoolFeeController;
use App\Http\Controllers\StatementController;

// ========================
// Authentication Routes
// ========================

// Root should go to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Show login form
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest:admin')->name('login');

// Handle login (AuthController)
Route::post('/login', [AuthController::class, 'login'])->middleware('guest:admin')->name('login.submit');

// Handle logout
//Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:admin')->name('logout');
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');

// ========================
// Dashboards
// ========================
Route::middleware('auth:admin')->group(function () {
    // Main dashboard (redirects based on role inside controller if needed)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Role-specific dashboards
    Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/clerk/dashboard', fn() => view('dashboard.clerk'))->name('clerk.dashboard');
});

});

// ========================
// Profile (admins/clerks only)
// ========================
Route::middleware('auth:admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================
// Admin-only routes
// ========================
Route::middleware(['auth:admin', 'role:admin'])->group(function () {
    // put admin-specific routes here
});

// ========================
// Clerk-only routes
// ========================
Route::middleware(['auth:admin', 'role:clerk'])->group(function () {
    // put clerk-specific routes here
});

// ========================
// Student & Staff Registration
// ========================
Route::get('/registration', [CustomRegisterController::class, 'showForm'])->name('register.form');
Route::post('/registration', [CustomRegisterController::class, 'store'])->name('register.store');

Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');





Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('auth:admin')->name('admin.dashboard');

// ======================= STUDENTS RECEIPTS =======================
Route::prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('students.index');

    // Receipt routes
    Route::get('{id}/receipts/create', [StudentReceiptController::class, 'create'])->name('students.receipts.create');
    Route::post('{id}/receipts/store', [StudentReceiptController::class, 'store'])->name('students.receipts.store');
    Route::get('/students/receipts/{receipt}', [StudentReceiptController::class, 'show'])->name('students.receipts.show');

    // Add payment to an existing receipt
    Route::post('receipts/{id}/add-payment', [StudentReceiptController::class, 'addPayment'])->name('students.receipts.addPayment');
});
Route::get('students/receipts/{id}/pdf', [StudentReceiptController::class, 'downloadPdf'])->name('students.receipts.pdf');
Route::post('/students/receipts/{id}/email', [StudentReceiptController::class, 'emailReceipt'])->name('students.receipts.email');
Route::post('/students/receipts/{id}/reminder', [StudentReceiptController::class, 'sendPaymentReminder'])->name('students.receipts.reminder');



// ======================= ADMIN FEES SETUP =======================
Route::prefix('admin/fees')->group(function () {
    Route::get('/', [SchoolFeeController::class, 'index'])->name('admin.fees.index');
    Route::post('/store', [SchoolFeeController::class, 'store'])->name('admin.fees.store');
});

Route::prefix('admin')->group(function () {
    Route::get('/fees', [SchoolFeeController::class, 'index'])->name('admin.fees.index');
    Route::post('/fees', [SchoolFeeController::class, 'store'])->name('admin.fees.store');
    Route::get('/fees/{id}/edit', [SchoolFeeController::class, 'edit'])->name('admin.fees.edit');
    Route::put('/fees/{id}', [SchoolFeeController::class, 'update'])->name('admin.fees.update');
    Route::delete('/fees/{id}', [SchoolFeeController::class, 'destroy'])->name('admin.fees.destroy');
});

Route::middleware(['auth:admin','role:admin'])->prefix('admin')->group(function () {
    Route::get('statements/payments', [StatementController::class, 'index'])->name('admin.statements.payments');
    Route::get('statements/payments/export/pdf', [StatementController::class, 'exportPdf'])->name('admin.statements.payments.pdf');
    Route::post('statements/payments/email', [StatementController::class, 'emailStatement'])->name('admin.statements.payments.email');
});


// Breeze/Fortify authentication routes (users, not admins)
require __DIR__.'/auth.php';
