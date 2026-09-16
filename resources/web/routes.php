<?

/**
 * 
 *          do not change the order of the keys
 *          first array must be "uninstalled_routes"
 * 
 *           
 */

return array(
    // includes if cart is not installed yet
    array(
        "Api::cart/api/install$"       => 'api/install',

        "Api::cart/api/uninstall$"     => 'api/uninstall',

        'Api::cart/api/get/config$'    => 'api/get/subject',

        "Api::cart/api/update/config$" => 'api/update/subject',

        "Api::cart/api/devtest$"       => 'api/devtest',

        'Get::cart(.*)'                => 'cart/badRequest'
    ),

    // includes after successful installation
    array(

        'Get::(cart)$'                            => 'cart/index',

        "Get::Shop::cart/add/{prod_slug}$"        => 'cart/add/product',

        "Get::Session::cart/delete/{prod_slug}$"  => 'cart/delete/product',

        "Get::Session::cart/remove/{prod_slug}$"  => 'cart/remove/product',

        "Get::Session::cart/update/{ship_slug}$"  => 'cart/update/shipping',

        "Order::cart/create/order$"         => 'order/create',

        "Api::cart/api/install$"            => 'api/install',

        "Api::cart/api/uninstall$"          => 'api/uninstall',

        "Api::cart/api/get/{get_list}$"     => 'api/get/subject',

        "Api::cart/api/update/{upd_list}$"  => 'api/update/subject',

        "Api::cart/api/check/order$"        => 'api/check/subject',

        "Api::cart/api/devtest$"            => 'api/devtest',

        'Get::cart(.*)'                     => 'cart/badRequest',

    )
);
