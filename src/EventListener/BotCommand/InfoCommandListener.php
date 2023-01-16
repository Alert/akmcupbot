<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackQueryEvent;
use App\Event\TgMessageEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: TgMessageEvent::class, method: 'commandHandler')]
#[AsEventListener(event: TgCallbackQueryEvent::class, method: 'buttonHandler')]
class InfoCommandListener extends AbstractCommandListener
{
    public string $name = 'info';
    public string $alias = 'инфо';

    /**
     * {@inheritdoc}
     */
    public function commandAction(UpdateObject $updateObj): void
    {
        $msg          = $updateObj->getMessage();
        $senderChatId = $msg->chat->id;

        $contactsPhone = $this->dynamicParamService->getValue('contacts.phone');

        $text = $this->dynamicParamService->getValue('start.response');
        $text = str_replace('%phone%', $contactsPhone, $text);

        $params = ['text' => $text, 'parse_mode' => 'MarkdownV2', 'disable_web_page_preview' => true,];
        $this->bot->sendMessage($params, $senderChatId);
    }

    /**
     * {@inheritdoc}
     */
    public function buttonAction(UpdateObject $updateObj): void
    {
    }
}
