<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Repository\EventEntityRepository;
use Borsaco\TelegramBotApiBundle\Service\Bot;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message as MessageObject;

abstract class AbstractCommandListener implements CommandListenerInterface
{
    /**
     * Command name
     *
     * @var string
     */
    public string $name;

    /**
     * Command alias
     *
     * @var string
     */
    public string $alias;

    protected Api $bot;
    protected TranslatorInterface $translator;
    protected ContainerBagInterface $cfg;
    protected bool $isDevMode;
    protected int $devChatId;
    protected EventEntityRepository $eventRepo;

    public function __construct(Bot                   $bot,
                                TranslatorInterface   $translator,
                                ContainerBagInterface $cfg,
                                EventEntityRepository $eventRepo)
    {
        $this->bot        = $bot->getBot();
        $this->translator = $translator;
        $this->cfg        = $cfg;
        $this->isDevMode  = $cfg->get('tg.dev_mode');
        $this->devChatId  = $cfg->get('tg.dev_chat_id');
        $this->eventRepo  = $eventRepo;
    }

// TODO: refactor
//    /**
//     * Check message data is a command
//     *
//     * @param TgCallbackEvent $e
//     * @return bool
//     */
//    protected function isCommand(TgCallbackEvent $e): bool
//    {
//        $entities = $e->getMessage()['entities'] ?? [];
//        $firstEntity = array_shift($entities);
//        $type = $firstEntity['type'] ?? '';
//
//        return $type === 'bot_command';
//    }
//
//    /**
//     * Match command name for different listener
//     *
//     * @param TgCallbackEvent $e
//     * @return bool
//     */
//    protected function commandNameMatching(TgCallbackEvent $e): bool
//    {
//        return $e->getCommand() === static::NAME;
//    }
//
//    public function handler(TgCallbackEvent $e): void
//    {
//        if (!$this->isCommand($e) || !$this->commandNameMatching($e)) return;
//
//        $this->action($e);
//    }

    /**
     * Send message wrapper
     *
     * @param array    $params
     * @param bool     $force        Ignore dev mode if true
     * @param int|null $senderChatId For check dev chat id
     *
     * @return MessageObject
     * @throws TelegramSDKException
     */
    protected function sendMessage(array $params, bool $force = false, ?int $senderChatId = null): MessageObject
    {
        if ($this->isDevMode && !$force) {
            $message = $this->bot->sendMessage([
                'chat_id' => $params['chat_id'],
                'text' => "бот ненадолго отошёл, попробуйте чуть позже", // todo: move to translations
            ]);

            if ($senderChatId === $this->devChatId) {
                $message = $this->bot->sendMessage($params);
            }

            return $message;
        }

        return $this->bot->sendMessage($params);
    }

    /**
     * Escape char for MarkdownV2
     *
     * @param string $char
     *
     * @return string
     */
    protected function escapeChar(string $char): string
    {
        $num = ord($char);
        return $num <= 126 ? '\\' . $char : $char;
    }

    /**
     * Escape string for MarkdownV2
     *
     * @param string $string
     *
     * @return string
     */
    protected function escapeString(string $string): string
    {
        return implode('', array_map([$this, 'escapeChar'], str_split($string)));
    }

}
