<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\api;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('/user',[api\UserManagementController::class,'get_info']);

//auth
Route::post('/user/login', [api\AuthController::class,'login']);

Route::post('/user/logout', [api\AuthController::class,'logout']);
Route::post('/user/info',[api\UserManagementController::class,'get_info']);
Route::post('/user/invite',[api\TeamManagementController::class,'send_invitation']);
Route::post('/user/invite/validate',[api\TeamManagementController::class,'validate_invitation']);
Route::post('/user/invite/confirm',[api\TeamManagementController::class,'confirm_invitation']);

Route::middleware('auth:sanctum')->get('/campaign/data-table/list', [api\CampaignController::class,'list']);

Route::post('/campaign/submit', [api\CampaignController::class,'submit']);
Route::post('/campaign/update', [api\CampaignController::class,'update']);
Route::post('/campaign/get', [api\CampaignController::class,'get']);
Route::post('/campaign/list', [api\CampaignController::class,'get_item']);

Route::get('/company/data-table/list', [api\CompanyController::class,'list']);
Route::get('/company/list', [api\CompanyController::class,'list_item']);
Route::post('/company/submit', [api\CompanyController::class,'submit']);
Route::post('/company/update', [api\CompanyController::class,'update']);
Route::post('/company/get', [api\CompanyController::class, 'get']);

Route::get('/user/data-table/list', [api\UserManagementController::class,'list']);
Route::post('/user/submit', [api\UserManagementController::class,'submit']);
Route::post('/user/update', [api\UserManagementController::class,'update']);
Route::post('/user/get', [api\UserManagementController::class,'get']);
Route::post('/user/list/item', [api\UserManagementController::class,'get_item']);
Route::post('/user/password/change', [api\UserManagementController::class,'change_password']);

Route::get('/postback/data-table/list', [api\PostbackController::class,'list']);
Route::post('/postback/submit', [api\PostbackController::class, 'submit']);
Route::post('/postback/update', [api\PostbackController::class, 'update']);
Route::post('/postback/list/item', [api\PostbackController::class, 'get_item']);
Route::post('/postback/edit', [api\PostbackController::class, 'edit']);

Route::post('/postback/log/submit', [api\PostbackController::class, 'submit_log']);

Route::get('/lead/data-table/list', [api\DataLeadController::class,'list']);
Route::post('/lead/data-table/detail', [api\DataLeadController::class, 'detail']);
Route::post('/lead/data-table/assign', [api\DataLeadController::class, 'assign']);
Route::post('/lead/status/update', [api\DataLeadController::class, 'update_status']);
Route::post('/lead/data-table/histories', [api\DataLeadController::class, 'histories']);
Route::post('/lead/data-table/submit', [api\DataLeadController::class, 'submit']);

Route::get('/category/list', [api\CompanyController::class, 'list_categories']);

Route::get('/team-management/list', [api\TeamManagementController::class, 'list']);

Route::post('/data/submit', [api\ClientController::class,'submit']);
