<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\StockTypeController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PriceTypeController;
use App\Http\Controllers\Api\ForestBeatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    Route::post('/auth/login', [AuthController::class, 'loginUser']);

    Route::group(['middleware' => 'jwt.auth'], function ($router) {

        //Purchase routes
        Route::group(['prefix' => 'dashboard'], function () {

            Route::get('/', [DashboardController::class, 'index']);
            Route::get('/total-stock/{id?}', [DashboardController::class, 'yearly_total_stock']);
            Route::get('/category/{id?}', [DashboardController::class,'view_category']);
            Route::get('/category-range/{id1?}/{id?}', [DashboardController::class,'view_category_range']);
            Route::get('/category-bit/{id1?}/{id?}', [DashboardController::class,'view_category_bit']);
    
            Route::get('/seedling-all', [DashboardController::class,'view']);
            
            Route::get('/seedling/{id?}', [DashboardController::class,'view_seedling']);
            Route::get('/seedling-range/{id1?}/{id?}', [DashboardController::class,'view_seedling_range']);
            Route::get('/seedling-bit/{id1?}/{id?}', [DashboardController::class,'view_seedling_bit']);

        });
        
        Route::get('/forest-beats', [ForestBeatController::class, 'index']);

        Route::group(['prefix' => 'inventory'], function () {

            Route::get('/stock-types',[StockTypeController::class,'index']);
            Route::get('/budgets',[BudgetController::class,'index']);
            Route::get('/product-categories',[ProductController::class,'categoryIndex']);
            Route::get('/products/{id?}', [ProductController::class,'productIndex']);
            Route::get('/price-types',[PriceTypeController::class,'index']);
            

            //Purchase routes
            Route::group(['prefix' => 'purchase'], function () {

                Route::get('/', [PurchaseController::class, 'index'])->middleware('can:read,App\Models\Purchase');
                Route::post('/store', [PurchaseController::class, 'store'])->middleware('can:create,App\Models\Purchase');
                Route::get('/view/{id}', [PurchaseController::class, 'view'])->middleware('can:view,App\Models\Purchase');
                Route::put('/update/{id}', [PurchaseController::class, 'update'])->middleware('can:update,App\Models\Purchase');
                Route::delete('/details/delete/{id}', [PurchaseController::class, 'deletepurchaseDetails'])->middleware('can:delete_purchase_details,App\Models\Purchase');
                Route::delete('/delete/{id}/{sid?}', [PurchaseController::class, 'delete'])->middleware('can:delete,App\Models\Purchase');

                // TODO: make separate method for approval and disapproval
                Route::put('/approve/{id?}', [PurchaseController::class,'approval'])->middleware('can:approval,App\Models\Purchase');
                Route::put('/disapprove/{id?}', [PurchaseController::class,'approval'])->middleware('can:approval,App\Models\Purchase');

                //Comment
                Route::put('/comment/{id?}', [PurchaseController::class,'comment'])->middleware('can:approval,App\Models\Purchase');
                Route::put('/comment_reverse/{id?}', [PurchaseController::class,'comment_reverse'])->middleware('can:approval,App\Models\Purchase');
                Route::get('/comment_view/{id?}', [PurchaseController::class, 'comment_view'])->middleware('can:approval,App\Models\Purchase');



            });

            //Purchase routes
            Route::group(['prefix' => 'sale'], function () {

                Route::get('/', [SaleController::class, 'index'])->middleware('can:read,App\Models\Sale');
                Route::post('/store', [SaleController::class, 'store'])->middleware('can:create,App\Models\Sale');
                Route::get('/view/{id}', [SaleController::class, 'view'])->middleware('can:view,App\Models\Sale');
                Route::put('/update/{id}', [SaleController::class, 'update'])->middleware('can:update,App\Models\Sale');
                Route::delete('/details/delete/{id}', [SaleController::class, 'deletesaleDetails'])->middleware('can:delete_sale_details,App\Models\Sale');
                Route::delete('/delete/{id}/{sid?}', [SaleController::class, 'delete'])->middleware('can:delete,App\Models\Sale');

                Route::put('/approve/{id?}', [SaleController::class,'approval'])->middleware('can:approval,App\Models\Sale');
                Route::put('/disapprove/{id?}', [SaleController::class,'approval'])->middleware('can:approval,App\Models\Sale');

                Route::put('/comment/{id?}', [SaleController::class,'comment'])->middleware('can:approval,App\Models\Purchase');
                Route::put('/comment_reverse/{id?}', [SaleController::class,'comment_reverse'])->middleware('can:approval,App\Models\Purchase');
                Route::get('/comment_view/{id?}', [SaleController::class, 'comment_view'])->middleware('can:approval,App\Models\Sale');

            });
        });
    });
});
