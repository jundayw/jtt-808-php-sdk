<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9105Response extends Response
{
    protected $msgId = 0x9105;
    protected $title = '实时音视频传输状态通知';

    protected $channelNumber;       // 逻辑通道号
    protected $lossRate;            // 丢包率

    /**
     * @param string $channelNumber 逻辑通道号
     * @return $this
     */
    public function setChannelNumber(string $channelNumber)
    {
        $this->channelNumber = $channelNumber;
        return $this;
    }

    /**
     * @param float $lossRate 当前传输通道的丢包率,数值曾以
     * @return $this
     */
    public function setLossRate(float $lossRate = 0)
    {
        $this->lossRate = bcmul($lossRate, 100, 0);
        return $this;
    }

    public function response()
    {
        $this->body = pack('CC',
            $this->channelNumber,
            $this->lossRate
        );
        return $this;
    }

}