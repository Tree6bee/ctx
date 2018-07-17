<?php

namespace Tests\Ctx;

use Tests\Tree6bee\Ctx\Ctx;
use Tree6bee\Ctx\Rpc\Client;

/**
 * @todo 增加rpc的单测
 */
class CtxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ctx
     */
    protected $ctx;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        //初始化用于测试的ctx单例对象
        $this->ctx = Ctx::getInstance();
    }

    public function testCtx()
    {
        $ret = $this->ctx->Ctx->setMessage('Ctx.');
        $this->assertEquals(true, $ret);

        //factory
        /** @var Ctx $ctx */
        $ctx = Ctx::getInstance();
        $ret = $ctx->Ctx->getMessage();
        $this->assertEquals('hello Ctx.', $ret);
    }

    public function testRpc()
    {
        // $ctx = \Mockery::mock($this->ctx->Ctx)->makePartial();
        $this->assertNull(null);
    }
}
