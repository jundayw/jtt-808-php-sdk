<?php

namespace Jundayw\JTT808;

use Exception;
use Jundayw\JTT808\MessageResponse\Response;

class Message
{
    protected $flag = 0x7e;
    protected $msgId;
    protected MessageHeader $msgHeader;
    protected $msgBody;

    public function __construct()
    {
        $this->msgHeader = new MessageHeader();
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag): void
    {
        $this->flag = $flag;
    }

    /**
     * @return mixed
     */
    public function getMsgId()
    {
        return $this->msgId;
    }

    /**
     * @return MessageHeader
     */
    public function getMsgHeader()
    {
        return $this->msgHeader;
    }

    /**
     * @param MessageHeader $msgHeader
     */
    public function setMsgHeader($msgHeader)
    {
        $this->msgHeader = $msgHeader;
    }

    /**
     * @return mixed
     */
    public function getMsgBody()
    {
        return $this->msgBody;
    }

    /**
     * @param mixed $msgBody
     */
    public function setMsgBody($msgBody)
    {
        $this->msgBody = $msgBody;
    }

    /**
     * 数据封装
     *
     * @param Response $response
     * @return string
     */
    public function encode(Response $response)
    {
        // 标识位
        $flag = pack('C', $this->getFlag());
        // 消息头 + 消息体
        $message = $response->encode();
        // 消息头 + 消息体 + 检验码
        $message = $message . $this->getCheckCode($message);
        // 反转义处理
        $message = str_replace(pack('C', 0x7d), pack('n', 0x7d01), $message);
        $message = str_replace(pack('C', 0x7e), pack('n', 0x7d02), $message);
        // 消息结构
        return $flag . $message . $flag;
    }

    /**
     * 数据解封
     *
     * @param $bytes
     * @return array|string|string[]
     * @throws Exception
     */
    public function decode($bytes)
    {
        // 转义处理
        $decode = substr($bytes, 1, -1);
        $decode = str_replace(pack('n', 0x7d02), pack('C', 0x7e), $decode);
        $decode = str_replace(pack('n', 0x7d01), pack('C', 0x7d), $decode);
        $bytes  = substr_replace($bytes, $decode, 1, -1);

        // 标识位校验
        $startIndex = substr($bytes, 0, 1) == pack("C", $this->getFlag());
        $endIndex   = substr($bytes, -1, 1) == pack("C", $this->getFlag());
        if ($startIndex === false || $endIndex === false) {
            throw new Exception('Invalid message format');
        }

        // 检验码校验
        $message  = substr($bytes, 1, -2);
        $checkSum = substr($bytes, -2, -1);
        if ($checkSum != $this->getCheckCode($message)) {
            throw new Exception('Invalid check code');
        }

        $this->msgId = bin2hex(substr($message, 0, 2));
        $this->setMsgHeader($this->msgHeader);
        $this->setMsgBody($this->msgHeader->decode($message));

        return $bytes;
    }

    private function getCheckCode($bytes)
    {
        $checkCode = 0;
        for ($i = 0; $i < strlen($bytes); $i++) {
            $checkCode ^= ord($bytes[$i]);
        }
        return pack('C', $checkCode);
    }
}