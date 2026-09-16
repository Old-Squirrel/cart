<?php

namespace Cart;


class HttpRequestHandler
{
    private array $handler;
    private $request;

    public function __construct(string $uri)
    {
        $handler        = self::parse($uri);
        $this->handler  = [$handler->controller, $handler->method];
        $this->request  = $handler->request;
    }


    public function handle(): void
    {

        $response = call_user_func($this->handler, $this->request);

        _response()
            ->setBody($response)
            ->send();
    }



    /**
     * return trimed uri
     */
    public static function getUri(): string
    {
        [$schema, $host, $port, $path, $quuery, $fragment] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_SCHEME | PHP_URL_HOST | PHP_URL_PORT | PHP_URL_PATH | PHP_URL_QUERY);

        if (!empty($_SERVER['REQUEST_URI']))  return rtrim($_SERVER['REQUEST_URI'], '/');
    }


    /**
     * return current http method
     */
    public static function getMethod($lower_case = ''): string
    {
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            $method = _helper()->str_clear($_SERVER['REQUEST_METHOD']);
            return !empty($lower_case)  ? strtolower($method) : $method;
        };
    }


    /**
     * get shop domain
     */
    public static function domain(bool $with_protocol = true): string
    {
        $protocol = 'https://';
        return  $with_protocol ? $protocol . $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_NAME'];
    }



    /**
     * return page url
     */
    public static function currentPageUrl(): string
    {
        return self::domain() . self::getUri();
    }


    /**
     * check for the header
     */
    public static function hasHeader(string $title): bool
    {
        return !empty($_SERVER) && array_key_exists($title, $_SERVER);
    }

    /**
     * return the header value
     */
    public static function getHeaders(string $title): string
    {
        if (self::hasHeader($title)) return filter_input(INPUT_SERVER, $title, FILTER_SANITIZE_SPECIAL_CHARS);
        return '';
    }


    /**
     * parse request uri, define $controller, $controller::method, $request
     */
    private static function parse(string $matched_route)
    {
        $data = [];
        $request_uri = self::getUri();
        $list        = explode('/', $matched_route);
        $controller  = array_shift($list);
        $controller  = _helper()->class_name('', 'controller', $controller);
        $method      = array_shift($list);
        if (!empty($list)) {
            $uri    = explode('/', $request_uri);
            $params = array_reverse($list);
            foreach ($params as $param_name) {
                $data[$param_name] = array_pop($uri);
            }
        }
        $request  = Request::getInstance($data);
        $instance = new $controller();
        return (object)[
            'controller' => $instance,
            'method'     => $method,
            'request'    => $request
        ];
    }
}
