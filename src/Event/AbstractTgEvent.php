<?php
declare(strict_types=1);

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Update as UpdateObject;

abstract class AbstractTgEvent extends Event
{
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

    /**
     * Get update object
     *
     * @return UpdateObject
     */
    public function getUpdateObject(): UpdateObject
    {
        return $this->updateObject;
    }
}
