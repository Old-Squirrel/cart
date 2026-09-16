<?

namespace Cart;

class WordpressInstaller extends CoreInstaller
{
    private string $plugin_name;
    private string $plugin_dir_path;
    private string $install_dir_path;
    private object $src;


    public function __construct()
    {

        $this->init();
    }


    /**
     * install cart
     */
    public function up()
    {
        if ($this->create_plugin_dir()) {
            self::$state['theme']  = Core::getShopData('theme_name');
            self::$state['plugin'] = $this->plugin_name;
            return self::fresh();
        }
        $errors = self::errorFound('mark', 'wordpress plugin');
        return self::fresh();
    }

    /**
     *  remove files and dirs
     */
    public function down()
    {
        $procedure          = [];
        $procedure['files'] = $this->remove_plugin_files();
        $procedure['dirs']  = $this->remove_plugin_dir();
        $its_ok             = array_reduce($procedure, fn ($init, $result) => $init && $result === true, true);
        if (!$its_ok) self::errorFound('copy', 'or delete dir or ');
        return self::fresh();
    }

    /**
     * confirm defined data availability
     */
    public function confirm()
    {
        $check_file = $this->plugin_dir_path . '/' . $this->src->plugin_file;
        $its_ok     = file_exists($check_file);
        if ($its_ok) {
            self::$state['theme']  = Core::getShopData('theme_name');
            self::$state['plugin'] = $this->plugin_name;
        } else {
            self::errorFound('file', 'plugin file');
        }
        return self::fresh();
    }

    /**
     *  define and set paths to source data
     */
    private function init()
    {
        $this->plugin_name      = 'shop-cart';
        $plugin_file_name       = $this->plugin_name.'.php';
        $this->plugin_dir_path  = wp_shop_path() . '/wp-content/plugins/'. $this->plugin_name;
        $this->install_dir_path = SHOP_CART_PATH . '/resources/wordpress_install';
        $this->src              = (object)['plugin_file'  => $plugin_file_name, 'css' => 'css/ed-cart-wpshop.css', 'js' => 'js/ed-cart-wpshop.js'];
    }

    /**
     * copy src files to 'wp-content/plugins' directory
     */
    private function create_plugin_dir(): bool
    {
        $plugin_dir   = $this->plugin_dir_path . '/';
        $install_dir  = $this->install_dir_path . '/';
        $dirs_created = mkdir($plugin_dir . 'public/css/', 0755, true) && mkdir($plugin_dir . 'public/js/', 0755, true);
        if (!$dirs_created) self::errorFound('copy', 'or create plugin dir or');
        if ($dirs_created) {
            $index_file          = file_put_contents($plugin_dir . 'index.php', '<?php' . '// Silence is golden.');
            $pub_index_file      = file_put_contents($plugin_dir . 'public/index.php', '<?php' .  '// Silence is golden.');
            $procedure['index']  = $index_file !== false && $pub_index_file !== false;
            $procedure['plugin'] = copy($install_dir . $this->src->plugin_file, $plugin_dir . $this->src->plugin_file);
            $procedure['css']    = copy($install_dir . $this->src->css, $plugin_dir . 'public/' . $this->src->css);
            $procedure['js']     = copy($install_dir . $this->src->js, $plugin_dir . 'public/' . $this->src->js);
        }
        foreach ($procedure as $name => $result) {
            if ($result == false) self::errorFound('copy', 'or create dir or ' . $name);
        }
        return self::status() === 'success';
    }

    /**
     * delete plugin dirs
     */
    private function remove_plugin_dir(): bool
    {
        $plugin_dir = $this->plugin_dir_path . '/';
        $public_dir = $plugin_dir . 'public/';
        $dirs = [
            $plugin_dir,
            $public_dir,
            $public_dir . 'css/',
            $public_dir . 'js/',
        ];
        return array_reduce($dirs, fn ($init, $dir_path) => $init && rmdir($dir_path), true);
    }

    /**
     * delete plugin files
     */
    private function remove_plugin_files(): bool
    {
        $plugin_dir = $this->plugin_dir_path . '/';
        $public_dir = $plugin_dir . 'public/';
        $files = [
            $plugin_dir . 'index.php',
            $public_dir . 'index.php',
            $public_dir . $this->src->css,
            $public_dir . $this->src->js,
            $plugin_dir . $this->src->plugin_file
        ];
        return array_reduce($files, fn ($init, $file) => $init && unlink($file), true);
    }
}


