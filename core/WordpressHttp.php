<?

namespace Cart;


class WordpressHttp extends CoreHttpClient
{
    public function __construct()
    {
    }

    public function httpGet($url, $args = array())
    {
        return wp_remote_get($url, $args);
    }

    public function httpPost($url, $headers, $body)
    {
        return  wp_remote_post($url, array(
            'method'  => 'POST',
            'headers' => $headers,
            'body'    => $body
        ));
    }

    public function getResponseBody($response = [])
    {
        $body = wp_remote_retrieve_body($response);
        return $body;
    }


    public function getResponseHeaders($response = [])
    {
        $headers = wp_remote_retrieve_headers($response);
        return $headers;
    }
}
