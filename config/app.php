<?php

namespace Config;

use Lib\Random;

return [
    'STATE' => Random::generateRandomString(18),
    'SCOPE' => 'repo',
    'ACCESS_TOKEN_LINK' => 'https://github.com/login/oauth/access_token',
    'AUTHORIZE_LINK' => 'https://github.com/login/oauth/authorize',
    'VIEW_ROUTE' => __DIR__ .'/../src/Views',
    'ASSIGNEE_URL_NUMBER' => 16,
];

