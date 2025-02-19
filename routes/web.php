<?php

use App\Http\Controllers\AccountantController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CeoController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', [HomeController::class, 'test'])->name('test');
Route::post('/test', [HomeController::class, 'api'])->name('api');
//Route::post('/test', [HomeController::class, 'process'])->name('process');

Route::get('/', [LoginController::class, 'login'])->name('login')->middleware('login');
Route::post('/', [LoginController::class, 'processLogin'])->name('process_login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/employees/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');
Route::post('/employees/attendance_api', [EmployeeController::class, 'attendance_api'])->name('employees.attendance_api');
Route::put('/employees/checkin', [EmployeeController::class, 'checkin'])->name('employees.checkin');
Route::put('/employees/checkin', [EmployeeController::class, 'checkin'])->name('employees.checkin');
Route::put('/employees/checkout', [EmployeeController::class, 'checkout'])->name('employees.checkout');

Route::put('/managers/checkin', [ManagerController::class, 'checkin'])->name('managers.checkin');
Route::put('/managers/checkout', [ManagerController::class, 'checkout'])->name('managers.checkout');
Route::get('/managers/attendance', [ManagerController::class, 'attendance'])->name('managers.attendance');

Route::put('/accountants/checkin', [AccountantController::class, 'checkin'])->name('accountants.checkin');
Route::put('/accountants/checkout', [AccountantController::class, 'checkout'])->name('accountants.checkout');
Route::get('/accountants/attendance', [AccountantController::class, 'attendance'])->name('accountants.attendance');

Route::get('/ceo/time', [CeoController::class, 'time'])->name('ceo.time');
Route::post('/ceo/time_change/', [CeoController::class, 'time_change'])->name('ceo.time_change');
Route::post('/ceo/time_save/', [CeoController::class, 'time_save'])->name('ceo.time_save');
Route::get('/ceo/fines', [FinesController::class, 'index'])->name('ceo.fines');
Route::post('/ceo/fines_store', [FinesController::class, 'store'])->name('ceo.fines_store');
Route::post('/ceo/fines_update', [FinesController::class, 'update'])->name('ceo.fines_update');
Route::post('/ceo/pay_rate_api', [CeoController::class, 'pay_rate_api'])->name('ceo.pay_rate_api');
Route::post('/ceo/pay_rate_change/', [CeoController::class, 'pay_rate_change'])->name('ceo.pay_rate_change');
Route::post('/ceo/pay_rate_store/', [CeoController::class, 'pay_rate_store'])->name('ceo.pay_rate_store');
Route::post('/ceo/manager_name', [CeoController::class, 'manager_name'])->name('ceo.manager_name');
Route::get('/ceo/department', [DepartmentController::class, 'index'])->name('ceo.department');
Route::post('/ceo/department_employees', [DepartmentController::class, 'department_employees'])->name('ceo.department_employees');
Route::post('/ceo/department/store', [DepartmentController::class, 'store'])->name('ceo.department.store');
Route::post('/ceo/department/update', [DepartmentController::class, 'update'])->name('ceo.department.update');
Route::post('/ceo/department/manager_role', [DepartmentController::class, 'manager_role'])->name('ceo.department.manager_role');
Route::post('/ceo/information', [DepartmentController::class, 'information'])->name('ceo.information');
Route::get('/ceo/create_emp', [CeoController::class, 'create_emp'])->name('ceo.create_emp');


Route::resource('employees', EmployeeController::class)->except([
    'show',
]);
Route::resource('accountants', AccountantController::class)->except([
    'show',
]);
Route::resource('managers', ManagerController::class)->except([
    'show',
]);
Route::resource('ceo', CeoController::class)->except([
    'show',
]);
