<?php

namespace Jundayw\JTT808\MessageRequest;

use Jundayw\JTT808\Concerns\TerminalParameters;

class Message0104Request extends Request
{
    use TerminalParameters;

    private $msgId = 0x0104;
    private $title = '查询终端参数应答';

    public $ackFlowId = 0;
    public $number = 0;
    public $parameters = [];

    public function decode($bytes = null)
    {
        $bytes = $this->message->getMsgBody();

        $data            = unpack("nackFlowId/Cnumber", $bytes);
        $this->ackFlowId = $data['ackFlowId'];
        $this->number    = $data['number'];

        return $this->getParameters(substr($bytes, 3));
    }

    private function getParameters($bytes): Message0104Request
    {
        $parameters = [];
        for ($i = 0; $i < strlen($bytes); $i += 5) {
            $key    = hexdec(bin2hex(substr($bytes, $i, 4)));
            $length = hexdec(bin2hex(substr($bytes, $i + 4, 1)));
            $value  = substr($bytes, $i + 5, $length);
            if (array_key_exists($key, $parameters)) {
                if (!is_array($parameters[$key])) {
                    $parameters[$key] = [$parameters[$key]];
                }
                $parameters[$key][] = $value;
            } else {
                $parameters[$key] = $value;
            }
            $i += $length;
        }
        return $this->getParametersType($parameters);
    }

    private function getParametersType($parameters): Message0104Request
    {
        foreach ($parameters as $index => $parameter) {
            if (!array_key_exists($index, $this->type)) {
                continue;
            }
            $format  = $this->type[$index];
            $unpacks = [];
            foreach (is_array($parameter) ? $parameter : [$parameter] as $param) {
                $unpack = unpack("{$format}parameter", $param)['parameter'];
                if ($format == 'a*') {
                    $unpack = mb_convert_encoding($unpack, 'utf-8', 'GBK');
                }
                $unpacks[] = $unpack;
            }
            $this->parameters[$index] = count($unpacks) == 1 ? current($unpacks) : $unpacks;
        }
        return $this;
    }
}