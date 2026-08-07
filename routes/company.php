<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyInvitationController;
use App\Http\Controllers\CompanySwitchController;
use App\Http\Controllers\DataBreachController;
use App\Http\Controllers\DpiaController;
use App\Http\Controllers\SubjectAccessRequestController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Onboarding routes: reachable even before a company is selected.
// These must NOT sit behind ensure.company.selected — a brand-new user
// with no company would otherwise be redirected in a loop trying to
// reach the very page that lets them create their first one. This is
// exactly the bug that caused "companies.create" to throw a
// RouteNotFoundException before this route existed.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');

    Route::get('/invitations/{token}/accept', [CompanyInvitationController::class, 'accept'])
        ->name('invitations.accept');
});

// Tenant routes: require an active company in session.
Route::middleware(['auth', 'verified', 'ensure.company.selected'])->group(function () {

    Route::put('/companies/switch/{company}', [CompanySwitchController::class, 'update'])
        ->name('companies.switch');

    Route::get('/companies/members', [CompanyInvitationController::class, 'index'])
        ->name('companies.members');
    Route::post('/invitations', [CompanyInvitationController::class, 'store'])
        ->name('invitations.store');
    Route::delete('/invitations/{invitation}', [CompanyInvitationController::class, 'destroy'])
        ->name('invitations.destroy');

    Route::resource('sars', SubjectAccessRequestController::class)
        ->parameters(['sars' => 'sar']);

    Route::resource('data-breaches', DataBreachController::class)
        ->parameters(['data-breaches' => 'breach'])
        ->names('breaches');

    Route::resource('dpias', DpiaController::class);

    Route::resource('suppliers', SupplierController::class);
});
