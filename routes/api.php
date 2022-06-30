<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaboratoryController;
use \App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PassportController;
use \App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//Gets
{
    //Users
    {
        //All Users
        Route::get('users', [UserController::class, 'allUsers']);
        //Root user
        Route::get('users/{id}', [UserController::class, 'userById']);
        //User by id
        Route::get('users/find/{id}', [UserController::class, 'rootUser']);
        //Get code
        Route::get('users/code/{email}', [UserController::class, 'getCode']);
    }
    //Labs
    {
        //All Laboratories
        Route::get('labs', [LaboratoryController::class, 'allLabs']);
        //Get softvers
        Route::get('labs/softVers/{id}', [LaboratoryController::class, 'softVers']);
        //Get all labSoftVers
        Route::get('labs/allSoftVers/get', [LaboratoryController::class, 'allSoftVers']);
        //Get all labEq
        Route::get('labs/allLabEq/get', [LaboratoryController::class, 'allLabEq']);
        //Laboratory by id
        Route::get('labs/{id}', [LaboratoryController::class, 'labById']);
        //Get Type
        Route::get('labs/types/get', [LaboratoryController::class, 'allTypes']);
        //Get subtypes
        Route::get('labs/{type}/subTypes', [LaboratoryController::class, 'getSubTypes']);
        //Get soft
        Route::get('labs/softwares/get', [LaboratoryController::class, 'getSoft']);
        //Get software version
        Route::get('labs/{soft}/version', [LaboratoryController::class, 'getVersionOfSoft']);
        //Get all software version
        Route::get('labs/versions/get', [LaboratoryController::class, 'allVersions']);
        //Get template soft
        Route::get('labs/template/get', [LaboratoryController::class, 'getTemplateSoftware']);
        //Get all template soft versions
        Route::get('labs/templateSoftVers/get', [LaboratoryController::class, 'allTemplateSoftware']);
        //Get versions for template
        Route::get('labs/{template}/version', [LaboratoryController::class, 'getVersionForTemplateSoftware']);
        //Get all responsibles
        Route::get('labs/resp/get', [LaboratoryController::class, 'allResp']);
        //Get responsibles
        Route::get('labs/resp/{lab}', [LaboratoryController::class, 'getResp']);
        //Get eqs
        Route::get('labs/eq/{lab}', [LaboratoryController::class, 'getLabEquipment']);
        //Get all attribute values
        Route::get('labs/eqVal/get', [LaboratoryController::class, 'allEqVal']);
        //Get attribute values
        Route::get('labs/eqVal/{lab_equipment}', [LaboratoryController::class, 'getEquipmentValue']);
    }
    //Eqs
    {
        //Get all eq
        Route::get('eqs', [EquipmentController::class, 'allEq']);
        //Get eq by id
        Route::get('eqs/{id}', [EquipmentController::class, 'eqById']);
        //Get attributes of eq
        Route::get('eqs/{eq}/atts', [EquipmentController::class, 'getAtts']);
        //Get attributes of eq
        Route::get('eqs/atts/get', [EquipmentController::class, 'allAtts']);
        //Get attribute list values
        Route::get('eqs/attVals/get', [EquipmentController::class, 'allAttVals']);
        //Get attribute list values
        Route::get('eqs/{att}/attVals', [EquipmentController::class, 'getAttVals']);
    }
    //Passport
    {
        //Get all passport
        Route::get('psp', [PassportController::class, 'getAllPassports']);
        //Get by id
        Route::get('psp/{id}', [PassportController::class, 'getPassportById']);
        //Get key
        Route::get('psp/key/get', [PassportController::class, 'getAllKeys']);
        //Get all masks of passport
        Route::get('psp/{id}/masks', [PassportController::class, 'getAllMasks']);
    }
    //Logs
    {
        //Get last log
        Route::get('logs', [LogController::class, 'getLast']);
        //Get log by id
        Route::get('logs/{id}', [LogController::class, 'getById']);
    }
}
//Posts
{
    //Users
    {
        //Add user
        Route::post('users', [UserController::class, 'addUser']);
        //Auth
        Route::post('users/auth', [UserController::class, 'authUser']);
    }
    //Labs
    {
        //Add lab
        Route::post('labs', [LaboratoryController::class, 'addLab']);
        //Add eq to lab
        Route::post('labs/eq/add', [LaboratoryController::class, 'addLabEq']);
        //Add attribute value to lab
        Route::post('labs/attVal/add', [LaboratoryController::class, 'addLabAttVal']);
        //Add picture
        Route::post('labs/picture/{lab}', [LaboratoryController::class, 'addPicture']);
        //Add type
        Route::post('labs/type/add', [LaboratoryController::class, 'addType']);
        //Add subtype
        Route::post('labs/subType/{type}', [LaboratoryController::class, 'addSubType']);
        //Add soft to lab
        Route::post('labs/softVers/add', [LaboratoryController::class, 'addLaboratoriesSoftware']);
        //Add template
        Route::post('labs/template/add', [LaboratoryController::class, 'addTemplate']);
        //Connect template to softVer
        Route::post('labs/templateToSoft/add', [LaboratoryController::class, 'addTemplateSoftware']);
        //Add soft
        Route::post('labs/soft/add', [LaboratoryController::class, 'addSoftware']);
        //Add software version
        Route::post('labs/version/add', [LaboratoryController::class, 'addVersion']);
        //Add resp
        Route::post('labs/resp/add', [LaboratoryController::class, 'addApiResp']);
    }
    //Eqs
    {
        //Add eq
        Route::post('eqs', [EquipmentController::class, 'addEq']);
        //Add atts
        Route::post('eqs/att/add', [EquipmentController::class, 'addAtt']);
        //Add attVals
        Route::post('eqs/attVal/add', [EquipmentController::class, 'addAttVal']);
    }
    //Passports
    {
        //Add passport
        Route::post('psp', [PassportController::class, 'addPassport']);
        //Add file
        Route::post('psp/file/add', [PassportController::class, 'editFile']);
        //Add mask
        Route::post('psp/mask/add', [PassportController::class, 'addMask']);
    }
}
//Puts
{
    //Users
    {
        //Update user
        Route::put('users', [UserController::class, 'editUser']);
        //Change admin
        Route::put('users/newAdmin/change', [UserController::class, 'updateRole']);
    }
    //Labs
    {
        //Update lab
        Route::put('labs', [LaboratoryController::class, 'editLab']);
        //Update labsoft
        Route::put('labs/labSoftVer/upd', [LaboratoryController::class, 'editLabSoft']);
        //Update labeq
        Route::put('labs/labEq/upd', [LaboratoryController::class, 'editLabEq']);
        //Update attval of eq of lab
        Route::put('labs/attVal/upd', [LaboratoryController::class, 'editLabAttVal']);
        //Update image
        Route::put('labs/image/upd', [LaboratoryController::class, 'editImage']);
        //Edit resp
        Route::put('labs/resp/upd', [LaboratoryController::class, 'editResp']);
    }
    //Eqs
    {
        //Update eq
        Route::put('eqs', [EquipmentController::class, 'editEq']);
        //Update attributes of eq
        Route::put('eqs/att/upd', [EquipmentController::class, 'editAtt']);
        //Update attribute values
        Route::put('eqs/attVal/upd', [EquipmentController::class, 'editAttVal']);
    }
    //Passports
    {
        //Update passport
        Route::put('psp', [PassportController::class, 'editPassport']);
        //Update file
        Route::put('psp/file/upd', [PassportController::class, 'editFile']);
        //Update mask
        Route::put('psp/mask/upd', [PassportController::class, 'editMask']);
    }
}
//Deletes
{
    //Users
    {
        //Delete user
        Route::delete('users/{user}/{status}', [UserController::class, 'deleteUser']);
    }
    //Labs
    {
        //Delete labsoft
        Route::delete('labs/labSoft/{id}', [LaboratoryController::class, 'deleteSoft']);
        //Delete resp
        Route::delete('labs/resp/{id}', [LaboratoryController::class, 'deleteResp']);
        //Delete labEq
        Route::delete('labs/labEq/{id}', [LaboratoryController::class, 'deleteEq']);
        //Delete attval of lab
        Route::delete('labs/attVal/{id}', [LaboratoryController::class, 'deleteAttVal']);
        //Delete lab
        Route::delete('labs/{lab}/{status}', [LaboratoryController::class, 'deleteLab']);
    }
    //Eqs
    {
        //Delete attribute
        Route::delete('eqs/att/{id}', [EquipmentController::class, 'deleteAtt']);
        //Delete list attribute value
        Route::delete('eqs/attVal/{id}', [EquipmentController::class, 'deleteAttVal']);
        //Delete eq
        Route::delete('eqs/{id}/{status}', [EquipmentController::class, 'deleteEq']);
    }
    //Passports
    {
        //Delete mask
        Route::delete('psp/mask/{id}', [PassportController::class, 'delMask']);
        //Delete passport
        Route::delete('psp/{id}/{status}', [PassportController::class, 'delPassport']);
    }
}
