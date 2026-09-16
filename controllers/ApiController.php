<?

namespace Cart;


class ApiController extends BaseController
{

    public function __construct()
    {
        _response()->setType('api');
    }

    /**
     * provide requested data
     */

    public function get(Request $request)
    {
        $subject = $request->get('subject');
        switch (true) {
            case $subject == 'config':
                return  _cart()
                    ->config()
                    ->get();
            case $subject == 'core':
                return _cart()
                    ->installer()
                    ->confirm();
            case $subject == 'counter':
                $val      = fn () => rand(0, 30);
                $counters = [
                    'buy_product_click' => $val(),
                    'order_created'     => $val(),
                    'google_referrer'   => $val(),
                    'customers_number'  => $val(),
                    'mobile_device'     => $val()
                ];
                $config_counters = _cart()->config()->get('counters');
                return array_filter($counters, fn ($val, $key) => in_array($key, $config_counters), 1);
        }
    }


    /**
     * 
     * update subject data 
     */
    public function update(Request $request)
    {
        $subject = $request->get('subject');
        switch (true) {
            case $subject == 'config':
                $update = $this->apiRequest($subject);
                $config = _cart()
                    ->resource()
                    ->store($subject, $update)
                    ->config()
                    ->update()
                    ->save()
                    ->get();
                return $config;

            case $subject == 'product':
                $data = $this->apiRequest($subject);
                return ShopModel::table($subject)
                    ->clearTable()
                    ->seedTable($data);

            case $subject == 'trade':
                $config   = _cart()->config()->get('all');
                $currency = $config['currency']['title'];
                if ($currency == 'EUR') return 'success';
                $update    = $this->apiRequest('rate')['rates'];
                $config['currency']['rate'] = $update[$currency];
                return _cart()
                    ->resource()
                    ->store('config', $config)
                    ->config()
                    ->update()
                    ->save()
                    ->get('status');
        }
    }

    /**
     * 
     */
    public function check(Request $request)
    {
        $subject = $request->get('subject');
        // switch (true) {
        //     case $subject === 'order':
        //         $model = new ShopOrder();

        // }
        return '';
    }

    /**
     * store installation data
     * call installer 'up'
     */
    public function install()
    {
        if (_cart()->installed()) return ['status' => 'success'];
        $config = $this->apiRequest('config');
        $config = _cart()
            ->resource()
            ->store('config', $config)
            ->config()
            ->update()
            ->save()
            ->get();
        if ($config['status'] === 'success') {
            $product_list = $this->apiRequest('product');
            return _cart()
                ->resource()
                ->store('install', ['product'  => $product_list])
                ->installer()
                ->up();
        }
        return _cart()
            ->config()
            ->get();
    }

    /**
     * call installer 'down'
     */
    public function uninstall()
    {
        if (!_cart()->installed()) return ['status' => 'success'];
        return _cart()
            ->installer()
            ->down();
    }

    /**
     * 
     */
    public function devtest(Request $request)
    {
        $data = [
            'language'    => '',
            'time'        => '',
            'total'       => '0.00',
            'subtotal'    => '0.00',
            'insurance'   => '0.00',
            'currency'    => '',
            'products'    => '',
            'billing'     => '',
            'shipping'    => '',
            'browser'     => '',
            'http'        => '',
            'payment'     => '',
            'comment'     => '',
            'shop_domain' => '',
            'status'      => '',
        ];
        $resp = $this->apiPostRequest('order', $data);
        $resp = json_decode($resp);
        return $resp;
    }
}
