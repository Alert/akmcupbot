<?php
declare(strict_types=1);

namespace App\EventListener\BotCommand;

use App\Event\TgCallbackQueryEvent;
use App\Event\TgMessageEvent;
use App\Service\BotService;
use App\Service\DynamicParamService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Telegram\Bot\Objects\Update as UpdateObject;

abstract class AbstractCommandListener implements CommandListenerInterface, ButtonListenerInterface
{
    const BUTTONS_PER_ROW = 2;

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

    /**
     * Bot wrapper
     *
     * @var BotService
     */
    protected BotService $bot;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * Doctrine
     *
     * @var ManagerRegistry
     */
    protected ManagerRegistry $doctrine;

    /**
     * Dynamic param service
     *
     * @var DynamicParamService
     */
    protected DynamicParamService $dynamicParamService;

    public function __construct(BotService          $bot,
                                TranslatorInterface $translator,
                                ManagerRegistry     $doctrine,
                                DynamicParamService $dynamicParamService)
    {
        $this->bot                 = $bot;
        $this->translator          = $translator;
        $this->doctrine            = $doctrine;
        $this->dynamicParamService = $dynamicParamService;
    }

    /**
     * {@inheritdoc}
     */
    public function commandHandler(TgMessageEvent $event): void
    {
        $updateObj = $event->getUpdateObject();

        if ($this->isCommand($updateObj) && $this->commandNameMatching($updateObj)) {
            $this->commandAction($updateObj);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buttonHandler(TgCallbackQueryEvent $event): void
    {
        $this->buttonAction($event->getUpdateObject());
    }

    /**
     * Command action
     *
     * @param UpdateObject $updateObj
     *
     * @return void
     */
    abstract public function commandAction(UpdateObject $updateObj): void;

    /**
     * Button action
     *
     * @param UpdateObject $updateObj
     *
     * @return void
     */
    abstract public function buttonAction(UpdateObject $updateObj): void;

    /**
     * Check message data is a command
     *
     * @param UpdateObject $updateObject
     *
     * @return bool
     */
    private function isCommand(UpdateObject $updateObject): bool
    {
        return str_starts_with($updateObject->getMessage()?->text, '/');
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
