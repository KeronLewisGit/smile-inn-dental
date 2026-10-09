<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// Public site
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/clinic', [SiteController::class, 'clinic'])->name('clinic');
Route::get('/team', [SiteController::class, 'team'])->name('team');
Route::get('/team/{member}', [SiteController::class, 'member'])->name('team.show');
Route::get('/testimonials', [SiteController::class, 'testimonials'])->name('testimonials');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::get('/privacy', [SiteController::class, 'privacy'])->name('privacy');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/invisalign', [ServiceController::class, 'invisalign'])->name('invisalign');
Route::get('/emergency', [ServiceController::class, 'emergency'])->name('emergency');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/book', [BookingController::class, 'create'])->name('book');
Route::get('/book/slots', [BookingController::class, 'slots'])->middleware('throttle:60,1')->name('book.slots');
Route::post('/book', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('book.store');
Route::get('/book/done/{token}', [BookingController::class, 'done'])->name('book.done');
Route::get('/appointment/{token}', [BookingController::class, 'manage'])->name('book.manage');
Route::post('/appointment/{token}/cancel', [BookingController::class, 'cancel'])->name('book.cancel');

Route::post('/contact', [InquiryController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:6,1')->name('newsletter.store');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'show'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
    });

    Route::middleware(['auth', 'staff'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('calendar', [Admin\AppointmentController::class, 'calendar'])->name('calendar');
        Route::resource('appointments', Admin\AppointmentController::class)->except('edit');
        Route::patch('appointments/{appointment}/status', [Admin\AppointmentController::class, 'status'])->name('appointments.status');
        Route::get('appointments-slots', [Admin\AppointmentController::class, 'slots'])->name('appointments.slots');

        Route::resource('patients', Admin\PatientController::class)->except('destroy');
        Route::resource('inquiries', Admin\InquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('subscribers', [Admin\SubscriberController::class, 'index'])->name('subscribers.index');
        Route::get('subscribers/export', [Admin\SubscriberController::class, 'export'])->name('subscribers.export');
        Route::delete('subscribers/{subscriber}', [Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy');

        Route::resource('team', Admin\TeamController::class)->except('show');
        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::resource('appointment-types', Admin\AppointmentTypeController::class)->except('show')->parameters(['appointment-types' => 'type']);

        Route::middleware('staff:admin')->group(function () {
            Route::get('settings', [Admin\SettingsController::class, 'edit'])->name('settings');
            Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
            Route::resource('users', Admin\UserController::class)->only(['index', 'store', 'update', 'destroy']);
        });
    });
});
