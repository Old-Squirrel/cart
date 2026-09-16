<?

namespace Cart;

class BaseController
{

    /**
     * handle illegal request
     */
    public function badRequest()
    {
        http_response_code(404);
        exit;
    }

    /**
     *get http client instance
     */
    public function httpClient(): HttpClientAdapter
    {
        $client = new HttpClientAdapter;
        return $client;
    }

    /**
     * perform remote api  GET request
     */
    protected function apiRequest(string $route_name, bool $data_only = true)
    {
        $response = $this->httpClient()
            ->httpGet($this->getApiRoutes($route_name), [
             'timeout' => 120,
             'headers' => array('X-Api-Key' => _cart()->resource()->keys('api_key'))
            ]);
        $response = $response->getResponseBody();
        $response = json_decode($response, true);
       
        return $response['data'];
    }

    /**
     * perform remote api POST request
     */
    protected function apiPostRequest($route_name, array $body)
    {
        $request_headers = ['X-Api-Key' => _cart()->resource()->keys('api_key'), 'Content-Type' => 'application/json'];
        return $this->httpClient()
            ->httpPost($this->getApiRoutes($route_name), $request_headers, json_encode($body))
            ->getResponseBody();
    }

    /**
     * get remote api URL
     */
    protected function getApiRoutes(string $name): string
    {
        $routes = _cart()->resource()->loader()->get('web', 'api_routes');
        return $routes['domain'] . $routes[$name];
    }


}
