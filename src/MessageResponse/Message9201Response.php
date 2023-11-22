<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9201Response extends Response
{
    protected $msgId = 0x9201;
    protected $title = '平台下发远程录像回放请求';

    protected $ipLength;                                    // 服务器IP地址长度长度n
    protected $ip;                                          // 实时视屏服务器IP地址
    protected $TCPPort;                                     // 实时视屏服务器TCP端口号,不使用TCP传输时置0
    protected $UDPPort;                                     // 实时视屏服务器UDP端口号,不使用UDP传输时置0
    protected $channelNumber;                               // 逻辑通道号
    protected $videoType = 0;                               // 音视频类型:0-音视屏1-音频2-视屏3-视频或音视频
    protected $streamType = 0;                              // 码流类型:0-主码流或子码流1-主码流2-子码流,如果此通道只传输音频,此字段设置0
    protected $storageType = 0;                             // 存储器类型:0-主存储器或灾备存储器1-主存储器2-灾备存储器
    protected $playback = 0;                                // 回放方式:0-正常回放1-快进回放2-关键帧快退回放3-关键帧播放4-单帧上传
    protected $leverage = 0;                                // 快进或快退倍数:回放方式为1或2时有效，否者为0,0-无效1-1倍2-2倍3-4倍4-8倍5-16倍
    protected $startTime;                                   // 开始时间:YY-MM-DD-HH-MM-SS,回放方式为4时,该字段表示单帧上传时间
    protected $endTime = '000000000000';                    // 结束时间:YY-MM-DD-HH-MM-SS,为0表示一直回放,回放方式为4时，该字段无效

    /**
     * @param string $ip 实时视屏服务器IP地址
     * @return $this
     */
    public function setIp(string $ip)
    {
        $ip             = mb_convert_encoding($ip, 'GBK', 'UTF-8');
        $this->ipLength = strlen($ip);
        $this->ip       = $ip;
        return $this;
    }

    /**
     * @param int $TCPPort 实时视屏服务器TCP端口号,不使用TCP传输时置0
     * @return $this
     */
    public function setTCPPort(int $TCPPort)
    {
        $this->TCPPort = $TCPPort;
        return $this;
    }

    /**
     * @param int $UDPPort 实时视屏服务器UDP端口号,不使用UDP传输时置0
     * @return $this
     */
    public function setUDPPort(int $UDPPort)
    {
        $this->UDPPort = $UDPPort;
        return $this;
    }

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
     * 0-主码流或子码流 1-主码流 2-子码流,如果此通道只传输音频,此字段设置0
     * @return $this
     */
    public function setStreamType(int $streamType = 0)
    {
        $this->streamType = $streamType;
        return $this;
    }

    /**
     * @param int $storageType 存储器类型
     * 0-主存储器或灾备存储器 1-主存储器 2-灾备存储器
     * @return $this
     */
    public function setStorageType(int $storageType = 0)
    {
        $this->storageType = $storageType;
        return $this;
    }

    /**
     * @param int $playback 回放方式
     * 0-正常回放 1-快进回放 2-关键帧快退回放 3-关键帧播放 4-单帧上传
     * @return $this
     */
    public function setPlayback(int $playback = 0)
    {
        $this->playback = $playback;
        return $this;
    }

    /**
     * @param int $leverage 快进或快退倍数回放方式为1或2时有效，否者为0
     * 0-无效 1-1倍 2-2倍 3-4倍 4-8倍 5-16倍
     * @return $this
     */
    public function setLeverage(int $leverage = 0)
    {
        $this->leverage = $leverage;
        return $this;
    }

    /**
     * @param string $startTime 开始时间:回放方式为4时,该字段表示单帧上传时间
     * @return $this
     */
    public function setStartTime(string $startTime)
    {
        $this->startTime = date('ymdHis', strtotime($startTime));;
        return $this;
    }

    /**
     * @param string $endTime 结束时间:为0表示一直回放,回放方式为4时，该字段无效
     * @return $this
     */
    public function setEndTime(string $endTime = null)
    {
        $this->endTime = is_null($endTime) ? '000000000000' : date('ymdHis', strtotime($endTime));
        return $this;
    }

    public function response()
    {
        $this->body = pack('C', $this->ipLength) . pack('a*', $this->ip) . pack('nnCCCCCCH12H12',
                $this->TCPPort,
                $this->UDPPort,
                $this->channelNumber,
                $this->videoType,
                $this->streamType,
                $this->storageType,
                $this->playback,
                $this->leverage,
                $this->startTime,
                $this->endTime
            );
        return $this;
    }


}