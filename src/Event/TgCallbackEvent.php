<?php
declare(strict_types=1);

namespace App\Event;

use DateTime;
use Symfony\Contracts\EventDispatcher\Event;

class TgCallbackEvent extends Event
{
    public const NAME = 'tg.callback';

    private int $updateId;
    private array $message;
    private DateTime $date;

    public function __construct(array $data)
    {
        $this->updateId = (int)$data['update_id'] ?? null;
        $this->message = $data['message'] ?? null;

        $ts = $this->message['date'] ?? time();
        $this->date = new DateTime('@' . $ts);
    }

    public function getUpdateId(): int
    {
        return $this->updateId;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getText(): string
    {
        return $this->message['text'] ?? '';
    }

    /**
     * Get only command name
     *
     * @return string
     */
    public function getCommand(): string
    {
        $text = $this->getText();
        if (!str_starts_with($text, '/')) return '';
        $text = ltrim($text, '/');
        $delimiterPos = strpos($text, ' ');
        return $delimiterPos ? substr($text, 0, $delimiterPos) : $text;
    }

    /**
     * Get text after command
     *
     * @return string
     */
    public function getTextAfterCommand(): string
    {
        return trim(strpbrk($this->getText(), ' '));
    }
}
