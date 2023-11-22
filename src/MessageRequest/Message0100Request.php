<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0100Request extends Request
{
    private $msgId = 0x0100;
    private $title = '终端注册';

    public $provinceId;           // 省域ID，2个字节
    public $cityId;               // 市县域ID，2个字节
    public $manufacturerId;       // 制造商ID，5个字节
    public $terminalModel;        // 终端型号，20个字节
    public $terminalId;           // 终端ID，7个字节
    public $plateColor;           // 车牌颜色，1个字节
    public $plateNumber;          // 车辆标识，字符串

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();

        $data = unpack("nprovince/ncity/a5manuf/a20model/a7tid/Ccolor/a*number", $bytes);

        $this->provinceId     = $data['province'];
        $this->cityId         = $data['city'];
        $this->manufacturerId = $data['manuf'];
        $this->terminalModel  = $data['model'];
        $this->terminalId     = $data['tid'];
        $this->plateColor     = $data['color'];
        $this->plateNumber    = mb_convert_encoding($data['number'], 'utf-8', 'GBK');

        return $this;
    }
}