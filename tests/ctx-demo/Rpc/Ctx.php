<?php

namespace Tests\Tree6bee\Ctx\Rpc;

class Ctx
{
    public function rpcDebug($var = array())
    {
        return 'Rpc: ' . print_r($var, true);
    }
}
