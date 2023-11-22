<?php

namespace Jundayw\JTT808\MessageRequest;

class Message1003Request extends Request
{
    private $msgId = 0x1003;
    private $title = '终端上传音视屏属性';

    public $audioCode;                 // 输入音视频编码方式 见表12
    public $audioCodeList = [];        // 输入音视频编码方式 见表12
    public $audioChannelNumber;        // 输入音视频声道数
    public $fs;                        // 输入音视频采样率
    public $bps;                       // 输入音视频采样位数
    public $audioLength;               // 音屏帧长度
    public $audioOutput;               // 是否支持音频输出
    public $videoCode;                 // 视频编码方式 见表19
    public $audioNumber;               // 终端支持的最大音频物理通道数量
    public $videoNumber;               // 终端支持的最大视频物理通道数量

    public function decode($bytes = null)
    {
        $bytes = $bytes ?? $this->message->getMsgBody();

        $data                     = unpack("CaudioCode/CaudioChannelNumber/Cfs/Cbps/naudioLength/CaudioOutput/CvideoCode/CaudioNumber/CvideoNumber", $bytes);
        $this->audioCode          = $data["audioCode"];
        $this->audioChannelNumber = $data["audioChannelNumber"];
        $this->fs                 = $data["fs"];
        $this->bps                = $data["bps"];
        $this->audioLength        = $data["audioLength"];
        $this->audioOutput        = $data["audioOutput"];
        $this->videoCode          = $data["videoCode"];
        $this->audioNumber        = $data["audioNumber"];
        $this->videoNumber        = $data["videoNumber"];

        return $this->getAudioCode();
    }

    private function getAudioCode()
    {
        $codec = [
            1 => 'G.721',
            2 => 'G.722',
            3 => 'G.723',
            4 => 'G.728',
            5 => 'G.729',
            6 => 'G.711A',
            7 => 'G.711U',
            8 => 'G.726',
            9 => 'G.729A',
            10 => 'DVI4_3',
            11 => 'DVI4_4',
            12 => 'DVI4_8K',
            13 => 'DVI4_16K',
            14 => 'LPC',
            15 => 'S16BE_STEREO',
            16 => 'S16BE_MONO',
            17 => 'MPEGAUDIO',
            18 => 'LPCM',
            19 => 'AAC',
            20 => 'WMA9STD',
            21 => 'HEAAC',
            22 => 'PCM_VOICE',
            23 => 'PCM_AUDIO',
            24 => 'AACLC',
            25 => 'MP3',
            26 => 'ADPCMA',
            27 => 'MP4AUDIO',
            28 => 'AMR',
            98 => 'H.264',
            99 => 'H.265',
            100 => 'AVS',
            101 => 'SVAC',
        ];

        foreach ($codec as $index => $desc) {
            if ($this->audioCode >> $index & 1) {
                $this->audioCodeList[$index] = $desc;
            }
        }

        return $this;
    }
}