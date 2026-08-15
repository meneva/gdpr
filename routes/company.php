<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyInvitationController;
use App\Http\Controllers\CompanySwitchController;
use App\Http\Controllers\DataBreachController;
use App\Http\Controllers\DpiaController;
use App\Http\Controllers\ProcessingActivityController;
use App\Http\Controllers\SubjectAccessRequestController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrainingCompletionController;
use App\Http\Controllers\TrainingCourseController;
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

    Route::get('sars/export/csv', [SubjectAccessRequestController::class, 'exportCsv'])->name('sars.export.csv');
    Route::get('sars/export/pdf', [SubjectAccessRequestController::class, 'exportPdf'])->name('sars.export.pdf');
    Route::resource('sars', SubjectAccessRequestController::class)
        ->parameters(['sars' => 'sar']);

    Route::get('data-breaches/export/csv', [DataBreachController::class, 'exportCsv'])->name('breaches.export.csv');
    Route::get('data-breaches/export/pdf', [DataBreachController::class, 'exportPdf'])->name('breaches.export.pdf');
    Route::resource('data-breaches', DataBreachController::class)
        ->parameters(['data-breaches' => 'breach'])
        ->names('breaches');

    Route::get('dpias/export/csv', [DpiaController::class, 'exportCsv'])->name('dpias.export.csv');
    Route::get('dpias/export/pdf', [DpiaController::class, 'exportPdf'])->name('dpias.export.pdf');
    Route::resource('dpias', DpiaController::class);

    Route::get('suppliers/export/csv', [SupplierController::class, 'exportCsv'])->name('suppliers.export.csv');
    Route::get('suppliers/export/pdf', [SupplierController::class, 'exportPdf'])->name('suppliers.export.pdf');
    Route::resource('suppliers', SupplierController::class);

    Route::get('processing-activities/export/csv', [ProcessingActivityController::class, 'exportCsv'])->name('processing-activities.export.csv');
    Route::get('processing-activities/export/pdf', [ProcessingActivityController::class, 'exportPdf'])->name('processing-activities.export.pdf');
    Route::resource('processing-activities', ProcessingActivityController::class)
        ->parameters(['processing-activities' => 'activity']);

    Route::get('training-courses/export/csv', [TrainingCourseController::class, 'exportCsv'])->name('training-courses.export.csv');
    Route::get('training-courses/export/pdf', [TrainingCourseController::class, 'exportPdf'])->name('training-courses.export.pdf');
    Route::resource('training-courses', TrainingCourseController::class)
        ->parameters(['training-courses' => 'course']);

    Route::post('training-courses/{course}/completions', [TrainingCompletionController::class, 'store'])
        ->name('training-completions.store');
    Route::patch('training-completions/{completion}/toggle', [TrainingCompletionController::class, 'toggleComplete'])
        ->name('training-completions.toggle');
    Route::put('training-completions/{completion}', [TrainingCompletionController::class, 'update'])
        ->name('training-completions.update');
    Route::delete('training-completions/{completion}', [TrainingCompletionController::class, 'destroy'])
        ->name('training-completions.destroy');
});
