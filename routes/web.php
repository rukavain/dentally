<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\adminPanel\AdminController;
use App\Http\Controllers\adminPanel\ImageController;
use App\Http\Controllers\staffPanel\StaffController;
use App\Http\Controllers\clientPanel\ClientController;
use App\Http\Controllers\adminPanel\InventoryController;
use App\Http\Controllers\adminPanel\ProcedureController;
use App\Http\Controllers\dentistPanel\DentistController;
use App\Http\Controllers\patientPanel\PatientController;
use App\Http\Controllers\patientPanel\PaymentController;
use App\Http\Controllers\PaymentController as ControllersPaymentController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile-overview', [ProfileController::class, 'profileOverview'])->name('profile');
    // Route::patch('/profile/{user}', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'editProfile'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/appointments/add-walk-in', [AppointmentController::class, 'addWalkIn'])->name('add.walkIn');
    Route::get('/appointments/add-online/{patient}', [AppointmentController::class, 'addOnline'])->name('add.online');
    Route::post('/appointments', [AppointmentController::class, 'storeWalkIn'])->name('store.walkIn');
    Route::post('/appointments/{patient}', [AppointmentController::class, 'storeOnline'])->name('store.online');

    //Working Routes
    Route::get('/appointments/add-walk-in/dentists/{branch}', [DentistController::class, 'getDentists']); //Branch Select
    Route::get('/appointments/add-walk-in/schedules/{dentistId}', [DentistController::class, 'getSchedulesByDentist']);
    Route::get('/appointments/add-walk-in/timeslots/{scheduleId}', [DentistController::class, 'getAvailableTimeSlots']);
    Route::get('/appointments/add-walk-in/schedule/{scheduleId}', [DentistController::class, 'getScheduleDetails']);

    //Testing Routes
});

require __DIR__ . '/auth.php';


Route::get('/send-test-email', function () {
    Mail::to('giocode007@gmail.com')->send(new TestMail());
    return 'Email send successfully!';
});

// Route::get('/send-test-mail', function () {
//     Mail::send(new TestMail());
//     return 'Test email sent!';
// });

// Route::get('/send-mail', [EmailController::class, 'sendHelloEmail']);

Route::group(['middleware' => ['auth', 'verified', 'role:admin,staff,dentist']], function () {
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//remove dentist here
Route::group(['middleware' => ['auth', 'verified', 'role:admin,staff']], function () {
    Route::get('/patient-list', [PatientController::class, 'patient_list'])->name('patient_list');
    Route::get('/schedule', [AdminController::class, 'schedule'])->name('schedule');
    Route::get('/inventory', [InventoryController::class, 'inventory'])->name('inventory');
    Route::get('/procedure', [ProcedureController::class, 'procedure'])->name('procedure');
    Route::get('/branch', [AdminController::class, 'branch'])->name('branch');

    //Image Upload
    Route::post('/upload-image', [ImageController::class, 'uploadImage'])->name('upload.image');

    //Schedule
    Route::get('/admin/add-dentist-schedule', [ScheduleController::class, 'addSchedule'])->name('add.schedule');
    Route::post('/dentist-schedule', [ScheduleController::class, 'storeSchedule'])->name('store.schedule');
    Route::get('/schedule/{scheduleId}/edit', [ScheduleController::class, 'editSchedule'])->name('schedule.edit');
    Route::put('/schedule/{scheduleId}/update', [ScheduleController::class, 'updateSchedule'])->name('schedule.update');
    Route::delete('/schedule/{id}/delete', [ScheduleController::class, 'deleteSchedule'])->name('schedule.delete');
    Route::get('/show-schedule/{schedule}', [ScheduleController::class, 'show'])->name('show.schedule');

    // Route::get('/admin/scheduled-dates/{dentistId}', [ScheduleController::class, 'fetchScheduledDates']);
    Route::get('/scheduled-dates/{dentistId}', [ScheduleController::class, 'fetchScheduledDates']);

    //Payment TEsting
    Route::get('{id}/payment-list', [PaymentController::class, 'paymentList'])->name('payments.list');
    Route::get('/patient/{id}/payment', [PaymentController::class, 'create'])->name('payments.form');
    Route::post('/payments/{paymentId}/store', [PaymentController::class, 'storePartialPayment'])->name('payments.store');
    Route::get('/payments/{paymentId}/history', [PaymentController::class, 'showPaymentHistory'])->name('payments.history');

    Route::get('{id}/payment-list/pending', [PaymentController::class, 'pendingPayment'])->name('payments.pending');
    Route::post('/payments/{id}/approve', [PaymentController::class, 'approvePayment'])->name('payments.approve');


    //Testing
    Route::get('/appointments/show-appointment/{appointment}', [AppointmentController::class, 'show'])->name('show.appointment');
    Route::get('/appointments/walk-in-request', [AppointmentController::class, 'walkInAppointment'])->name('appointments.walkIn');
    Route::get('/appointments/online-request', [AppointmentController::class, 'onlineAppointment'])->name('appointments.online');

    //Archiving Patients
    Route::post('/archive-patient/{patient}', [PatientController::class, 'archivePatient'])->name('archive.patient');
    Route::post('/restore-patient/{patient}', [PatientController::class, 'restorePatient'])->name('restore.patient');

    // Patients
    Route::get('/add-patient', [PatientController::class, 'addPatient'])->name('add.patient');
    Route::post('/patients', [PatientController::class, 'storePatient'])->name('store.patient');
    Route::get('/edit-patient/{patient}', [PatientController::class, 'editPatient'])->name('edit.patient');
    Route::put('/patients/{patient}', [PatientController::class, 'updatePatient'])->name('update.patient');
    Route::get('/show-patient/{patient}', [PatientController::class, 'showPatient'])->name('show.patient');
    Route::get('/show-patient/{patient}/patient-contract', [PatientController::class, 'patientContract'])->name('patient.contract');
    Route::get('/show-patient/{patient}/patient-background', [PatientController::class, 'patientBackground'])->name('patient.background');
    Route::get('/show-patient/{patient}/patient-xray', [PatientController::class, 'patientXray'])->name('patient.xray');

    //Testing Patient
    Route::get('/active-patient-list', [PatientController::class, 'activePatient'])->name('patient.active');
    Route::get('/archived-patient-list', [PatientController::class, 'archivedPatient'])->name('patient.archived');

    //Procedures
    Route::get('/procedure/add', [ProcedureController::class, 'addProcedure'])->name('procedure.add');
    Route::post('/procedure/store', [ProcedureController::class, 'storeProcedure'])->name('procedure.store');
    Route::get('/procedure/{id}/edit', [ProcedureController::class, 'editProcedure'])->name('procedure.edit');
    Route::put('/procedure/{id}/update', [ProcedureController::class, 'updateProcedure'])->name('procedure.update');
    Route::delete('/procedure/{id}/delete', [ProcedureController::class, 'deleteProcedure'])->name('procedure.delete');

    //Branches
    Route::get('/branch/add', [AdminController::class, 'addBranch'])->name('branch.add');
    Route::post('/branch/store', [AdminController::class, 'storeBranch'])->name('branch.store');
    Route::get('/branch/{id}/edit', [AdminController::class, 'editBranch'])->name('branch.edit');
    Route::put('/branch/{id}/update', [AdminController::class, 'updateBranch'])->name('branch.update');
    Route::delete('/branch/{id}/delete', [AdminController::class, 'deleteBranch'])->name('branch.delete');

    //Inventories
    Route::get('/inventory/add/item', [InventoryController::class, 'addItem'])->name('item.add');
    Route::post('/inventory/store', [InventoryController::class, 'storeItem'])->name('item.store');
    Route::get('/inventory/edit/{id}', [InventoryController::class, 'editItem'])->name('item.edit');
    Route::put('/inventory/update/{id}', [InventoryController::class, 'updateItem'])->name('item.update');
    Route::delete('/inventory/delete/{id}', [InventoryController::class, 'deleteItem'])->name('item.delete');

    //Sale report
    Route::get('/sales-report', [AdminController::class, 'salesReport'])->name('sales');
});

// Admin Routes
Route::group(['middleware' => ['auth', 'verified', 'role:admin']], function () {
    //Navbar
    Route::get('/admin/dashboard', [AdminController::class, 'overview'])->name('admin.dashboard');
    Route::get('/admin/dentist', [AdminController::class, 'dentist'])->name('dentist');
    Route::get('/admin/staff', [AdminController::class, 'staff'])->name('staff');

    //Viewing user profile

    // Dentist
    Route::get('/admin/add-dentist', [DentistController::class, 'addDentist'])->name('add.dentist');
    Route::post('/dentist', [DentistController::class, 'storeDentist'])->name('store.dentist');
    Route::get('/admin/edit-dentist/{dentist}', [DentistController::class, 'editDentist'])->name('edit.dentist');
    Route::put('/dentists/{dentist}', [DentistController::class, 'updateDentist'])->name('update.dentist');
    Route::get('/admin/show-dentist/{dentist}', [DentistController::class, 'showDentist'])->name('show.dentist');

    // Staff
    Route::get('/admin/add-staff', [StaffController::class, 'addStaff'])->name('add.staff');
    Route::post('/staff', [StaffController::class, 'storeStaff'])->name('store.staff');
    Route::get('/admin/edit-staff/{staff}', [StaffController::class, 'editStaff'])->name('edit.staff');
    Route::put('/staffs/{staff}', [StaffController::class, 'updateStaff'])->name('update.staff');
    Route::get('/admin/show-staff/{staff}', [StaffController::class, 'showStaff'])->name('show.staff');

    //Audit Log
    Route::get('/audit-logs', [AdminController::class, 'viewAuditLogs'])->name('audit.logs');
});

// Staff Routes
Route::group(['middleware' => ['auth', 'verified', 'role:staff']], function () {
    Route::get('/staff/dashboard', [StaffController::class, 'overview'])->name('staff.dashboard');
    // Route::get('/staff/patient-list', [StaffController::class, 'patient_list'])->name('patient_list');

});
// Dentist Routes
Route::group(['middleware' => ['auth', 'verified', 'role:dentist']], function () {
    Route::get('/dentist/{dentist}/dashboard', [DentistController::class, 'overview'])->name('dentist.dashboard');
    // Route::get('/staff/patient-list', [StaffController::class, 'patient_list'])->name('patient_list');

    Route::get('/dentist/payments', [DentistController::class, 'viewPayments'])->name('dentist.payments');

    //
    Route::get('/dentist/{id}/appointments/pending', [DentistController::class, 'pendingAppointment'])->name('appointments.pending');
    Route::get('/dentist/{id}/appointments/approved', [DentistController::class, 'approvedAppointment'])->name('appointments.approved');
    Route::get('/dentist/{id}/appointments/declined', [DentistController::class, 'declinedAppointment'])->name('appointments.declined');
    Route::get('/dentist/{id}/payments-list', [DentistController::class, 'appointmentPayment'])->name('appointments.payment');
    Route::get('/dentist/appointments/show/{id}', [DentistController::class, 'showDentistAppointmentInfo'])->name('appointments.show');

    Route::post('/appointments/{id}/approve', [AppointmentController::class, 'approve'])->name('appointments.approve');
    Route::post('/appointments/{id}/decline', [AppointmentController::class, 'decline'])->name('appointments.decline');

    //Payment
    Route::get('/dentist/payment/{id}', [DentistController::class, 'createDentistPayment'])->name('dentist.paymentForm');
    Route::post('dentist/payment/{id}/store', [DentistController::class, 'storeDentistPartialPayment'])->name('dentist.paymentStore');
    Route::get('/dentist/{paymentId}/history', [DentistController::class, 'showDentistPaymentHistory'])->name('dentist.paymentHistory');
});

//Client Routes
Route::group(['middleware' => ['auth', 'verified', 'role:client',]], function () {
    Route::get('/client/dashboard', [ClientController::class, 'dashboard'])->name('dashboard'); //for redirection
    Route::get('/client/dashboard/overview/{id}', [ClientController::class, 'profileOverview'])->name('client.overview');
    Route::get('/client/records/{id}', [ClientController::class, 'clientRecords'])->name('client.records');

    Route::get('/client/{appointmentId}/payment', [ClientController::class, 'createClientPayment'])->name('client.form');
    Route::post('/client/{paymentId}/store', [ClientController::class, 'storeClientPartialPayment'])->name('client.store');
    Route::get('/client/{paymentId}/history', [ClientController::class, 'showClientPaymentHistory'])->name('client.history');


    Route::post('client/upload-proof', [ClientController::class, 'uploadProof'])->name('client.proof');

    Route::post('/client/{appointmentId}/pay', [ControllersPaymentController::class, 'testPay'])->name('client.pay');
    Route::get('success', [ControllersPaymentController::class, 'success']);

    // Route::get('/appointment/request', [AppointmentController::class, 'create'])->name('appointments.request');
    // Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointments.store');
});



