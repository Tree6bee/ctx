<?php

namespace Ctx\Rpc;

class Ctx
{
    public function debug($var = array())
    {
        return 'Rpc:' . print_r($var, true) .

            '<pre>mysql config:' .
            print_r($this->ctx->Ctx->getSConf('default.master@mysql'), true) . 
            '</pre>';
    }
}
