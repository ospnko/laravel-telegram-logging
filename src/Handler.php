<?php

namespace ScaryLayer\Logging\Telegram;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class Handler extends AbstractProcessingHandler
{
    /**
     * Telegram API address
     */
    protected string $api = 'https://api.telegram.org';

    /**
     * Telegram bot token
     */
    protected string $token;

    /**
     * Telegram chat id of chat where message will be sent
     */
    protected string $chatId;

    /**
     * Create new TelegramLogHandler instance
     */
    public function __construct($level)
    {
        parent::__construct(Logger::toMonologLevel($level), true);

        if (!config('logging.channels.telegram'))
            throw new Exception('Telegram logging configuration not found');

        $this->token = config('logging.channels.telegram.token');
        $this->chatId = config('logging.channels.telegram.chat_id');
    }

    /**
     * Writes the record down to the log of the implementing handler
     */
    public function write(array $record): void
    {
        $exception = $record['context']['exception'];

        $message = (new Message)
            ->bold(config('app.name'))
            ->space()
            ->property('TYPE', $record['level_name'], true)
            ->property('TIME', now()->toDateTimeString())
            ->property('ENV', $record['channel'])
            ->property('URL', request()->url())
            ->property('IP', request()->ip())
            ->space()
            ->code($record['message'])
            ->space()
            ->line("{$exception->getFile()} [{$exception->getLine()} line]");

        $response = $this->send($message);

        if ($response->status() != 200) {
            $exceptionMessage = 'Unable to log message to Telegram: '
                . $response->body();
            throw new Exception($exceptionMessage);
        }
    }

    /**
     * Send request to Telegram Bot API
     */
    protected function send(string $message): Response
    {
        $url = "$this->api/bot$this->token/sendMessage";

        return Http::post($url, [
            'text' => $message,
            'chat_id' => $this->chatId,
            'parse_mode' => 'html'
        ]);
    }
}
