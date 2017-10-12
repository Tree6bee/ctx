<?php

namespace Tree6bee\Ctx\Rpc;

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
            $method = $_POST['method'];
            $args = isset($_POST['args']) ? $_POST['args'] : array();

            header('Content-Type: application/json; charset=utf-8');
            try {
                $data = call_user_func_array(array($ctx->$class, $method), $args);
                $ret = array(
                    'code'      => 0,   //返回码
                    'data'      => $data, //返回数据体
                    'error'     => '',  //返回消息
                    'trace'     => '',
                    // 'ec' => 0, //错误代码 0：正确，-1：服务器错误，1：请求错误
                    // 'em' => array(),    //错误的消息
                );
            } catch (\Exception $e) {
                $ret = array(
                    'code'      => -1,   //返回码
                    'data'      => array(), //返回数据体
                    'error'     => $e->getMessage(),  //返回消息
                    'trace'     => $e->getTraceAsString(),
                );
            }
        } else {
                $ret = array(
                    'code'      => -2,   //返回码
                    'data'      => array(), //返回数据体
                    'error'     => '非法的请求',  //返回消息
                    'trace'     => '',
                );
        }
        echo json_encode($ret);
    }
}
