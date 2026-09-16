<?

namespace Cart;

class OrderMiddleware extends HttpRequestMiddleware
{

    public function handle(?HttpRequestMiddleware $next): ?HttpRequestMiddleware
    {

        if (!$this->sameOrigin()) {
            http_response_code(403);
            exit;
        }
        if (HttpRequestHandler::getMethod() !== 'POST') {
            header("Allow:POST");
            http_response_code(405);
            exit;
        }

        $form = $_POST;
        _request()->push('order', $form);
        return $next;
    }
}
