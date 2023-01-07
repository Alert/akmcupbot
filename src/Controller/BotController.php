<?php
declare(strict_types=1);

namespace App\Controller;


use App\Event\TgCallbackEvent;
use App\Service\BotHelperService;
use App\Service\LoggerService;
use Borsaco\TelegramBotApiBundle\Service\Bot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;

#[Route('/bot', name: 'bot.')]
class BotController extends AbstractController
{
    /**
     * Bot api
     *
     * @var Api
     */
    private Api $bot;

    /**
     * Event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * Logger service
     *
     * @var LoggerService
     */
    private LoggerService $logger;

    /**
     * Bot helper
     *
     * @var BotHelperService
     */
    private BotHelperService $botHelper;

    /**
     * Constructor
     *
     * @param Bot                      $bot
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerService            $logger
     * @param BotHelperService         $botHelper
     */
    public function __construct(Bot                      $bot,
                                EventDispatcherInterface $dispatcher,
                                LoggerService            $logger,
                                BotHelperService         $botHelper)
    {
        $this->bot        = $bot->getBot();
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->botHelper  = $botHelper;
    }

    /**
     * Get income data from Telegram by webhook
     *
     * @return Response
     */
    #[Route('/callback/', name: 'callback')]
    public function callback(): Response
    {
        $data = $this->bot->getWebhookUpdate();
        $this->logger->logWebhookData($data);

        if (!$this->botHelper->hasMessageSendDate($data) || $this->botHelper->isMessageTimedOut($data)) {
            $this->logger->getBotLogger()->warning('Incoming data don\'t have date or timed out');
        }

        $event = new TgCallbackEvent($data);
        $this->dispatcher->dispatch($event, TgCallbackEvent::NAME);

        return new Response();
    }

}
