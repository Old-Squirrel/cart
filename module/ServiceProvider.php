<?

namespace Cart;

abstract class ServiceProvider extends CartResource
{

    private static $resource;
    private static $installer;
    private static $router;
    private static $config;
    private static $controller;


    /**
     * get resource instance
     */
    public function resource(): ServiceProvider
    {
        if (empty(self::$resource)) {
            self::$resource = new class extends ServiceProvider {};
        }
        return self::$resource;
    }


    /**
     * @return CartInstaller instance;
     */
    public function installer(): CartInstaller
    {

        self::$installer = new CartInstaller();
        return self::$installer;
    }


    /**
     * get router instance
     * @return Router instance;
     */
    public function router(): Router
    {
        $_routes = fn():array => $this->loader()->get('web', 'routes')[$this->installed()];
        if ($this->installed()) {
            if (!empty(self::$router)) self::$router = new Router($this->store('routes', $_routes())->resource());
                
            
            return self::$router;
        }
        if (!$this->installed()) {
            self::$router = new Router($this->store('routes', $_routes())->resource());
        }
        return self::$router;
    }


    /**
     * get cart config instance
     */
    public function config(): Config
    {
        self::$config = Config::getInstance();
        return self::$config;
    }



    /**
     * get cart config instance
     */
    public function controller(): ShopController
    {
        if (empty(self::$controller)) self::$controller = new ShopController($this);
        return self::$controller;
    }


    /**
     * get CustomerCart data or CustomerCart $instance if $data_only == false
     * simulate data for an empty cart if no session
     */
    public function customer(bool $data_only = false): object
    {
        if (_helper()->session_started()) {
            $cart = CustomerCart::start();
            if ($data_only === false) return $cart;
            $data = $cart->get('all');
            $data['empty'] = empty($data['items']);
            return (object)$data;
        }
        return (object)_helper()->empty_cart();
    }


    /**
     * 
     */
    public function behavior(string $target)
    {
        $behavior_list = $this->config()->get('behavior');
        if (method_exists('Cart\Behavior', $target) && array_key_exists($target, $behavior_list)) {
            return Behavior::$target($behavior_list[$target]);
        }
        return null;
    }


    /**
     * check cart installation state
     */
    public function installed(): bool
    {
        return  file_exists(SHOP_CART_PATH . '/resources/includes/' . $this->keys('install_key') . '.php');
    }
}
