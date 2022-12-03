<?php
declare(strict_types=1);

namespace App\Event;

use DateTime;
use Symfony\Contracts\EventDispatcher\Event;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Update as UpdateObject;

class TgCallbackEvent extends Event
{
    public const NAME = 'tg.callback';

    private UpdateObject $updateObject;


    public function __construct(UpdateObject $updateObject)
    {
        $this->updateObject = $updateObject;
    }

    public function getUpdateObject(): UpdateObject
    {
        return $this->updateObject;
    }

//    /**
//     * Get only command name
//     *
//     * @return string
//     */
//    public function getCommand(): string
//    {
//        $text = $this->getText();
//        if (!str_starts_with($text, '/')) return '';
//        $text         = ltrim($text, '/');
//        $delimiterPos = strpos($text, ' ');
//        return $delimiterPos ? substr($text, 0, $delimiterPos) : $text;
//    }
//
//    /**
//     * Get text after command
//     *
//     * @return string
//     */
//    public function getTextAfterCommand(): string
//    {
//        return trim(strpbrk($this->getText(), ' '));
//    }
}
