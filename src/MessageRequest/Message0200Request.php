<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0200Request extends Request
{
    private $msgId = 0x0200;
    private $title = '位置信息汇报';

    public $alarm;                                // 报警标志，按照 JT/T808 2011 版的表 34。
    public $alarmList = [];                       // 报警信息
    public $state;                                // 状态位标志，按照 JT/T808 2011 版的表 35。
    public $statusList = [];                      // 状态位信息
    public $latitude;                             // 纬度，单位为 1×10^-6 度。
    public $longitude;                            // 经度，单位为 1×10^-6 度。
    public $height;                               // 高程，单位为米（m）。
    public $speed;                                // 速度，单位为千米每小时（km/h）。
    public $direction;                            // 方向，范围为 0~359，表示度数。
    public $time;                                 // 时间，BCD 码。
    public $extendList = [];                      // 可选项，扩展信息。

    public function decode($bytes = null)
    {
        $bytes = $bytes ?? $this->message->getMsgBody();

        $data            = unpack("Nalarm/Nstate/Nlatitude/Nlongitude/nheight/nspeed/ndirection/H12time", $bytes);
        $this->alarm     = $data["alarm"];
        $this->state     = $data["state"];
        $this->latitude  = $data["latitude"] / pow(10, 6);
        $this->longitude = $data["longitude"] / pow(10, 6);
        $this->height    = $data["height"];
        $this->speed     = $data["speed"] / pow(10, 1);
        $this->direction = $data["direction"];
        $this->time      = sprintf("20%02d-%02d-%02d %02d:%02d:%02d", ...str_split($data["time"], 2));

        return $this->getAlarmList()->getStatusList()->getExtraItems($bytes);
    }

    /**
     * 报警标志位
     *
     * @return $this
     */
    private function getAlarmList()
    {
        $alarms = [
            0 => "紧急报警",
            1 => "超速报警",
            2 => "疲劳驾驶",
            3 => "预警",
            4 => "GNSS模块故障",
            5 => "GNSS天线未接或被剪断",
            6 => "GNSS天线短路",
            7 => "终端主电源欠压",
            8 => "终端主电源掉电",
            9 => "终端LCD或显示器故障",
            10 => "TTS模块故障",
            11 => "摄像头故障",
            12 => "道路运输证IC卡模块故障",
            13 => "超速预警",
            14 => "疲劳驾驶预警",
            18 => "当天累计驾驶超时",
            19 => "超时停车",
            20 => "进出区域",
            21 => "进出路线",
            22 => "路段行驶时间不足/过长",
            23 => "路线偏离报警",
            24 => "车辆 VSS 故障",
            25 => "车辆油量异常",
            26 => "车辆被盗(通过车辆防盗器)",
            27 => "车辆非法点火",
            28 => "车辆非法位移",
            29 => "碰撞预警",
            30 => "侧翻预警",
            31 => "非法开门报警",
        ];

        foreach ($alarms as $index => $desc) {
            if ($this->alarm >> $index & 1) {
                $this->alarmList[$index] = $desc;
            }
        }

        return $this;
    }

    /**
     * 状态位
     *
     * @return $this
     */
    private function getStatusList()
    {
        $status = [
            0 => ['ACC 关', 'ACC 开'],
            1 => ['定位 关', '定位 开'],
            2 => ['北纬', '南纬'],
            3 => ['东经', '西经'],
            4 => ['运营状态', '停运状态'],
            5 => ['经纬度未经保密', '经纬度已经保密'],
            8 => ['空车', '半载'],
            9 => ['保留', '满载'],
            10 => ['车辆油路正常', '车辆油路断开'],
            11 => ['车辆电路正常', '车辆电路断开'],
            12 => ['车门解锁', '车门加锁'],
            13 => ['门 1 关', '门 1 开'],
            14 => ['门 2 关', '门 2 开'],
            15 => ['门 3 关', '门 3 开'],
            16 => ['门 4 关', '门 4 开'],
            17 => ['门 5 关', '门 5 开'],
            18 => ['未使用 GPS 卫星进行定位', '使用 GPS 卫星进行定位'],
            19 => ['未使用北斗卫星进行定位', '使用北斗卫星进行定位'],
            20 => ['未使用 GLONASS 卫星进行定位', '使用 GLONASS 卫星进行定位'],
            21 => ['未使用 Galileo 卫星进行定位', '使用 Galileo 卫星进行定位'],
        ];

        foreach ($status as $index => $desc) {
            if ($key = ($this->state >> $index & 1)) {
                $this->statusList[$key][$index] = $desc[1];
            } else {
                $this->statusList[$key][$index] = $desc[0];
            }
        }

        return $this;
    }

    /**
     * 位置附加信息项
     *
     * @param $bytes
     * @return $this
     */
    private function getExtraItems($bytes)
    {
        if (empty($extraBytes = substr($bytes, 28))) {
            return $this;
        }

        $extras = [];
        for ($i = 0; $i < strlen($extraBytes); $i += 2) {
            $key          = substr($extraBytes, $i, 1);
            $length       = hexdec(bin2hex(substr($extraBytes, $i + 1, 1)));
            $value        = substr($extraBytes, $i + 2, $length);
            $extras[$key] = bin2hex($value);
            $i            += $length;
        }

        return $this->getExtraItemsValue($extras)->getExtraItemsValues($extras);
    }

    private function getExtraItemsValue($extras = [])
    {
        $extraItems = [
            0x01 => '里程',
            0x02 => '油量',
            0x03 => '速度',
            0x04 => '需要人工确认报警事件',
            0x30 => '无线通信网络信号强度',
            0x31 => 'GNSS 定位卫星数',
        ];

        foreach ($extraItems as $key => $item) {
            $byteKey = pack('C', $key);
            if (!array_key_exists($byteKey, $extras)) {
                continue;
            }
            $this->extendList[$key] = [
                $item => hexdec($extras[$byteKey]),
            ];
        }

        return $this;
    }

    private function getExtraItemsValues($extras = [])
    {
        $extraItems = [
            0x25 => [
                '车辆信号状态' => [
                    0 => "近光灯信号",
                    1 => "远光灯信号",
                    2 => "右转向灯信号",
                    3 => "左转向灯信号",
                    4 => "制动信号",
                    5 => "倒档信号",
                    6 => "雾灯信号",
                    7 => "示廓灯",
                    8 => "喇叭信号",
                    9 => "空调状态",
                    10 => "空挡信号",
                    11 => "缓速器工作",
                    12 => "ABS 工作",
                    13 => "加热器工作",
                    14 => "离合器状态",
                ],
            ],
            0x2A => [
                'IO状态' => [
                    0 => "深度休眠状态",
                    1 => "休眠状态",
                ],
            ],
        ];

        foreach ($extraItems as $key => $items) {
            $byteKey = pack('C', $key);
            if (!array_key_exists($byteKey, $extras)) {
                continue;
            }
            foreach ($items as $item => $dicts) {
                $dictionaries = [];
                foreach ($dicts as $index => $dict) {
                    if ($extras[$byteKey] >> $index & 1) {
                        $dictionaries[$index] = $dict;
                    }
                }
                if ($dictionaries) {
                    $this->extendList[$key][$item] = $dictionaries;
                }
            }
        }

        return $this;
    }
}