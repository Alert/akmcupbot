<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackQueryEvent;
use App\Event\TgMessageEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: TgMessageEvent::class, method: 'commandHandler')]
#[AsEventListener(event: TgCallbackQueryEvent::class, method: 'buttonHandler')]
class StartCommandListener extends AbstractCommandListener
{
    public string $name = 'start';
    public string $alias = 'старт';

    /**
     * {@inheritdoc}
     */
    public function commandAction(UpdateObject $updateObj): void
    {
        $msg          = $updateObj->getMessage();
        $senderChatId = $msg->chat->id;

        $params = [
            'text' => $this->dynamicParamService->getValue('start.response'),
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
        ];
        $this->bot->sendMessage($params, $senderChatId);
    }

    /**
     * {@inheritdoc}
     */
    public function buttonAction(UpdateObject $updateObj): void
    {
    }
}
