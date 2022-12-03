<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;

interface CommandListenerInterface
{
    public const NAME = null;

    public function handler(TgCallbackEvent $e): void;
}
