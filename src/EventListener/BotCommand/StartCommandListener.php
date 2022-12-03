<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class StartCommandListener extends AbstractCommandListener
{
    public const NAME = 'start';
    public const ALIAS = 'старт';

    public function handler(TgCallbackEvent $e): void
    {
        $update = $e->getUpdateObject();

        if ($update->objectType() === 'message') {
            $text = $update->getMessage()->text ?? '';
            if (str_starts_with($text, '/' . self::NAME) || str_starts_with($text, '/' . self::ALIAS))
                $this->commandAction($update);
        }
    }

    protected function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;
        $username     = $msg->from->firstName ?: $msg->from->username;

        $params = [
            'chat_id' => $senderChatId,
            'text' => $this->translator->trans(
                'start.response',
                ['%username%' => $username],
                'tg_commands'
            ),
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
        ];
        $this->sendMessage($params, true, $senderChatId);
    }
}
