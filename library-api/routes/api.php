<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\MemberController;

Route::apiResource('books', BookController::class);
Route::apiResource('members', MemberController::class);
