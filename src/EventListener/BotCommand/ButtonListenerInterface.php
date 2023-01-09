<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use Telegram\Bot\Objects\Update as UpdateObject;

/**
 * Interface for all buttons listeners
 */
interface ButtonListenerInterface
{
    /**
     * Buttons action
     *
     * @param UpdateObject $updateObject
     *
     * @return void
     */
    public function btnAction(UpdateObject $updateObject): void;
}
