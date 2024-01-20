<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\LaboratoryController;
use \App\Http\Controllers\EquipmentController;
use \App\Http\Controllers\ArchiveController;
use \App\Http\Controllers\ProfileController;
use \App\Http\Controllers\PassportController;

Route::get('/', function () {
    return view('/auth/login');
})->name('auth');
//Profile
{
    Route::get('/profile', [ProfileController::class, 'getUsers'])->name('profile')->middleware('auth');
    Route::post('/profile/updateUser', [ProfileController::class, 'changeDataSubmit'])->name('updateUser')->middleware('auth');
    Route::post('/profile/changeAdmin', [ProfileController::class, 'changeAdmin'])->name('changeAdmin')->middleware('auth');
    Route::post('/profile/addUser', [ProfileController::class, 'addUser'])->name('addUser')->middleware('auth');
    Route::post('/profile/deleteUser', [ProfileController::class, 'deleteUser'])->name('deleteUser')->middleware('auth');
    Route::get('/profile/deleteAccount', [ProfileController::class, 'deleteAccount'])->name('deleteAccount')->middleware('auth');
    Route::get('/profile/printUsers', [ProfileController::class, 'printUsers'])->name('printUsers')->middleware('auth');
}
//Labs
{
    Route::get('/home', [LaboratoryController::class, 'getLabs'])->name('home')->middleware('auth');
    Route::get('/laboratories/change/{id}', [LaboratoryController::class, 'changeCreateLab'])->name('changeLab')->middleware('auth');
    Route::get('/laboratories/delete/{id}', [LaboratoryController::class, 'deleteWebLab'])->name('deleteLab')->middleware('auth');
    Route::get('/laboratories/create', [LaboratoryController::class, 'createLab'])->name('createLab')->middleware('auth');
    Route::get('/laboratories/truncateAll', [LaboratoryController::class, 'truncateAll'])->name('truncateAll')->middleware('auth');

    Route::post('/laboratories/loadSubTypes', [LaboratoryController::class, 'loadSubTypes'])->name('updateSubTypes');
    Route::post('/laboratories/loadVersions', [LaboratoryController::class, 'loadVersions'])->name('loadVersions');
    Route::post('/laboratories/addNewEqs', [LaboratoryController::class, 'addNewEqs'])->name('addNewEqs');
    Route::post('/laboratories/changeEq', [LaboratoryController::class, 'changeEq'])->name('changeEq');
    Route::post('/laboratories/addSoft', [LaboratoryController::class, 'addSoft'])->name('addSoft');
    Route::post('/laboratories/saveLab', [LaboratoryController::class, 'saveLab'])->name('saveLab');
    Route::post('/laboratories/addResp', [LaboratoryController::class, 'addResp'])->name('addResp');
}
//Eqs
{
    Route::get('/equipments', [EquipmentController::class, 'getEqs'])->name('equipments')->middleware('auth');
    Route::get('/equipments/updateEq/{id}', [EquipmentController::class, 'updateEq'])->name('updateEq')->middleware('auth');
    Route::get('/equipments/deleteEq/{id}', [EquipmentController::class, 'deleteWebEq'])->name('deleteEq')->middleware('auth');
    Route::get('/equipments/createEq', [EquipmentController::class, 'createEq'])->name('createEq')->middleware('auth');

    Route::post('/equipments/createUpdateEq/submit', [EquipmentController::class, 'createUpdateEqSubmit'])->name('createUpdateEqSubmit')->middleware('auth');
}
//Passport
{
    Route::get('/passports/home', [PassportController::class, 'getPassports'])->name('passports')->middleware('auth');
    Route::get('/passports/create', [PassportController::class, 'createPassport'])->name('createPassport')->middleware('auth');
    Route::get('/passports/edit/{id}', [PassportController::class, 'changeCreatePassport'])->name('changePassport')->middleware('auth');
    Route::get('/passports/delete/{id}', [PassportController::class, 'deletePassport'])->name('deletePassport')->middleware('auth');

    Route::post('/passports/savePassport', [PassportController::class, 'savePassport'])->name('savePassport')->middleware('auth');
    Route::post('/passports/downloadFile', [PassportController::class, 'downloadFile'])->name('downloadFile')->middleware('auth');
}
//Archive
{
    Route::get('/archive', [ArchiveController::class, 'getElements'])->name('archive')->middleware('auth');
    Route::get('/archive/restoreLab/{id}', [ArchiveController::class, 'restoreLab'])->name('restoreLab')->middleware('auth');
    Route::get('/archive/restoreEq/{id}', [ArchiveController::class, 'restoreEq'])->name('restoreEq')->middleware('auth');
    Route::get('/archive/restoreUser/{id}', [ArchiveController::class, 'restoreUser'])->name('restoreUser')->middleware('auth');
    Route::get('/archive/restorePassport/{id}', [ArchiveController::class, 'restorePassport'])->name('restorePassport')->middleware('auth');
}
Auth::routes();
