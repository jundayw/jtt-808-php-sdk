<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9101Response extends Response
{
    protected $msgId = 0x9101;
    protected $title = '实时音视屏传输请求';

    protected $ipLength;                   // 服务器IP地址长度长度n
    protected $ip;                         // 实时视屏服务器IP地址
    protected $TCPPort;                    // 实时视屏服务器TCP端口号
    protected $UDPPort;                    // 实时视屏服务器UDP端口号
    protected $channelNumber;              // 逻辑通道号
    protected $dataType = 0;               // 数据类型:0-音视屏1-视屏2-双向对讲3-监听4-中心广播5-透传
    protected $streamType = 0;             // 码流类型:0-主码流1-子码流

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
     * @param int $TCPPort 实时视屏服务器TCP端口号
     * @return $this
     */
    public function setTCPPort(int $TCPPort)
    {
        $this->TCPPort = $TCPPort;
        return $this;
    }

    /**
     * @param int $UDPPort 实时视屏服务器UDP端口号
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
     * @param int $dataType 数据类型:0-音视屏1-视屏2-双向对讲3-监听4-中心广播5-透传
     * @return $this
     */
    public function setDataType(int $dataType = 0)
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @param int $streamType 码流类型:0-主码流1-子码流
     * @return $this
     */
    public function setStreamType(int $streamType = 0)
    {
        $this->streamType = $streamType;
        return $this;
    }

    public function response()
    {
        $this->body = pack('C',
                $this->ipLength
            ) . pack('a*',
                $this->ip
            ) . pack('nnCCC',
                $this->TCPPort,
                $this->UDPPort,
                $this->channelNumber,
                $this->dataType,
                $this->streamType
            );
        return $this;
    }
}