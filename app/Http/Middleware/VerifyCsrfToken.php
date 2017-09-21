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
        '/medic/account/settings',
        '/medic/account/offices/*',
        '/medic/appointments',
        '/medic/appointments/*',
        '/medic/schedules',
        '/medic/schedules/*',
        '/medic/invoices/*',
        '/clinic/appointments',
        '/clinic/appointments/*',
        '/appointments',
        '/appointments/*',
        '/account/avatars',
        '/assistant/invoices/*',
        '/clinic/invoices/*',
        '/appointments/reminder',
        '/polls/send',
    ];
}
