<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\FirstLoginRedirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware([FirstLoginRedirect::class])->group(function () {
    Route::get('/', [ProductController::class,'index'])->name('index');
    Route::get('/all',[ProductController::class,'all'])->name('all');
    Route::get('/mylist',[ProductController::class,'mylist'])->name('mylist');
    Route::get('/item/{id}',[ProductController::class,'detail'])->name('detail');
    Route::get('/mypage/profile',[UserController::class,'profile'])->middleware(['auth'])->name('profile');
    Route::post('/mypage/profile',[UserController::class,'storeProfile'])->middleware(['auth'])->name('storeProfile');

    Route::post('/detail/like/{id}',[ProductController::class,'like'])->middleware(['auth','verified'])->name('like');

    Route::post('/item/comment',[ProductController::class,'postComment'])->middleware(['auth','verified'])->name('comment');
    Route::get('/purchase/{id}',[ProductController::class,'goPurchase'])->middleware(['auth','verified'])->name('goPurchase');

    Route::get('/purchase/address/{id}',[ProductController::class,'goChange'])->middleware(['auth','verified'])->name('goChange');
    Route::post('/purchase/change-address',[ProductController::class,'change'])->middleware(['auth','verified'])->name('change');

    Route::post('/purchase/product/sold',[ProductController::class,'purchase'])->middleware(['auth','verified'])->name('payment.session');
    Route::get('/purchase/product/success/{id}',[ProductController::class,'success'])->middleware(['auth','verified'])->name('success');
    Route::get('/purchase/product/cancel/{id}',[ProductController::class,'cancel'])->middleware(['auth','verified'])->name('cancel');

    Route::get('/mypage',[ProductController::class,'mypage'])->middleware(['auth','verified'])->name('mypage');
    Route::get('/mypage/sell',[ProductController::class,'mypageSell'])->middleware(['auth','verified'])->name('mypage-sell');
    Route::get('/mypage/buy',[ProductController::class,'mypageBuy'])->middleware(['auth','verified'])->name('mypage-buy');
    
    Route::get('/sell',[ProductController::class,'exhibit'])->middleware(['auth','verified'])->name('exhibit');
    Route::post('/sell',[ProductController::class,'sell'])->middleware(['auth','verified'])->name('sell');   
});