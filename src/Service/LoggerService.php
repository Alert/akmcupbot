<?php
declare(strict_types=1);

namespace App\Service;

use App\Repository\WebhookLogRepository;
use DateTime;
use Doctrine\DBAL\Exception as DBALException;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Objects\Update as UpdateObject;

/**
 * Logger service
 */
class LoggerService
{
    /**
     * @var WebhookLogRepository
     */
    private WebhookLogRepository $webhookLogRepo;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $botLogger;

    /**
     * Construct
     *
     * @param WebhookLogRepository $webhookLogRepo
     * @param LoggerInterface      $botLogger
     */
    public function __construct(WebhookLogRepository $webhookLogRepo, LoggerInterface $botLogger)
    {
        $this->webhookLogRepo = $webhookLogRepo;
        $this->botLogger      = $botLogger;
    }

    /**
     * Get specific file logger for bot
     *
     * @return LoggerInterface
     */
    public function getBotLogger(): LoggerInterface
    {
        return $this->botLogger;
    }

    /**
     * Log webhook data to db
     *
     * @param UpdateObject $data
     *
     * @return void
     */
    public function logWebhookData(UpdateObject $data): void
    {
        $this->getBotLogger()->info('Webhook receive data', compact('data'));

        $message   = $data->getMessage();
        $username  = $message->from?->username;
        $firstName = $message->from?->firstName;
        $lastName  = $message->from?->lastName;

        try {
            $this->webhookLogRepo->savePlainSql(new DateTime(), $username, $firstName, $lastName, (string)$data);
        } catch (DBALException $e) {
            $this->getBotLogger()->error('DBAL error: ' . $e->getMessage());
        }
    }

}
