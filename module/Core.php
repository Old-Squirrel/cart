<?

namespace Cart;

abstract class Core
{

    private static $register = [
        'core'      => ['wordpress'],
        'adapter'   => ['model', 'http', 'installer']
    ];

    /**
     * create and return instance of 'core' class
     * according alias parameter
     */
    public static function getInstance(CoreAdapter $adapter)
    {
        $alias      = $adapter->getAlias();
        if(!in_array($alias , self::$register['adapter']))return false;
        $class      = self::getClass($alias);
        $instance   = new $class($adapter);
        return $instance;
    }


    /**
     * get registred aliases
     */
    public static function getRegister(string $key)
    {
        return self::$register[$key];
    }

    /**
     * get full class name by alias
     */
    public static function getClass($alias): string
    {
        $core_alias = Config::getAttribute('core');
        return _helper()->class_name('', $alias, $core_alias);
    }

    /**
     * get shop static data, title, desc, lang etc.
     */
    public static function getShopData(string $attr = '')
    {
        $shop_data = [
            'shop_title'      => get_bloginfo('name'),
            'description'     => get_bloginfo('description'),
            'language'        => get_bloginfo('language'),
            'site_url'        => get_bloginfo('url'),
            'domain'          => Config::getAttribute('domain'),
            'theme_name'      => get_stylesheet(),
            //'redirect_header' => "X-Redirect-By:WordPress",
        ];
        if (empty($attr)) {
            return (object)$shop_data;
        }
        return array_key_exists($attr, $shop_data) ? $shop_data[$attr] : false;
    }
}
