<?

namespace Cart;


abstract class CartResource
{
    private static array $storage = [];

    private static FileLoader $loader;


    /**
     * get loader instance
     */
    public function loader(): FileLoader
    {
        if (empty(self::$loader)) {
            self::$loader = new class extends FileLoader
            {
            };
        }

        return self::$loader;
    }


    /**
     * retrieve data from storage ;
     * delete $storage[$inner_name] as needed
     */
    public function get(string $inner_name, bool $unset_after = false)
    {
        if (!array_key_exists($inner_name, self::$storage)) return '';
        $data = self::$storage[$inner_name];
        if ($unset_after === true) unset(self::$storage[$inner_name]);
        return $data;
    }

    /**
     * share inner data
     */
    public function store(string $inner_name, $value)
    {
        self::$storage[$inner_name] = $value;
        return $this;
    }


    /**
     * get specific key
     */
    public function keys(string $key): string
    {
        $base_key    = 'cart';
        $domain      = Config::getAttribute('domain');
        $local_key   = md5($base_key);  // 54013ba69c196820e56801f1ef5aad54
        $api_key     = 'WV0lXwIDW4GCDANoeALRUSmL3nvrxcRl' . $domain;
        $server_key  = $local_key . $domain;
        $install_key = 'sh0pcrt'. md5($domain.'installed'); //sh0pcrtedfd9f0eea068a54db70c22d10637fcb

        $list = array(
            'install_key' => $install_key,
            'api_key'     => md5($api_key), //a251ececc9311ca617eb8539cf35f5c0
            'local_key'   => $local_key, //54013ba69c196820e56801f1ef5aad54
            'server_key'  => md5($server_key),  // 21587eddd855dfb9ddf0b0129ea965ad
        );

        return $list[$key];
    }
}
