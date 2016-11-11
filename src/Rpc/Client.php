<?php

namespace Tree6bee\Ctx\Rpc;

use Tree6bee\Ctx\Rpc\Http\ClientContract as HttpClientContract;
use Tree6bee\Ctx\Exceptions\Exception;

/**
 * Rpc Client
 *
 * @copyright sh7ning 2016.1
 * @author    sh7ning
 * @version   0.0.1
 * @example
 */
class Client
{
    private $agent = 'CtxRpc 1.0';

    public function __construct($host, $modName, HttpClientContract $httpClient = null)
    {
        $this->host = $host;
        $this->modName = $modName;
        $this->httpClient = $httpClient;
    }

    public function __invoke($method, $args)
    {
        $body = $this->buildRpcReq($method, $args);
        $response = $this->httpClient->request('post', $this->host, $body, array(
            'User-Agent: ' . $this->agent,
        ));

        return $this->parseRpcData($response, $body);
    }

    private function buildRpcReq($method, $args)
    {
        return array(
            'class'     => $this->modName,
            'method'    => $method,
            'args'      => $args,
        );
    }

    /**
     * 解析rpc返回数据
     * @param $response 返回数据
     * @param $request 请求数据 主要用于rpc发生错误的时候记录日志
     */
    private function parseRpcData($response, $request = array())
    {
        //$curl_info 包含信息包括:url, content_type, http_code, header_size, request_size, total_time
        // echo $response['total_time'];
        $log = json_encode(array(
            'host'      => $this->host,
            'request'   => $request,
            'response'  => $response,
        ));

        if (200 != $response['http_code']) {
            throw new Exception(
                'rpc请求失败, http_code: ' . $response['http_code'] . " error, info: " . $log
            );
        }

        $data = $response['body'];
        $data = json_decode($data, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($data) || ! isset($data['ret'])) {
            throw new Exception('rpc返回值非法:' . $log);
        }

        if (0 === $data['ret']) {
            return $data['data'];
        } else {
            throw new Exception($data['msg']);
        }
    }
}
