<?php

use App\Http\Controllers\FetchUrlController;
use App\Http\Controllers\uploadFileController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Volt::route('/','editor-js');
Volt::route('/e/{id}', 'editor-js-show');
Route::get('/fetchUrl', [FetchUrlController::class, 'fetch']);
Route::post('/editor/upload', [uploadFileController::class, 'upload']);
