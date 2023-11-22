<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9202Response extends Response
{
    protected $msgId = 0x9202;
    protected $title = '平台下发远程录像回放控制';

    protected $channelNumber;                      // 音视屏通道号
    protected $playback = 0;                       // 回放控制:0-开始回放1-暂停回放2-结束回放3-快进回放4-关键帧快退回放5-拖动回放6-关键帧播放
    protected $leverage = 0;                       // 快进或快退倍数:回放方式为1或2时有效，否者为0,0-无效1-1倍2-2倍3-4倍4-8倍5-16倍
    protected $dvr = '000000000000';               // 拖动位置回放:YY-MM-DD-HH-MM-SS,回放控制为5时,该字段有效

    /**
     * @param string $channelNumber 音视屏通道号
     * @return $this
     */
    public function setChannelNumber(string $channelNumber)
    {
        $this->channelNumber = $channelNumber;
        return $this;
    }

    /**
     * @param int $playback 回放控制
     * 0-开始回放 1-暂停回放 2-结束回放 3-快进回放 4-关键帧快退回放 5-拖动回放 6-关键帧播放
     * @return $this
     */
    public function setPlayback(int $playback = 0)
    {
        $this->playback = $playback;
        return $this;
    }

    /**
     * @param int $leverage 快进或快退倍数回放方式为1或2时有效，否者为0,
     * 0-无效 1-1倍 2-2倍 3-4倍 4-8倍 5-16倍
     * @return $this
     */
    public function setLeverage(int $leverage = 0)
    {
        $this->leverage = $leverage;
        return $this;
    }

    /**
     * @param string $dvr 拖动位置回放:YY-MM-DD-HH-MM-SS,回放控制为5时,该字段有效
     * @return $this
     */
    public function setDvr(string $dvr = null)
    {
        $this->dvr = is_null($dvr) ? '000000000000' : date('ymdHis', strtotime($dvr));;
        return $this;
    }

    public function response()
    {
        $this->body = pack('CCCH12',
            $this->channelNumber,
            $this->playback,
            $this->leverage,
            $this->dvr
        );
        return $this;
    }
}