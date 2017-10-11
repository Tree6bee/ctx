<?php

namespace Tests\Tree6bee\Ctx;

use Tree6bee\Ctx\Ctx as BasicCtx;

/**
 * Class Ctx
 * @property \Tests\Tree6bee\Ctx\Service\Ctx\Ctx $Ctx
 */
class Ctx extends BasicCtx
{
    /**
     * 框架单例，静态变量保存全局实例
     */
    protected static $ctxInstance;

    protected $ctxNamespace = 'Tests\Tree6bee\Ctx';
}
