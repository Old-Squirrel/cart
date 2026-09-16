<?
namespace Cart;

spl_autoload_register(function ($class) {
    if (!class_exists($class)) {
        $dirs = [
            'module',
            'module/database',
            'module/http',
            'module/install',
            'traits',
            'core',
            'controllers',
            'http',
            'http/middlewares',
            'models',
            'exceptions',
            'render'
        ];
        foreach ($dirs as $dir) {
            $file = SHOP_CART_PATH . '/' . $dir  . str_replace(__NAMESPACE__ . '\\', '/', $class) . '.php';
            if (is_file($file)) require $file;
        }
    }
});

