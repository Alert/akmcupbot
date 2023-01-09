<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Service\BotService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class InfoCommandListener extends AbstractCommandListener
{
    public string $name = 'info';
    public string $alias = 'инфо';

    public function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;

        $contactsPhone = $this->dynamicParamService->getValue('contacts.phone');

        $params = [
            'text' => $this->translator->trans(
                'info.response',
                ['%phone%' => BotService::escapeString($contactsPhone)],
                'tg_commands'
            ),
            'parse_mode' => 'MarkdownV2',
            'disable_web_page_preview' => true,
        ];
        $this->bot->sendMessage($params, $senderChatId);
    }

    public function btnAction(UpdateObject $updateObject): void
    {
    }
}
