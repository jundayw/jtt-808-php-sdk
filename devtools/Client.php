<?php

include './../../../autoload.php';

// 创建一个 TCP Socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    exit;
}

// 连接到指定的地址和端口
if (socket_connect($socket, "127.0.0.1", 8808) === false) {
    echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
    exit;
}

echo "Connected to server 127.0.0.1:8808 ...\n";

while (true) {
    echo PHP_EOL;
    echo "input:";
    $message = fgets(STDIN);
    $message = str_replace(PHP_EOL, '', $message);

    if (empty($message)) {
        continue;
    }

    $bytes = pack('H*', $message);
    // 发送消息
    socket_write($socket, $bytes, strlen($bytes));
    // 接收服务器应答消息
    $buf = socket_read($socket, 1024);
    if ($buf === false) {
        echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
        exit;
    }
    // 打印应答消息内容
    echo "Received reply message:" . PHP_EOL;
    echo bin2hex($buf) . PHP_EOL;
}

// 关闭 Socket 连接
socket_close($socket);
