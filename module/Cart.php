<?

namespace Cart;


class Cart extends ServiceProvider
{

    protected static $instance;


    protected function __construct()
    {
    }

    public static function getInstance(): Cart
    {
        if (empty(self::$instance)) {
            self::$instance = new Cart;
        }
        return self::$instance;
    }

    /**
     * include classes and functions
     */
    public static function boot()
    {
        //define('CART_VERSION', '1.0.0');


        function _cart(): Cart
        {
            return Cart::getInstance();
        }

        function _response(): HttpResponse
        {
            return HttpResponse::getInstance();
        }

        function _request(array $params = []): Request
        {
            return Request::getInstance($params);
        }

        function _helper(): Helper
        {
            return Helper::getInstance();
        }




        ////////////////////////////////////////////////////////
        date_default_timezone_set('UTC');

        ////////////////////////////////////////////////////////

        require_once wp_shop_path() . '/' . 'wp-load.php';
    }

    /**
     * no clone
     */
    public function __clone()
    {
        return false;
    }
}
