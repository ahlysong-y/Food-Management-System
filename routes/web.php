<?php

use Illuminate\Support\Facades\Route;

// 🛠️ កែប្រែត្រង់នេះ៖ ឱ្យវាបង្វែរទិសដៅ (Redirect) ទៅកាន់ផ្លូវ /admin ដោយស្វ័យប្រវត្តិ
Route::get('/', function () {
    return redirect('/admin');
});
