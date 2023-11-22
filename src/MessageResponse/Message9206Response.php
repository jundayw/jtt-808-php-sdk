<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9206Response extends Response
{
    protected $msgId = 0x9206;
    protected $title = '文件上传指令';

    protected $ipLength;                     // 服务器IP地址长度长度k
    protected $ip;                           // FTP服务器IP地址
    protected $port;                         // FTP服务器端口
    protected $usernameLength;               // 用户名长度
    protected $username;                     // 用户名
    protected $passwordLength;               // 密码长度
    protected $password;                     // FTP密码
    protected $routeLength;                  // 文件上传路径长度
    protected $route;                        // 文件上传路径
    protected $channelNumber;                // 逻辑通道号
    protected $startTime;                    // 开始时间:YY-MM-DD-HH-MM-SS
    protected $endTime;                      // 结束时间:YY-MM-DD-HH-MM-SS
    protected $alarm = '0';                  // 报警标志，按照 JT/T808 2011 版的表 18 与表13
    protected $videoType = 0;                // 音视频类型:0-音视屏1-音频2-视屏3-视频或音视频
    protected $streamType = 0;               // 码流类型:0-主码流或子码流1-主码流2-子码流
    protected $storageType = 0;              // 存储位置:0-主存储器或灾备存储器1-主存储器2-灾备存储器
    protected $taskCondition = 3;            // 默认3代表支持WIFI、LAN下载->任务执行条件用bit位表示：bit0:WIFI,为1时表示WI-FI下可下载;bit1:LAN,为1时表示LAN连接时可下载;bit3:3G/4G,为1时表示3G/4G连接时可下载;

    /**
     * @param string $ip FTP服务器IP地址
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
     * @param int $port FTP服务器端口
     * @return $this
     */
    public function setPort(int $port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param string $username 用户名
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->usernameLength = strlen($username);
        $this->username       = $username;
        return $this;
    }

    /**
     * @param string $password FTP密码
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->passwordLength = strlen($password);
        $this->password       = $password;
        return $this;
    }

    /**
     * @param string $route 文件上传路径
     * @return $this
     */
    public function setRoute(string $route)
    {
        $this->routeLength = strlen($route);
        $this->route       = $route;
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
     * @param string|null $startTime 开始时间
     * @return $this
     */
    public function setStartTime(string $startTime)
    {
        $this->startTime = date('ymdHis', strtotime($startTime));;
        return $this;
    }

    /**
     * @param string|null $endTime 结束时间
     * @return $this
     */
    public function setEndTime(string $endTime)
    {
        $this->endTime = date('ymdHis', strtotime($endTime));;
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
     * 0-主码流或子码流 1-主码流 2-子码流
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
     * @param bool $wifi bit0:WIFI,为1时表示WI-FI下可下载;
     * @param bool $lan bit1:LAN,为1时表示LAN连接时可下载;
     * @param bool $mobile bit3:3G/4G,为1时表示3G/4G连接时可下载;
     * @return $this
     */
    public function setTaskCondition(bool $wifi = true, bool $lan = true, bool $mobile = false)
    {
        $this->taskCondition = ($wifi ? 0b00000001 : 0) | ($lan ? 0b00000010 : 0) | ($mobile ? 0b00001000 : 0);
        return $this;
    }

    public function response()
    {
        $this->body = pack('C', $this->ipLength)
            . pack('a*', $this->ip)
            . pack('nC', $this->port, $this->usernameLength)
            . pack('a*', $this->username)
            . pack('C', $this->passwordLength)
            . pack('a*', $this->password)
            . pack('C', $this->routeLength)
            . pack('a*', $this->route)
            . pack('CH12H12PCCCC',
                $this->channelNumber,
                $this->startTime,
                $this->endTime,
                $this->alarm,
                $this->videoType,
                $this->streamType,
                $this->storageType,
                $this->taskCondition
            );
        return $this;
    }
}