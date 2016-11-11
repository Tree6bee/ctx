<?php

namespace Tree6bee\Ctx\Basic;

use Tree6bee\Ctx\Exceptions\Exception;
use ReflectionClass;
use Tree6bee\Ctx\Rpc\Client;
use Tree6bee\Ctx\Rpc\Http\EasyCurl;

/**
 * 所有的业务模块基类
 *
 * @copyright sh7ning 2016.1
 * @author    sh7ning
 * @version   0.0.1
 */
abstract class Ctx
{
    /*--- part.1 框架核心 ---*/
    /**
     * 模块名
     * 辅助方法有setModName() 和 getModName()
     */
    private $modName = '';

    /**
     * 请勿调用，系统会执行一次
     */
    final public function setModName($modName)
    {
        if ($modName && is_string($modName) && empty($this->modName)) {
            $this->modName = $modName;
        }
    }

    final public function getModName()
    {
        return $this->modName;
    }

    /**
     * 是否已经实例化过，防止被再次调用
     */
    private $inited = false;

    /**
     * 模块初始化方法
     */
    public function init()
    {
        //只能被框架调用一次，不允许用户调用
        if ($this->inited) {
            throw new Exception('method deny,invoke:' . __METHOD__ . '@' . get_class($this));
        }
        $this->inited = true;
    }

    /**
     * 检查是否掉用过父类的init
     */
    final public function checkInited()
    {
        if (! $this->inited) {
            throw new Exception('u should invoke method: parent::init(),invoke:' . __METHOD__ . '@' . get_class($this));
        }
    }

    /**
     * 加载模块子类
     * 备注：这里不直接用 __get() 实例化模块内子类是因为方便加载多个实例化对象，方便子类不同对象复用(如多个profile)
     * 这里用 protected 关键字是为了防止外部模块调用：如 $ctx->模块->loadC()，这样外部模块只能调用模块的mod声明的方法
     * 所有的模块子类只能让mod模块文件去进行调用
     */
    final protected function loadC()
    {
        $args = func_get_args();
        $class = array_shift($args);
        if (! empty($this->modName)) {
            $class = ucfirst($class);
            $className = '\Ctx\Service\\' . $this->modName . '\\' . $class;
            $classReflection = new ReflectionClass($className);
            //classReflection 拥有的方法: isAbstract | isInterface | isSubclassOf | hasMethod | getMethod('方法名')->isPublic()
            //getConstructor()->getParameters() 获取构造函数的参数(方便实现依赖注入)
            $subObj = $classReflection->newInstanceArgs($args);
            // if ($subObj instanceof self)  也可以, if ($subObj instanceof static) 不行
            if ($classReflection->isSubclassOf(__CLASS__)) {
                $subObj->ctx = $this->ctx;
                $subObj->setModName($this->modName);
                $subObj->init();
                $subObj->checkInited();
            }
            return $subObj;
        } else {    //还未完成初始化(在构造函数__construct中调用loadC)是不允许调用父类的loadC
            throw new Exception('can not loadC until construct obj, invoke:' . __METHOD__ . '@' . get_class($this));
        }
    }

    /**
     * 远程Rpc调度
     */
    public function __call($method, $args)
    {
        return $this->invokeRpc($method, $args);
    }

    /**
     * rpc配置
     */
    protected $rpc = array();

    /**
     * 远程Rpc调度实际逻辑，方便子类进行更灵活的操作如:显式调用,异步调用等
     */
    protected function invokeRpc($method, $args)
    {
        if (empty($this->rpc['method']) || ! in_array($method, $this->rpc['method']) || empty($this->rpc['host'])) {
            throw new Exception('非法调用:' .$method . '@' . get_class($this));
        }

        $rpc = new Client($this->rpc['host'], $this->modName, (new EasyCurl));

        return $rpc($method, $args);
    }
}
