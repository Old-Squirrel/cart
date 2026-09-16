<?
namespace Cart;

class ApiMiddleware extends HttpRequestMiddleware
{

    public function handle(?HttpRequestMiddleware $next): ?HttpRequestMiddleware
    {


        $key = _cart()->resource()->keys('server_key') ;

        if (
            HttpRequestHandler::getMethod() !== 'GET' ||
            !preg_match("~$key~", HttpRequestHandler::getHeaders('HTTP_X_API_KEY'))
        ) {
            http_response_code(403);
            exit();
        }
   
        _response()->setType('api');
        return $next;
    }
}
