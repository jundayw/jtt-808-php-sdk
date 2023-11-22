<?php

include './../../../autoload.php';

// 打开文件
$handle = fopen('../resources/RTP.log', 'r');

$rtp    = new \Jundayw\JTT808\MessageRequest\RTPRequest();
$flag   = hex2bin($rtp->frameFlag);
$buffer = '';
// 按行读取文件内容
while (($line = fgets($handle)) !== false) {

    if (trim($line) == '') {
        continue;
    }
    $line = trim($line, PHP_EOL);
    $line = hex2bin($line);

    $buffer .= $line;

    while ($length = strpos($buffer, $flag, 1)) {
        $live     = substr($buffer, 0, $length);
        $response = $rtp->decode($live);

        $key = join('-', [
            $response->simNum,
            $response->channelId,
        ]);


        if ($response->isVideo) {
            file_put_contents('../resources/' . $key . '.h264', $response->body, FILE_APPEND);
        }

        if ($response->isAudio) {
            file_put_contents('../resources/' . $key . '.pcm', $response->body, FILE_APPEND);
        }

        $buffer = substr($buffer, $length);
    }
}

// 关闭文件
fclose($handle);
