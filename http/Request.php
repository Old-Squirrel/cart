<?

namespace Cart;

class Request
{

    private static Request $instance;

    private array $data;

    private function __construct(array $params = [])
    {
        if (!empty($params)) {
            foreach ($params as $key => $param) {
                $this->$key = $param;
            }
        }
        $default     = ['uri' => HttpRequestHandler::getUri()];
        $this->data  = empty($params) ? $default : $default + $params;
    }

    /**
     * @return Request $request
     */
    public static function getInstance(array $params = []): Request
    {
        if (empty(self::$instance)) self::$instance = new Request($params);
        return self::$instance;
    }


    /**
     * add request params
     */
    public function push(string $param, $value): Request
    {
        $this->data[$param] = $value;
        return $this;
    }

    /**
     * get request data
     */
    public function get(string $param = '')
    {
        $data = $this->data;
        switch (true) {
            case array_key_exists($param, $data):
                return $data[$param];
            case isset($this->$param):
                return $this->$param;
            default:
                return $this->data;
        }
    }

    /**
     * json representation of data
     * empty string if no data
     */
    public function json(string $param): string
    {
        $data = $this->get($param);
        return is_array($data) ? json_encode($data) : '';
    }

    /**
     * get specific header
     */
    public function headers(string $header_title): string
    {
        return HttpRequestHandler::getHeaders($header_title);
    }
}
