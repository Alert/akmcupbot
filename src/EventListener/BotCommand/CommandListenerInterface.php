<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;

interface CommandListenerInterface
{
    public const COMMAND_NAME = null;

    public function handler(TgCallbackEvent $e): void;
}
