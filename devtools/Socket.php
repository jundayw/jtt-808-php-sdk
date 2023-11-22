<?php

use Jundayw\JTT808\Message;
use Jundayw\JTT808\MessageRequest\Message0002Request;
use Jundayw\JTT808\MessageRequest\Message0100Request;
use Jundayw\JTT808\MessageRequest\Message0102Request;
use Jundayw\JTT808\MessageRequest\Message0200Request;
use Jundayw\JTT808\MessageResponse\Message8001Response;
use Jundayw\JTT808\MessageResponse\Message8100Response;
use Jundayw\JTT808\MessageResponse\Message8300Response;
use Jundayw\JTT808\MessageResponse\Message9101Response;

include './../../../autoload.php';

// 创建一个 TCP Socket
$server_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($server_socket === false) {
    echo "socket_create() failed:" . socket_strerror(socket_last_error()) . "\n";
    exit;
}
socket_set_option($server_socket, SOL_SOCKET, SO_REUSEADDR, true);
// 绑定到指定地址和端口
if (socket_bind($server_socket, "0.0.0.0", 8808) === false) {
    echo "socket_bind() failed:" . socket_strerror(socket_last_error($server_socket)) . "\n";
    socket_close($server_socket);
    exit;
}

// 开始监听
if (socket_listen($server_socket) === false) {
    echo "socket_listen() failed:" . socket_strerror(socket_last_error($server_socket)) . "\n";
    socket_close($server_socket);
    exit;
}

echo "Server started and listening on 0.0.0.0:8808 ...\n";
$client_sockets = [$server_socket];
$write          = null;
$except         = null;
// 循环接收和处理客户端连接
while (true) {
    $client_sockets_reads = $client_sockets;

    // 等待 socket 上的数据
    if (socket_select($client_sockets_reads, $write, $except, null) === false) {
        echo "socket_select() failed:" . socket_strerror(socket_last_error()) . "\n";
        continue;
    }

    foreach ($client_sockets_reads as $socket) {
        if ($socket == $server_socket) {
            // 接收客户端连接
            $clientSocket = socket_accept($server_socket);
            if ($clientSocket === false) {
                echo "socket_accept() failed:" . socket_strerror(socket_last_error($server_socket)) . "\n";
                continue;
            }
            $client_sockets[] = $clientSocket;
            echo "Client connected:" . socket_getpeername($clientSocket, $address, $port) . "\n";
            continue;
        }

        $length = socket_recv($socket, $bytes, 1024, MSG_DONTWAIT);

        if ($length === false || is_null($bytes)) {
            // 断开连接
            socket_close($socket);
            unset($client_sockets[array_search($socket, $client_sockets)]);
            echo "Client disconnected" . "\n";
            continue;
        }

        try {
            // 解析消息
            $message = new Message();
            $message->decode($bytes);

            switch ($message->getMsgId()) {
                case '0100':
                    // 终端注册
                    $request = new Message0100Request($message);
                    // 打印消息内容
                    var_dump([
                        '制造商ID' => $request->manufacturerId,
                        '终端型号' => $request->terminalModel,
                        '终端ID' => $request->terminalId,
                        '车牌颜色' => $request->plateColor,
                        '车辆标识' => $request->plateNumber,
                    ]);
                    // 终端注册应答
                    $response = new Message8100Response($message);
                    // 构造应答消息
                    $response->response(0, '123456');
                    $bytes = $message->encode($response);
                    // 发送应答消息
                    socket_write($socket, $bytes, strlen($bytes));
                    echo 'Message8100Response:' . bin2hex($bytes) . PHP_EOL . PHP_EOL . PHP_EOL;
                    break;
                case '0002':
                    // 终端心跳
                    $request = new Message0002Request($message);
                    var_dump([
                        'getMsgId' => $request->getMessage()->getMsgHeader()->getMsgId(),
                        'getTerminalId' => $request->getMessage()->getMsgHeader()->getTerminalId(),
                        'getMsgFlowId' => $request->getMessage()->getMsgHeader()->getMsgFlowId(),
                    ]);

                    // // 0x9101实时音视屏传输请求
                    // $response = new Message9101Response($message);
                    // // $response->getMessage()->getMsgHeader()->setMsgFlowId(555);
                    // // $response->getMessage()->getMsgHeader()->setTerminalId('202304281209');
                    // $response->setIp('127.0.0.1')
                    //     ->setTCPPort(1708)
                    //     ->setChannelNumber(1);
                    // $response->response();
                    // $bytes = $message->encode($response);
                    // // 发送应答消息
                    // socket_write($socket, $bytes, strlen($bytes));
                    // echo 'Message9101Response:' . bin2hex($bytes) . PHP_EOL . PHP_EOL . PHP_EOL;

                    // // 文本信息下发
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
                    //     ->setTextMsg('文本信息下发');
                    // $response = $response->response();
                    // $bytes    = $message->encode($response);
                    // // 发送应答消息
                    // socket_write($socket, $bytes, strlen($bytes));
                    // echo 'Message8300Response:' . bin2hex($bytes) . PHP_EOL . PHP_EOL . PHP_EOL;

                    // 平台通用应答
                    $response = new Message8001Response($message);
                    $response->response(0);
                    $bytes = $message->encode($response);
                    // 发送应答消息
                    socket_write($socket, $bytes, strlen($bytes));
                    echo 'Message8100Response:' . bin2hex($bytes) . PHP_EOL . PHP_EOL . PHP_EOL;
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
                    // 发送应答消息
                    socket_write($socket, $bytes, strlen($bytes));
                    var_dump(bin2hex($bytes));
                    break;
                case '0200':
                    // 位置信息汇报
                    $request = new Message0200Request($message);
                    // 打印消息内容
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
                    // 构造应答消息
                    $response->response(0);
                    $bytes = $message->encode($response);
                    // 发送应答消息
                    socket_write($socket, $bytes, strlen($bytes));
                    echo 'Message8001Response:' . bin2hex($bytes) . PHP_EOL . PHP_EOL . PHP_EOL;
                    break;
                default:
                    // 平台通用应答
                    $response = new Message8001Response($message);
                    // 构造应答消息
                    $response->response(0);
                    $bytes = $message->encode($response);
                    // 发送应答消息
                    socket_write($socket, $bytes, strlen($bytes));
                    echo 'Message8001Response:' . bin2hex($bytes) . PHP_EOL . PHP_EOL . PHP_EOL;
                    break;
            }

        } catch (Exception $e) {
            echo "Eexception: " . $e->getMessage() . "\n";
            break;
        }
    }
}

// 关闭服务器 Socket
socket_close($server_socket);
