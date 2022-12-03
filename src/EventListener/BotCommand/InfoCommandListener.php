<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class InfoCommandListener extends AbstractCommandListener
{
    public const NAME = 'info';
    public const ALIAS = 'инфо';

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
