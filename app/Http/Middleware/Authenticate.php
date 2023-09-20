<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'TransactionController@index');
        Route::get('/deposit', 'TransactionController@showDeposits');
        Route::post('/deposit', 'TransactionController@deposit');
        Route::get('/withdrawal', 'TransactionController@showWithdrawals');
        Route::post('/withdrawal', 'TransactionController@withdraw');
    });
    
}
