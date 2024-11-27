<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\TestJob;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    TestJob::dispatch('test123213321')->onQueue('test1');
    TestJob::dispatch('jopa')->onQueue('test2');
    TestJob::dispatch('huy')->onQueue('test3');

    dd('test');
});

Route::get('/test1', function () {
    // TestJob::dispatch('test')->onQueue('test1');
    dd('test1');
});