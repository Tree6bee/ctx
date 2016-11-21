<?php

namespace Tree6bee\Ctx\Rpc\Http;

/**
 * rpc http客户端协议约定
 */
interface ClientContract
{
    public function request($method = 'get', $url, $body = array(), $headers = array());
}