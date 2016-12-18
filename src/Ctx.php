<?php

namespace Tree6bee\Ctx;

use Tree6bee\Ctx\Exceptions\Exception;
use Tree6bee\Support\Ctx\Loader;
use ReflectionClass;

/**
 * 通用context对象
 *
 * @copyright sh7ning 2016.1
 * @author    sh7ning
 * @version   0.0.1
 * @example
 */
abstract class Ctx
{
    /**
     * 私有克隆函数，防止外办克隆对象
     */
    private function __clone()
    {
    }

    /**
     * 框架单例，静态变量保存全局实例
     * @description 这里设置为private，是为了让该静态属性必须被继承，且必须为 protected
     */
    private static $ctxInstance;

    /**
     * 请求单例
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (empty(static::$ctxInstance)) {
            static::$ctxInstance = new static();
        }

        return static::$ctxInstance;
    }

    /**
     * ctx_base 目录
     * @var $ctxBase
     */
    private $ctxBase;

    /**
     * ctx命名空间
     * @var $ctxNamespace
     */
    private $ctxNamespace;

    /**
     * 私有构造函数，防止外界实例化对象
     */
    private function __construct()
    {
        $thisReflection = new ReflectionClass($this);
        $this->ctxBase = dirname($thisReflection->getFileName());
        $this->ctxNamespace = $thisReflection->getNamespaceName();
    }

    /**
     * 自动单例获取ctx服务框架的模块
     */
    public function __get($m)
    {
        //不想增加对首字母大小写的判断
        //强制调用的时候模块名大写
        $m = ucfirst($m);
        $file = $this->ctxBase . '/Service/' . $m . '/Ctx.php';
        if (is_file($file)) {
            //这里不能用require_once防止屏蔽了多次加载同一个模块
            //模块接口文件必须是单例，防止错误的调用模块接口
            Loader\includeFile($file);
            //古怪的className，因为模块接口文件禁止被loadC
            $className = '\\' . $this->ctxNamespace . '\Service\\' . $m . '\\' . $m . 'Ctx';
            $this->$m = new $className();
            $this->$m->ctx = $this;
            $this->$m->initWithArgs($this->ctxNamespace, $m);
            return $this->$m;
        }
        throw new Exception("Module {$m} do not exist, failed to load file: " . $file);
    }

    /**
     * 调用方法
     *
     * @update 去掉ctx对mysql(loadDB)和redis(loadRedis)的接管，防止外部非ctx模块代码外操作数据
     */
    public function __call($method, $args)
    {
        // switch ($method) {
        //加载数据库
        // case 'loadDB':
        //     return call_user_func_array(array($this->Ctx, 'loadDB'), $args);
        //     break;
        // default:
        // }
        throw new Exception($method . '@ctx method do not exist.');
    }
}
