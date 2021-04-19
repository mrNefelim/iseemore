<?php


namespace App\YoutubeBundle\Service\Telegram;


use Telegram\Bot\Api as TelegramApi;
use Telegram\Bot\Exceptions\TelegramOtherException;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Exceptions\TelegramSDKException;

class MessageToCreator
{
    private $instance;
    private $creatorId;

    /**
     * @param string $token
     * @param string $creatorId
     * @throws TelegramSDKException
     */
    public function __construct(string $token, string $creatorId)
    {
        $this->creatorId = $creatorId;
        $this->instance = new TelegramApi($token);
    }

    /**
     * @param string $text
     * @throws SendMessageException
     */
    public function send(string $text): void
    {
        try {
            $this->instance->sendMessage(['chat_id' => $this->creatorId, 'text' => $text]);
        } catch (TelegramOtherException | TelegramResponseException $exception) {
            throw new SendMessageException($exception->getMessage());
        }
    }
}