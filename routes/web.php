<?php

use Illuminate\Support\Facades\Route;

// 🛠️ បើចង់ឱ្យរុញចំទៅកាន់ទំព័រ Login ផ្ទាល់តែម្តង៖
Route::get('/', function () {
    return redirect('admin');
});
