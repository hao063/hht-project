<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('test', function() {
    return 'test';
});

// TODO: set password for user login
Route::get('/set-password/{slug}/{password}', function($slug, $password) {
    $user                = \App\Models\Organizer::where('slug', $slug)->first();
    $user->password_hash = \Illuminate\Support\Facades\Hash::make($password);
    $user->save();
    return response()->json([
        'status'  => 200,
        'message' => 'Set password success',
        'data'    => [],
    ]);
});
Route::group(['middleware' => 'auth_token'], function() {
    // TODO: campaign
    Route::group(['prefix' => 'campaign'], function() {
        Route::get('/', [CampaignController::class, 'getListCampaigns']);
        Route::post('/create', [CampaignController::class, 'createCampaign']);
        Route::get('/get-edit/{id}', [CampaignController::class, 'getCampaign']);
        Route::post('/edit/{id}', [CampaignController::class, 'editCampaign']);
        Route::get('/detail/{id}', [CampaignController::class, 'detailCampaign']);
    });

    // TODO: tickets
    Route::post('/ticket/create', [TicketController::class, 'createTicket']);

    // TODO: area
    Route::get('/get-area/{id}', [AreaController::class, 'getAreas']);
    Route::post('/area/create', [AreaController::class, 'createArea']);

    // TODO: place
    Route::post('/place/create', [PlaceController::class, 'createPlace']);

    // TODO: session
    Route::post('/session/create', [SessionController::class, 'createSession']);
    Route::get('/session/get-edit/{session_id}', [SessionController::class, 'getEditSession']);
    Route::post('/session/edit/{session_id}', [SessionController::class, 'editSession']);
    Route::get('/get/place/area/{id}', [SessionController::class, 'getPlaceAndArea']);

    // TODO: report
    Route::get('/report/{campaign_id}', [ReportController::class, 'getDataReport']);


    Route::get('/get-user', [AuthController::class, 'getUser']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
