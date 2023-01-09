<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use Telegram\Bot\Objects\Update as UpdateObject;

/**
 * Interface for all command listeners
 */
interface CommandListenerInterface
{
    /**
     * Command action
     *
     * @param UpdateObject $updateObject
     *
     * @return void
     */
    public function commandAction(UpdateObject $updateObject): void;
}
