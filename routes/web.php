<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientSourcesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['login' => false]);

Route::get('/', function () {

    if (Auth::check()){
        switch(strtolower(Auth::user()->role)){
            case 'admin':
                return redirect(route('client-sources.show'));
                break;
        }
    }
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([ 'prefix' => 'policies', 'middleware' => ['auth', 'user-role:admin']],function (){
    Route::group([
        'prefix' => 'client-sources'
    ], function () {
        Route::get("/", [ClientSourcesController::class, 'show'])->name("client-sources.show");
        Route::get("/create", [ClientSourcesController::class, 'showCreateForm'])->name("client-sources.create");
        Route::post("/create", [ClientSourcesController::class, 'create']);
        Route::get("/update/{id}", [ClientSourcesController::class, 'showUpdateForm'])->name("client-sources.update");
        Route::post("/update/{id}", [ClientSourcesController::class, 'update']);
        Route::post("/delete/{id}", [ClientSourcesController::class, 'delete'])->name("client-sources.delete");
    });

});