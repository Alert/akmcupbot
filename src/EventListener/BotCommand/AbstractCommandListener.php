<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackEvent;
use App\Repository\EventRepository;
use App\Service\BotService;
use App\Service\DynamicParamService;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Telegram\Bot\Objects\Update as UpdateObject;

abstract class AbstractCommandListener implements CommandListenerInterface, ButtonListenerInterface
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

    protected BotService $bot;
    protected TranslatorInterface $translator;
    protected bool $isDevMode;
    protected int $devChatId;
    protected EventRepository $eventRepo;

    /**
     * Dynamic param service
     *
     * @var DynamicParamService
     */
    protected DynamicParamService $dynamicParamService;

    public function __construct(BotService            $bot,
                                TranslatorInterface   $translator,
                                EventRepository       $eventRepo,
                                DynamicParamService   $dynamicParamService,
                                ContainerBagInterface $cfg)
    {
        $this->bot                 = $bot;
        $this->translator          = $translator;
        $this->eventRepo           = $eventRepo;
        $this->dynamicParamService = $dynamicParamService;

        $this->isDevMode = $cfg->get('tg.dev_mode');
        $this->devChatId = $cfg->get('tg.dev_chat_id');
    }

    public function handler(TgCallbackEvent $e): void
    {
        $update = $e->getUpdateObject();

        if ($this->isCommand($update) && $this->commandNameMatching($update)) {
            $this->commandAction($update);
        }

        if ($this->isCallbackQuery($update)) {
            $this->btnAction($update);
        }
    }

    /**
     * Check message data is a command
     *
     * @param UpdateObject $updateObject
     *
     * @return bool
     */
    private function isCommand(UpdateObject $updateObject): bool
    {
        return $updateObject->objectType() === 'message' && str_starts_with($updateObject->getMessage()->text, '/');
    }

    /**
     * Check income data is callback query
     *
     * @param UpdateObject $updateObject
     *
     * @return bool
     */
    private function isCallbackQuery(UpdateObject $updateObject): bool
    {
        return $updateObject->objectType() === 'callback_query';
    }

    /**
     * Match command name for different listener
     *
     * @param UpdateObject $updateObject
     *
     * @return bool
     */
    private function commandNameMatching(UpdateObject $updateObject): bool
    {
        $text         = trim($updateObject->getMessage()->text);
        $delimiterPos = strpos($text, ' ');

        $cmdName = substr($text, 1, $delimiterPos !== false ? $delimiterPos : null);

        return $cmdName === $this->name || $cmdName === $this->alias;
    }


}
