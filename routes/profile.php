<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile')->group(function (){
    Route::get('index', [ProfileController::class,'index'])
        ->name('profile.index');

    Route::post('changepassword', [ProfileController::class,'changePassword'])
        -> name('profile.password.change'); // change password route
    Route::get('2fa/{turn}', [ProfileController::class,'change2fa'])
        -> name('profile.2fa.change'); // change 2fa

    // add or remove to whishlist
//    Route::get('add/wishlist/{product}', [ProfileController::class,'addRemoveWishlist']) -> name('profile.wishlist.add');
//    Route::get('wishlist', [ProfileController::class,'wishlist']) -> name('profile.wishlist');


});
