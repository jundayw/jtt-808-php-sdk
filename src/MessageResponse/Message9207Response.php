<?php

namespace Jundayw\JTT808\MessageResponse;

class Message9207Response extends Response
{
    protected $msgId = 0x9207;
    protected $title = '文件上传控制';

    protected $ackFlowId = 0; // 应答流水号
    protected $uac = 0;       // 上传控制:0-暂停 1-继续 2-取消

    /**
     * @param int $ackFlowId 应答流水号
     * @return $this
     */
    public function setAckFlowId(int $ackFlowId)
    {
        $this->ackFlowId = $ackFlowId;
        return $this;
    }

    /**
     * @param int $uac 上传控制
     * 0-暂停 1-继续 2-取消
     * @return $this
     */
    public function setUac(int $uac = 0)
    {
        $this->uac = $uac;
        return $this;
    }

    public function response()
    {
        $this->body = pack('nC',
            $this->ackFlowId,
            $this->uac
        );
        return $this;
    }

}