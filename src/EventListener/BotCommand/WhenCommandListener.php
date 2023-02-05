<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Entity\EventEntity;
use App\Event\TgCallbackQueryEvent;
use App\Event\TgMessageEvent;
use App\Service\BotService;
use Carbon\Carbon;
use DateTimeInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Telegram\Bot\Objects\Update as UpdateObject;

#[AsEventListener(event: TgMessageEvent::class, method: 'commandHandler')]
#[AsEventListener(event: TgCallbackQueryEvent::class, method: 'buttonHandler')]
class WhenCommandListener extends AbstractCommandListener
{
    public string $name = 'when';
    public string $alias = 'когда';

    /**
     * {@inheritdoc}
     */
    public function commandAction(UpdateObject $updateObj): void
    {
        $msg          = $updateObj->getMessage();
        $senderChatId = $msg->chat->id;

        $event = $this->doctrine->getRepository(EventEntity::class)->findOneUncompleted();

        if (!$event) {
            $text = $this->translator->trans('no_info', [], 'common');
            $this->bot->sendMessage(['text' => $text], $senderChatId);
            return;
        }

        $text = $this->translator->trans('when.response', [
            'num' => $event->getNum(),
            'readable_dates' => BotService::escapeString($event->getReadableDates()),
            'days_count_down' => BotService::escapeString($this->getDaysCountDownText($event->getDateStart())),
        ], 'common');

        $params = [
            'text' => $text,
            'parse_mode' => 'MarkdownV2',
            'disable_web_page_preview' => true,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [[
                        'text' => $this->translator->trans('when.btn.info.text', [], 'common'),
                        'callback_data' => sprintf('s%de%d.info', $event->getSeason()->getId(), $event->getNum()),
                    ]],
                ],
            ]),
        ];

        $this->bot->sendMessage($params, $senderChatId);
    }

    /**
     * {@inheritdoc}
     */
    public function buttonAction(UpdateObject $updateObj): void
    {
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
        $today    = Carbon::now()->setTime(0, 0, 0);
        $diffDays = $date < $today ? 0 : $today->diffInDays($date);

        return $this->translator->trans('days_count_down', ['days' => $diffDays], 'common');
    }
}
