<?php

namespace Lib;

use Exception;

class TokenException extends Exception
{

    public function canNotGetData(): void
    {
        echo "Error: can not get data from access token.\n";
        exit;
    }

    public function canNotGet(): void
    {
        echo "Error: can not get token.\n";
        exit;
    }
}
