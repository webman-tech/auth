<?php

use Webman\Route;

// admin 路由
Route::group('/admin', function () {
    Route::post('/auth/login', [app\admin\controller\AuthController::class, 'login']);
});
Route::group('/admin', function () {
    Route::get('/auth/info', [app\admin\controller\AuthController::class, 'info']);
    Route::post('/auth/logout', [app\admin\controller\AuthController::class, 'logout']);
})->middleware([
    app\middleware\SetAuthGuardAdmin::class,
    Kriss\WebmanAuth\Middleware\Authentication::class,
]);

// api 路由
Route::group('/api', function () {
    Route::post('/auth/login', [app\api\controller\AuthController::class, 'login']);
});
Route::group('/api', function () {
    Route::get('/auth/info', [app\api\controller\AuthController::class, 'info']);
    Route::post('/auth/logout', [app\api\controller\AuthController::class, 'logout']);
})->middleware([
    Kriss\WebmanAuth\Middleware\Authentication::class,
]);
