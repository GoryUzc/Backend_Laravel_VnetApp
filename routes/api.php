<?php

use App\Http\Controllers\InstallationOrderController;
use App\Http\Controllers\OrderPdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\MeetingController;


Route::prefix('/v1')->group(function () {
    
    // Rutas públicas
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);
    Route::get('/franchises/list', [LoginController::class, 'listFranchisesUser']);
    Route::get('/contractor/list', [ContractorController::class,'listForRegister']);
    Route::get('/roles/list', [LoginController::class, 'listRoleUser']);
    
    // RUTAS PROSPECTOS

    // Rutas para login  de verificacion de correo OTP prospectos
    Route::post('/prospect/verify-email', [LoginController::class, 'verifyProspect']);
    Route::post('/prospect/send-otp', [LoginController::class, 'sendOtpToProspect']);
    Route::get('/prospect/consult/{id}', [ProspectController::class, 'consultProspectAradial']);

    //Ruta para consultar prospecto por Id y crear citas 
    Route::get('/prospect/detail/{id}', [ProspectController::class, 'prospectDetails'])->middleware('prospect.auth');
    Route::post('/meeting/create', [MeetingController::class, 'registerMeeting'])->middleware('prospect.auth');

    // RUTAS PROTEGIDAS jwt y role

    // CRUD de citas 
    Route::get('/meetings/list', [MeetingController::class, 'listMeeting'])->middleware('jwt.auth','checkrole:1,2,3,4');
    Route::get('/meetings/list/unassigned', [MeetingController::class, 'listMeetingUnassigned'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/meetings/list/assigned', [MeetingController::class, 'listAllMeetingAssigned'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/meetings/user', [MeetingController::class, 'listMeetingUserAssigned'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/meetings/user/process', [MeetingController::class, 'listMeetingUserProcess'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::put('/meetings/updated/status/init/{id}', [MeetingController::class, 'initMeetingUpdatedStatus'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::put('/meetings/updated/status/end/{id}', [MeetingController::class, 'endMeetingUpdatedStatus'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::post('/meetings/create', [MeetingController::class, 'registerMeeting'])->middleware( 'jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/meetings/detail/{id}', [MeetingController::class, 'detailsMeeting'])->middleware( 'jwt.auth', 'checkrole:1,2,3,4');
    Route::put('/meetings/take/{id}', [MeetingController::class, 'takeMeeting'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::put('/meetings/update/{id}', [MeetingController::class, 'updateMeeting'])->middleware( 'jwt.auth', 'checkrole:1');
    Route::delete('/meetings/delete/{id}', [MeetingController::class, 'deleteMeeting'])->middleware('jwt.auth', 'checkrole:1');
    
        
    // CRUD de Prospectos
    Route::post('/prospects/create', [ProspectController::class, 'registerProspect'])->middleware('jwt.auth','checkrole:1,2');
    Route::get('/prospects/list', [ProspectController::class, 'listProspects'])->middleware('jwt.auth','checkrole:1,2,3,4');
    Route::get('/prospects/detail/{id}', [ProspectController::class, 'prospectDetails'])->middleware('jwt.auth','checkrole:1,2,3,4');
    Route::put('/prospects/update/{id}', [ProspectController::class, 'updateProspect'])->middleware('jwt.auth','checkrole:1');
    Route::delete('/prospects/delete/{id}', [ProspectController::class, 'deleteProspect'])->middleware('jwt.auth','checkrole:1');


    // CRUD de Usuarios
    Route::post('/users/create', [UserController::class, 'registerUser'])->middleware('jwt.auth','checkrole:1');
    Route::get('/users/list', [UserController::class, 'listUser'])->middleware('jwt.auth','checkrole:1,2,3,4');
    Route::get('/users/detail/{id}', [UserController::class, 'detailsUser'])->middleware('jwt.auth','checkrole:1,2,3,4');
    Route::put('/users/update/{id}', [UserController::class, 'updateUser'])->middleware('jwt.auth','checkrole:1,2,3,4');
    Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser'])->middleware('jwt.auth','checkrole:1,2,3');


    //CRUD Contratistas
    Route::post('/contractors/create', [ContractorController::class, 'registerContractor'])->middleware('jwt.auth','checkrole:1,2');
    Route::get('/contractors/list', [ContractorController::class, 'listContractor'])->middleware('jwt.auth','checkrole:1,2');
    Route::get('/contractors/detail/{id}', [ContractorController::class, 'detailsContractor'])->middleware('jwt.auth','checkrole:1,2,3');
    Route::put('/contractors/update/{id}', [ContractorController::class, 'updateContractor'])->middleware('jwt.auth','checkrole:1,2,3');
    Route::delete('/contractors/delete/{id}', [ContractorController::class, 'deleteContractor'])->middleware('jwt.auth','checkrole:1');
    Route::get('/contractor/workers', [ContractorController::class, 'listworkerscontractor'])->middleware('jwt.auth','checkrole:1,2,3,4');

    // CRUD orden de installation 
    Route::post('/orders/create', [InstallationOrderController::class, 'registerOrderInstallation'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/orders/list', [InstallationOrderController::class, 'listOrderInstallation'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/orders/pdf/{id}', [OrderPdfController::class, 'downloadPdf']);  //->middleware('jwt.auth');
    Route::get('/orders/pdf/preview/{id}', [OrderPdfController::class, 'previewPdf']); //->middleware('jwt.auth');
    Route::get('/orders/detail/{id}', [InstallationOrderController::class, 'detailOrderInstallation'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::put('/orders/update/{id}', [InstallationOrderController::class, 'updateOrderInstallation'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::get('/orders/user/installation/{id}', [InstallationOrderController::class, 'detailOrderUserInstallation']);   //->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::delete('/orders/delete/{id}', [InstallationOrderController::class, 'deleteOrderInstallation'])->middleware('jwt.auth', 'checkrole:1,2,3,4');
    Route::post('/orders/upload-signature/{id}', [InstallationOrderController::class, 'uploadSignature']); // Ruta publica de la firma;
    });
    
