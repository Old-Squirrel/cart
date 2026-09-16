<?

namespace Cart;

final class CustomerCart
{
    use CurrencyTrait;
    
    private static $instance;
    private static $items;
    private static $products;
    private static $amount;
    private static $total;
    private static $sub_total;
    private static $shipping_list;
    private static $chosen_shipping;
    private static $session_id;
    

    private function __construct()
    {
        self::init($this);
     
    }

    /**
     * get customer cart service instance 
     */
    public static function start(): CustomerCart
    {
        if (empty(self::$session_id)) self::$session_id = Session::session_id();
        if (!Session::status()) Session::start();
        if (empty(self::$instance)) self::$instance = new CustomerCart();
        return self::$instance;
    }

    /**
     * get customer cart data
     */
    public function get(string $property)
    {
        $list  = get_class_vars(self::class);
        unset($list['instance']);
        unset($list['session_id']);
        switch (true) {
            case $property === 'all':
                return $list;
            case $property === 'id':
                return self::$session_id;
            case array_key_exists($property, $list):
                return $list[$property];
        }
    }

    /**
     * update session items
     */
    public function update_items(string $product_slug, string $action = 'plus')
    {
        $call_method = $action . '_item';
        Session::$call_method($product_slug);
        if ($action == 'minus' || $action == 'remove') {
            self::init($this);
        };
    }

    /**
     * get allowed payment methods
     */
    public function payment(): array
    {
        $config_data = _cart()->config()->get('payment');
        $inclusions = [1 => 'payment/credit_card.php', 2 => ''];
        $checked    = [1 => 'checked', 2 => false];

        $with  = fn ($method) => [
            'slug'      => 'pay_' . $method['id'],
            'inclusion' => $inclusions[$method['id']],
            'checked'   => $checked[$method['id']],
        ];

        $data = array_map(fn (array $method): object => (object)array_merge($method, $with($method)), $config_data);
        return $data;
    }

    /**
     * 
     */
    public static function destroy()
    {
        self::empty();
        self::$instance = false;
        Session::destroy();
      
    }
    /**
     * update session chosen_shipping value
     * define cart chosen_shipping, total, shipping_list
     */
    public function update_shipping(string $shipping_slug): void
    {
        Session::set('chosen_shipping', $shipping_slug);
        self::set_shipping($this);
    }


    /**
     * determine customer's cart data
     */
    private static function init(CustomerCart $cart)
    {
        $items = Session::get('items');
        
        $items = !is_array($items) ? [] : $items;
        switch (true) {
            case empty($items):
                self::empty();
                break;
            case !empty($items):
                self::set_products($items);
                self::set_shipping($cart);
                break;
        }
    }

    /**
     * define customer selected delivery method if possible;
     * set cheapest method if not; 
     * define shipping methods list;
     */
    private static function set_shipping(CustomerCart $cart): void
    {
        $chosen_shipping = Session::get('chosen_shipping');
        $allowed_methods = self::allowed_methods();
        $method = [];
        switch (true) {
            case empty($chosen_shipping):
                $method = _first($allowed_methods);
                break;
            case !empty($chosen_shipping):
                $method = _first(array_filter($allowed_methods, fn ($method) => $method['slug'] === $chosen_shipping));
                $method = empty($method) ? _first($allowed_methods) : $method;
                break;
        }

        self::$chosen_shipping = $method['slug'];
        self::$total = self::$sub_total + $method['cost'];
        $chosen    = fn ($ship): bool   => self::$chosen_shipping === $ship['slug'];
        $checked   = fn ($ship): string => $chosen($ship) ? 'checked' : '';
        $with = fn ($ship): array => [
            'price'     => $cart->with_currency($ship['cost']),
            'chosen'    => $chosen($ship),
            'checked'   => $checked($ship)
        ];
        self::$shipping_list = array_map(fn (array $ship) => (object)array_merge($ship, $with($ship)), $allowed_methods);
        Session::set('chosen_shipping', self::$chosen_shipping);
    }

    /**
     * list of available delivery methods according to the order amount
     */
    private static function allowed_methods(): array
    {
        $shipping_list = _cart()->config()->get('shipping');
        $condition = empty(self::$sub_total) ? 0 : self::$sub_total;
        $list = array_filter($shipping_list, fn ($ship) => $ship['usage_condition'] <= $condition);
        $list = array_map(fn ($ship) => $ship =  $ship  + ['slug' => _helper()->make_slug($ship['id'], 'ship')], $list);
        return $list;
    }

    /**
     * set products, items, sub_total, amount 
     */
    private static function set_products(array $items)
    {

        $products = [];
        $sub_total = [];
        $total_amount = 0;
        $model = new ShopProduct();

        foreach ($items as $slug => $amount) {
            $id = _helper()->parse_slug($slug);
            $product = $model->select('id, api_id, price, title, image_path', ['key' => 'id', 'value' => $id])[0];
            $product['cart_amount'] = $amount;
            $product['price']       = $model->pretty_price($product);
            $product['sub_total']   = $model->pretty_price($product) * $amount;
            $product['slug']        = $slug;
            $sub_total[]            = $product['sub_total'];
            $products[]             = (object)$product;
            $total_amount           += $amount;
        }
        self::$products  = $products;
        self::$items     = $items;
        self::$sub_total = array_sum($sub_total);
        self::$amount    = $total_amount;
    }

    /**
     * set default values
     */
    private static function empty(): void
    {
        self::$session_id    = '';
        self::$products      = [];
        self::$items         = [];
        self::$sub_total     = 0;
        self::$amount        = 0;
        self::$shipping_list = [];
        self::$total         = 0;
        self::$chosen_shipping = '';
    }
}
