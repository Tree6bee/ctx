<?php

namespace Tests\Tree6bee\Ctx\Service\Ctx;

use Tests\Tree6bee\Ctx\Basic\Ctx;

/**
 * 模块接口声明文件
 * 备注：文件命名跟模块中的其他类不同，因为要防止模块声明类只能被实例化一次
 * 也就是只能用ctx->模块 来实例化，不能用loadC来实例化更多
 */
class CtxCtx extends Ctx
{
    public function init()
    {
        $this->demo = $this->loadC('Demo', 'hello');
    }

    /**
     * 测试代码
     */
    public function setMessage($var)
    {
        return $this->demo->setMessage($var);
    }

    public function getMessage()
    {
        return $this->demo->getMessage();
    }

    /**
     * @deprecated 调试代码
     */
    protected $rpc = array(
        'host'      => 'http://cf.sh7ne.dev/public/rpc.php',
        'method'    => array(
            'rpcDebug',
        ),
    );

    /**
     * 测试代码
     */
    private function rpcDebug($var = array())
    {
        return $var;
    }
}
