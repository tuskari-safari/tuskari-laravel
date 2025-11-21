<?php

use App\Events\TestEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
include('artisan.php');

Route::get('action', function(){
    return Inertia::render('Test');
});
 
Route::post('send-typing', function(Request $request){
    TestEvent::dispatch($request);
});
 
Route::get('event', function(){
    return Inertia::render('ListenEvent');
});
