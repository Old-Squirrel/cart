<?

namespace Cart;


class Config extends ConfigOptions
{
    use StatusInspectorTrait;
    private static $instance;
    protected static $is_saved;


    protected function __construct()
    {
    }


    /**
     * get Config $instance
     */
    public static function getInstance(): Config
    {
        if (empty(self::$instance)) {
            self::build();
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * get config data
     */
    public function get(string $property = '')
    {
        switch (true) {
            case $property === 'json':
                $state  = self::$state;
                return ['state' => json_encode($state)];
            case $property === 'all':
                return self::$state;
            case empty($property):
                self::verification();
                $options = array('available' => self::options());
                $errors  = array('errors'    => self::$errors);
                $status  = array('status'    => self::fresh()['status']);
                return self::$state + $errors + $status + $options;
            case array_key_exists($property, self::$state):
                return self::$state[$property];
            case array_key_exists($property, self::fresh()):
                return self::fresh()[$property];
        }
    }

    /**
     * store in database
     */
    public function save(): Config
    {
        //error_log('save_config'.print_r([self::$state] ,1));
        if (self::status(true)) {
            $model  = new ShopConfig();
            if ($model->tableExist() || $model->createTable()) {
                $config = self::$state;
                $config = ['state' => json_encode($config)];
                $save   = $model->create($config);
                if (is_int($save)) {
                    self::$is_saved = true;
                    return $this;
                }
                $errors = self::errorFound('save', 'config');
            };
            $errors = self::errorFound('save', 'config');
        }
        self::$is_saved = null;
        return $this;
    }


    /**
     * update and validate config state
     */
    public function update(): Config
    {
        $config = _cart()->resource()->get('config', true);

        if (is_array($config)) self::create($config, true);
        // error_log('update_config'.print_r([$config, self::$state] ,1));
        return $this;
    }


    /**
     * add hash string to $state
     */
    protected static function verification(): void
    {
        $state  = self::$state;
        $state  = array_filter($state, fn (string $prop) => $prop != 'tables' && $prop != 'counters', ARRAY_FILTER_USE_KEY);
        ksort($state);
        // error_log('state_ver'.print_r($state,1));
        self::$state['hash'] = md5(json_encode($state));
    }


    /**
     * special method to retrieve config data before Config instance
     */
    public static function getAttribute(string $key)
    {
        $state    = self::main();
        $valid    = fn ($key) => array_key_exists($key, $state) && self::rules($key, $state[$key]);
        if ($valid($key)) return $state[$key];
        return false;
    }


    /**
     * build config
     */
    protected static function build(): void
    {
        self::$state  = [];
        self::$errors = [];
        if (_cart()->installed() || self::$is_saved) {
            $config = new ShopConfig();
            $config = $config->last();
            if (is_array($config)) {
                self::create($config);
                return;
            }
        }
        self::create(self::main(), true);
    }

    /**
     * validate input data
     */
    protected static function validate(): void
    {
        if (empty(self::$state)) {
            self::errorFound('unknown', 'config is empty');
            return;
        }
        self::$errors = [];
        foreach (self::$state as $name => $value) {
            if (!self::rules($name, $value)) self::errorFound('attribute', $name);
        }
    }

    /**
     * set config state data
     */
    protected static function create(array $config = [], bool $validate = false): void
    {

        if (count($config) == 0) $validate = true;
        if (count($config) >= 1) {
            $config = array_filter($config, fn (string $prop) => $prop != 'core' && $prop != 'domain' && $prop != 'tables', ARRAY_FILTER_USE_KEY);
            foreach (self::main() as $property => $value) {
                if (!empty($config[$property])) self::$state[$property] = $config[$property];
                if (empty($config[$property]))  self::$state[$property] = $value;
            }
        }
        if ($validate === true) self::validate();
    }


    /**
     * 
     */
    protected static function rules($prop_name = null, $prop_value = null): bool
    {

        if (!array_key_exists($prop_name, self::requirements())) return true;

        $list = [

            'is_set'        => fn () => !empty($prop_value), //|| array_key_exists($prop_name, $main),

            'is_file'       => fn () => true, //_helper()->check_files(Source::innerRoutes($prop_name, false), $prop_value),

            'is_coef'       => fn () => $prop_value > 0.5 && $prop_value <= 2.0,

            'is_core'       => fn () => _helper()->check_core($prop_value, Core::getRegister('adapter')),

            'is_option'     => fn () =>  check_in_list($prop_value, self::options($prop_name)),

            'nullable'      => fn () =>  true, //empty($prop_value) || array_key_exists($prop_name, self::$state) && check_in_list($prop_value, self::options($prop_name)),

            'all_options'   => fn () => is_equal_arrs(array_keys(self::options($prop_name)), array_keys($prop_value)),

        ];

        foreach (self::requirements($prop_name) as $callback) {
            if (!array_key_exists($callback, $list)) return false;
            $_func = $list[$callback];
            return $_func();
        }

        return false;
    }
}
