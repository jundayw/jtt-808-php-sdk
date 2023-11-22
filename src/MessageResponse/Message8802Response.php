<?php

namespace Jundayw\JTT808\MessageResponse;

class Message8802Response extends Response
{
    protected $msgId = 0x8802;
    protected $title = '存储多媒体数据检索';

    protected $multimediaType = 0;                      // 多媒体类型 0：图像；1：音频；2：视频
    protected $channelId = 0;                           // 通道 ID 0 表示检索该媒体类型的所有通道
    protected $eventCode = 0;                           // 事件项编码0：平台下发指令；1：定时动作；2：抢劫报警触发；3：碰撞侧翻报警触发；其他保留
    protected $startTime = '000000000000';              // 起始时间 不按时间范围则将起始时间/结束时间都设为00-00-00-00-00-00
    protected $endTime = '000000000000';                // 结束时间 不按时间范围则将起始时间/结束时间都设为00-00-00-00-00-00

    // 多媒体类型
    public function setMultimediaType(int $multimediaType = 0)
    {
        $this->multimediaType = $multimediaType;
        return $this;
    }

    // 通道 ID
    public function setChannelId(int $channelId = 0)
    {
        $this->channelId = $channelId;
        return $this;
    }

    // 事件项编码
    public function setEventCode(int $eventCode = 0)
    {
        $this->eventCode = $eventCode;
        return $this;
    }

    // 起始时间
    public function setStartTime(string $startTime = null)
    {
        $this->startTime = $startTime == null ? '000000000000' : date('ymdHis', strtotime($startTime));
        return $this;
    }

    // 结束时间
    public function setEndTime(string $endTime = null)
    {
        $this->endTime = $endTime == null ? '000000000000' : date('ymdHis', strtotime($endTime));
        return $this;
    }

    public function response()
    {
        $this->body = pack('CCCH12H12',
            $this->multimediaType,
            $this->channelId,
            $this->eventCode,
            $this->startTime,
            $this->endTime
        );
        return $this;
    }
}