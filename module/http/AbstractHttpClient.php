<?php

namespace Cart;

abstract class AbstractHttpClient
{

    abstract function httpGet(string $url, $args = []);

    abstract function httpPost(string $url, array $headers, array $body);

    abstract function getResponseBody($response = []);

    abstract function getResponseHeaders($response = []);
}
