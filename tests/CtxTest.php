<?php

namespace Tests\Tree6bee\Ctx;

use Tree6bee\Ctx\Rpc\Http\EasyCurl;

/**
 * @todo 增加rpc的单测
 */
class CtxTest extends \PHPUnit_Framework_TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        //初始化用于测试的ctx单例对象
        $this->ctx = \Tests\Tree6bee\Ctx\Ctx::getInstance();
    }

    public function testCtx()
    {
        $ret = $this->ctx->Ctx->setMessage('Ctx.');
        $this->assertEquals(true, $ret);

        //factory
        $ctx = \Tests\Tree6bee\Ctx\Ctx::getInstance();
        $ret = $ctx->Ctx->getMessage('Ctx.');
        $this->assertEquals('hello Ctx.', $ret);
    }
}
