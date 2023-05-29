<?php

use App\Http\Controllers\ComplainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\editor\ImageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
Route::post('upload', [ImageController::class,'upload'])->name('editor-img-upload');

Route::group(['middleware' => ['auth']],function (){
    Route::controller(DashboardController::class)->group(function (){
        Route::match(['post','get'],'dashboard','index')->name('dashboard');
    });

    Route::controller(ComplainController::class)->group(function (){
        Route::match(['post','get'],'add-complain','create')->name('add.complain');
        Route::match(['post','get'],'complain-list','show')->name('individual.list.complain');
        Route::match(['post','get'],'complain-list-dept','deptList')->name('departmental.list.complain');
        Route::match(['post','get'],'my-complain-list','myList')->name('my.list.complain');
        Route::match(['post','get'],'my-complain-trash-list','myTrashList')->name('my.trash.list.complain');
        Route::match(['post','get'],'view/{complainID}','singleView')->name('single.view.complain');
        Route::match(['post','get'],'edit/{complainID}','editMe')->name('edit.me.complain');
        Route::match(['post','get'],'delete/{complainID}','destroy')->name('delete.complain');
    });


});


require __DIR__.'/auth.php';
