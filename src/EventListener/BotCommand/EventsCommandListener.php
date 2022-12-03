<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use DateTime;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class EventsCommandListener extends AbstractCommandListener
{
    public const NAME = 'events';
    public const ALIAS = 'этапы';

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
            'text' => $this->translator->trans('events.response', [], 'tg_commands'),
            'parse_mode' => 'MarkdownV2',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => $this->translator->trans('events.1.btn.text', [], 'tg_commands'), 'callback_data' => 'events.1']],
                    [['text' => $this->translator->trans('events.2.btn.text', [], 'tg_commands'), 'callback_data' => 'events.2']],
                    [['text' => $this->translator->trans('events.3.btn.text', [], 'tg_commands'), 'callback_data' => 'events.3']],
                    [['text' => $this->translator->trans('events.4.btn.text', [], 'tg_commands'), 'callback_data' => 'events.4']],
                    [['text' => $this->translator->trans('events.5.btn.text', [], 'tg_commands'), 'callback_data' => 'events.5']],
                ],
            ])];
        $this->sendMessage($params, false, $senderChatId);
    }
}
