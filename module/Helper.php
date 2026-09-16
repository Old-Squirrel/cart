<?

namespace Cart;

class Helper
{

    use SingletonTrait;
    use CurrencyTrait;

    private function __construct()
    {
    }

    /**
     *translates the specified phrase
     */
    public function translate(string $phrase, bool $upper_case = false): string
    {
        return Translator::getInstance()->get($phrase, $upper_case);
    }



    /**
     * Determines whether a class exists and implements abstract subclasses
     */
    public function check_core(string $core_name, array $class_list)
    {
        $dir = 'core';

        $class_names = array_map(fn ($class) => ucfirst($core_name) . $class, $class_list);

        $files = array_map(fn ($class) => $dir . $class . '.php', $class_names);

        $files_exist = array_reduce($files, fn ($init, $file) => $init && file_exists($file), true);

        if (!$files_exist) return false;

        $parent_extends = array_map(fn ($class_name, $class_parent) => class_parents($class_name)[$class_parent] == $class_parent, $class_names, $class_list);

        $parent_extends = array_reduce($parent_extends, fn ($init, $parent) => $init && $parent, true);

        return $parent_extends;
    }


    /**
     * Determines if a file exists
     */
    public function check_files(string $dir, $files, $ext = '.php', $delimiter = '/'): bool
    {
        switch (true) {
            case is_string($files):
                return file_exists($dir . $files . $ext);

            case is_array($files) && array_reduce(array_keys($files), fn ($init, $file) => $init && is_dir($dir . $file), true):
                $sub_dirs = array_keys($files);
                return array_reduce($sub_dirs, fn ($init, $sub_dir) => $init &&
                    file_exists($dir . $sub_dir . $delimiter . $files[$sub_dir][0]), true);
            default:
                return array_reduce($files, fn ($init, $file) => $init && file_exists($dir . $file . $ext), true);
        }
    }

    /**
     * Determine if string contains substring
     */
    public function str_contains(string $haystack, string $needle): bool
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }

    /**
     * 
     */
    public function compress(string $content): string
    {
        // remove comments
        $content = preg_replace('~/*\*[^*]*\*+([^/][^*]*\*+)*/~', '', $content);
        // // remove tabs, spaces, newlines, etc.
        $content = str_replace(array("\r\n", "\r", "\n", "\t", "\s"), '', $content);
        return $content;
    }


    /**
     * @param string $haystack string to clear
     * @param array $chars forbidden chars list
     * @param bool $merge merge with default list or replace
     */
    public function str_clear(string $haystack, array $chars = [], bool $merge = true): string
    {
        if (empty($haystack)) return '';

        $forbidden = ['<', '>'];

        switch (!empty($chars)) {
            case $merge !== false:
                $forbidden = array_merge($forbidden, $chars);
                break;
            case $merge === false:
                $forbidden = $chars;
                break;
        }
        return str_replace($forbidden, '', $haystack);
    }



    /**
     * @return array|false dir content without ' . ', ' .. '
     */
    public function scan(string $dir, string $delimiter = '/')
    {
        $content = array();
        $scanned = scandir($dir);
        foreach ($scanned as $value) {
            if (!in_array($value, array('.', '..'))) {
                $subdir = $dir . $delimiter . $value;
                is_dir($subdir) ?
                    $content[$value] = $this->scan($subdir) :
                    $content[] = $value;
            }
        }

        return $content;
    }

    /**
     * simulate data for an empty cart
     */
    public function empty_cart(): array
    {
        return [
            'empty'            => true,
            'removed'          => '',
            'chosen_shipping'  => '',
            'shipping_list'    => array(),
            'sub_total'        => 0,
            'total'            => 0,
            'total_amount'     => 0,
            'products'         => array(),
            'amount'           => 0,
            'items'            => array()
        ];
    }

    /**
     * generate class name according your own logic
     */
    public function class_name(string $prefix, string $suffix, string $class)
    {

        $list = ['model', 'http', 'middleware', 'installer', 'controller', 'view'];
        $namespace = __NAMESPACE__ . '\\';
        $class = trim($class);

        switch (true) {
            case in_array(strtolower($suffix), $list):
                $class_name = ucfirst(strtolower($class)) . ucfirst($suffix);
                return  $namespace . $class_name;
            case strtolower($prefix) === 'shop':
                $class  = str_replace('s_', '_', $class);   //makes singular form  like "products_table --> product_table "
                $name   = explode('_', $class)[1];
                $name  = $this->str_contains($name, 'cie') ? str_replace('cie', 'cy', $name) : $name;
                return [$name => $namespace . ucfirst($prefix) . ucfirst($name)];
            default:
                return $namespace . $class;
        }
    }

    /**
     * retrieve data
     */
    public function parse_slug(string $slug, $delim = '-'): int
    {
        return explode($delim, $slug)[1];
    }

    /**
     * generate slug string
     */
    public function make_slug(int $id, $prefix = 'prod', $delim = '-'): string
    {
        return $prefix . $delim . $id . $delim . date('d');
    }

    /**
     * check session status
     */
    public function session_started(){
        return !empty($_SERVER) && !empty($_SERVER['HTTP_COOKIE']) && Session::status();
    }
}
