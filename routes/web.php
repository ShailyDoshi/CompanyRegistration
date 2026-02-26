<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/',[TestController::class,'index']);

Route::prefix('/companies')->as('companies.')->group(function (){
    Route::get('/index',[CompanyController::class,'index'])->name('index');
    Route::get('/form/{company?}',[CompanyController::class,'form'])->name('form');
    Route::post('/create-or-update/{company?}',[CompanyController::class,'createOrUpdate'])->name('create_or_update');
    Route::get('/get-data',[CompanyController::class,'getAllData'])->name('get_all_data');
    Route::get('/delete/{company}',[CompanyController::class,'delete'])->name('delete');
});
