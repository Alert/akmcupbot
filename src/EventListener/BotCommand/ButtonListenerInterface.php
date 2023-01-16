<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackQueryEvent;

/**
 * Interface for all buttons listeners
 */
interface ButtonListenerInterface
{
    /**
     * Button handler
     *
     * @param TgCallbackQueryEvent $event
     *
     * @return void
     */
    public function buttonHandler(TgCallbackQueryEvent $event): void;
}
