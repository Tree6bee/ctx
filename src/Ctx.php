<?php

namespace Tree6bee\Ctx;

use Tree6bee\Ctx\Exceptions\Exception;

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
     * @return Ctx
     */
    public static function getInstance()
    {
        if (empty(static::$ctxInstance)) {
            static::$ctxInstance = new static();
        }

        return static::$ctxInstance;
    }

    /**
     * ctx命名空间
     */
    protected $ctxNamespace;

    /**
     * 私有构造函数，防止外界实例化对象
     */
    protected function __construct()
    {
        //去掉反射提高性能
        // $thisReflection = new ReflectionClass($this);
        // $this->ctxNamespace = $thisReflection->getNamespaceName();
    }

    /**
     * 自动单例获取ctx服务框架的模块
     * 模块接口文件必须是单例，防止错误的调用模块接口
     */
    public function __get($m)
    {
        //不想增加对首字母大小写的判断
        //强制调用的时候模块名大写
        $m = ucfirst($m);
        if (property_exists($this, $m)) {
            throw new Exception("Module name {$m} should begin with a capital letter.");
        }

        $className = '\\' . $this->ctxNamespace . '\Service\\' . $m . '\\Ctx';
        $this->$m = new $className();
        $this->$m->ctx = $this;
        $this->$m->initWithArgs($this->ctxNamespace, $m);
        return $this->$m;
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
