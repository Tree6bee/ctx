<?php

namespace Tree6bee\Ctx\Rpc\Http;

/**
 * rpc http客户端协议约定
 */
interface ClientContract
{
    public function request($method, $url, $body = array(), $headers = array(), $options = array());

    public function getHttpCode();

    public function getHeader();

    public function getBody();

    public function getCurlInfo();

    public function getTotalTime();
}