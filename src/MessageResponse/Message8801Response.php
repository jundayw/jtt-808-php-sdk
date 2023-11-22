<?php

namespace Jundayw\JTT808\MessageResponse;

class Message8801Response extends Response
{
    protected $msgId = 0x8801;
    protected $title = '摄像头立即拍摄命令';

    protected $channelId = 1;                               // 通道ID
    protected $shootingCommand = 1;                         // 拍摄命令 0 表示停止拍摄;0xFFFF 表示录像;其它表示拍照张数
    protected $shootingInterval = 0;                        // 拍照间隔/录像时间 秒,0 表示按最小间隔拍照或一直录像
    protected $saveFlag = 0;                                // 保存标志 1:保存 0:实时上传
    protected $resolution = 0x01;                           // 分辨率
    protected $quality = 1;                                 // 图像/视频质量:1-10，1 代表质量损失最小，10 表示压缩比最大
    protected $brightness = 0;                              // 亮度0-255
    protected $contrast = 0;                                // 对比度0-127
    protected $saturation = 0;                              // 饱和度0-127
    protected $chroma = 0;                                  // 色度0-255

    public function setChannelId(int $channelId = 1)
    {
        $this->channelId = $channelId;
        return $this;
    }

    // 停止拍摄
    public function setStopCommand()
    {
        $this->shootingCommand = 0;
        return $this;
    }

    // 录像
    public function setVideoCommand()
    {
        $this->shootingCommand = 0xFFFF;
        return $this;
    }

    // 拍照张数
    public function setPhotoCommand(int $number = 1)
    {
        $this->shootingCommand = $number;
        return $this;
    }

    public function setShootingInterval(int $shootingInterval = 0)
    {
        $this->shootingInterval = $shootingInterval;
        return $this;
    }

    public function setSaveFlag(int $saveFlag = 1)
    {
        $this->saveFlag = $saveFlag;
        return $this;
    }

    // 分辨率 0x01:320*240
    // 分辨率 0x02:640*480
    // 分辨率 0x03:800*600
    // 分辨率 0x04:1024*768
    // 分辨率 0x05:176*144;[Qcif]
    // 分辨率 0x06:352*288;[Cif]
    // 分辨率 0x07:704*288;[HALF D1]
    // 分辨率 0x08:704*576;[D1]
    public function setResolution(int $resolution = 0x01)
    {
        $this->resolution = $resolution;
        return $this;
    }

    public function setQuality(int $quality = 1)
    {
        $this->quality = $quality;
        return $this;
    }

    public function setBrightness(int $brightness = 0)
    {
        $this->brightness = $brightness;
        return $this;
    }

    public function setContrast(int $contrast = 0)
    {
        $this->contrast = $contrast;
        return $this;
    }

    public function setSaturation(int $saturation = 0)
    {
        $this->saturation = $saturation;
        return $this;
    }

    public function setChroma(int $chroma = 0)
    {
        $this->chroma = $chroma;
        return $this;
    }

    public function response()
    {
        $this->body = pack('CnnCCCCCCC',
            $this->channelId,
            $this->shootingCommand,
            $this->shootingInterval,
            $this->saveFlag,
            $this->resolution,
            $this->quality,
            $this->brightness,
            $this->contrast,
            $this->saturation,
            $this->chroma
        );
        return $this;
    }
}