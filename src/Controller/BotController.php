<?php
declare(strict_types=1);

namespace App\Controller;


use App\Event\TgEventFactory;
use App\Service\BotService;
use App\Service\LoggerService;
use LogicException;
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
        $updateObj = $this->bot->getWebhookUpdate();
        $this->logger->logWebhookData($updateObj);

        if (!$this->bot->hasMessageSendDate($updateObj) || $this->bot->isMessageTimedOut($updateObj)) {
            $this->logger->getBotLogger()->warning('Incoming data don\'t have date or timed out');
        }

        try {
            $event = (new TgEventFactory())->create($updateObj);
            $this->dispatcher->dispatch($event, $event::class);
        } catch (LogicException $e) {
            $this->logger->getBotLogger()->warning($e->getMessage());
        }

        return new Response();
    }

}
