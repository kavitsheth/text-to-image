<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::post('/text-to-image',[MainController::class,'TextToImage'])->name('textToImage');

Route::get('/',[MainController::class,'index'])->name('index');

Route::post('/',[MainController::class,'test'])->name('test');

