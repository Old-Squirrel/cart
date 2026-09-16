<?

namespace Cart;

class ConfigOptions
{
 
    /**
     * config main data list
     */
    protected static function main(): array
    {
        $domain = $_SERVER['SERVER_NAME'];
        return [
            'core'    => 'wordpress',
            'domain'  => $domain,
            'tables'  =>  array(
                'shop_configs_table',
                'shop_products_table',
                'shop_orders_table',
                'shop_counters_table'
            ),
            'counters'  => array(
                'buy_product_click',
                'order_created',
                'google_referrer',
                'customers_number',
                'mobile_device'
            ),
            'price_coef' => '1.0',

            'templates'  => [
                "cart"          => "default.php",
                "checkout"      => "default.php",
                "thank_you"     => "default.php",
                "product_table" => "default.php",
            ],

            'behavior'  => [
                'cart_redirect'     => 'to_checkout',
                'product_image'     => 'show',
                'top_sales'         => 'hide',
                'shipping_billing'  => 'combine'
            ],

            'countries'  => array(),

            'shipping'   => array(),

            'payment'    => array(
                [
                    'id'     => 1,
                    'title'  => 'Credit Card',
                    'params' => [
                        "card_name",
                        "card_number",
                        "card_type",
                        "card_expiry",
                        "card_cvv",
                    ]
                ]
            ),

            'language'   => '',

            'storage'    =>  array(),

            'currency'   => 'EUR'
        ];
    }

    /**
     * provides list of config requirements, callbacks titles
     */
    protected static function requirements(string $property = '')
    {
        $list = array(
            'core'          => array('is_set'),
            'domain'        => array('is_set'),
            'language'      => array('is_set'),
            'tables'        => array('is_set'),
            'templates'     => array('is_set'),
            'price_coef'    => array('nullable'),
            'storage'       => array('is_set'),
            'counters'      => array('nullable'),
            'behavior'      => array('is_set'),
            'currency'      => array('is_set'),
            'shipping'      => array('is_set'),
            'payment'       => array('is_set'),
            'countries'     => array('is_set')
        );
        if (empty($property)) return $list;
        return array_key_exists($property, $list) ? $list[$property] : $list;
    }

    /**
     * provides config options list
     */

    protected static function options(string $property = '')
    {
        $main      = self::main();
        $loader    = _cart()->resource()->loader();
        $tables    = array_map(fn ($file_name) => str_replace('.php', '', $file_name), $loader->getContent('tables', true));
        $templates = array_filter($loader->getContent('templates', true), fn ($dir_name) => $dir_name != 'includes', ARRAY_FILTER_USE_KEY);
        $behavior  = array(
            'cart_redirect'     => array('to_checkout', 'to_cart', 'stay_current'),
            'product_image'     => array('show', 'hide'),
            'top_sales'         => array('show', 'hide'),
            'shipping_billing'  => array('combine', 'split')
        );
        $list   =  array(
            'core'            => Core::getRegister('core'),
            'domain'          => $main['domain'],
            'price_coef'      => array('min' => '0.5', 'max' => '2', 'step' => '0.05'),
            'tables'          => $tables,
            'templates'       => $templates,
            'behavior'        => $behavior,
            'counters'        => $main['counters'],
            'currency'        => null,
            'shipping'        => null,
            'payment'         => null,
            'language'        => null,
            'storage'         => null,
            'countries'       => null,

        );
        if (empty($property)) return $list;
        return array_key_exists($property, $list) ? $list[$property] : $list;
    }
}
