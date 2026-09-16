<?

namespace Cart;

abstract class FileLoader
{

    private static array $storage = [];

    

    /**
     *  get stored data according inner name
     */
    public function get(string $alias, string $file_name, string $ext = '.php')
    {
        self::load($alias, $file_name, $ext);
        return self::$storage[$alias][$file_name];
    }

    /**
     * extract variables before including file
     * include view file
     */
    public function includeView(string $path, array $vars)
    {
        $_inclusion = function (string $file_name, array $args = []) use ($vars) {
            $args = array_merge($args, $vars);
            extract($args);
            include SHOP_CART_PATH . '/resources/templates/includes/' . $file_name;
        };
        extract($vars);
        include SHOP_CART_PATH . '/resources/templates/' . $path;
    }

    /**
     * create file 
     */
    public function createFile(string $full_name, string $content): bool
    {
        $result = file_put_contents($full_name, $content);
        return is_int($result);
    }

    /**
     * delete file
     */
    public function deleteFile(string $full_name)
    {
        return unlink($full_name);
    }

    /**
     * get dir/file content
     * @return array|string|false
     * throw exception if file is missing
     */
    public function getContent(string $path, bool $dir = false)
    {
        switch (true) {
            case $dir != false:
                return _helper()->scan(SHOP_CART_PATH . "/resources/$path/");
            case file_exists($path):
                return file_get_contents($path);
            default:
                throw new \Exception();//FileMissedException('file is missing', 'api');
        }
    }


    /**
     * include 'cart/resources/file' 
     * save content as $storage[$alias][$file_name]
     * file content must be an array
     */
    private static function load(string $alias, string $file_name, string $ext = '.php'): void
    {
        if (empty(self::$storage[$alias][$file_name])) {

            $file = SHOP_CART_PATH . "/resources/$alias/$file_name" . $ext;

            self::$storage[$alias][$file_name] = self::requireArray(require $file);
        }
    }


    /**
     * check the including file to be an array
     */
    private static function requireArray(array $file): array
    {
        return $file;
    }
}
