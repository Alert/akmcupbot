<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;

/**
 * Interface for all command listeners
 */
interface CommandListenerInterface
{
    /**
     * @param TgCallbackEvent $e
     *
     * @return void
     */
    public function handler(TgCallbackEvent $e): void;
}
