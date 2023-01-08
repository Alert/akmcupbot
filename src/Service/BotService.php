<?php
declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update as UpdateObject;
use Telegram\Bot\Api;

/**
 * Bot helper service
 */
class BotService
{
    /**
     * Waiting time for message processing (seconds)
     */
    public const MESSAGE_TIME_OUT = 10;

    /**
     * Bot object
     *
     * @var Api
     */
    private Api $bot;

    /**
     * Constructor
     *
     * @throws TelegramSDKException
     */
    public function __construct(string $token)
    {
        $this->bot = new Api($token);
    }

    /**
     * Create and return bot api object
     *
     * @return Api
     */
    public function getApi(): Api
    {
        return $this->bot;
    }

    /**
     * Has date in message
     *
     * @param UpdateObject $data
     *
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
     *
     * @return bool
     */
    public function isMessageTimedOut(UpdateObject $data): bool
    {
        if (!$this->hasMessageSendDate($data)) {
            throw new InvalidArgumentException('Income message don\'t have date');
        }

        return (time() - $data->getMessage()->date) > self::MESSAGE_TIME_OUT;
    }
}
