<?php

namespace App\Service;

use App\Repository\WebhookLogRepository;
use DateTime;
use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Objects\Update as UpdateObject;

/**
 * Bot helper service
 */
class BotHelperService
{
    /**
     * Waiting time for message processing (seconds)
     */
    public const MESSAGE_TIME_OUT = 10;

    /**
     * Has date in message
     *
     * @param UpdateObject $data
     * @return bool
     */
    public function hasMessageSendDate(UpdateObject $data): bool
    {
        return (bool)$data->getMessage()?->date;
    }

    /**
     * Is message time out
     *
     * @param UpdateObject $data
     * @return bool
     */
    public function isMessageTimedOut(UpdateObject $data): bool
    {
        if (!$this->hasMessageSendDate($data)) {
            throw new \InvalidArgumentException('Income message don\'t have date');
        }

        return (time() - $data->getMessage()->date) > self::MESSAGE_TIME_OUT;
    }
}
