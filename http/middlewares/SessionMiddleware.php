<?

namespace Cart;

class SessionMiddleware extends HttpRequestMiddleware
{


    public function handle(?HttpRequestMiddleware $next): ?HttpRequestMiddleware
    {
        //$origin = 'https://' . Core::getShopData('domain');
        
        if (!$this->sameOrigin()) {
            http_response_code(403);
            exit;
        }
        if (!_helper()->session_started()) {
          _response()->wp_redirect('shop-cart');
            exit();
        }
      
       

        return $next;
    }
}
