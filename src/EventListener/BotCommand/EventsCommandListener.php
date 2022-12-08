<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\CallbackQuery;
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

    public function btnEventsBack(CallbackQuery $callback){
        $this->bot->editMessageText([
            'chat_id' => $callback->message->chat->id,
            'message_id' => $callback->message->messageId,
            'text' => $this->translator->trans('events.response', [], 'tg_commands'),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => $this->translator->trans('events.1.btn.text', [], 'tg_commands'), 'callback_data' => 'events.1']],
                    [['text' => $this->translator->trans('events.2.btn.text', [], 'tg_commands'), 'callback_data' => 'events.2']],
                    [['text' => $this->translator->trans('events.3.btn.text', [], 'tg_commands'), 'callback_data' => 'events.3']],
                    [['text' => $this->translator->trans('events.4.btn.text', [], 'tg_commands'), 'callback_data' => 'events.4']],
                    [['text' => $this->translator->trans('events.5.btn.text', [], 'tg_commands'), 'callback_data' => 'events.5']],
                ]]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }

    protected function btnAction(UpdateObject $updateObject)
    {
        $callback = $updateObject->callbackQuery;

        if ($callback->data === 'events.back') $this->btnEventsBack($callback);

        if ($callback->data === 'events.1') $this->btnEvents1($callback);
        if ($callback->data === 'events.1.field') $this->btnEvents1Field($callback);
        if ($callback->data === 'events.1.schedule') $this->btnEvents1Schedule($callback);
        if ($callback->data === 'events.1.result') $this->btnEvents1Result($callback);

        if ($callback->data === 'events.2') $this->btnEvents2($callback);
        if ($callback->data === 'events.2.field') $this->btnEvents2Field($callback);
        if ($callback->data === 'events.2.schedule') $this->btnEvents2Schedule($callback);
        if ($callback->data === 'events.2.result') $this->btnEvents2Result($callback);

        if ($callback->data === 'events.3') $this->btnEvents3($callback);
        if ($callback->data === 'events.4') $this->btnEvents4($callback);
        if ($callback->data === 'events.5') $this->btnEvents5($callback);
    }

    public function btnEvents1(CallbackQuery $callback){
        $this->bot->editMessageText([
            'chat_id' => $callback->message->chat->id,
            'message_id' => $callback->message->messageId,
            'text' => $this->translator->trans('events.response', [], 'tg_commands'),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'Схема поля', 'callback_data' => 'events.1.field'],['text' => 'Расписание', 'callback_data' => 'events.1.schedule']],
                    [['text' => 'Результаты', 'callback_data' => 'events.1.result']],
                    [['text' => '🔙 к списку этапов', 'callback_data' => 'events.back']]],
            ]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents1Field(CallbackQuery $callback){
        $this->bot->sendMediaGroup([
            'chat_id' => $callback->message->chat->id,
            'media' => json_encode([
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBEmONBLxyIJlfk-UVUrJPNuOaEvlQAALwxDEb7WlpSFNQ8wl3eH0wAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBE2ONBLzHgZcV3f1MLOghIdzXCHFEAALxxDEb7WlpSKXxIw6j1aT1AQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBFGONBLxT-zq1ypcMiqw4ycfCJrLhAALyxDEb7WlpSIGbtHt7MOXlAQADAgADcwADKwQ'],
            ]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents1Schedule(CallbackQuery $callback){
        $this->bot->sendMediaGroup([
            'chat_id' => $callback->message->chat->id,
            'media' => json_encode([
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBFWONBSlfhDTIXsze6C6BRXB7DIQjAAL2xDEb7WlpSP8JNFrKq-uXAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBFmONBSmcgHAtdwP1UKkWKd4qL7tVAAL3xDEb7WlpSE3OmWwWgjUsAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBF2ONBSmUCHGshw745mM0AAFgNbf2qQAC-MQxG-1paUhNQeJy5O8bhgEAAwIAA3MAAysE'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBGGONBSnQQMDacn9aCV3ip5ACFMNaAAL5xDEb7WlpSKHkhHtS-SoLAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBGWONBSkc3naHCkoOxMXCJ6epYX7sAAL6xDEb7WlpSHRrCAeICD3fAQADAgADcwADKwQ'],
            ]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents1Result(CallbackQuery $callback){
        $params = [
            'chat_id' => $callback->message->chat->id,
            'text' => str_replace('-', '\-', $this->translator->trans('events.1.result', [], 'tg_commands')),
            'parse_mode' => 'MarkdownV2',
        ];
        $this->sendMessage($params, false, $callback->message->chat->id);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }

    public function btnEvents2(CallbackQuery $callback){
        $this->bot->editMessageText([
            'chat_id' => $callback->message->chat->id,
            'message_id' => $callback->message->messageId,
            'text' => $this->translator->trans('events.response', [], 'tg_commands'),
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => 'Схема поля', 'callback_data' => 'events.2.field'],['text' => 'Расписание', 'callback_data' => 'events.2.schedule']],
//                    [['text' => 'Результаты', 'callback_data' => 'events.2.result']],
                    [['text' => 'Заявка', 'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSc3wgzDSgsTkGPwYPs1ZhWhifGUVSW0ID5d9LmeV19ZiYkQQA/viewform']],
                    [['text' => '🔙 к списку этапов', 'callback_data' => 'events.back']]],
            ]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents2Field(CallbackQuery $callback){
        $this->bot->sendMediaGroup([
            'chat_id' => $callback->message->chat->id,
            'media' => json_encode([
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN4Y4j8zvkU36ikwl_DKaodymfwIEEAAlbEMRtVOUlIBrchlFbJztgBAAMCAANzAAMrBA'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN5Y4j8zvHal_3u9WSv4dU1EkMV4eAAAlfEMRtVOUlIHecXVwcycLsBAAMCAANzAAMrBA'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN6Y4j8ztGotZcDBfcxpkfKlKv-LBEAAljEMRtVOUlIBWXOM4KaZ9UBAAMCAAN5AAMrBA'],
            ]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents2Schedule(CallbackQuery $callback){
        $this->bot->sendMediaGroup([
            'chat_id' => $callback->message->chat->id,
            'media' => json_encode([
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBfmOSU0ZD_rgfzdrwObYufa00XHscAAL0xTEbCMWQSBjD6t7WKKHlAQADAgADeQADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBf2OSU0a6Guse5J3H0cQBwCFRXVbTAAL1xTEbCMWQSAqb8IBoNlpjAQADAgADeQADKwQ'],
            ]),
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents2Result(CallbackQuery $callback){
        $this->sendMessage([
            'chat_id' => $callback->message->chat->id,
            'text' => 'информации ещё нет :(',
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }

    public function btnEvents3(CallbackQuery $callback){
        $this->sendMessage([
            'chat_id' => $callback->message->chat->id,
            'text' => 'информации ещё нет :(',
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents4(CallbackQuery $callback){
        $this->sendMessage([
            'chat_id' => $callback->message->chat->id,
            'text' => 'информации ещё нет :(',
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
    public function btnEvents5(CallbackQuery $callback){
        $this->sendMessage([
            'chat_id' => $callback->message->chat->id,
            'text' => 'информации ещё нет :(',
        ]);
        $this->bot->answerCallbackQuery(['callback_query_id' => $callback->id]);
    }
}
