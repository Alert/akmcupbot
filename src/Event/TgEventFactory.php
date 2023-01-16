<?php
declare(strict_types=1);

namespace App\Event;

use LogicException;
use Symfony\Contracts\EventDispatcher\Event;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Update as UpdateObject;

class TgEventFactory
{
    public const TYPE_MESSAGE = 'message';
    public const TYPE_CALLBACK_QUERY = 'callback_query';

    /**
     * Create event
     *
     * @param UpdateObject $updateObj
     *
     * @return Event
     */
    public function create(UpdateObject $updateObj): Event
    {
        return match ($updateObj->objectType()) {
            self::TYPE_MESSAGE => new TgMessageEvent($updateObj),
            self::TYPE_CALLBACK_QUERY => new TgCallbackQueryEvent($updateObj),
            default => throw new LogicException('Unsupported callback object type: ' . $updateObj->objectType()),
        };
    }
}
