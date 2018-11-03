<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //排除不需要csrf token验证的路由
        'login','member/create','address/create','address/update','order/create',
        'member/changePassword','member/forgetPassword','cart/create'
    ];
}
