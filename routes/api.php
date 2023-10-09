<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockReportController;
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

use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestCollection;

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

        Route::prefix("profile")->controller(ProfileController::class)->group(function () {
            Route::put('{id}', 'update')->name('profile.update');
            Route::post('change-password', 'chgPassword')->name('profile.chgPassword');
            Route::get("details", "details")->name("profile.details");
        });

        Route::apiResource("photo", PhotoController::class);
        Route::post("photo/multiple-delete", [PhotoController::class, "multipleDelete"])->name("photo.multiDel");

        Route::prefix("user")->middleware("adminOnly")->controller(UserController::class)->group(function () {
            Route::get("/", "list")->name("user.list");
            Route::get("banned-users", "bannedUsers")->name("user.bannedList");
            Route::post("register", "create")->name("user.register"); /* register route only admin can register */
            Route::put("position-management/{id}", "updatePosition")->name("user.updatePosition"); /* promotion route only admin access */
            Route::get("details/{id}", "details")->name("user.details");
            Route::put("ban/{id}", "ban")->name("user.ban");
            Route::put("unban/{id}", "unban")->name("user.unban");
        });

        Route::apiResource("brand", BrandController::class);
        Route::apiResource("product", ProductController::class);
        Route::apiResource("stock", StockController::class)->except("destroy", "store", "update");
        Route::post("stock/{prodID}", [StockController::class, "store"])->name("stock.store");

        Route::prefix("sale")->controller(SaleController::class)->group(function () {
            Route::post("checkout", "checkout")->name('sale.checkout')->middleware("isSaleClose");
            Route::get("recent-list", "recentList")->name('sale.recentList');
            Route::get("products-list", "productsList")->name('sale.prodList');
            Route::post("sale-close", "saleClose")->name("sale.close");
            Route::post("sum-daily-sales", "createMonthlySale")->name("sale.createMonthly");
            Route::post("sum-monthly-sales", "createYearlySale")->name("sale.createYearly");
        });

        Route::prefix("finance")->controller(FinanceController::class)->group(function () {
            Route::get("daily-sales", "dailySales")->name("finance.dailySales");
            Route::get("monthly-sales", "thisMonthSales")->name("finance.thisMonthSales");
            Route::get("yearly-sales", "thisYearSales")->name("finance.thisMonthSales");
            Route::get("custom-sales-list", "customSalesList")->name("finance.customSalesList");
        });

        Route::prefix("report")->group(function () {
            Route::controller(StockReportController::class)->group(function () {
                Route::get("stock-level-table", "stockLvlTable")->name("report.stockLvlTable");
                Route::get("stock-level-bar", "stockLvlBar")->name("report.stockLvlBar");
                Route::get("best-seller-brands", "bestSellerBrands")->name("report.bestSellerBrands");
            });

            Route::controller(SaleReportController::class)->group(function () {
                Route::get("brand-report", "brandSale")->name('report.brand');
                Route::get('today-report', 'todaySaleReport')->name('report.today');
                Route::get('weekly-report', 'weeklySaleReport')->name('report.weekly');
                Route::get('monthly-report', 'monthlySaleReport')->name('report.monthly');
                Route::get('yearly-report', 'yearlySaleReport')->name('report.yearly');
                Route::get('product-report', 'productReport')->name('report.product');
            });
        });

        Route::get('voucher/{voucher_number}', [VoucherController::class, 'show'])->name('voucher.show');

        Route::controller(OverviewController::class)->group(function(){
            Route::get('overview-page','overViewPage')->name('overview.todaySales');
            Route::get('weekly-overview','weeklySaleOverview')->name('overview.weekly');
            Route::get('monthly-overview','monthlySaleOverview')->name('overview.monthly');
            Route::get('yearly-overview','yearlySaleOverview')->name('overview.yearly');
        });

    });

    Route::controller(TestController::class)->group(function () {
        Route::post("test", "test");

    });
});
