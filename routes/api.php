<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherRecordController;
use App\Http\Middleware\OnlyAdmin;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("v1")->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post("login", "login")->name("auth.login");
    });
    
    Route::middleware("auth.banned")->group(function () {


        Route::middleware('auth:sanctum')->group(function () {
            Route::controller(AuthController::class)->group(function () {
                Route::get("devices", "devices")->name("auth.devices");
                Route::post("logout", 'logout')->name("auth.logout");
                Route::post("logout-all", 'logoutAll')->name("auth.logoutAll");
            });

            Route::controller(ProfileController::class)->group(function(){
                Route::put('profile/{id}','update')->name('profile.update');
                Route::post('change-password','chgPassword')->name('profile.chgPassword');
            });


            Route::apiResource("photos", PhotoController::class);
            Route::post("multiple-delete-photos", [PhotoController::class, "multipleDelete"]);

            Route::middleware("adminOnly")->controller(UserController::class)->group(function () {
                Route::get("users", "list")->name("user.list");
                Route::post("users", "create")->name("user.create"); /* register route only admin can register */
                Route::put("users/{id}", "updateRole")->name("user.updateRole"); /* promotion route only admin access */
                Route::post("ban-user", "ban")->name("user.ban");
                Route::post("unban-user", "unban")->name("user.unban");
            });

            // Route::apiResource("brand", BrandController::class);
            // Route::apiResource("product", ProductController::class);
            // Route::apiResource("stock", StockController::class);
            // Route::apiResource("voucher-record", VoucherRecordController::class);
        });

    });




});

