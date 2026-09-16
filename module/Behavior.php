<?

namespace Cart;

class Behavior
{


    /**
     * 
     */
    public static function shipping_billing(string $config_value): bool
    {
        $list = [
            'combine' => true,
            'split'   => false
        ];
        if (array_key_exists($config_value, $list)) return $list[$config_value];
        return false;
    }


    /**
     * 
     */
    public static function top_sales(string $config_value): array
    {
        $class_name = 'bestseller bestseller-';
        $shop_type  = Core::getShopData('theme_name');
        $color_map  = [
            'woodmart'       => 'woodmart',
            'generatepress'  => 'blue',
            'be_pillen'      => 'violet',
            'it_farm'        => 'cyan'
        ];
        $list = [
            'show' => [
                'bool'   => true,
                'value'  => $class_name . $color_map[$shop_type]
            ],
            'hide' => [
                'bool'   => false,
                'value'  => ''
            ]
        ];
        if (array_key_exists($config_value, $list)) return $list[$config_value];
        return [false];
    }


    /**
     * 
     */
    public static function product_image(string $config_value): bool
    {
        $list = [
            'show' => true,
            'hide' => false
        ];
        if (array_key_exists($config_value, $list)) return $list[$config_value];
        return false;
    }


    /**
     * 
     */
    public static function cart_redirect(string $config_value): array
    {
        $list = [
            'to_checkout'  => [
                'bool'  => true,
                'value' => 'checkout',
            ],
            'to_cart'      => [
                'bool'  => true,
                'value' => 'shop-cart',
            ],
            'stay_current' => [
                'bool'  => false,
                'value' => trim(str_replace(HttpRequestHandler::domain() ,"",_request()->headers('HTTP_REFERER')))
            ]
        ];
        if (array_key_exists($config_value, $list)) return $list[$config_value];
        return [false];
    }
}
