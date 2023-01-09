<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class StartCommandListener extends AbstractCommandListener
{
    public string $name = 'start';
    public string $alias = 'старт';

    public function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;
        $username     = $msg->from->firstName ?: $msg->from->username;

        $params = [
            'text' => $this->translator->trans('start.response', ['%username%' => $username], 'tg_commands'),
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
        ];
        $this->bot->sendMessage($params, $senderChatId);
    }

    public function btnAction(UpdateObject $updateObject): void
    {
    }
}
