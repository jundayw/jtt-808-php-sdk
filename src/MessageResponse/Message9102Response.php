<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9102Response extends Response
{
    protected $msgId = 0x9102;
    protected $title = '音视屏实时传输控制';

    protected $channelNumber;       // 逻辑通道号
    protected $controlInstruct;     // 控制指令:0-关闭音视频传输指令1-切换码流(增加暂停和继续)2-暂停该通道所有流的发送3-恢复暂停前流的发送，与暂停前的流类型一致4-关闭双向对讲
    protected $closeType;           // 关闭音视频类型:0-关闭该通道有关的音视频数据1-只关闭该通道有关的音频，保留该通道有关的视频2-只关闭该通道有关的视频，保留该通道有关的音频
    protected $streamType;          // 切换码流类型:0-主码流1-子码流


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
     * @param $controlInstruct
     * 控制指令:
     * 0-关闭音视频传输指令
     * 1-切换码流(增加暂停和继续)
     * 2-暂停该通道所有流的发送
     * 3-恢复暂停前流的发送，与暂停前的流类型一致
     * 4-关闭双向对讲
     * @return $this
     */
    public function setControlInstruct(int $controlInstruct = 0)
    {
        $this->controlInstruct = $controlInstruct;
        return $this;
    }

    /**
     * @param int $closeType
     * 关闭音视频类型:
     * 0-关闭该通道有关的音视频数据
     * 1-只关闭该通道有关的音频，保留该通道有关的视频
     * 2-只关闭该通道有关的视频，保留该通道有关的音频
     * @return $this
     */
    public function setCloseType(int $closeType = 0)
    {
        $this->closeType = $closeType;
        return $this;
    }

    /**
     * @param int $streamType
     * 切换码流类型:
     * 0-主码流
     * 1-子码流
     * @return $this
     */
    public function setStreamType(int $streamType = 0)
    {
        $this->streamType = $streamType;
        return $this;
    }

    public function response()
    {
        $this->body = pack('CCCC',
            $this->channelNumber,
            $this->controlInstruct,
            $this->closeType,
            $this->streamType
        );
        return $this;
    }

}