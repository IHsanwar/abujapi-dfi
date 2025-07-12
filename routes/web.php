<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth:api')->group(...); // 'api' harus terdaftar

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
        
use Illuminate\Support\Str;

Route::get('/test-upload', function () {
    try {
        $filename = 'test-' . Str::random(6) . '.txt';
        $content = 'Test upload ke Filebase via Laravel';
        $success = Storage::disk('filebase')->put($filename, $content);

        return $success ? "Uploaded: $filename" : 'Upload failed.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});


