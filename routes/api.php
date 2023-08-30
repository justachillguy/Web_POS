<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherRecordController;
use App\Http\Middleware\OnlyAdmin;
use App\Http\Middleware\SetAppJsonAceeptHeader;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix("v1")->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post("login", "login")->name("auth.login");
    });


    Route::middleware(["auth:sanctum", "isUserBanned", SetAppJsonAceeptHeader::class])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get("devices", "devices")->name("auth.devices");
            Route::post("logout", 'logout')->name("auth.logout");
            Route::post("logout-all", 'logoutAll')->name("auth.logoutAll");
        });

        Route::controller(ProfileController::class)->group(function () {
            Route::put('profile/{id}', 'update')->name('profile.update');
            Route::post('profile/change-password', 'chgPassword')->name('profile.chgPassword');
        });


        Route::apiResource("photo", PhotoController::class);
        Route::post("photo/multiple-delete", [PhotoController::class, "multipleDelete"])->name("photo.multiDel");

        Route::middleware("adminOnly")->controller(UserController::class)->group(function () {
            Route::get("user", "list")->name("user.list");
            Route::post("user/register", "create")->name("user.register"); /* register route only admin can register */
            Route::put("user/position-management/{id}", "updatePosition")->name("user.updatePosition"); /* promotion route only admin access */
            Route::get("user/details/{id}", "details")->name("user.details");
            Route::post("user/ban", "ban")->name("user.ban");
            Route::post("user/unban", "unban")->name("user.unban");
        });

        Route::apiResource("brand", BrandController::class);
        Route::apiResource("product", ProductController::class);
        Route::apiResource("stock", StockController::class)->except("destroy");

        Route::controller(SaleController::class)->group(function () {
            Route::post("sale/checkout", "checkout")->name('sale.checkout')->middleware("isSaleClose");
            Route::get('sale/list', 'list')->name('sale.list');
            Route::post("sale/sale-close", "saleClose")->name("sale.close");
        });

        Route::controller(FinanceController::class)->group(function () {
            Route::get("finance/daily-list", "dailyList")->name("finanace.daily");
        });

        Route::get('voucher/{voucher_number}', [VoucherController::class, 'show'])->name('voucher.show');
    });

    Route::controller(TestController::class)->group(function () {
        Route::post("test", "test");
    });
});
