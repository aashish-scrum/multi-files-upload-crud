<?php

use App\Http\Controllers\UploadController;
use App\Http\Controllers\MultiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UploadController::class, "getRows"])->name("single");
Route::post("/insert", [UploadController::class, "formwork"])->name("formwork");
Route::get("/edit/{id}", [UploadController::class, "getRows"])->name("edit");
Route::get("/delete/{id}", [UploadController::class, "delete"])->name("delete");
Route::get("/del_img/{id}", [UploadController::class, "del_img"])->name("remove_img");

Route::group(["prefix"=>"/multi"],function () {
    Route::get('/', [MultiController::class, "getRows"])->name("multi");
    Route::post('/insert', [MultiController::class, "formwork"])->name("multiinsert");
    Route::get('/delete/{id}', [MultiController::class, "delete"])->name("multi_del");
    Route::get('/edit/{id}', [MultiController::class, "getRows"])->name("multi_edit");
    Route::get('/removeone/{id}', [MultiController::class, "del_img"])->name("removeone");
});

