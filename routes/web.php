<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\ChartController;

use App\Http\Controllers\WebScrapController;
use App\Http\Controllers\WebScrappingController;


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

    // try{
    //     Mail::send('welcome', ['name' => 'John Doe'], function($message){
    //         $message->to("zohasaleem2000@gmail.com")
    //         ->subject("Testing Email");
    //     });
    // }catch(Exception $e){
    //         Debugbar::error($e);
    // }
    //Message
    // Debugbar::addMessage("Info");

    //Timeline
    // Debugbar::startMeasure("Testing", "Rendering our first message");

    //Exception
    // try{
    //     throw new Exception('Try Message');

    // }catch(Exception $e){

    //     Debugbar::addException($e);
    // }

    //Views
    // $name = "zoha";
    // return view('welcome', compact('name'));

    return view('welcome');
});


//dashboard for users 
Route::get('/dashboard', [UserController::class, 'loadDashboard'])->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin'])->name('dashboard'); //only users with role as admin can access this route 


Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/save-chat', [UserController::class, 'saveChat']);

    Route::post('/load-chats', [UserController::class, 'loadChats']);

    Route::post('/delete-chat', [UserController::class, 'deleteChat']);

    Route::post('/update-chat', [UserController::class, 'updateChat']);
});

require __DIR__.'/auth.php';


Route::get('/permission-page', [PermissionController::class, 'index'])->name('permission');


Route::post('/grant-permission', [PermissionController::class, 'grantPermissionToRole'])->name('grant.permission');



Route::get('mail', function(){
    return new \App\Mail\WelcomeEmail(123.45);
});


Route::get('/browser-shot', [PermissionController::class, 'browsershot'])->name('browser.shot');


// Route::get('/reload-captcha', [PermissionController::class, 'reloadCaptcha']);


Route::get('my-captcha', [PermissionController::class, 'myCaptcha'])->name('myCaptcha');
Route::post('my-captcha', [PermissionController::class, 'myCaptchaPost'])->name('myCaptcha.post');
Route::get('refresh_captcha', [PermissionController::class, 'refreshCaptcha'])->name('refresh_captcha');



Route::get('books', [PermissionController::class, 'books'])->name('books');

Route::get('/chart', [ChartController::class, 'index']);


Route::get('/web-scrap', [WebScrappingController::class, 'scrapeEcommerceProducts']);

Route::get('/web-scrap-daraz', [WebScrappingController::class, 'scrapeDarazProducts']);
