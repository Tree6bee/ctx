<?php

namespace Tree6bee\Ctx\Rpc;

use Tree6bee\Ctx\Rpc\Http\ClientContract as HttpClientContract;
use Tree6bee\Ctx\Exceptions\Exception;
use Tree6bee\Ctx\Rpc\Http\EasyCurl;

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

    protected static $httpClient = null;

    public function __construct($host, $modName)
    {
        $this->host = $host;
        $this->modName = $modName;
    }

    /**
     * 设置 http client 增加库得可测试性
     * @param HttpClientContract $httpClient
     */
    public static function setHttpClient(HttpClientContract $httpClient)
    {
        self::$httpClient = $httpClient;
    }

    protected static function getHttpClient()
    {
        if (is_null(self::$httpClient)) {
            return new EasyCurl;
        }

        return self::$httpClient;
    }

    public function __invoke($method, $args)
    {
        $body = $this->buildRpcReq($method, $args);
        $httpClient = self::getHttpClient();
        $response = $httpClient->request('post', $this->host, $body, array(
            'User-Agent: ' . $this->agent,
        ));

        return $this->parseRpcData($response, $body);
    }

    protected function buildRpcReq($method, $args)
    {
        return array(
            'class'     => $this->modName,
            'method'    => $method,
            'args'      => $args,
        );
    }

    /**
     * 解析rpc返回数据
     *
     * @param string $response 返回数据
     * @param array $request 请求数据 主要用于rpc发生错误的时候记录日志
     *
     * @return mixed
     * @throws Exception
     */
    protected function parseRpcData($response, $request = array())
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
