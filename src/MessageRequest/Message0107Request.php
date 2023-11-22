<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0107Request extends Request
{
    private $msgId = 0x0107;
    private $title = '查询终端属性应答';

    public $terminalType = [];                 // 终端类型，WORD
    public $manufacturerId;                    // 制造商 ID，BYTE[5]
    public $terminalModel;                     // 终端型号，BYTE[20]
    public $terminalId;                        // 终端 ID，BYTE[7]
    public $simNum;                            // 终端 SIM 卡 ICCID，BCD[10]
    public $hwVersion;                         // 终端硬件版本号，STRING
    public $swVersion;                         // 终端固件版本号，STRING
    public $gpsModule = [];                    // GNSS 模块属性，BYTE
    public $communicationModule = [];          // 通信模块属性，BYTE

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();

        $data = unpack("nterminalType/a5manufacturerId/a20terminalModel/a7terminalId/a8x/H20simNum/ChwVersionLength", $bytes);

        $terminalType         = $data['terminalType'];
        $this->manufacturerId = $data['manufacturerId'];
        $this->terminalModel  = $data['terminalModel'];
        $this->terminalId     = $data['terminalId'];
        $this->simNum         = $data['simNum'];

        $hwVersionLength = $data['hwVersionLength'];

        $hwVersion       = substr($bytes, 53, $hwVersionLength);
        $hwVersion       = unpack('H*', $hwVersion);
        $this->hwVersion = mb_convert_encoding($hwVersion[1], 'utf-8', 'GBK');

        $swVersionLength = hexdec(bin2hex(substr($bytes, 53 + $hwVersionLength, 1)));

        $swVersion       = substr($bytes, 54 + $hwVersionLength, $swVersionLength);
        $swVersion       = unpack('H*', $swVersion);
        $this->swVersion = mb_convert_encoding($swVersion[1], 'utf-8', 'GBK');

        $gpsModule           = substr($bytes, 54 + $hwVersionLength + $swVersionLength, 1);
        $communicationModule = substr($bytes, 55 + $hwVersionLength + $swVersionLength, 1);
        $gpsModule           = bin2hex($gpsModule);
        $gpsModule           = hexdec($gpsModule);
        $communicationModule = bin2hex($communicationModule);
        $communicationModule = hexdec($communicationModule);

        return $this->getTerminalType($terminalType)->getGPSModule($gpsModule)->getCommunicationModule($communicationModule);
    }

    private function getTerminalType($terminalType)
    {
        $types = [
            0 => ['不适用客运车辆', '适用客运车辆'],
            1 => ['不适用危险品车辆', '适用危险品车辆'],
            2 => ['不适用普通货运车辆', '适用普通货运车辆'],
            3 => ['不适用出租车辆', '适用出租车辆'],
            6 => ['不支持硬盘录像', '支持硬盘录像'],
            7 => ['一体机', '分体机'],
        ];

        foreach ($types as $index => $desc) {
            if ($key = ($terminalType >> $index & 1)) {
                $this->terminalType[$key][$index] = $desc[1];
            } else {
                $this->terminalType[$key][$index] = $desc[0];
            }
        }

        return $this;
    }

    private function getGPSModule($gpsModule)
    {
        $types = [
            0 => ['不支持 GPS 定位', '支持 GPS 定位'],
            1 => ['不支持北斗定位', '支持北斗定位'],
            2 => ['不支持 GLONASS 定位', '支持 GLONASS 定位'],
            3 => ['不支持 Galileo 定位', '支持 Galileo 定'],
        ];

        foreach ($types as $index => $desc) {
            if ($key = ($gpsModule >> $index & 1)) {
                $this->gpsModule[$key][$index] = $desc[1];
            } else {
                $this->gpsModule[$key][$index] = $desc[0];
            }
        }

        return $this;
    }

    private function getCommunicationModule($communicationModule)
    {
        $types = [
            0 => ['不支持GPRS通信', '支持GPRS通信'],
            1 => ['不支持CDMA通信', '支持CDMA通信'],
            2 => ['不支持TD-SCDMA通信', '支持TD-SCDMA通信'],
            3 => ['不支持WCDMA通信', '支持WCDMA通信'],
            4 => ['不支持CDMA2000通信', '支持CDMA2000通信'],
            5 => ['不支持TD-LTE通信', '支持TD-LTE通信'],
            7 => ['不支持其他通信方式', '支持其他通信方式'],
        ];

        foreach ($types as $index => $desc) {
            if ($key = ($communicationModule >> $index & 1)) {
                $this->communicationModule[$key][$index] = $desc[1];
            } else {
                $this->communicationModule[$key][$index] = $desc[0];
            }
        }

        return $this;
    }
}