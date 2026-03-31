<?php

namespace MessageOwl\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use MessageOwl\Resources\Contact;
use MessageOwl\Resources\Group;
use MessageOwl\Resources\Message;
use MessageOwl\Resources\Otp;
use MessageOwl\Responses\BalanceResponse;
use MessageOwl\Responses\SenderIdResponse;

/**
 * @method static Message message()
 * @method static Message messages()
 * @method static Otp otp()
 * @method static Group groups()
 * @method static Contact contacts()
 * @method static BalanceResponse balance()
 * @method static SenderIdResponse[] senderIds()
 *
 * @see \MessageOwl\MessageOwl
 */
class MessageOwl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'messageowl';
    }
}
