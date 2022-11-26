<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class InfoCommandListener extends AbstractCommandListener
{
    public const COMMAND_NAME = 'info';

    protected function action(TgCallbackEvent $e): void
    {
        $senderChatId = $e->getMessage()['from']['id'] ?? null;

        $params = [
            'chat_id' => $senderChatId,
            'text' => $this->translator->trans(
                'info.response',
                ['%phone%' => $this->cfg->get('contacts.phone')],
                'tg_commands'
            ),
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
        ];
        $this->sendMessage($params, true, $senderChatId);
    }
}
