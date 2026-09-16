<?

namespace Cart;

class OrderController extends BaseController
{

    use CurrencyTrait;

    public function create(Request $request)
    {
        $time = '' . time();
        $customer_cart = _cart()->customer();
        $data =  [
            'language'    => _cart()->config()->get('language'),
            'time'        => $time,
            'total'       => $this->trade($customer_cart->get('total'), true),
            'subtotal'    => $this->trade($customer_cart->get('sub_total'), true),
            'insurance'   => '0.00',
            'currency'    => $this->currency()->title,
            'products'    => $this->products(),
            'billing'     => $this->billing(),
            'shipping'    => $this->shipping(),
            'browser'     => $this->browser(),
            'http'        => $this->server(),
            'payment'     => $this->payment(),
            'comment'     => $this->getInput()->order_comment,
            'shop_domain' => _cart()->config()->get('domain'),
        ];
        $model    = new ShopOrder();
        $order_id = $this->save($model, $data);
        $data['order_id'] = $order_id;
        $api_response = $this->apiPostRequest('order', $data);
        $this->handleApiResponse($data, json_decode($api_response), $model);
    }


    /**
     * save the new order in shop database
     */
    private function save(ShopOrder $model, array $data): int
    {
        $status     =  md5('sent' . _cart()->customer()->get('session_id'));
        $api_status = 'sent';
        $order = [
            'total'      => $data['total'],
            'subtotal'   => $data['subtotal'],
            'insurance'  => '0.00',
            'currency'   => $this->currency()->title,
            'products'   => json_encode($data['products']),
            'shipping'   => json_encode($data['shipping']),
            'billing'    => json_encode($data['billing']),
            'browser'    => json_encode($data['browser']),
            'http'       => json_encode($data['http']),
            'payment'    => $this->payment(),
            'status'     => $status,
            'api_status' => $api_status,
        ];
        $order_id = $model->create($order);
        return $order_id;
    }

    /**
     * 
     */
    private function products()
    {
        $products  = _cart()->customer(true)->products;
        $prod_list = [];
        foreach ($products as $product) {
            $price = _helper()->trade($product->price, true);
            $prod_list[]  = [
                'id'       => $product->api_id,
                'price'    => $price,
                'qty'      => $product->cart_amount,
                'discount' => '0.00',
                'title'    => $product->title,
            ];
        }
        return $prod_list;
    }

   
    /**
     * get customer input data
     */
    private function getInput() : object
    {
        $input = _request()->get('order');
        return (object)$input;
    }


    /**
     * handle the order according to the api response
     */
    private function handleApiResponse(array $data, array $api_response): void
    {
        $actions = [
            'code_200' => fn () => $this->order_received($data),
            'code_422' => fn () => $this->redirect_back($data, $api_response),
            'code_500' => fn () => $this->no_api_response($data)
        ];
        $call_handler = $actions[$this->defineHandler($api_response)];
        $call_handler();
    }

    /**
     * api response contains a validation error
     */
    private function redirect_back(array $data, array $api_response): void
    {
        $model = new ShopOrder;
        $model->update(['api_status' => 'failed', 'status' => 'failed'], ['id' => $data['order_id']]);
        $errors = $api_response['errors'];
        
    }

    /**
     * there is no api response or an unknown error occurred
     */
    private function no_api_response(array $data): void
    {
        $model = new ShopOrder;
        $model->update(['api_status' => 'not_sent'], ['id' => $data['order_id']]);
        _response()->wp_redirect('thank-you', 302);
    }

    /**
     * no errors defined
     * the order was successfully received
     */
    private function order_received(array $data): void
    {
        $model = new ShopOrder;
        $model->update(['api_status' => 'received'], ['id' => $data['order_id']]);
        _response()->wp_redirect('thank-you', 302);
    }

    /**
     * 
     */
    private function defineHandler(array $api_response): string
    {
        switch (true) {
            case empty($api_response) || $api_response['error_code'] == 500:
                return 'code_500';
            case $api_response['error_code'] == 422:
                return 'code_422';
            case $api_response['error_code'] == 200 || $api_response['error_code'] == 0:
                return 'code_200';
        }
        return 'code_500';
    }

     /**
     * 
     */
    private function billing(): array
    {
        $input = $this->getInput();
        return [
            'bill_email'   => $input->bill_email,
            'bill_phone'   => $input->bill_phone,
            'bill_fname'   => $input->bill_fname,
            'bill_lname'   => $input->bill_lname,
            'bill_city'    => $input->bill_city,
            'bill_zip'     => $input->bill_zip,
            'bill_state'   => $input->bill_city,
            'bill_address' => $input->bill_address,
            'bill_country' => $input->bill_country,
        ];
    }


    /**
     * 
     */
    private function browser()
    {
        $ua = _request()->headers('HTTP_USER_AGENT');
        $data = [
            'js_time_string'    => '',
            'log_sockip'        => '',
            'log_colordepth'    => '24',
            'log_browser_lang'  => 'es-ES',
            'log_browser_reso'  => '1920 x 1080',
            'log_machine_type'  => 'desktop',
            'log_useragent'     =>  $ua,
            'log_plugins'       => '',
            'log_fonts'         => '',
            'log_mtime'         => '',
            'log_os'            => '',
            'log_jsversion'     => '1.5',
            'log_tzinfo'        => ''
        ];
        return $data;
    }



    /**
     * 
     */
    private function shipping()
    {
        $input   = $this->getInput();
        $session = _cart()->customer(true);
        $method  = _first(array_filter($session->shipping_list, fn ($ship) => $ship->chosen == true));
        return [
            'shipp_fname'   => $input->bill_fname,
            'shipp_lname'   => $input->bill_lname,
            'shipp_city'    => $input->bill_city,
            'shipp_zip'     => $input->bill_zip,
            'shipp_state'   => $input->bill_city,
            'shipp_address' => $input->bill_address,
            'shipp_country' => $input->bill_country,
            'shipp_method'  => $method->id,
            'shipp_price'   => $method->cost
        ];
    }


    /**
     * 
     */

    private function server()
    {
        $referer     = _request()->headers('HTTP_REFERER');
        $customer_ip = _request()->headers('REMOTE_ADDR');
        return [
            'http_ip'               => $customer_ip,
            'http_referer'          => $referer,
            'http_x_forwarded_for'  => '',
            'http_x_forwarded'      => '',
            'http_forwarded'        => '',
            'http_proxy_agent'      => '',
            'http_via'              => '',
            'http_proxy_connection' => '',
        ];
    }

    /**
     * 
     */
    private function payment()
    {
        $payments       = _cart()->config()->get('payment');
        $input_payment  = $this->getInput()->payment;
        $payment_method = _first(array_filter($payments, fn ($method) => $method['id'] == $input_payment['id']));
        $params = [];
        if (!empty($payment_method['params'])) {
            foreach ($payment_method['params'] as $param_name) {
                $params[$param_name] = $input_payment[$param_name];
            }
        }
        return [
            'method' => $input_payment['id'],
            'params' => $params
        ];
    }
}
