<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeHousingController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\SendMailController;
use Illuminate\Support\Facades\Route;
use App\Mail\HelloMail;

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
})->name('welcome');
// Route::get('/', function () {
//     Mail::to('recipient@example.com')->send(new HelloMail());
// });

Route::get('/login', [UserController::class, 'loginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::get('/admin/dashboard', function () {
    return view('admin.home');
})->name('dashboard')->middleware('auth');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile.show');
Route::post('/admin/profile/update', [AdminController::class, 'update'])->name('admin.profile.update');
Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
Route::patch('admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
Route::get('/admin/staff/list', [AdminController::class, 'showsStaffList'])->name('staff.list');
Route::delete('/staff/{id}', [AdminController::class, 'destroyStaff'])->name('staff.destroy');
Route::get('/staff/{id}/edit', [AdminController::class, 'editStaff'])->name('staff.edit');
Route::put('/staff/{id}', [AdminController::class, 'updateStaff'])->name('staff.update');
Route::post('/staff', [AdminController::class, 'storeStaff'])->name('staff.store');
Route::get('/staff/{id}', [AdminController::class, 'showStaff'])->name('staff.show');



Route::prefix('admin/leave-types')->group(function () {
    Route::get('/', [LeaveTypeController::class, 'index'])->name('leave_types.index'); // List all leave types
    Route::post('/store', [LeaveTypeController::class, 'store'])->name('leave_types.store'); // Create new
    Route::get('/{leaveType}', [LeaveTypeController::class, 'show'])->name('leave_types.show'); // Show single leave type
    Route::put('/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave_types.update'); // Update
    Route::delete('/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave_types.destroy'); // Delete
});


Route::get('/positions/{position}/edit', [PositionController::class, 'edit']);
Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
Route::delete('/positions/{position}', [PositionController::class, 'destroy']);
Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
Route::get('/positions', [PositionController::class, 'index'])->name(name: 'positions.index');

Route::get('/admin/sendmails', [SendMailController::class, 'sendMailList'])->name('admin.sendmail.list');



Route::get('/employee/dashboard', function () {
    return view('employee.home');
})->name('employee.dashboard')->middleware('auth');


Route::get('/sendmail/create', [SendMailController::class, 'create'])->name('sendmail.create'); // show form
Route::post('/sendmail', [SendMailController::class, 'store'])->name('sendmail.store'); // store + send email
Route::post('/admin/sendbackmail', [SendMailController::class, 'sendBack'])->name('sendmail.sendback');

Route::get('/excel-upload', function () {
    return view('excel_form');
});

Route::post('/admin/excel-calculate', [AdminController::class, 'calculate'])->name('excel.calculate');
Route::get('/admin/employee_housing_request', [EmployeeHousingController::class, 'employeeHouseList'])->name('employee_housing_request.list');
Route::get('/admin/housing/{id}/show', [EmployeeHousingController::class, 'showHouseRequest'])->name('employee_housing.show');
Route::put('/admin/housing/{id}/approve', [EmployeeHousingController::class, 'approveHouseRequest'])->name('employee_housing.approve');
Route::put('/admin/housing/{id}/reject', [EmployeeHousingController::class, 'rejectHouseRequest'])->name('employee_housing.reject');



Route::get('/employee_housing_request', [EmployeeHousingController::class, 'createForm'])->name('employee_housing_request.index');
Route::post('/employee_housing_request', [EmployeeHousingController::class, 'storeForm'])->name('employee_housing_request.store');

Route::get('/employee/profile', [EmployeeController::class, 'showProfile'])->name('employee.profile.show');
Route::put('/employee/profile/image-update', [EmployeeController::class, 'updateImage'])->name('employee.profile.image.update');


