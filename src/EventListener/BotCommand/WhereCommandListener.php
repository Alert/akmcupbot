<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class WhereCommandListener extends AbstractCommandListener
{
    public const COMMAND_NAME = 'where';

    protected function action(TgCallbackEvent $e): void
    {
        $senderChatId = $e->getMessage()['from']['id'] ?? null;

        $params = [
            'chat_id' => $senderChatId,
            'text' => $this->translator->trans('where.response', [], 'tg_commands'),
            'parse_mode' => 'Markdown',
        ];
        $this->sendMessage($params, false, $senderChatId);
    }
}
