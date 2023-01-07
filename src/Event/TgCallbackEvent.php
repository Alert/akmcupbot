<?php
declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Update as UpdateObject;

class TgCallbackEvent extends Event
{
    /**
     * Event name
     */
    public const NAME = 'tg.callback';

    /**
     * Event data
     *
     * @var UpdateObject
     */
    private UpdateObject $updateObject;

    /**
     * Constructor
     *
     * @param UpdateObject $updateObject
     */
    public function __construct(UpdateObject $updateObject)
    {
        $this->updateObject = $updateObject;
    }

    public function getUpdateObject(): UpdateObject
    {
        return $this->updateObject;
    }
}
