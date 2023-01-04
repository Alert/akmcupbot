<?php

namespace App\Controller;


use App\Event\TgCallbackEvent;
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
    private Api $bot;
    private EventDispatcherInterface $dispatcher;
    private LoggerService $logger;

    public function __construct(Bot $bot, EventDispatcherInterface $dispatcher, LoggerService $logger)
    {
        $this->bot        = $bot->getBot();
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
    }

    /**
     * Get income data from Telegram by webhook
     */
    #[Route('/callback/', name: 'callback')]
    public function callback(): Response
    {
        $data = $this->bot->getWebhookUpdate();

        $this->logger->logWebhookData($data);

        $event = new TgCallbackEvent($data);
        $this->dispatcher->dispatch($event, TgCallbackEvent::NAME);

        return new Response();
    }

}
