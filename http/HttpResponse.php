<?

namespace Cart;

class HttpResponse
{
    use SingletonTrait;

    
    private array $headers;
    private string $type;
    private $body;
   
    private function __construct()
    {
      
    }



    public function send(): void
    {

        $response_method = $this->type;

        $response = $this->$response_method($this->body);

        ob_start();

        array_map(fn ($header) => header($header), $response->headers);

        print_r($response->body);

        ob_end_flush();

        exit();
    }

    /**
     * 
     */
    public function render(string $sub_dir, array $data): string
    {
        $allowed = _cart()->config()->get('templates') + ['empty_cart' => 'includes/empty_cart_default.php'];
        if (empty($allowed[$sub_dir])) return '';
        $file    = $allowed[$sub_dir];
        $path    = $sub_dir === 'empty_cart' ? '/' . $file :  $sub_dir . '/' . $file;
        $pattern = "\h{4}";
        ob_start();
        _cart()
            ->resource()
            ->loader()
            ->includeView($path, $data);
        $content    = ob_get_clean();
        $this->body = preg_replace("~$pattern~", " ", $content);
        return $this->body;
    }


    /**
     * push header to response
     */

    public function header($header): HttpResponse
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * set response body
     */
    public function setBody($content): HttpResponse
    {
        $this->body = $content;

        return $this;
    }

    /**
     * set response type
     */
    public function setType(string $type): HttpResponse
    {
        if (method_exists($this, $type)) {
            $this->type = $type;
        }
        return $this;
    }

    /**
     * 
     */
    public function wp_redirect(string $uri, int $status_code = 303)
    {
        $url = Core::getShopData('site_url'). '/' . trim($uri, '/') ;
        header("Location: $url", true, $status_code);
        exit();
    }

    /**
     * prepare api response
     */
    private function api($content): HttpResponse
    {
        $content   = is_array($content) ? $content : [$content];
        $_response = fn (bool $response, array $data,  $er_code, $er_msg) => array(
            "response"   => $response,
            "data"       => $data,
            "error_code" => $er_code,
            "error_msg"  => $er_msg
        );
        $msg     = empty($content['failed']) ? '' : $content['failed'];
        $content = !array_key_exists('failed', $content) ?
            $_response(true, $content, 0, '') :
            $_response(false, [], 500, $msg);

        return $this->json($content);
    }

    /**
     * 
     */
    private function async(array $content) : HttpResponse
    {
        //Cross-Origin-Opener-Policy : 
        return $this->api($content);
    }

    /**
     * set header and encode body
     */
    private function json(array $content): HttpResponse
    {
        return $this->header('Content-Type:application/json')
            ->setBody(json_encode($content));
    }


}
