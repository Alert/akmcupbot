<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgMessageEvent;

/**
 * Interface for all command listeners
 */
interface CommandListenerInterface
{
    /**
     * Command event handler
     *
     * @param TgMessageEvent $event
     *
     * @return void
     */
    public function commandHandler(TgMessageEvent $event): void;
}
