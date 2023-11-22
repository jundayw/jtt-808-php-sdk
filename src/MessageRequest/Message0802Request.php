<?php

namespace Jundayw\JTT808\MessageRequest;

class Message0802Request extends Request
{
    private $msgId = 0x0802;
    private $title = '存储多媒体数据检索应答';

    public $ackFlowId = 0;                        // 应答流水号对应的多媒体数据检索消息的流水号
    public $multimediaNumber = 0;                 // 多媒体数据总项数满足检索条件的多媒体数据总项数
    public $searchItem = [];                      // 检索项多媒体检索项数据格式

    // 此数据基于$searchItem值
    public $multimediaID;
    public $multimediaType;
    public $channelId;
    public $eventCode;
    public $locationInformation;

    // 此数据基于$locationInformation值
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

}