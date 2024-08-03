<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

Route::get('shortens', [UrlController::class, 'index']); 
Route::post('shorten', [UrlController::class, 'shorten']); 
Route::get('{shortened_url}', [UrlController::class, 'redirect']); 
Route::delete('delete/{id}', [UrlController::class, 'destroy']); 
