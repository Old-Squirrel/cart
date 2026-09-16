<?php


define('SHOP_CART_PATH', dirname(__FILE__));


require SHOP_CART_PATH  . '/shop-cart.php';

Cart\_cart()
    ->router()
    ->run();
