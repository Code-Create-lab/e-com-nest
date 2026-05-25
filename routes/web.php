<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){

    return view('index');
});
Route::redirect('/admin', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('customers', CustomerController::class);

    Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
    Route::resource('leads', LeadController::class);

    Route::resource('projects', ProjectController::class);

    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::post('/projects/{project}/tasks/bulk', [TaskController::class, 'bulkStore'])->name('projects.tasks.bulk');
    Route::post('/projects/{project}/tasks/group-action', [TaskController::class, 'groupAction'])->name('projects.tasks.group-action');
    Route::patch('/projects/{project}/tasks/reorder', [TaskController::class, 'reorder'])->name('projects.tasks.reorder');
    Route::patch('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])->name('projects.tasks.update');
    Route::patch('/projects/{project}/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('projects.tasks.toggle');
    Route::delete('/projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])->name('projects.tasks.destroy');

    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::resource('invoices', InvoiceController::class);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
