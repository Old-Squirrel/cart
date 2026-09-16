<?

namespace Cart;

class GetMiddleware extends HttpRequestMiddleware
{

    public function handle(?HttpRequestMiddleware $next): HttpRequestMiddleware
    {
        if (HttpRequestHandler::getMethod() !== 'GET') {
            header("Allow:GET");
            http_response_code(405);
            exit;
        }


        return $next;
    }
}
