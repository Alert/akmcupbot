<?php
declare(strict_types=1);

namespace App\Controller;


use App\Event\TgCallbackEvent;
use App\Service\BotService;
use App\Service\LoggerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bot', name: 'bot.')]
class BotController extends AbstractController
{
    /**
     * Bot service
     *
     * @var BotService
     */
    private BotService $bot;

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
     * Constructor
     *
     * @param BotService               $bot
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerService            $logger
     */
    public function __construct(BotService               $bot,
                                EventDispatcherInterface $dispatcher,
                                LoggerService            $logger)
    {
        $this->bot        = $bot;
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
    }

    /**
     * Get income data from Telegram by webhook
     *
     * @return Response
     */
    #[Route('/callback/', name: 'callback')]
    public function callback(): Response
    {
        $data = $this->bot->getApi()->getWebhookUpdate();
        $this->logger->logWebhookData($data);

        if (!$this->bot->hasMessageSendDate($data) || $this->bot->isMessageTimedOut($data)) {
            $this->logger->getBotLogger()->warning('Incoming data don\'t have date or timed out');
        }

        $event = new TgCallbackEvent($data);
        $this->dispatcher->dispatch($event, TgCallbackEvent::NAME);

        return new Response();
    }

}
