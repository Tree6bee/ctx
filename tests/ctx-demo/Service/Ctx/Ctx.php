<?php

namespace Ctx\Service\Ctx;

use Ctx\Basic\Ctx;

/**
 * 模块接口声明文件
 * 备注：文件命名跟模块中的其他类不同，因为要防止模块声明类只能被实例化一次
 * 也就是只能用ctx->模块 来实例化，不能用loadC来实例化更多
 */
class CtxCtx extends Ctx
{
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
    public function debug($var = array())
    {
        return $var;
    }

    /**
     * 测试代码
     */
    private function rpcDebug($var = array())
    {
        return $var;
    }

    /**
     * 输出调试信息
     */
    public function dd()
    {
        array_map(function ($x) {
            // (new Dumper)->dump($x);
            echo '<pre>' . var_export($x, true) . '</pre>';
        }, func_get_args());
    }
}
