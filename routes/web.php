<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\FirstLoginRedirect;
use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/done',[UserController::class,'done'])->name('done');
Route::get('/mypage/profile',[UserController::class,'profile'])->name('profile');
Route::post('/mypage/profile',[UserController::class,'storeProfile'])->name('storeProfile');

Route::middleware([EnsureEmailIsVerified::class])->group(function (){   
    Route::get('/', [ProductController::class,'index'])->name('index');
    Route::get('/all',[ProductController::class,'all'])->name('all');
    Route::get('/mylist',[ProductController::class,'mylist'])->name('mylist');
    Route::get('/item/{id}',[ProductController::class,'detail'])->name('detail'); 
    Route::post('/detail/like/{id}',[UserController::class,'like'])->middleware(['auth','verified'])->name('like');

    Route::post('/item/comment',[UserController::class,'postComment'])->middleware(['auth','verified'])->name('comment');
    Route::get('/purchase/{id}',[ProductController::class,'goPurchase'])->middleware(['auth','verified'])->name('goPurchase');

    Route::get('/purchase/address/{id}',[ProductController::class,'goChange'])->middleware(['auth','verified'])->name('goChange');
    Route::post('/purchase/change-address',[ProductController::class,'change'])->middleware(['auth','verified'])->name('change');

    Route::post('/purchase/product/sold',[ProductController::class,'purchase'])->middleware(['auth','verified'])->name('payment.session');
    Route::get('/purchase/product/success/{id}',[ProductController::class,'success'])->middleware(['auth','verified'])->name('success');
    Route::get('/purchase/product/cancel/{id}',[ProductController::class,'cancel'])->middleware(['auth','verified'])->name('cancel');

    Route::get('/mypage',[UserController::class,'mypage'])->middleware(['auth','verified'])->name('mypage');
    Route::get('/mypage/sell',[UserController::class,'mypageSell'])->middleware(['auth','verified'])->name('mypage-sell');
    Route::get('/mypage/buy',[UserController::class,'mypageBuy'])->middleware(['auth','verified'])->name('mypage-buy');
    
    Route::get('/sell',[ProductController::class,'exhibit'])->middleware(['auth','verified'])->name('exhibit');
    Route::post('/sell',[ProductController::class,'sell'])->middleware(['auth','verified'])->name('sell');   
});