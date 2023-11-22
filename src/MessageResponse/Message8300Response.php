<?php

namespace Jundayw\JTT808\MessageResponse;

class Message8300Response extends Response
{
    protected $msgId = 0x8300;
    protected $title = '文本信息下发';

    protected $marker = 0;       // 文本信息标志位见表27
    protected $textMsg = '';     // 文本信息:最长为1024字节,经GBK编码

    /**
     * @param array $markers
     * 文本信息标志位见表27
     * 常数：1 4 8 16 32|0
     * 1:紧急
     * 4:终端显示器显示
     * 8:终端TTS播报
     * 16:广告屏显示
     * 32:CAN故障码信息 | 0:中心导航信息
     * 二进制：
     * 0b00000001:紧急
     * 0b00000100:终端显示器显示
     * 0b00001000:终端TTS播报
     * 0b00010000:广告屏显示
     * 0b00100000:CAN故障码信息 | 0b00000000:中心导航信息
     * @return $this
     */
    public function setMarker(array $markers = [])
    {
        $buffer = 0;
        foreach ($markers as $marker) {
            $buffer |= $marker;
        }
        $this->marker = $buffer;
        return $this;
    }

    /**
     * @param string $textMsg 文本信息:最长为1024字节,经GBK编码
     * @return $this
     */
    public function setTextMsg(string $textMsg = '')
    {
        $textMsg       = mb_substr($textMsg, 0, 1024);
        $this->textMsg = mb_convert_encoding($textMsg, 'GBK', 'UTF-8');
        return $this;
    }

    public function response()
    {
        $this->body = pack('Ca*',
            $this->marker,
            $this->textMsg
        );
        return $this;
    }
}