<?php

namespace Jundayw\JTT808\MessageResponse;

use Jundayw\JTT808\Concerns\TerminalParameters;

class Message8103Response extends Response
{
    use TerminalParameters;

    protected $msgId = 0x8103;
    protected $title = '设置终端参数';

    /**
     * 应答
     *
     * @param array $params
     * @return Message8103Response
     */
    public function response(array $params = []): Message8103Response
    {
        $body = [];
        foreach ($params as $key => $param) {
            if (!array_key_exists($key, $this->type)) {
                continue;
            }
            $format = $this->type[$key];
            foreach (is_array($param) ? $param : [$param] as $value) {
                if ($format == 'a*') {
                    $value = mb_convert_encoding($value, 'GBK', 'utf-8');
                }
                $body[] = pack('NC', $key, strlen($value = pack($format, $value))) . $value;
            }
        }
        $this->body = pack('C', count($body)) . join('', $body);
        return $this;
    }
}