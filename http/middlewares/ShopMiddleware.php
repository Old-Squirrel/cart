<?

namespace Cart;

class ShopMiddleware extends HttpRequestMiddleware
{

    public function handle(?HttpRequestMiddleware $next): ?HttpRequestMiddleware
    {

        if (!$this->sameOrigin()) {
            http_response_code(403);
            exit("");
        }

        return $next;
    }


}
