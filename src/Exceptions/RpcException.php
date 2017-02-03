<?php

namespace Tree6bee\Ctx\Exceptions;

class RpcException extends Exception
{
    /**
     * rpc exception trace
     *
     * @var string
     */
    protected $rpcTrace;

    public function __construct($message = '', $trace = '', $code = 0)
    {
        $this->rpcTrace = $trace;
        parent::__construct($message, $code);
    }

    public function getRpcTrace()
    {
        return $this->rpcTrace;
    }
}
