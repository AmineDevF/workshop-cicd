<?php
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class);

Route::get('/health', function () {
    return response()->json([
        'status'  => 'ok',
        'version' => '1.0.0',
        'db'      => DB::connection()->getPdo() ? 'connected' : 'error',
        'time'    => now()->toISOString(),
    ]);
});