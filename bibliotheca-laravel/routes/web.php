<?php

use Illuminate\Support\Facades\Route;


//put this for the view's url
Route::get('/', function () {
    return view('login');
});

Route::get('/', function () {
    return view('dashboard');
});
