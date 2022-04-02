<?php

namespace Bootstrap;

use Bootstrap\IncludePath;

require __DIR__ .'/../config/app.php';
require __DIR__ .'/../bootstrap/includePath.php';

$includeFile = new IncludePath(REQUIREDPATHS);
$includeFile->addAllRequiredFiles();
