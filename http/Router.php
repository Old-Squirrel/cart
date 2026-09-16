<?

namespace Cart;

class Router
{
    private array $routes;


    public function __construct(CartResource $resource)
    {
        $this->routes  = $resource->get('routes');
    }


    /**
     * app entry point
     */
    public function run(): void
    {
        $class_name = fn ($class) => _helper()->class_name('', 'middleware', $class);
        foreach ($this->routes as $uri_pattern => $matched_route) {
            $middlewares = $this->init($uri_pattern);
            $route       = array_pop($middlewares);
            if (preg_match("~$route~", HttpRequestHandler::getUri())) {
                $request_handler = new HttpRequestHandler($matched_route);
                $middlewares = array_map(fn ($middleware) => $middleware = $class_name($middleware), $middlewares);
                $invoke      = function ($next) use (&$middlewares) {
                    $init    = array_shift($middlewares);
                    $init    = new $init();
                    return $init->handle($next);
                };
                $middlewares     =  array_map(fn (&$middleware) => $invoke(new $middleware), $middlewares);
                $request_handler->handle($matched_route);
                break;
            }
        }
        gc_collect_cycles();
        exit;
    }

    /**
     *  replace route {vars} with the appropriate values
     *  return splitted route  
     */
    private function init(string $pattern): array
    {
        $varlist = [
            '{prod_slug}'  => 'prod-[0-9]{1,5}-' . date('d'),
            '{get_list}'   => '(core|config|counter)',
            '{upd_list}'   => '(product|config|trade)',
            '{ship_slug}'  => 'ship-[0-9]{1,5}-' . date('d'),
        ];
        foreach ($varlist as $needle => $replace) {
            if (_helper()->str_contains($pattern, $needle)) {
                $pattern = str_replace($needle, $replace, $pattern);
                return explode('::', $pattern);
            }
        }
        return explode('::', $pattern);
    }
}
