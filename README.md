# 安装方法

命令行下, 执行 composer 命令安装:

```shell
composer require jundayw/jtt-808-php-sdk
```

# 上行消息对照表

| 消息ID   | 位置                 | 说明     |
|--------|--------------------|--------|
| 0x0001 | Message0001Request | 终端通用应答 |
| 0x0002 | Message0002Request | 终端心跳   |
| 0x0003 | Message0003Request | 终端注销   |
| 0x0100 | Message0100Request | 终端注册   |
| 0x0102 | Message0102Request | 终端鉴权   |
| 0x0104 | Message0104Request | 查询终端参数应答   |
| 0x0107 | Message0107Request | 查询终端属性应答   |
| 0x0200 | Message0200Request | 位置信息汇报   |
| 0x0201 | Message0201Request | 位置信息查询应答   |
| 0x0802 | Message0802Request | 存储多媒体数据检索应答   |
| 0x0805 | Message0805Request | 摄像头立即拍摄命令应答   |
| 0x1003 | Message1003Request | 终端上传音视屏属性   |
| 0x1206 | Message1206Request | 文件上传完成通知   |

# 下行消息对照表

| 消息ID   | 位置                  | 说明     |
|--------|---------------------|--------|
| 0x8001 | Message8001Response | 平台通用应答   |
| 0x8100 | Message8100Response | 终端注册应答   |
| 0x8103 | Message8103Response | 设置终端参数   |
| 0x8104 | Message8104Response | 查询终端参数   |
| 0x8106 | Message8106Response | 查询终端指定参数   |
| 0x8107 | Message8107Response | 查询终端属性   |
| 0x8201 | Message8201Response | 位置信息查询   |
| 0x8300 | Message8300Response | 文本信息下发   |
| 0x8801 | Message8801Response | 摄像头立即拍摄命令   |
| 0x8802 | Message8802Response | 存储多媒体数据检索   |
| 0x9003 | Message9003Response | 查询终端音视屏属性   |
| 0x9101 | Message9101Response | 实时音视屏传输请求   |
| 0x9102 | Message9102Response | 音视屏实时传输控制   |
| 0x9105 | Message9105Response | 实时音视频传输状态通知   |
| 0x9201 | Message9201Response | 平台下发远程录像回放请求   |
| 0x9202 | Message9202Response | 平台下发远程录像回放控制   |
| 0x9205 | Message9205Response | 查询资源列表   |
| 0x9206 | Message9206Response | 文件上传指令   |
| 0x9207 | Message9207Response | 文件上传控制   |

# 演示案例

```php
use Jundayw\JTT808\Message;
use Jundayw\JTT808\MessageRequest\Message0100Request;
use Jundayw\JTT808\MessageRequest\Message0200Request;
use Jundayw\JTT808\MessageResponse\Message8001Response;
use Jundayw\JTT808\MessageResponse\Message8100Response;

$bytes = hex2bin('7E0100002D01234567891200B800010002313233000034353600000000000000000000000000000000003132333435000002B2E2413132333435187E');
$bytes = hex2bin('7E8100000D0123456789124B3000B80031323334353637383930D57E');
$bytes = hex2bin('7E0200002601234567891200B900000000000000030261136106F0124D0058029400002304261530320104000004CE02020000AA7E');
$bytes = hex2bin('7E800100050123456789124B3E00B9020000D17E');

$bytes = hex2bin('7E0200003001234567891200B900000010000000030261136106F0124D0058029400002304261530320104000004ce020200372504000000052a020001967E');

try {
    $message = new Message();
    $message->decode($bytes);

    switch ($message->getMsgId()) {
        case '0100':
            // 终端注册
            $request = new Message0100Request($message);
            var_dump([
                '制造商ID' => $request->manufacturerId,
                '终端型号' => $request->terminalModel,
                '终端ID' => $request->terminalId,
                '车牌颜色' => $request->plateColor,
                '车辆标识' => $request->plateNumber,
            ]);
            // 终端注册应答
            $response = new Message8100Response($message);
            $response->response(0, '123456');
            var_dump(bin2hex($message->encode($response)));
            break;
        case '0200':
            // 位置信息汇报
            $request = new Message0200Request($message);
            var_dump([
                '报警信息' => $request->alarmList,
                '状态位信息' => $request->statusList,
                '纬度' => $request->latitude,
                '经度' => $request->longitude,
                '高程' => $request->height,
                '速度' => $request->speed,
                '时间' => $request->time,
                '扩展信息' => $request->extendList,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            var_dump(bin2hex($message->encode($response)));
            break;
    }

} catch (Exception $e) {
    var_dump('Eexception', $e->getMessage());
    die;
}
```