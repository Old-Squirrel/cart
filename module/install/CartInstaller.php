<?

namespace Cart;

class CartInstaller extends AbstractInstaller implements CoreAdapter
{
    use AdapterTrait;

    private static $storage = [];
    private $core;
    private string $file;
    private string $alias = 'installer';


    public function __construct()
    {
        $this->file   = '/resources/includes/' . $this->resource()->keys('install_key') . '.php';
        $this->core   = Core::getInstance($this);
        self::$state  = [];
        self::$errors = [];
    }


    /**
     * install cart
     * create db tables
     */
    public function up()
    {
        $data    = $this->resource()->get('install');
      //  error_log('installer_data'.print_r($data,1));
        $models  = array_filter(ShopModel::children(), fn ($child) => $child != 'config', ARRAY_FILTER_USE_KEY);
        foreach ($models as $alias => $instance) {
            $model = new $instance;
            $this->store($alias, $model);
            if (!$model->createTable()) return $this->rollback('create', $alias);
            if (empty($data[$alias]))   continue;
            if (!is_int($model->clearTable()->seedTable($data[$alias]))) return $this->rollback('save', $alias);
        }
        $this->core->up();
        if (!$this->mark('install')) return $this->rollback('mark', '');
        return self::fresh();
    }


    /**
     * delete all data created via cart installer
     */
    public function down()
    {
        $models = ShopModel::children();
        foreach ($models as $instance) {
            $model = new $instance;
            $model->clearTable()->dropTable();
        }
        $this->mark('uninstall');
        $this->core->down();
        return self::fresh();
    }

    /**
     * confirm defined data availability
     */
    public function confirm()
    {
        return $this->core->confirm();
    }


    /**
     * save data to self::$storage
     */
    private function store(string $key, $data)
    {
        self::$storage[$key] = $data;
        return $this;
    }

    /**
     * abort installation and delete installed data
     */
    private function rollback(string $error_name, string $error_context): array
    {
        $data = self::$storage;
        array_map(fn ($model) => $model->dropTable(), $data);
        return self::errorFound($data[$error_name], $data[$error_context]);
    }


    /**
     * set installation marker
     */
    private function mark(string $marker): bool
    {
        switch (true) {
            case $marker === 'install':
                $content = '<? return [' . date("Ymd") . ']?>';
                return $this->resource()
                    ->loader()
                    ->createFile(SHOP_CART_PATH.$this->file, $content);
            case $marker === 'uninstall':
                return $this->resource()
                    ->loader()
                    ->deleteFile(SHOP_CART_PATH.$this->file);
        }
    }
}
