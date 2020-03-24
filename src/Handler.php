<?php

namespace ScaryLayer\Logging\Telegram;

use Exception;
use Illuminate\Support\Facades\Http;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class Handler extends AbstractProcessingHandler
{
    const API_DOMAIN = 'https://api.telegram.org/bot';

    protected $token;
    protected $chatId;

    public function __construct($level)
    {
        parent::__construct(Logger::toMonologLevel($level), true);

        if (!config('logging.channels.telegram')) {
            throw new Exception('Telegram logging configuration not found');
        }

        $this->token  = config('logging.channels.telegram.token');
        $this->chatId = config('logging.channels.telegram.chat_id');
    }

    public function write($record): void
    {
        $this->send($this->format($record));
    }

    public function send(string $message)
    {
        $url = self::API_DOMAIN . $this->token . '/sendMessage';

        return Http::get($url, [
            'text' => $message,
            'chat_id' => $this->chatId,
            'parse_mode' => 'html'
        ]);
    }

    public function format($record)
    {
        return "[{$record['level_name']}] <b>" . config('app.name') . "</b>\n"
            . "———————————\n"
            . "TIME: " . now()->toDateTimeString() . "\n"
            . "ENV: {$record['channel']} \n"
            . "URL: " . request()->url() . "\n"
            . "IP: " . request()->ip() . "\n"
            . "\n"
            . "<b>{$record['message']}</b>\n"
            . $record['context']['exception']->getFile()
            . " [{$record['context']['exception']->getLine()}]";
    }
}
