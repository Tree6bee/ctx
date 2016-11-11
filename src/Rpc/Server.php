<?php

namespace Tree6bee\Ctx\Basic\Rpc;

/**
 * Rpc Server
 *
 * @copyright sh7ning 2016.1
 * @author    sh7ning
 * @version   0.0.1
 * @example
 */
class Server
{
    public function run($ctx)
    {
        $agent = 'CtxRpc 1.0';
        if (isset($_SERVER['HTTP_USER_AGENT']) &&
            $_SERVER['HTTP_USER_AGENT'] == $agent &&
            isset($_POST['class'], $_POST['method'])
        ) {
            $class = $_POST['class'];
            $class = 'Ctx\Rpc\\' . $class;
            $method = $_POST['method'];
            $args = isset($_POST['args']) ? $_POST['args'] : array();
            $obj = new $class;
            $obj->ctx = $ctx;
            header('Content-Type: application/json; charset=utf-8');
            try {
                $data = call_user_func_array(array($obj, $method), $args);
                $ret = array(
                    'ret'   => 0,   //返回码
                    'data'  => $data, //返回数据体
                    'msg'   => '',  //返回消息
                    // 'ec' => 0, //错误代码 0：正确，-1：服务器错误，1：请求错误
                    // 'em' => array(),    //错误的消息
                );
            } catch (\Exception $e) {
                $ret = array(
                    'ret'   => -1,   //返回码
                    'data'  => array(), //返回数据体
                    'msg'   => $e->getTraceAsString(),  //返回消息
                );
            }
        } else {
                $ret = array(
                    'ret'   => -2,   //返回码
                    'data'  => array(), //返回数据体
                    'msg'   => '非法的请求',  //返回消息
                );
        }
        echo json_encode($ret);
    }
}
