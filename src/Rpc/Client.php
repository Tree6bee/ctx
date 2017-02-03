<?php

namespace Tree6bee\Ctx\Rpc;

use Tree6bee\Ctx\Rpc\Http\ClientContract as HttpClientContract;
use Tree6bee\Ctx\Rpc\Http\Client as HttpClient;

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
            return new HttpClient;
        }

        return self::$httpClient;
    }

    public function __invoke($method, $args)
    {
        $body = $this->buildRpcReq($method, $args);
        $httpClient = self::getHttpClient();
        return $httpClient->post($this->host, $body, array(
            'User-Agent: ' . $this->agent,
        ));
    }

    protected function buildRpcReq($method, $args)
    {
        return array(
            'class'     => $this->modName,
            'method'    => $method,
            'args'      => $args,
        );
    }
}
