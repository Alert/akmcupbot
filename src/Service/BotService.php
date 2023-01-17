<?php
declare(strict_types=1);

namespace App\Service;

use InvalidArgumentException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message as MessageObject;
use Telegram\Bot\Objects\Update as UpdateObject;
use Telegram\Bot\Api;

/**
 * Bot helper service
 */
class BotService
{
    /**
     * Waiting time for message processing (seconds)
     */
    public const MESSAGE_TIME_OUT = 10;

    /**
     * Exclusion for escape MarkdownV2
     */
    public const ESCAPE_CHAR_EXCLUSION = ['*', '_', '~'];

    /**
     * @var string
     */
    public readonly string $name;

    /**
     * Api object
     *
     * @var Api
     */
    private Api $api;

    /**
     * Is dev mode
     *
     * @var bool
     */
    private bool $isDevMode;

    /**
     * Dev chat identifier
     *
     * @var int
     */
    protected int $devChatId;

    /**
     * Translator
     *
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * Constructor
     *
     * @throws TelegramSDKException
     */
    public function __construct(string              $name,
                                string              $token,
                                bool                $isDevMode,
                                int                 $devChatId,
                                TranslatorInterface $translator)
    {
        $this->api        = new Api($token);
        $this->name       = $name;
        $this->isDevMode  = $isDevMode;
        $this->devChatId  = $devChatId;
        $this->translator = $translator;
    }

    /**
     * Get bot name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Send message wrapper
     *
     * @param array    $params
     * @param int|null $senderChatId For check dev chat id
     *
     * @return MessageObject
     * @throws TelegramSDKException
     */
    public function sendMessage(array $params, ?int $senderChatId = null): MessageObject
    {
        if ($this->isDevMode && $senderChatId !== $this->devChatId) {
            return $this->api->sendMessage([
                'chat_id' => $params['chat_id'],
                'text' => $this->translator->trans('maintenance', [], 'common'),
            ]);
        }

        if ($senderChatId) {
            $params['chat_id'] = $senderChatId;
        }

        return $this->api->sendMessage($params);
    }

    /**
     * Edit message
     *
     * @param string|int $chatId
     * @param int        $messageId
     * @param string     $text
     * @param array      $inlineKeyboardElements Array of rows and buttons
     *
     * @return MessageObject
     * @throws TelegramSDKException
     */
    public function editMessageText(string|int $chatId,
                                    int        $messageId,
                                    string     $text,
                                    array      $inlineKeyboardElements = []): MessageObject
    {
        $params = ['chat_id' => $chatId, 'message_id' => $messageId, 'text' => $text];
        if ($inlineKeyboardElements) {
            $params['reply_markup'] = json_encode(['inline_keyboard' => $inlineKeyboardElements]);
        }

        return $this->api->editMessageText($params);
    }

    /**
     * Send media group
     *
     * @param string|int $chatId
     * @param array      $mediaItems Array of media elements
     *
     * @return MessageObject
     * @throws TelegramSDKException
     */
    public function sendMediaGroup(string|int $chatId, array $mediaItems): MessageObject
    {
        return $this->api->sendMediaGroup(['chat_id' => $chatId, 'media' => json_encode($mediaItems)]);
    }

    /**
     * Get webhook update
     *
     * @return UpdateObject
     */
    public function getWebhookUpdate(): UpdateObject
    {
        return $this->api->getWebhookUpdate();
    }

    /**
     * Has date in message
     *
     * @param UpdateObject $data
     *
     * @return bool
     */
    public function hasMessageSendDate(UpdateObject $data): bool
    {
        return (bool)$data->getMessage()?->date;
    }

    /**
     * Is message time out
     *
     * @param UpdateObject $data
     *
     * @return bool
     */
    public function isMessageTimedOut(UpdateObject $data): bool
    {
        if (!$this->hasMessageSendDate($data)) {
            throw new InvalidArgumentException('Income message don\'t have date');
        }

        return (time() - $data->getMessage()->date) > self::MESSAGE_TIME_OUT;
    }

    /**
     * Send answers to callback queries sent from inline keyboards.
     *
     * @param string|int $callbackQueryId
     *
     * @return void
     * @throws TelegramSDKException
     */
    public function answerCallbackQuery(string|int $callbackQueryId): void
    {
        $this->api->answerCallbackQuery(['callback_query_id' => $callbackQueryId]);
    }

    /**
     * Escape char for MarkdownV2
     *
     * @param string $char
     *
     * @return string
     */
    public static function escapeChar(string $char): string
    {
        if (in_array($char, self::ESCAPE_CHAR_EXCLUSION)) {
            return $char;
        }

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
    public static function escapeString(string $string): string
    {
        return implode('', array_map('\App\Service\BotService::escapeChar', str_split($string)));
    }

}
