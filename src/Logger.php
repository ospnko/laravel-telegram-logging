<?php

namespace ScaryLayer\Logging\Telegram;

use Monolog\Logger as MonologLogger;

class Logger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        return new MonologLogger(config('app.name'), [
            new Handler($config['level'])
        ]);
    }
}
