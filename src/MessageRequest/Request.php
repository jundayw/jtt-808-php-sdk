<?php

namespace Jundayw\JTT808\MessageRequest;

use Jundayw\JTT808\Contracts\RequestContract;
use Jundayw\JTT808\Message;

abstract class Request implements RequestContract
{
    protected Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->decode();
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * 数据解封
     *
     * @param $bytes
     * @return mixed
     */
    public function decode($bytes = null)
    {
        return $this;
    }
}