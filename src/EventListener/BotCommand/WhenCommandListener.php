<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

// TODO: delete
#[AsEventListener(event: 'tg.callback', method: 'handler')]
class WhenCommandListener extends AbstractCommandListener
{
    public const NAME = 'when';
    public const ALIAS = 'когда';

    public function handler(TgCallbackEvent $e): void
    {
        $update = $e->getUpdateObject();

        if ($update->objectType() === 'message') {
            $text = $update->getMessage()->text ?? '';
            if (str_starts_with($text, '/' . self::NAME) || str_starts_with($text, '/' . self::ALIAS))
                $this->commandAction($update);
        }

        if ($update->objectType() === 'callback_query') {
            $this->btnAction($update);
        }
    }

    protected function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;

        $params = [
            'chat_id' => $senderChatId,
            'text' => $this->translator->trans('when.response', [], 'tg_commands'),
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => 'Схема поля', 'callback_data' => 'when.field'],
                    ['text' => '📝 Расписание', 'callback_data' => 'when.schedule'],
                    ['text' => 'Заявка', 'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSc3wgzDSgsTkGPwYPs1ZhWhifGUVSW0ID5d9LmeV19ZiYkQQA/viewform'],
                ]],
            ])];

        $this->sendMessage($params, false, $senderChatId);
    }

    protected function btnAction(UpdateObject $updateObject)
    {
        $callback = $updateObject->callbackQuery;

        if ($callback->data === 'when.schedule') {
//            $this->bot->sendMediaGroup([
//                'chat_id' => $callback->message->chat->id,
//                'media' => json_encode([
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBi2OUddkVbxl4YG4Yk8bm67XmfWRMAAL2wDEb9OGgSAKEh1h8Cj81AQADAgADeQADKwQ'],
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBjGOUddlR1T6ZsXo7uZz4-p8GRu0cAAL3wDEb9OGgSJHtN9GE-5b5AQADAgADeQADKwQ'],
//                ]),
//            ]);
//            $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
            $this->sendMessage([
                'chat_id' => $callback->message->chat->id,
                'text' => 'информации ещё нет :(',
            ]);
            $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
        }

        if ($callback->data === 'when.field') {
//            $this->bot->sendMediaGroup([
//                'chat_id' => $callback->message->chat->id,
//                'media' => json_encode([
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN4Y4j8zvkU36ikwl_DKaodymfwIEEAAlbEMRtVOUlIBrchlFbJztgBAAMCAANzAAMrBA'],
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN5Y4j8zvHal_3u9WSv4dU1EkMV4eAAAlfEMRtVOUlIHecXVwcycLsBAAMCAANzAAMrBA'],
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN6Y4j8ztGotZcDBfcxpkfKlKv-LBEAAljEMRtVOUlIBWXOM4KaZ9UBAAMCAAN5AAMrBA'],
//                ]),
//            ]);
//            $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
            $this->sendMessage([
                'chat_id' => $callback->message->chat->id,
                'text' => 'информации ещё нет :(',
            ]);
            $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
        }
    }

}
