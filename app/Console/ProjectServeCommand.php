<?php

namespace App\Console;

use Illuminate\Foundation\Console\ServeCommand;
use Symfony\Component\Process\PhpExecutableFinder;

class ProjectServeCommand extends ServeCommand
{
    /**
     * Apply development-server upload limits without changing the machine's
     * global php.ini. Production PHP-FPM reads the matching public/.user.ini.
     */
    protected function serverCommand(): array
    {
        $server = file_exists(base_path('server.php'))
            ? base_path('server.php')
            : base_path('vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php');

        return [
            (new PhpExecutableFinder)->find(false),
            '-d',
            'upload_max_filesize=50M',
            '-d',
            'post_max_size=64M',
            '-d',
            'max_execution_time=120',
            '-S',
            $this->host() . ':' . $this->port(),
            $server,
        ];
    }
}
