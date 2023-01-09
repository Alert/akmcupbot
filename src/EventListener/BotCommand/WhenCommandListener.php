<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use App\Service\BotService;
use Carbon\Carbon;
use DateTimeInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: 'tg.callback', method: 'handler')]
class WhenCommandListener extends AbstractCommandListener
{
    public string $name = 'when';
    public string $alias = 'когда';

    public function commandAction(UpdateObject $updateObject): void
    {
        $msg          = $updateObject->getMessage();
        $senderChatId = $msg->chat->id;

        $event = $this->eventRepo->findOneUncompleted();

        if (!$event) {
            $text = $this->translator->trans('no_info', [], 'common');
            $this->bot->sendMessage(['text' => $text], $senderChatId);
            return;
        }

        $text = $this->translator->trans('when.response', [
            '%num%' => $event->getNum(),
            '%readable_dates%' => BotService::escapeString($event->getReadableDates()),
            '%days_count_down%' => BotService::escapeString($this->getDaysCountDownText($event->getDateStart())),
        ], 'tg_commands');

        $params = [
            'text' => $text,
            'parse_mode' => 'MarkdownV2',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    ['text' => 'Схема поля', 'callback_data' => 'when.field'],
                    ['text' => '📝 Расписание', 'callback_data' => 'when.schedule'],
                    ['text' => 'Заявка', 'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSc3wgzDSgsTkGPwYPs1ZhWhifGUVSW0ID5d9LmeV19ZiYkQQA/viewform'],
                ]],
            ])];

        $this->bot->sendMessage($params, $senderChatId);
    }

    /**
     * Get count down days text
     *
     * @param DateTimeInterface $date
     *
     * @return string
     */
    private function getDaysCountDownText(DateTimeInterface $date): string
    {
        $diffDays = $date < Carbon::now() ? 0 : Carbon::now()->diffInDays($date);

        return $this->translator->trans('days_count_down', ['days' => $diffDays], 'common');
    }

    public function btnAction(UpdateObject $updateObject): void
    {
        $callback = $updateObject->callbackQuery;

        if ($callback->data === 'when.schedule') {
//            $this->botApi->sendMediaGroup([
//                'chat_id' => $callback->message->chat->id,
//                'media' => json_encode([
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBi2OUddkVbxl4YG4Yk8bm67XmfWRMAAL2wDEb9OGgSAKEh1h8Cj81AQADAgADeQADKwQ'],
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAIBjGOUddlR1T6ZsXo7uZz4-p8GRu0cAAL3wDEb9OGgSJHtN9GE-5b5AQADAgADeQADKwQ'],
//                ]),
//            ]);
            $this->bot->sendMessage(['text' => 'информации ещё нет :('], $callback->message->chat->id);
            $this->bot->answerCallbackQuery($callback->id);
        }

        if ($callback->data === 'when.field') {
//            $this->botApi->sendMediaGroup([
//                'chat_id' => $callback->message->chat->id,
//                'media' => json_encode([
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN4Y4j8zvkU36ikwl_DKaodymfwIEEAAlbEMRtVOUlIBrchlFbJztgBAAMCAANzAAMrBA'],
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN5Y4j8zvHal_3u9WSv4dU1EkMV4eAAAlfEMRtVOUlIHecXVwcycLsBAAMCAANzAAMrBA'],
//                    ['type' => 'photo', 'media' => 'AgACAgIAAxkBAAN6Y4j8ztGotZcDBfcxpkfKlKv-LBEAAljEMRtVOUlIBWXOM4KaZ9UBAAMCAAN5AAMrBA'],
//                ]),
//            ]);
            $this->bot->sendMessage(['text' => 'информации ещё нет :('], $callback->message->chat->id);
            $this->bot->answerCallbackQuery($callback->id);
        }
    }

}
