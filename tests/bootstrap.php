<?php

declare( strict_types = 1 );

namespace Ocolin\Slack\Test;

require_once __DIR__ . '/../vendor/autoload.php';

use Ocolin\EasyEnv\EasyEnvFileHandleError;
use Ocolin\EasyEnv\Env;

try {
    Env::load( files: __DIR__ . '/../.env', append: true );
}
catch( EasyEnvFileHandleError $e ) {
    echo $e->getMessage();
    exit(1);
}
