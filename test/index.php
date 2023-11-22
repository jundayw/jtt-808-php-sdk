<?php

use Jundayw\JTT808\Message;
use Jundayw\JTT808\MessageRequest\Message0001Request;
use Jundayw\JTT808\MessageRequest\Message0002Request;
use Jundayw\JTT808\MessageRequest\Message0003Request;
use Jundayw\JTT808\MessageRequest\Message0100Request;
use Jundayw\JTT808\MessageRequest\Message0102Request;
use Jundayw\JTT808\MessageRequest\Message0104Request;
use Jundayw\JTT808\MessageRequest\Message0107Request;
use Jundayw\JTT808\MessageRequest\Message0200Request;
use Jundayw\JTT808\MessageRequest\Message0201Request;
use Jundayw\JTT808\MessageRequest\Message0805Request;
use Jundayw\JTT808\MessageRequest\Message1003Request;
use Jundayw\JTT808\MessageRequest\Message1206Request;
use Jundayw\JTT808\MessageResponse\Message8001Response;
use Jundayw\JTT808\MessageResponse\Message8100Response;
use Jundayw\JTT808\MessageResponse\Message8103Response;
use Jundayw\JTT808\MessageResponse\Message8104Response;
use Jundayw\JTT808\MessageResponse\Message8106Response;
use Jundayw\JTT808\MessageResponse\Message8107Response;
use Jundayw\JTT808\MessageResponse\Message8201Response;
use Jundayw\JTT808\MessageResponse\Message8300Response;
use Jundayw\JTT808\MessageResponse\Message8801Response;
use Jundayw\JTT808\MessageResponse\Message8802Response;
use Jundayw\JTT808\MessageResponse\Message9003Response;
use Jundayw\JTT808\MessageResponse\Message9101Response;
use Jundayw\JTT808\MessageResponse\Message9102Response;
use Jundayw\JTT808\MessageResponse\Message9105Response;
use Jundayw\JTT808\MessageResponse\Message9201Response;
use Jundayw\JTT808\MessageResponse\Message9202Response;
use Jundayw\JTT808\MessageResponse\Message9206Response;
use Jundayw\JTT808\MessageResponse\Message9207Response;

include './../../../autoload.php';

// // 终端通用应答
// $bytes = hex2bin('7e0001000501234567891200b800b80012008d7e');
// 终端心跳
$bytes = hex2bin('7E000200002023042812090001377E');
// // 终端注销
// $bytes = hex2bin('7E000300000123456789120001997E');
// // 终端注册
// $bytes = hex2bin('7E0100002D01234567891200B800010002313233000034353600000000000000000000000000000000003132333435000002B2E2413132333435187E');
// // 终端鉴权
// $bytes = hex2bin('7E0102000A20230428120902CD30313233343536373839F37E');
// // 查询终端参数应答
// $bytes = hex2bin('7e01040062202304281209022b00010900000001040000000f00000002040000000f00000003040000000500000004040000000f00000005040000000500000006040000000f000000070400000005000000480b3133363336333633363336000000480b31333833383338333833387d7e');
// 查询终端属性应答
// $bytes = hex2bin('7e01070042202304281209022b00A50000000056000000000000000000000000000000000000005600000000001234000000000000000800000000000000002345050000000001050000000002A5A5b77e');
// // 位置信息汇报
// $bytes = hex2bin('7E0200002601234567891200B900000000000000030261136106F0124D0058029400002304261530320104000004CE02020000AA7E');
// // 位置信息汇报（报警+附加）
// $bytes = hex2bin('7E0200003001234567891200B900000010000000030261136106F0124D0058029400002304261530320104000004ce020200372504000000052a020001967E');
// $bytes = hex2bin('7E0200005F00195733261706E000080000000C020100000000000000000000000000002210181125340104000000000202000003020000040200001404000000001504000000001604000000001702000018030000002504000000002A0200002B04000000003001FF3101001D7E');
// // 位置信息查询
// $bytes = hex2bin('7E0201003201234567891200B9000100000010000000030261136106F0124D0058029400002304261530320104000004ce020200372504000000052a020001947E');

// // 平台通用应答
// $bytes = hex2bin('7E800100050123456789124B3E00B9020000D17E');
// // 终端注册应答
// $bytes = hex2bin('7E8100000D0123456789124B3000B80031323334353637383930D57E');

// // 摄像头立即拍摄命令应答
// $bytes = hex2bin('7e08050003202304281209022b000102107e');
// $bytes = hex2bin('7e0805000D202304281209022b000100000200000001000000021d7e');
// // 文件上传完成通知
// $bytes = hex2bin('7e12060003202304281209000101237e');


try {
    $message = new Message();
    $message->decode($bytes);

    switch ($message->getMsgId()) {
        case '0001':
            // 终端通用应答
            $request = new Message0001Request($message);
            var_dump([
                'ackFlowId' => $request->ackFlowId,
                'ackId' => $request->ackId,
                'code' => $request->code,
            ]);
            break;
        case '0002':
            // 终端心跳
            $request = new Message0002Request($message);
            var_dump([
                'getMsgId' => $request->getMessage()->getMsgHeader()->getMsgId(),
                'getTerminalId' => $request->getMessage()->getMsgHeader()->getTerminalId(),
                'getMsgFlowId' => $request->getMessage()->getMsgHeader()->getMsgFlowId(),
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '0003':
            // 终端注销
            $request = new Message0003Request($message);
            var_dump([
                'terminalId' => $request->getMessage()->getMsgHeader()->getTerminalId(),
            ]);
            break;
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
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '0102':
            // 终端鉴权
            $request = new Message0102Request($message);
            var_dump([
                'authCode' => $request->authCode,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '0104':
            // 查询终端参数应答
            $request = new Message0104Request($message);
            var_dump([
                'ackFlowId' => $request->ackFlowId,
                'number' => $request->number,
                'parameters' => $request->parameters,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '1003':
            // 终端上传音视屏属性
            $request = new Message1003Request($message);
            var_dump([
                'audioCode' => $request->audioCode,
                'videoCode' => $request->videoCode,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '0107':
            // 查询终端属性应答
            $request = new Message0107Request($message);
            var_dump([
                'terminalType' => $request->terminalType,
                'manufacturerId' => $request->manufacturerId,
                'terminalModel' => $request->terminalModel,
                'terminalId' => $request->terminalId,
                'simNum' => $request->simNum,
                'hwVersion' => $request->hwVersion,
                'swVersion' => $request->swVersion,
                'gpsModule' => $request->gpsModule,
                'communicationModule' => $request->communicationModule,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '1206':
            // 文件上传完成通知
            $request = new Message1206Request($message);
            var_dump([
                'ackFlowId' => $request->ackFlowId,
                'code' => $request->code,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
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
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '0201':
            // 位置信息查询应答
            $request = new Message0201Request($message);
            var_dump([
                '应答流水号' => $request->ackFlowId,
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
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
        case '0805':
            // 摄像头立即拍摄命令应答
            $request = new Message0805Request($message);
            var_dump([
                'ackFlowId' => $request->ackFlowId,
                'code' => $request->code,
                'multimediaNumber' => $request->multimediaNumber,
            ]);
            // 平台通用应答
            $response = new Message8001Response($message);
            $response->response(0);
            $bytes = $message->encode($response);
            var_dump(bin2hex($bytes));
            break;
    }

    // // 8103
    // // 设置终端参数
    // $message  = new Message();
    // $response = new Message8103Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->response([
    //     0x0001 => 15,
    //     0x0002 => 15,
    //     0x0003 => 5,
    //     0x0004 => 15,
    //     0x0005 => 5,
    //     0x0006 => 15,
    //     0x0007 => 5,
    //     0x0048 => [
    //         13636363636,
    //         13838383838,
    //     ],
    // ]);
    // $bytes = $message->encode($response);
    // var_dump(bin2hex($bytes));

    // // 8104
    // // 查询终端参数
    // $message  = new Message();
    // $response = new Message8104Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $bytes = $message->encode($response);
    // var_dump(bin2hex($bytes));

    // // 8106
    // // 查询指定终端参数
    // $message  = new Message();
    // $response = new Message8106Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->response([
    //     0x0001,
    //     0x0002,
    //     0x0003,
    //     0x0004,
    //     0x0005,
    //     0x0006,
    //     0x0007,
    //     0x0013,
    // ]);
    // $bytes = $message->encode($response);
    // var_dump(bin2hex($bytes));

    // // 8107
    // // 查询终端属性
    // $message  = new Message();
    // $response = new Message8107Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('012345678912');
    // $bytes = $message->encode($response);
    // var_dump(bin2hex($bytes));

    // // 8201
    // // 位置信息查询
    // $message  = new Message();
    // $response = new Message8201Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $bytes = $message->encode($response);
    // var_dump(bin2hex($bytes));

    // // 0x8300文本信息下发
    // $message  = new Message();
    // $response = new Message8300Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setMarker([
    //     0b00000001,
    //     0b00000100,
    //     0b00001000,
    //     0b00010000,
    //     0b00000000,
    //     // 1,4,8,16,32
    // ])
    //     ->setTextMsg('test')
    //     ->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 8801摄像头立即拍摄命令
    // $message  = new Message();
    // $response = new Message8801Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // // $response->setChannelId()
    // //     ->setStopCommand()
    // //     ->setVideoCommand()
    // //     ->setPhotoCommand()
    // //     ->setShootingInterval()
    // //     ->setSaveFlag()
    // //     ->setResolution()
    // //     ->setQuality()
    // //     ->setBrightness()
    // //     ->setContrast()
    // //     ->setSaturation()
    // //     ->setChroma();
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 8802存储多媒体数据检索
    // $message  = new Message();
    // $response = new Message8802Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // // $response->setMultimediaType()
    // //     ->setChannelId()
    // //     ->setEventCode()
    // //     ->setStartTime()
    // //     ->setEndTime('2023-05-04 17:00:00');
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9003查询终端音视屏属性
    // $message  = new Message();
    // $response = new Message9003Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9101实时音视屏传输请求
    // $message  = new Message();
    // $response = new Message9101Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setIp('127.0.0.1')
    //     ->setTCPPort(8080)
    //     ->setUDPPort(9090)
    //     ->setChannelNumber(123456)
    //     ->setDataType(0)
    //     ->setStreamType(1);
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9102音视屏实时传输控制
    // $message  = new Message();
    // $response = new Message9102Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setChannelNumber('88888888')
    //     ->setControlInstruct(2);
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9105实时音视频传输状态通知
    // $message  = new Message();
    // $response = new Message9105Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setChannelNumber('88888888')
    //     ->setLossRate(1);
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9201平台下发远程录像回放请求
    // $message  = new Message();
    // $response = new Message9201Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setIp('127.0.0.1')
    //     ->setTCPPort(8080)
    //     ->setUDPPort(0)
    //     ->setChannelNumber(123456)
    //     ->setVideoType(0)
    //     ->setStreamType(1)
    //     ->setStartTime('2023-05-10 13:13:13');
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9202平台下发远程录像回放控制
    // $message  = new Message();
    // $response = new Message9202Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setChannelNumber(123456)
    //     ->setPlayback(0)
    //     ->setLeverage(1)
    //     ->setDvr('2023-05-10 13:13:13');
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9205查询资源列表
    // $message  = new Message();
    // $response = new Message9202Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setChannelNumber(123456);
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9206文件上传指令
    // $message  = new Message();
    // $response = new Message9206Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setIp('127.0.0.1')
    //     ->setPort(9090)
    //     ->setUsername('root')
    //     ->setPassword('123456')
    //     ->setRoute('/var/www/file')
    //     ->setChannelNumber('123456')
    //     ->setStartTime('2023-05-10 16:06:06')
    //     ->setEndTime('2023-05-10 16:06:06');
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));

    // // 0x9207文件上传控制
    // $message  = new Message();
    // $response = new Message9207Response($message);
    // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
    // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
    // $response->setAckFlowId('1123');
    // $response = $response->response();
    // var_dump(bin2hex($message->encode($response)));
} catch (Exception $e) {
    var_dump('Eexception', $e->getMessage());
    die;
}