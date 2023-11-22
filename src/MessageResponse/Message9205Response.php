<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9205Response extends Response
{
    protected $msgId = 0x9205;
    protected $title = '查询资源列表';

    protected $channelNumber;                // 逻辑通道号
    protected $startTime = '000000000000';   // 开始时间:YY-MM-DD-HH-MM-SS,全0表示无起始时间条件
    protected $endTime = '000000000000';     // 结束时间:YY-MM-DD-HH-MM-SS,全0表示无终止时间条件
    protected $alarm = '0';                  // 报警标志，按照 JT/T808 2011 版的表 18 与表13
    protected $videoType = 0;                // 音视频类型:0-音视屏1-音频2-视屏3-视频或音视频
    protected $streamType = 0;               // 码流类型:0-所有码流1-主码流2-子码流
    protected $storageType = 0;              // 存储器类型:0-所有存储器1-主存储器2-灾备存储器

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
     * @param string|null $startTime 开始时间
     * @return $this
     */
    public function setStartTime(string $startTime = null)
    {
        $this->startTime = is_null($startTime) ? '000000000000' : date('ymdHis', strtotime($startTime));;
        return $this;
    }

    /**
     * @param string|null $endTime 结束时间
     * @return $this
     */
    public function setEndTime(string $endTime = null)
    {
        $this->endTime = is_null($endTime) ? '000000000000' : date('ymdHis', strtotime($endTime));;
        return $this;
    }


    /**
     * @param string $alarm 报警标志
     * @return $this
     */
    public function setAlarm(string $alarm = '0')
    {
        $this->alarm = $alarm;
        return $this;
    }

    /**
     * @param int $videoType 音视频类型
     * 0-音视屏 1-音频 2-视屏 3-视频或音视频
     * @return $this
     */
    public function setVideoType(int $videoType = 0)
    {
        $this->videoType = $videoType;
        return $this;
    }

    /**
     * @param int $streamType 码流类型
     * 0-所有码流 1-主码流 2-子码流
     * @return $this
     */
    public function setStreamType(int $streamType = 0)
    {
        $this->streamType = $streamType;
        return $this;
    }

    /**
     * @param int $storageType 存储器类型
     * 0-所有存储器 1-主存储器 2-灾备存储器
     * @return $this
     */
    public function setStorageType(int $storageType = 0)
    {
        $this->storageType = $storageType;
        return $this;
    }

    public function response()
    {
        $this->body = pack('CH12H12PCCC',
            $this->channelNumber,
            $this->startTime,
            $this->endTime,
            $this->alarm,
            $this->videoType,
            $this->streamType,
            $this->storageType
        );
        return $this;
    }

}