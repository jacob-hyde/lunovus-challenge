<?php

use App\Http\Controllers\Api\GithubUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('github')->group(function () {
    Route::get('search', [GithubUserController::class, 'search'])->name('github.search');
    Route::get('{username}', [GithubUserController::class, 'show'])->name('github.show');
    Route::get('{username}/followers', [GithubUserController::class, 'followers'])->name('github.followers');
});
