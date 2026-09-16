<?

namespace Cart;

abstract class ShopConfig
{
    protected static function manager()
    {

        $data = [
            'shop_title'      => 'Brain Farmacia',       
            'description'     => 'Farmacia de venta libre',
            'site_dir'        => '/productos',
            'html_pages'      => '/resources/install/html_pages.json',
            'replace_content' => '/resources/install/replace_content.json'
        ];
    }

    protected static function dev()
    {
        $domain = $_SERVER['SERVER_NAME'];
        $core   = 'html';
        $data = [
            'core'          => $core,
            'site_url'      =>'https://' . $domain . '/',
            'domain'        => $domain,
            'session_name'  => 'cart',
            'cart_url'      => 'cart',
            'storage'       => '/resources/includes/translator_storage.php',
            // 'css_file'      => '/resources/install/ed-cart-'.$core.'shop.css',
            // 'js_file'       => '/resources/install/ed-cart-'.$core.'shop.js'
        ];
    }
}
