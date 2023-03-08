<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusClassController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteStopController;
use App\Http\Controllers\PaymentController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//agent
Route::post('/register', [AgentController::class, 'register']);
Route::post('/login', [AgentController::class, 'login']);
Route::post('/delete-agent/{id}', [AgentController::class, 'destroy']);
Route::get('/get-all-agent', [AgentController::class, 'index']);
Route::get('/get-agent/{id}', [AgentController::class, 'show']);
//role
Route::post('/create-role', [RoleController::class, 'store']);
Route::post('/update-role/{id}', [RoleController::class, 'update']);
Route::post('/delete-role/{id}', [RoleController::class, 'destroy']);
Route::get('/get-all-role', [RoleController::class, 'index']);
Route::get('/get-role/{id}', [RoleController::class, 'show']);
//city
Route::post('/create-city', [CityController::class, 'store']);
Route::post('/update-city/{id}', [CityController::class, 'update']);
Route::post('/delete-city/{id}', [CityController::class, 'destroy']);
Route::get('/get-all-city', [CityController::class, 'index']);
Route::get('/get-city/{id}', [CityController::class, 'show']);
//bus
Route::post('/create-bus', [BusController::class, 'store']);
Route::post('/update-bus/{id}', [BusController::class, 'update']);
Route::post('/delete-bus/{id}', [BusController::class, 'destroy']);
Route::get('/get-all-bus', [BusController::class, 'index']);
Route::get('/get-bus/{id}', [BusController::class, 'show']);
//bus classes
Route::post('/create-busclasses', [BusClassController::class, 'store']);
Route::post('/update-busclasses/{id}', [BusClassController::class, 'update']);
Route::post('/delete-busclasses/{id}', [BusClassController::class, 'destroy']);
Route::get('/get-all-busclasses', [BusClassController::class, 'index']);
Route::get('/get-busclasses/{name}', [BusClassController::class, 'show']);
//stop city
Route::post('/create-stop', [StopController::class, 'store']);
Route::post('/update-stop/{id}', [StopController::class, 'update']);
Route::post('/delete-stop/{id}', [StopController::class, 'destroy']);
Route::get('/get-all-stop', [StopController::class, 'index']);
Route::get('/get-stop/{id}', [StopController::class, 'show']);
Route::get('/get-stop-city/{cityName}', [StopController::class, 'showByCity']);
//route
Route::post('/create-route', [RouteController::class, 'store']);
Route::post('/update-route/{id}', [RouteController::class, 'update']);
Route::post('/delete-route/{id}', [RouteController::class, 'destroy']);
Route::get('/get-all-route', [RouteController::class, 'index']);
Route::get('/get-route/{id}', [RouteController::class, 'show']);
//route stop
Route::post('/create-routestop', [RouteStopController::class, 'store']);
Route::post('/update-routestop/{id}', [RouteStopController::class, 'update']);
Route::post('/delete-routestop/{id}', [RouteStopController::class, 'destroy']);
Route::get('/get-all-routestop', [RouteStopController::class, 'index']);
Route::get('/get-routestop/{id}', [RouteStopController::class, 'show']);
//ticket
Route::get('/check-route/{departure_city}/{arrival_city}', [TicketController::class, 'findRoute']);
Route::post('/check-available-seats', [TicketController::class, 'checkAvailableSeats']);
Route::post('/purchase-ticket', [TicketController::class, 'purchaseTicket']);
Route::get('/payment-details/{ticket_id}',[TicketController::class,'showPaymentDetails']);
Route::post('process-payment', [TicketController::class,'processPayment']);
