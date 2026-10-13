<?php

use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ModerationController as AdminModerationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\EventCatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Organizer\CertificateController as OrganizerCertificateController;
use App\Http\Controllers\Organizer\CheckinController as OrganizerCheckinController;
use App\Http\Controllers\Organizer\CommitteeController as OrganizerCommitteeController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\ParticipantController as OrganizerParticipantController;
use App\Http\Controllers\ParticipantDashboardController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventCatalogController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventCatalogController::class, 'show'])->name('events.show');

// Public Certificate Verification (FR-14)
Route::get('/verify-certificate', [CertificateVerificationController::class, 'index'])->name('certificates.verify');
Route::get('/verify-certificate/{code}', [CertificateVerificationController::class, 'show'])->name('certificates.verify.show');

/*
|--------------------------------------------------------------------------
| Authentication (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // In-app Notifications (FR-16)
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

    // Participant Booking & Checkout
    Route::get('/events/{event}/checkout/{ticket}', [RegistrationController::class, 'checkout'])->name('events.checkout');
    Route::post('/events/{event}/checkout/{ticket}', [RegistrationController::class, 'store'])->name('events.checkout.store');

    // Participant Dashboard
    Route::prefix('participant')->name('participant.')->group(function () {
        Route::get('/tickets', [ParticipantDashboardController::class, 'tickets'])->name('tickets.index');
        Route::get('/tickets/{registration}', [ParticipantDashboardController::class, 'showTicket'])->name('tickets.show');
        Route::get('/tickets/{registration}/download', [ParticipantDashboardController::class, 'downloadTicket'])->name('tickets.download');

        Route::get('/certificates', [ParticipantDashboardController::class, 'certificates'])->name('certificates.index');
        Route::post('/certificates/{registration}/claim', [ParticipantDashboardController::class, 'claimCertificate'])->name('certificates.claim');
        Route::get('/certificates/{certificate}/download', [ParticipantDashboardController::class, 'downloadCertificate'])->name('certificates.download');

        Route::get('/profile', [ParticipantDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [ParticipantDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // Event Reviews (FR-15)
    Route::post('/events/{event}/reviews', [ParticipantDashboardController::class, 'storeReview'])->name('events.reviews.store');

    /*
    |--------------------------------------------------------------------------
    | Organizer Routes (Role: organizer, admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:organizer,admin')->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');

        // Events CRUD & Tickets
        Route::get('/events', [OrganizerEventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('events.create');
        Route::post('/events', [OrganizerEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [OrganizerEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [OrganizerEventController::class, 'destroy'])->name('events.destroy');
        Route::post('/events/{event}/submit', [OrganizerEventController::class, 'submitForReview'])->name('events.submit');
        Route::post('/events/{event}/status', [OrganizerEventController::class, 'updateStatus'])->name('events.status');

        Route::get('/events/{event}/tickets', [OrganizerEventController::class, 'tickets'])->name('events.tickets');
        Route::post('/events/{event}/tickets', [OrganizerEventController::class, 'storeTicket'])->name('events.tickets.store');
        Route::delete('/events/{event}/tickets/{ticket}', [OrganizerEventController::class, 'destroyTicket'])->name('events.tickets.destroy');

        // Participant Management & Payment Verifications (FR-11, FR-08)
        Route::get('/events/{event}/participants', [OrganizerParticipantController::class, 'index'])->name('participants.index');
        Route::post('/payments/{payment}/verify', [OrganizerParticipantController::class, 'verifyPayment'])->name('payments.verify');
        Route::get('/events/{event}/participants/export', [OrganizerParticipantController::class, 'export'])->name('participants.export');

        // QR Check-in Scanner (FR-10)
        Route::get('/events/{event}/scanner', [OrganizerCheckinController::class, 'scanner'])->name('checkin.scanner');
        Route::post('/events/{event}/scan', [OrganizerCheckinController::class, 'processScan'])->name('checkin.scan');

        // Committee Delegation (FR-12)
        Route::get('/events/{event}/committee', [OrganizerCommitteeController::class, 'index'])->name('committee.index');
        Route::post('/events/{event}/committee', [OrganizerCommitteeController::class, 'store'])->name('committee.store');
        Route::delete('/events/{event}/committee/{member}', [OrganizerCommitteeController::class, 'destroy'])->name('committee.destroy');

        // Certificate Management (FR-13)
        Route::get('/events/{event}/certificates', [OrganizerCertificateController::class, 'index'])->name('certificates.index');
        Route::post('/registrations/{registration}/certificate', [OrganizerCertificateController::class, 'generate'])->name('certificates.generate');
        Route::post('/events/{event}/certificates/generate-all', [OrganizerCertificateController::class, 'generateAll'])->name('certificates.generate_all');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Role: admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Event Moderation (FR-19)
        Route::get('/moderation', [AdminModerationController::class, 'index'])->name('moderation.index');
        Route::get('/moderation/{event}', [AdminModerationController::class, 'show'])->name('moderation.show');
        Route::post('/moderation/{event}/approve', [AdminModerationController::class, 'approve'])->name('moderation.approve');
        Route::post('/moderation/{event}/reject', [AdminModerationController::class, 'reject'])->name('moderation.reject');

        // Master Categories (FR-04)
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role');

        // Audit Logs (FR-20)
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit_logs.index');
    });
});
