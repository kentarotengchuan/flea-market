<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class,'index'])->name('index');
Route::get('/all',[ProductController::class,'all'])->name('all');
Route::get('/mylist',[ProductController::class,'mylist'])->name('mylist');
Route::get('/item/{id}',[ProductController::class,'detail'])->name('detail');

Route::middleware(['auth','verified'])->group(function(){
    Route::get('/mypage/profile',[UserController::class,'profile'])->name('profile');
    Route::post('/mypage/profile',[UserController::class,'storeProfile'])->name('storeProfile');

    Route::post('/detail/like/{id}',[ProductController::class,'like'])->name('like');

    Route::post('/item/comment',[ProductController::class,'postComment'])->name('comment');
    Route::get('/purchase/{id}',[ProductController::class,'goPurchase'])->name('goPurchase');

    Route::get('/purchase/address/{id}',[ProductController::class,'goChange'])->name('goChange');
    Route::post('/purchase/change-address',[ProductController::class,'change'])->name('change');

    Route::post('/purchase/product/sold',[ProductController::class,'purchase'])->name('payment.session');
    Route::get('/purchase/product/success/{id}',[ProductController::class,'success'])->name('success');
    Route::get('/purchase/product/cancel/{id}',[ProductController::class,'cancel'])->name('cancel');

    Route::get('/mypage',[ProductController::class,'mypage'])->name('mypage');
    Route::get('/mypage/sell',[ProductController::class,'mypageSell'])->name('mypage-sell');
    Route::get('/mypage/buy',[ProductController::class,'mypageBuy'])->name('mypage-buy');
    
    Route::get('/sell',[ProductController::class,'exhibit'])->name('exhibit');
    Route::post('/sell',[ProductController::class,'sell'])->name('sell');
});

