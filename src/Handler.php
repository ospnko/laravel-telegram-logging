<?php

namespace ScaryLayer\Logging\Telegram;

use Exception;
use Illuminate\Support\Facades\Http;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class Handler extends AbstractProcessingHandler
{
    const API_DOMAIN = 'https://api.telegram.org';

    protected $token;
    protected $chatId;

    public function __construct($level)
    {
        parent::__construct(Logger::toMonologLevel($level), true);

        if (!config('logging.channels.telegram')) {
            throw new Exception('Telegram logging configuration not found');
        }

        $this->token = config('logging.channels.telegram.token');
        $this->chatId = config('logging.channels.telegram.chat_id');
    }

    public function write($record): void
    {
        $url = self::API_DOMAIN
            . '/bot' . $this->token
            . '/sendMessage';

        Http::get($url, [
            'text' => $record['formatted'],
            'chat_id' => $this->chatId,
            'parse_mode' => 'html'
        ]);
    }
}
