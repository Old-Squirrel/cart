<?php

/**
 * this file is a part of datanomy cart api
 *
 *           
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       shop-cart
 * Description:       generated during cart installation
 * Version:           1.3.3
 * Author:            Datanomy
 */

/*	

*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
define('SHOP_CART_VERSION', '1.3.3');
define('SHOP_CART_PlUGIN', plugin_dir_url(__FILE__));

add_shortcode('ipill_cash_products', 'show_product_table');
add_shortcode('shop_cart_render', 'shop_cart_render');
add_filter('wp_nav_menu_items', 'add_shop_cart_logo', 10, 2);



add_action('wp_enqueue_scripts', 'shop_cart_enqueue_script');



function shop_cart_enqueue_script()
{
    $ver = 'cv' . str_replace('.', '0', constant('SHOP_CART_VERSION')) . date('d');
    wp_register_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css');
    wp_enqueue_style('shop-cart-styles',  plugin_dir_url(__FILE__) . 'public/css/ed-cart-wpshop.css', array('bootstrap-icons'), SHOP_CART_VERSION, 'all');
    wp_enqueue_script('shop-cart-scripts', plugin_dir_url(__FILE__) . 'public/js/ed-cart-wpshop.js', array('jquery'), $ver, true);
}



function run_shop_cart()
{
    if (!function_exists('Cart\_cart')) {
        define('SHOP_CART_PATH', ABSPATH . 'cart' );
        require constant('SHOP_CART_PATH') . '/' . 'shop-cart.php';
    }
    return Cart\_cart()->controller();
}


function shop_cart_render($attr)
{
    $call_method = $attr['name'];
    return run_shop_cart()->$call_method();
}

add_filter('wp_nav_menu_items', 'add_shop_cart_logo', 10, 2);
function add_shop_cart_logo($items, $args)
{

    if ($args->theme_location === 'primary') {
        $items = run_shop_cart()->logo($items);
    }
    return $items;
}

function show_product_table($attr)
{   
    $category = $attr['name'];
    if (empty($category)) return '';
    return run_shop_cart()->products($category);
}

