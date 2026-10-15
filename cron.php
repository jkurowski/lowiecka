<?php

/**
 * Cron entry point - runs the Laravel scheduler (app/Console/Kernel.php).
 *
 * Set the hosting cron to run every minute:
 *   * * * * * php /path/to/project/cron.php >> /dev/null 2>&1
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden');
}

chdir(__DIR__);

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('schedule:run');

echo $kernel->output();

$kernel->terminate(new Symfony\Component\Console\Input\ArrayInput([]), $status);

exit($status);
