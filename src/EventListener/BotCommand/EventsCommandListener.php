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
    public string $name = 'events';
    public string $alias = 'этапы';

    public function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;

        $params = [
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
        $this->bot->sendMessage($params, $senderChatId);
    }

    public function btnEventsBack(CallbackQuery $callback)
    {
        $this->bot->editMessageText($callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.response', [], 'tg_commands'),
            [
                [['text' => $this->translator->trans('events.1.btn.text', [], 'tg_commands'), 'callback_data' => 'events.1']],
                [['text' => $this->translator->trans('events.2.btn.text', [], 'tg_commands'), 'callback_data' => 'events.2']],
                [['text' => $this->translator->trans('events.3.btn.text', [], 'tg_commands'), 'callback_data' => 'events.3']],
                [['text' => $this->translator->trans('events.4.btn.text', [], 'tg_commands'), 'callback_data' => 'events.4']],
                [['text' => $this->translator->trans('events.5.btn.text', [], 'tg_commands'), 'callback_data' => 'events.5']],
            ]
        );
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnAction(UpdateObject $updateObject):void
    {
        $callback = $updateObject->callbackQuery;

        if ($callback->data === 'events.back') $this->btnEventsBack($callback);

        if ($callback->data === 'events.1') $this->btnEvents1($callback);
        if ($callback->data === 'events.1.field') $this->btnEvents1Field($callback);
        if ($callback->data === 'events.1.schedule') $this->btnEvents1Schedule($callback);
        if ($callback->data === 'events.1.broadcast') $this->btnEvents1Broadcast($callback);
        if ($callback->data === 'events.1.result') $this->btnEvents1Result($callback);

        if ($callback->data === 'events.2') $this->btnEvents2($callback);
        if ($callback->data === 'events.2.field') $this->btnEvents2Field($callback);
        if ($callback->data === 'events.2.schedule') $this->btnEvents2Schedule($callback);
        if ($callback->data === 'events.2.broadcast') $this->btnEvents2Broadcast($callback);
        if ($callback->data === 'events.2.result') $this->btnEvents2Result($callback);

        if ($callback->data === 'events.3') $this->btnEvents3($callback);
        if ($callback->data === 'events.4') $this->btnEvents4($callback);
        if ($callback->data === 'events.5') $this->btnEvents5($callback);
    }

    public function btnEvents1(CallbackQuery $callback)
    {
        $this->bot->editMessageText($callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.response', [], 'tg_commands'),
            [
                [['text' => 'Схема поля', 'callback_data' => 'events.1.field'], ['text' => 'Расписание', 'callback_data' => 'events.1.schedule']],
                [['text' => 'Результаты', 'callback_data' => 'events.1.result'], ['text' => 'Записи игр', 'callback_data' => 'events.1.video']],
                [['text' => '🔙 к списку этапов', 'callback_data' => 'events.back']],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents1Field(CallbackQuery $callback)
    {
        $this->bot->sendMediaGroup(
            $callback->message->chat->id,
            [
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBEmONBLxyIJlfk-UVUrJPNuOaEvlQAALwxDEb7WlpSFNQ8wl3eH0wAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBE2ONBLzHgZcV3f1MLOghIdzXCHFEAALxxDEb7WlpSKXxIw6j1aT1AQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBFGONBLxT-zq1ypcMiqw4ycfCJrLhAALyxDEb7WlpSIGbtHt7MOXlAQADAgADcwADKwQ'],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents1Schedule(CallbackQuery $callback)
    {
        $this->bot->sendMediaGroup(
            $callback->message->chat->id,
            [
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBFWONBSlfhDTIXsze6C6BRXB7DIQjAAL2xDEb7WlpSP8JNFrKq-uXAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBFmONBSmcgHAtdwP1UKkWKd4qL7tVAAL3xDEb7WlpSE3OmWwWgjUsAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBF2ONBSmUCHGshw745mM0AAFgNbf2qQAC-MQxG-1paUhNQeJy5O8bhgEAAwIAA3MAAysE'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBGGONBSnQQMDacn9aCV3ip5ACFMNaAAL5xDEb7WlpSKHkhHtS-SoLAQADAgADcwADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBGWONBSkc3naHCkoOxMXCJ6epYX7sAAL6xDEb7WlpSHRrCAeICD3fAQADAgADcwADKwQ'],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents1Broadcast(CallbackQuery $callback)
    {
        $this->bot->editMessageText(
            $callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.broadcast', [], 'tg_commands'),
            [
                'inline_keyboard' => [
                    [
                        ['text' => '1️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239019'],
                        ['text' => '2️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239026'],
                    ],
                    [
                        ['text' => '3️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239022'],
                        ['text' => '4️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239032'],
                    ],
                    [['text' => '🔙 к списку этапов', 'callback_data' => 'events.back']]],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents1Result(CallbackQuery $callback)
    {
        $params = [
            'text' => str_replace('-', '\-', $this->translator->trans('events.1.result', [], 'tg_commands')),
            'parse_mode' => 'MarkdownV2',
        ];
        $this->bot->sendMessage($params, $callback->message->chat->id);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents2(CallbackQuery $callback)
    {
        $this->bot->editMessageText($callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.response', [], 'tg_commands'), [
                [['text' => 'Схема поля', 'callback_data' => 'events.2.field'], ['text' => '📝 Расписание', 'callback_data' => 'events.2.schedule']],
                [['text' => 'Результаты', 'callback_data' => 'events.2.result'], ['text' => 'Записи игр', 'callback_data' => 'events.2.broadcast']],
//                    [['text' => 'Заявка', 'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSc3wgzDSgsTkGPwYPs1ZhWhifGUVSW0ID5d9LmeV19ZiYkQQA/viewform']],
                [['text' => '🔙 к списку этапов', 'callback_data' => 'events.back']],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents2Field(CallbackQuery $callback)
    {
        $this->bot->sendMediaGroup(
            $callback->message->chat->id,
            [
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN4Y4j8zvkU36ikwl_DKaodymfwIEEAAlbEMRtVOUlIBrchlFbJztgBAAMCAANzAAMrBA'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN5Y4j8zvHal_3u9WSv4dU1EkMV4eAAAlfEMRtVOUlIHecXVwcycLsBAAMCAANzAAMrBA'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN6Y4j8ztGotZcDBfcxpkfKlKv-LBEAAljEMRtVOUlIBWXOM4KaZ9UBAAMCAAN5AAMrBA'],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents2Schedule(CallbackQuery $callback)
    {
        $this->bot->sendMediaGroup(
            $callback->message->chat->id,
            [
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBi2OUddkVbxl4YG4Yk8bm67XmfWRMAAL2wDEb9OGgSAKEh1h8Cj81AQADAgADeQADKwQ'],
                ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBjGOUddlR1T6ZsXo7uZz4-p8GRu0cAAL3wDEb9OGgSJHtN9GE-5b5AQADAgADeQADKwQ'],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents2Broadcast(CallbackQuery $callback)
    {
        $this->bot->editMessageText(
            $callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.broadcast', [], 'tg_commands'),
            [
                'inline_keyboard' => [
                    [
                        ['text' => '1️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239036'],
                        ['text' => '2️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239041'],
                    ],
                    [
                        ['text' => '3️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239038'],
                        ['text' => '4️⃣ Дивизион', 'url' => 'https://vk.com/video-215238258_456239045'],
                    ],
                    [['text' => '🔙 к списку этапов', 'callback_data' => 'events.back']]],
            ]);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents2Result(CallbackQuery $callback)
    {
        $params = [
            'text' => str_replace('-', '\-', $this->translator->trans('events.2.result', [], 'tg_commands')),
            'parse_mode' => 'MarkdownV2',
        ];
        $this->bot->sendMessage($params, $callback->message->chat->id);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents3(CallbackQuery $callback)
    {
        $this->bot->sendMessage(['text' => 'информации ещё нет :('], $callback->message->chat->id);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents4(CallbackQuery $callback)
    {
        $this->bot->sendMessage(['text' => 'информации ещё нет :('], $callback->message->chat->id);
        $this->bot->answerCallbackQuery($callback->id);
    }

    public function btnEvents5(CallbackQuery $callback)
    {
        $this->bot->sendMessage(['text' => 'информации ещё нет :('], $callback->message->chat->id);
        $this->bot->answerCallbackQuery($callback->id);
    }
}
