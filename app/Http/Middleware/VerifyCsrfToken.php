<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/medic/patients/files',
        '/medic/patients/files/delete',
        '/medic/patients/photos',
        '/medic/account/avatars',
        '/medic/appointments',
        '/medic/appointments/*',
        '/appointments',
        '/appointments/*',
        '/account/avatars',
    ];
}
