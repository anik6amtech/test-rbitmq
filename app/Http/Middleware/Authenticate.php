<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return void
     */
    protected function redirectTo($request)
    {
        //user verification will be done from here

        if (! $request->expectsJson()) {
            abort(response()->json(responseFormatter(DEFAULT_403), 403));
        }
    }
}
