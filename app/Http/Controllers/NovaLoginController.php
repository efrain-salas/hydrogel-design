<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Controllers\LoginController;

class NovaLoginController extends LoginController
{
    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect('/resources/designs');
    }
}
