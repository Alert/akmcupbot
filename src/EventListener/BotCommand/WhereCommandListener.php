<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class WhereCommandListener extends AbstractCommandListener
{
    public string $name = 'where';
    public string $alias = 'где';

    public function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;

        $params = [
            'text' => $this->translator->trans('where.response', [], 'tg_commands'),
            'parse_mode' => 'Markdown',
        ];
        $this->bot->sendMessage($params, $senderChatId);
    }

    public function btnAction(UpdateObject $updateObject): void
    {
    }
}
