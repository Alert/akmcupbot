<?php

namespace App\Controller;


use App\Event\TgCallbackEvent;
use Borsaco\TelegramBotApiBundle\Service\Bot;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;

#[Route('/bot', name: 'bot.')]
class BotController extends AbstractController
{
    private Api $bot;
    private LoggerInterface $botLogger;
    private EventDispatcherInterface $dispatcher;

    public function __construct(Bot $bot, LoggerInterface $botLogger, EventDispatcherInterface $dispatcher)
    {
        $this->bot        = $bot->getBot();
        $this->botLogger  = $botLogger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get income data from Telegram by webhook
     */
    #[Route('/callback/', name: 'callback')]
    public function callback(): Response
    {
        $data = $this->bot->getWebhookUpdate();

        $this->botLogger->info('Webhook receive data', compact('data'));

        $event = new TgCallbackEvent($data);
        $this->dispatcher->dispatch($event, TgCallbackEvent::NAME);

        return new Response();
    }

}
