<?

namespace Cart;

class HttpClientAdapter extends AbstractHttpClient implements CoreAdapter
{
    use AdapterTrait;

    private $alias = 'http';
    protected $response;
    private static $client;


    public function __construct()
    {
        self::$client = Core::getInstance($this);
    }

    public function httpGet(string $url, $args = [])
    {
        $this->response = self::http()->httpGet($url, $args);
        return $this;
    }


    public function httpPost($url, $headers, $body)
    {

        $this->response = self::http()->httpPost($url, $headers, $body);
        return $this;
    }

    public function getResponseBody($response = [])
    {
        $response = self::http()->getResponseBody($this->response);
        return $response;
    }


    public function getResponseHeaders($response = [])
    {
        $response = self::http()->getResponseHeaders($this->response);
        return $response;
    }


    protected static function http()
    {
        return self::$client;
    }
}
