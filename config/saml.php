<?php

return [

    'auth_redirect_route' => 'dashboard',
    'redirect_route' => 'dashboard',
    'signin_route' => 'signin',
    'inertia' => true,
    'slo_redirect' =>  env('SLO_REDIRECT', 'autum.com.br'),
    'autum_acs_url' =>  env('IDP_URL', 'https://accounts-local.autum.com.br') . '/login',
    'autum_sls_url' =>  env('IDP_URL', 'https://accounts-local.autum.com.br') . '/logout',
];