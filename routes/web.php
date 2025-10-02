<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PatientRegisterController;
use App\Http\Controllers\Admin\DoctorRegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;

// Admin
use App\Http\Controllers\Admin\DoctorApprovalController;
use App\Http\Controllers\Admin\AdminPatientController;
use App\Http\Controllers\Admin\MedicalRecordController;
use App\Http\Controllers\Admin\PatientAppointmentController;

// Doctor
use App\Http\Controllers\DoctorController;

// Laboratory
use App\Http\Controllers\LaboratoryRequestController;

// Guest Routes
Auth::routes(['verify' => true]);

Route::get('/', fn() => view('welcome'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/terms', fn() => view('terms'))->name('terms');
});

// Email Verification Routes
Route::get('/verify-account/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (!URL::hasValidSignature($request)) abort(403, 'Invalid or expired verification link.');
    if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) abort(403, 'Invalid verification hash.');

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    Auth::login($user);

    return match ($user->role ?? 'patient') {
        'admin' => redirect()->route('admin.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        default => redirect()->route('patient.dashboard'),
    };
})->name('custom.verify');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('redirect.by.role');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/resend', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')->name('verification.resend');

    Route::get('/redirect-by-role', function () {
        return match (Auth::user()->role ?? 'patient') {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            default => redirect()->route('patient.dashboard'),
        };
    })->name('redirect.by.role');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/password/change', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AppointmentController::class, 'dashboard'])->name('dashboard');

    // Appointments
    Route::get('appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{id}/{status}', [PatientAppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Patient Management
    Route::get('patients', [AdminPatientController::class, 'index'])->name('patients.index');
    Route::get('patients/create', [AdminPatientController::class, 'create'])->name('patients.create');
    Route::post('patients', [AdminPatientController::class, 'store'])->name('patients.store');

    // Medical Records
    Route::get('patients/{patient}/records', [MedicalRecordController::class, 'index'])->name('records.index');
    Route::get('patients/{patient}/records/create', [MedicalRecordController::class, 'create'])->name('records.create');
    Route::post('patients/{patient}/records/manual', [MedicalRecordController::class, 'storeManual'])->name('records.store');
    Route::get('records/{record}', [MedicalRecordController::class, 'show'])->name('records.show');
    Route::get('records/{record}/print', [MedicalRecordController::class, 'print'])->name('records.print');

    // Doctor 
    Route::get('doctors', [DoctorRegisterController::class, 'index'])->name('doctors.index'); // List doctors
    Route::get('doctors/create', [DoctorRegisterController::class, 'create'])->name('doctors.create');
    Route::post('doctors', [DoctorRegisterController::class, 'store'])->name('doctors.store');

    // Vital Signs
    Route::get('patients/{patient}/appointments/{appointment}/vitals/create', [MedicalRecordController::class, 'createVitals'])->name('vitals.create');
    Route::post('patients/{patient}/appointments/{appointment}/vitals', [MedicalRecordController::class, 'storeVitals'])->name('vitals.store');

});

// Doctor Routes
Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::get('dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('schedule', [DoctorController::class, 'schedule'])->name('schedule');
    Route::post('schedule/update', [DoctorController::class, 'updateSchedule'])->name('schedule.update');
    Route::get('{doctor}/available-slots', [DoctorScheduleController::class, 'availableSlots']);

    // Medical Records
    Route::get('records', [DoctorController::class, 'index'])->name('records.index');
    Route::get('records/{record}', [DoctorController::class, 'show'])->name('records.show');
    Route::put('records/{record}', [DoctorController::class, 'update'])->name('records.update');
    Route::post('records/{appointment}', [DoctorController::class, 'store'])->name('records.store');

    // Reminders
    Route::get('reminders', [App\Http\Controllers\Doctor\ReminderController::class, 'index'])->name('reminders');
});

// Patient Routes
Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // Appointments
    Route::get('appointments/reminders', [AppointmentController::class, 'reminders'])->name('reminders');

    // Profile
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('profile/store', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Public Appointment Routes
Route::get('appointments/create/{doctor}', [AppointmentController::class, 'create'])->name('appointments.create');
Route::get('appointments/history', [AppointmentController::class, 'history'])->name('appointments.history');
Route::post('appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
Route::get('appointments/{appointment}/reschedule', [AppointmentController::class, 'edit'])->name('appointments.reschedule');
Route::patch('appointments/{appointment}/reschedule', [AppointmentController::class, 'updateReschedule'])->name('appointments.updateReschedule');

// Patient Registration
Route::get('register/patient', [PatientRegisterController::class, 'showRegistrationForm'])->name('patient.register');
Route::post('register/patient', [PatientRegisterController::class, 'register'])->name('register.patient');

// Laboratory Routes
Route::get('doctor/lab-requests', [LaboratoryRequestController::class, 'doctorIndex'])->name('doctor.laboratory.requests');
Route::get('requests/create/{patient}', [LaboratoryRequestController::class, 'create'])->name('laboratory.create');
Route::get('patients/{patient}/laboratory', [LaboratoryRequestController::class, 'create'])->name('laboratory.create');
Route::post('laboratory/store', [LaboratoryRequestController::class, 'store'])->name('laboratory.store');
Route::get('laboratory/requests', [LaboratoryRequestController::class, 'index'])->name('laboratory.index');
Route::get('laboratory/requests/{id}', [LaboratoryRequestController::class, 'show'])->name('laboratory.show');
Route::put('laboratory/requests/{id}', [LaboratoryRequestController::class, 'update'])->name('laboratory.update');
Route::get('laboratory/patient/{patientId}/appointment/{appointmentId?}', [LaboratoryRequestController::class, 'indexPatientLabRequests'])->name('laboratory.patientRequests');
Route::post('laboratory/attach-result/{id}', [LaboratoryRequestController::class, 'attachResult'])->name('laboratory.attachResult');
// routes/web.php

use App\Http\Controllers\NotificationController;

Route::middleware(['auth'])->group(function () {
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    
    // Optional: route to fetch unread notifications (already used in polling)
    Route::get('/notifications/unread', [NotificationController::class, 'fetchUnread'])->name('notifications.fetchUnread');
});
Route::post('patients/storeManual', [App\Http\Controllers\Admin\PatientRecordsController::class, 'storeManual'])
        ->name('patients.storeManual');
// Show editable consultation form
Route::get('records/{record}/edit', [DoctorController::class, 'show'])
    ->name('doctor.records.edit');

// Show record (read-only)
Route::get('records/{record}', [DoctorController::class, 'showRecord'])
    ->name('doctor.records.show');

// Update consultation record
Route::put('records/{record}', [DoctorController::class, 'update'])
    ->name('doctor.records.update');
Route::get('records/{record}/edit', [DoctorController::class, 'show'])
    ->name('doctor.records.form');
    // routes/web.php

Route::get('/appointments/{id}', [AppointmentController::class, 'show'])
    ->name('appointments.show');
use App\Http\Controllers\AppointmentReminderController;

Route::post('/admin/appointments/trigger-reminders', [AppointmentReminderController::class, 'triggerAll'])
    ->name('admin.appointments.triggerReminders');


Route::get('/notifications/fetch-unread', function () {
    $user = Auth::user();
    return response()->json([
        'count' => $user->unreadNotifications->count(),
        'notifications' => $user->unreadNotifications->map(function ($n) {
            return [
                'id' => $n->id,
                'appointment_id' => $n->data['appointment_id'] ?? null,
                'message' => $n->data['message'] ?? '',
            ];
        }),
    ]);
})->name('notifications.fetchUnread')->middleware('auth');

Route::prefix('admin')->name('admin.')->group(function () {
    

    Route::post('/patients/store-manual', [PatientController::class, 'storeManual'])
        ->name('patients.storeManual');
});
