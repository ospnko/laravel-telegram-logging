<?php

namespace ScaryLayer\Logging\Telegram;

use Stringable;

class Message implements Stringable
{
    /**
     * End result of message generating
     */
    protected $message = [];

    /**
     * Add bold line to message
     */
    public function bold(string $text): self
    {
        $text = strip_tags($text);
        $text = explode("\n", $text)[0];

        $this->message[] = "<b>$text</b>";

        return $this;
    }

    /**
     * Add line to message
     */
    public function line(string $line): self
    {
        $line = strip_tags($line);
        $this->message[] = $line;

        return $this;
    }

    /**
     * Add property line to message
     */
    public function property(
        string $name,
        string $value,
        bool $isBold = false
    ): self {
        $value = strip_tags($value);
        $value = $isBold ? "<b>$value</b>" : $value;

        $this->message[] = "$name: $value";

        return $this;
    }

    /**
     * Add empty line to message
     */
    public function space(): self
    {
        $this->message[] = '';

        return $this;
    }

    /**
     * Convert message to string
     */
    public function __toString(): string
    {
        $string = '';
        foreach ($this->message as $line) {
            $string .= "$line\n";
        }

        $string = $this->limitStringLength($string);

        return $string;
    }

    /**
     * Cut string if it's longer than limit
     */
    protected function limitStringLength(string $string): string
    {
        $limit = 4000;

        return mb_strlen($string) > $limit
            ? mb_substr($string, 0, $limit) . '...'
            : $string;
    }
}