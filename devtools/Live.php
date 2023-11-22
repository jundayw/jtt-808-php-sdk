<?php

include './../../../autoload.php';

// 创建一个 TCP Socket
$server_socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($server_socket === false) {
    echo "socket_create() failed:" . socket_strerror(socket_last_error()) . "\n";
    exit;
}
socket_set_option($server_socket, SOL_SOCKET, SO_REUSEADDR, true);
// 绑定到指定地址和端口
if (socket_bind($server_socket, "0.0.0.0", 1708) === false) {
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

echo "Server started and listening on 0.0.0.0:1708 ...\n";
$client_sockets = [$server_socket];
$buffer         = '';
$rtp            = new \Jundayw\JTT808\MessageRequest\RTPRequest();

// 音视频
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -b:v 500k -pix_fmt yuv420p -s 1280x720 -r 25 -c:a aac -b:a 128k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -b:v 128k -pix_fmt yuv420p -s 1280x720 -r 25 -c:a aac -b:a 128k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -b:v 128k -pix_fmt yuv420p -s 1280x720 -r 25 -c:a aac -b:a 128k -af 'anlmdn' -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -b:v 128k -pix_fmt yuv420p -s 1280x720 -r 25 -c:a aac -b:a 128k -af 'adeclip' -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -b:v 128k -pix_fmt yuv420p -s 1280x720 -r 30 -c:a aac -b:a 64k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";

$cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -s 1280x720 -r 25 -c:a aac -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";

// // 只推音频
// $cmd = "ffmpeg -re -f mulaw -ar 8000 -ac 1 -i pipe:3 -c:a aac -b:a 64k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";
// // 只推视频
// $cmd = "ffmpeg -re -f h264 -i pipe:0 -preset ultrafast -c:v libx264 -s 1280x720 -r 25 -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";

// // 多路
// $cmd = "ffmpeg -re -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -map 0:v -map 1:a -c:a aac -b:a 64k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -map 0:v -map 1:a -vcodec libx264 -vprofile baseline -acodec libmp3lame -ar 44100 -ac 1 -f flv rtmp://127.0.0.1:1935/hls/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv rtmp://127.0.0.1:1935/dash/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv /www/server/nginx/html/rtmp/mp4/018270167059-1.mp4 -y";
// $cmd = "ffmpeg -re -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -map 0:v -map 1:a -c:a aac -b:a 64k -preset ultrafast -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -map 0:v -map 1:a -vcodec libx264 -vprofile baseline -acodec libmp3lame -ar 44100 -ac 1 -f -preset ultrafast flv rtmp://127.0.0.1:1935/hls/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -preset ultrafast -f flv rtmp://127.0.0.1:1935/dash/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv /www/server/nginx/html/rtmp/mp4/018270167059-1.mp4 -y";
// $cmd = "ffmpeg -re -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -map 0:v -map 1:a -preset ultrafast -c:v libx264 -c:a aac -b:a 128k -af 'anlmdn' -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -map 0:v -map 1:a -vcodec libx264 -vprofile baseline -acodec libmp3lame -ar 44100 -ac 1 -preset ultrafast -f flv rtmp://127.0.0.1:1935/hls/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -preset ultrafast -f flv rtmp://127.0.0.1:1935/dash/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv /www/server/nginx/html/rtmp/mp4/018270167059-1.mp4 -y";
// $cmd = "ffmpeg -re -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -c:v libx264 -map 0:v -map 1:a -c:a aac -b:a 64k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -map 0:v -map 1:a -vcodec libx264 -vprofile baseline -acodec libmp3lame -ar 44100 -ac 1 -f flv rtmp://127.0.0.1:1935/hls/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv rtmp://127.0.0.1:1935/dash/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv /www/server/nginx/html/rtmp/mp4/018270167059-1.mp4 -y";

// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -s 1280x720 -r 25 -af 'anlmdn' -map 0:v -map 1:a -c:a aac -b:a 64k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -map 0:v -map 1:a -vcodec libx264 -vprofile baseline -acodec libmp3lame -ar 44100 -ac 1 -f flv rtmp://127.0.0.1:1935/hls/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv rtmp://127.0.0.1:1935/dash/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv /www/server/nginx/html/rtmp/mp4/018270167059-1.mp4 -y";
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -map 0:v -map 1:a -c:a aac -b:a 64k -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -map 0:v -map 1:a -vcodec libx264 -vprofile baseline -acodec libmp3lame -ar 44100 -ac 1 -f flv rtmp://127.0.0.1:1935/hls/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv rtmp://127.0.0.1:1935/dash/018270167059-1 -map 0:v -map 1:a -c:v copy -c:a copy -f flv /www/server/nginx/html/rtmp/mp4/018270167059-1.mp4 -y";

// // 可用音视频
// $cmd = "ffmpeg -f h264 -i pipe:0 -f mulaw -ar 8000 -ac 1 -i pipe:3 -preset ultrafast -c:v libx264 -s 1280x720 -r 25 -c:a aac -af 'anlmdn' -f flv rtmp://127.0.0.1:1935/live/018270167059-1 -y";

$descriptorspec = [
    0 => ["pipe", "r"], // STDIN
    1 => ["pipe", "w"], // STDOUT
    2 => ["pipe", "w"], // STDERR
    3 => ["pipe", "r"], // STDIN
];
// 创建子进程通道
$process = proc_open($cmd, $descriptorspec, $pipes);

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

        $length = socket_recv($socket, $bytes, 8192, MSG_DONTWAIT);

        if ($length === false || is_null($bytes)) {
            // 断开连接
            socket_close($socket);
            unset($client_sockets[array_search($socket, $client_sockets)]);
            echo "Client disconnected" . "\n";
            continue;
        }

        file_put_contents('1078.txt', bin2hex($bytes), FILE_APPEND);

        $buffer .= $bytes;

        while ($length = strpos($buffer, hex2bin('30316364'), 1)) {
            $live     = substr($buffer, 0, $length);
            $response = $rtp->decode($live);
            // 生成唯一标识
            $key = join('-', [
                $response->simNum,
                $response->channelId,
            ]);
            // 视频数据写入管道
            if ($response->isVideo) {
                fwrite($pipes[0], $response->body);
            }
            // 音频数据写入管道
            if ($response->isAudio) {
                fwrite($pipes[3], $response->body);
            }
            // 更新 Buffer
            $buffer = substr($buffer, $length);
        }

    }
}
// 关闭服务器 Socket
socket_close($server_socket);
