<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\AgeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Mail\UserReportEmail;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'show_users'])->name('myusers');
Route::get('/users/export', [UserController::class, 'exportToExcel'])->name('users.export');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
//Route::get('/users/{id}/pdf', [UserController::class, 'showPDF'])->name('user.pdf');
Route::get('/pdf/generate/{id}', [PDFController::class, 'generatePDF'])->name('user.pdf');;
Route::post('/users/generate-multiple-pdf', [UserController::class, 'generateMultiplePDF'])->name('user.generateMultiplePDF');
Route::get('/users/download-zip', [UserController::class, 'downloadZip'])->name('user.downloadZip');
// In routes/web.php
Route::get('/getages',[AgeController::class, 'index'])->name('ages.index');
Route::post('/add/ages',[AgeController::class, 'save'])->name('ages.save');
//Route::post('/your-route/save', 'AgeController@save')->name('age.save');

Route::get('/noaccess',function()
{
    return view('noaccess');
});

//sending user mails
Route::get('/sendmail', function () {
      // Send email with the PDF attached
      Mail::to("tgrkelvins@gmail.com")
      ->send(new UserReportEmail());
});

Route::post('/users/send-emails', [UserController::class, 'generateMultiplePDF_and_mail'])->name('user.sendEmails');

//allowances routes
//Route::get('/allowances', [AllowanceController::class, 'index']);
Route::get('/allowances', [UserController::class, 'index'])->name('users.allowances')->middleware('agecheck');
Route::get('/allowances/data', [UserController::class, 'getData'])->name('users.getData');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
