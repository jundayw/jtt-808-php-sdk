<?php

namespace Jundayw\JTT808;

class MessageHeader
{
    // 消息ID
    protected $msgId;
    // 消息体属性
    protected $msgAttributes;
    // 终端手机号
    protected $terminalId;
    // 消息流水号
    protected $msgFlowId;
    // 消息分包
    protected $msgSubPackage;

    // 消息体长度
    protected $msgBodyLength = 0;
    // 是否有子包
    protected $msgHasSubPackage = 0;
    // 加密类型
    protected $msgEncryptionType = 0;
    // 版本：2019
    protected $version = 0;
    // 保留
    protected $reserved = 0;

    /**
     * @return mixed
     */
    public function getMsgId()
    {
        return $this->msgId;
    }

    /**
     * @param mixed $msgId
     */
    public function setMsgId($msgId): void
    {
        $this->msgId = $msgId;
    }

    /**
     * @return mixed
     */
    public function getMsgAttributes()
    {
        return $this->msgAttributes;
    }

    /**
     * @param mixed $msgAttributes
     */
    public function setMsgAttributes($msgAttributes): void
    {
        $this->msgAttributes = $msgAttributes;
    }

    /**
     * @return mixed
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * @param mixed $terminalId
     */
    public function setTerminalId($terminalId): void
    {
        $this->terminalId = $terminalId;
    }

    /**
     * @return mixed
     */
    public function getMsgFlowId()
    {
        return $this->msgFlowId;
    }

    /**
     * @param mixed $msgFlowId
     */
    public function setMsgFlowId($msgFlowId): void
    {
        $this->msgFlowId = $msgFlowId;
    }

    /**
     * @return mixed
     */
    public function getMsgSubPackage()
    {
        return $this->msgSubPackage;
    }

    /**
     * @param mixed $msgSubPackage
     */
    public function setMsgSubPackage($msgSubPackage): void
    {
        $this->msgSubPackage = $msgSubPackage;
    }

    /**
     * @return int
     */
    public function getMsgBodyLength(): int
    {
        return $this->msgBodyLength;
    }

    /**
     * @return int
     */
    public function getMsgHasSubPackage(): int
    {
        return $this->msgHasSubPackage;
    }

    /**
     * @return int
     */
    public function getMsgEncryptionType(): int
    {
        return $this->msgEncryptionType;
    }

    /**
     * 消息体
     * unpack('nmsgId/nmsgAttributes/H12terminalId/nmsgFlowId', $bytes)
     *
     * @param $bytes
     * @return string
     */
    public function decode($bytes)
    {
        // 十六进制结果
        // $this->msgId         = bin2hex(substr($bytes, 0, 2));
        // $this->msgAttributes = $this->msgAttributes(bin2hex(substr($bytes, 2, 2)));
        // $this->terminalId    = bin2hex(substr($bytes, 4, 6));
        // $this->msgFlowId     = bin2hex(substr($bytes, 10, 2));
        // 十进制结果
        $unpack              = unpack('nmsgId/nmsgAttributes/H12terminalId/nmsgFlowId', $bytes);
        $this->msgId         = $unpack['msgId'];
        $this->msgAttributes = $this->msgAttributes($unpack['msgAttributes']);
        $this->terminalId    = $unpack['terminalId'];
        $this->msgFlowId     = $unpack['msgFlowId'];
        if ($this->msgHasSubPackage) {
            $this->msgSubPackage = bin2hex(substr($bytes, 12, -$this->msgBodyLength));
        }
        return substr($bytes, -$this->msgBodyLength);
    }

    /**
     * 消息体点属性 word(16) 2bytes
     *
     * @param $attributes
     * @return mixed
     */
    public function msgAttributes($attributes)
    {
        // [0-9]消息体长度 0000,0011,1111,1111(3ff)
        $this->msgBodyLength = ($attributes & 0x3ff);
        // [10-12]加密类型 0001,1100,0000,0000(1c00)
        $this->msgEncryptionType = (($attributes & 0x1c00) >> 10);
        // [13] 是否有子包 010,0000，0000，000(2000)(是否有子包)
        $this->msgHasSubPackage = (($attributes & 0x2000) >> 13);
        // [14-15] 保留为 1100,0000,0000,0000(C000)
        $this->reserved = (($attributes & 0xC000) >> 14);
        // @todo 版本控制：2019
        // $this->version  = (($attributes & 0x4000) >> 14);
        // $this->reserved = (($attributes & 0x8000) >> 15);
        return $attributes;
    }

}