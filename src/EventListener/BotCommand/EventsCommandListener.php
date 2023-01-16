<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Entity\EventDetailEntity;
use App\Entity\EventEntity;
use App\Entity\SeasonEntity;
use App\Event\TgCallbackQueryEvent;
use App\Event\TgMessageEvent;
use App\Object\CallbackParsedDataObj;
use App\Service\BotService;
use DateTime;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: TgMessageEvent::class, method: 'commandHandler')]
#[AsEventListener(event: TgCallbackQueryEvent::class, method: 'buttonHandler')]
class EventsCommandListener extends AbstractCommandListener
{
    public string $name = 'events';
    public string $alias = 'этапы';

    /**
     * {@inheritdoc}
     */
    public function commandAction(UpdateObject $updateObj): void
    {
        $msg          = $updateObj->getMessage();
        $senderChatId = $msg->chat->id;

        $params = [
            'text' => $this->translator->trans('events.response', [], 'common'),
            'parse_mode' => 'MarkdownV2',
            'reply_markup' => json_encode(['inline_keyboard' => $this->getEventsBtnsList()]),
        ];

        $this->bot->sendMessage($params, $senderChatId);
    }

    /**
     * {@inheritdoc}
     */
    public function buttonAction(UpdateObject $updateObj): void
    {
        $senderChatId          = $updateObj->callbackQuery->message->chat->id;
        $callbackParsedDataObj = new CallbackParsedDataObj($updateObj->callbackQuery->data);

        $event = $this->doctrine->getRepository(EventEntity::class)
            ->findOneBySeasonNumAndEventNum($callbackParsedDataObj->seasonNum, $callbackParsedDataObj->eventNum);

        if ($callbackParsedDataObj->action === 'info') {
            $this->btnEventInfo($event, $updateObj->callbackQuery);
            return;
        }

        if ($callbackParsedDataObj->action === 'back') {
            $this->btnEventsBack($updateObj->callbackQuery);
            return;
        }

        if ($callbackParsedDataObj->action === EventDetailEntity::BTN_ACTION_BROADCAST) {
            $this->btnEventBroadcast($event, $updateObj->callbackQuery);
            return;
        }

        if (!$detail = $event->findDetailByBtnAction($callbackParsedDataObj->action)) return;

        switch ($detail->getType()) {
            case EventDetailEntity::TYPE_MEDIA:
                $this->bot->sendMediaGroup($senderChatId, json_decode($detail->getValue()));
                break;
            case EventDetailEntity::TYPE_TEXT:
                $params = ['text' => BotService::escapeString($detail->getValue()), 'parse_mode' => 'MarkdownV2'];
                $this->bot->sendMessage($params, $senderChatId);
                break;
        }

        $this->bot->answerCallbackQuery($updateObj->callbackQuery->id);
    }

    /**
     * Handler for button event info
     *
     * @param EventEntity   $event
     * @param CallbackQuery $callbackData
     *
     * @return void
     * @throws TelegramSDKException
     */
    public function btnEventInfo(EventEntity $event, CallbackQuery $callbackData): void
    {
        $season = $event->getSeason();

        $rows       = $row = [];
        $countInRow = 0;
        foreach ($event->getDetails() as $detail) {
            if ($detail->getType() === EventDetailEntity::TYPE_LINK) {
                $btn = ['text' => $detail->getBtnText(), 'url' => $detail->getValue()];
            } else {
                $btnCallbackData = sprintf('s%de%d.%s', $season->getId(), $event->getNum(), $detail->getBtnAction());
                $btn             = ['text' => $detail->getBtnText(), 'callback_data' => $btnCallbackData];
            }
            $row[] = $btn;

            if (++$countInRow === self::BUTTONS_PER_ROW) {
                $rows[]     = $row;
                $row        = [];
                $countInRow = 0;
            }
        }
        if ($row) $rows[] = $row;

        if ($rows) {
            $text = $this->translator->trans('event.btn.text', [
                'num' => $event->getNum(),
                'readable_dates' => $event->getReadableDates(),
            ], 'common');
        } else {
            $text = $this->translator->trans('no_info', [], 'common');
        }

        $rows[] = [[
            'text' => $this->translator->trans('btn.back-to-events-list', [], 'common'),
            'callback_data' => sprintf('s%de%d.back', $season->getId(), $event->getNum()),
        ]];

        $this->bot->editMessageText($callbackData->message->chat->id, $callbackData->message->messageId, $text, $rows);
        $this->bot->answerCallbackQuery($callbackData->id);
    }

    /**
     * Back to events list button
     *
     * @param CallbackQuery $callback
     *
     * @return void
     * @throws TelegramSDKException
     */
    public function btnEventsBack(CallbackQuery $callback): void
    {
        $this->bot->editMessageText($callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.response', [], 'common'),
            $this->getEventsBtnsList()
        );
        $this->bot->answerCallbackQuery($callback->id);
    }

    /**
     * Get buttons list of events in last season
     *
     * @return array
     */
    private function getEventsBtnsList(): array
    {
        $season = $this->doctrine->getRepository(SeasonEntity::class)->findLast();
        $events = $season->getEvents();

        $buttons = [];
        foreach ($events as $event) {
            $textKey = $event->getDateEnd() < new DateTime() ? 'event.btn.text.passed' : 'event.btn.text';

            $buttons[] = [[
                'text' => $this->translator->trans($textKey, [
                    'num' => $event->getNum(),
                    'readable_dates' => $event->getReadableDates(),
                ], 'common'),
                'callback_data' => sprintf('s%de%d.info', $season->getId(), $event->getNum()),
            ]];
        }

        return $buttons;
    }

    /**
     * Button event 'broadcast'
     *
     * @param EventEntity   $event
     * @param CallbackQuery $callback
     *
     * @return void
     * @throws TelegramSDKException
     */
    public function btnEventBroadcast(EventEntity $event, CallbackQuery $callback): void
    {
        if (!$detail = $event->findDetailByBtnAction(EventDetailEntity::BTN_ACTION_BROADCAST)) return;

        $rows   = json_decode($detail->getValue());
        $rows[] = [[
            'text' => $this->translator->trans('btn.back-to-events-list', [], 'common'),
            'callback_data' => sprintf('s%de%d.back', $event->getSeason()->getId(), $event->getNum()),
        ]];

        $this->bot->editMessageText(
            $callback->message->chat->id,
            $callback->message->messageId,
            $this->translator->trans('events.broadcast', [], 'common'),
            $rows
        );
        $this->bot->answerCallbackQuery($callback->id);
    }

}
