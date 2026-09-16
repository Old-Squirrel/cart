<?

namespace Cart;

trait SingletonTrait
{

    private static object $instance;



    public static function getInstance()
    {
        if (empty(self::$instance)) {
            $class = get_class();
            self::$instance = new $class();
        };
        return self::$instance;
    }


    public function __clone()
    {
        return false;
    }
}
