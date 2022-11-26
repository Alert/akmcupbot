<?php

namespace App\Controller;


use App\Event\TgCallbackEvent;
use Borsaco\TelegramBotApiBundle\Service\Bot;
use JsonException;
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
    private LoggerInterface $botIncomeLogger;
    private EventDispatcherInterface $dispatcher;

    public function __construct(Bot $bot, LoggerInterface $botIncomeLogger, EventDispatcherInterface $dispatcher)
    {
        $this->bot = $bot->getBot();
        $this->botIncomeLogger = $botIncomeLogger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get income data from Telegram by webhook
     */
    #[Route('/callback/', name: 'callback')]
    public function callback(): Response
    {
        try {
            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $error = $e->getMessage();
            $this->botIncomeLogger->error('Parse JSON error', compact('error', 'raw'));
            return new Response();
        }

        $this->botIncomeLogger->info('Webhook data from TG', compact('data'));

        $event = new TgCallbackEvent($data);
        $this->dispatcher->dispatch($event, TgCallbackEvent::NAME);

        return new Response();
    }
}
