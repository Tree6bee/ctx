<?php

namespace Tree6bee\Ctx\Rpc\Http;

use Tree6bee\Ctx\Exceptions\RpcException;
use Tree6bee\Support\Ctx\Http\Client as HttpClient;
use Tree6bee\Ctx\Exceptions\Exception;

class Client extends HttpClient implements ClientContract
{
    /**
     * 发起rpc请求并解析rpc返回的响应数据
     */
    public function post($url, $body = array(), $headers = array(), $options = array())
    {
        $this->request('POST', $url, $body, $headers, $options);

        //$curl_info 包含信息包括:url, content_type, http_code, header_size, request_size, total_time
        // echo $response['total_time'];
        $log = json_encode(array(
            'host'      => $url,
            'request'   => $body,
            'response'  => $this->getBody(),
        ));

        if (200 != $this->getHttpCode()) {
            throw new Exception(
                'rpc请求失败, http_code: ' . $this->getHttpCode() . " error, info: " . $log
            );
        }

        $data = json_decode($this->getBody(), true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($data) || ! isset($data['code'])) {
            throw new Exception('rpc返回值非法:' . $log);
        }

        if (0 === $data['code']) {
            return $data['data'];
        } else {
            throw new RpcException($data['error'], $data['trace']);
        }
    }
}
