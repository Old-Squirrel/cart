<?php

namespace Cart;

/**
 * return object representation of an array
 */
function _object(array $arr)
{
    return  json_decode(json_encode($arr));
}

/**
 * Determines the presence of all array elements in another array
 */
function check_in_list(array $needle, array $haystack): bool
{
    return array_reduce($needle, fn ($init, $property) => $init && in_array($property, $haystack), true);
}

/**
 * check arrays equality
 */
function is_equal_arrs($arr1, $arr2): bool
{
    return !empty($arr1) && array_diff($arr1, $arr2) == array_diff($arr2, $arr1) &&
        count($arr1) === count($arr2);
}

/**
 *  return Closure that returns $first for first call and $second for all others
 */
function dynamic(string $first, string $second)
{
    $i = 0;
    $arr = [$first, $second];
    return function () use (&$i, $arr) {
        return $arr[$i++ > 0];
    };
}

/**
 * sort array
 */
function sort_by_column(&$array, $column, $order = SORT_ASC)
{
    array_multisort(
        array_column($array, $column),
        $order,
        $array
    );
}

/**
 * get the first element of the array
 */
function _first(array $arr)
{
    $result = $arr;
    $result = array_shift($result);
    return $result;
}

/**
 * @return timestamp according $period 
 */
function time_interval($period)
{
    $list = ['day' => 1, 'week' => 7, 'month' => 30, 'year' => 365];
    $time = empty($list[$period]) ? $period : $list[$period];
    $result = time() + 60 * 60 * 24 * $time;

    return $result;
}

/**
 * shortened form of the translation function call
 */
function _lang(string $phrase, bool $upper_case = true): string
{
    return _helper()->translate($phrase, $upper_case);
}

/**
 * get path to wp directory
 */
function wp_shop_path(): string
{
    $domain      = Config::getAttribute('domain');
    $domain_cart = $domain . '/' . 'cart';
    $wp_path     = rtrim(str_replace($domain_cart, $domain, SHOP_CART_PATH), '/');
    return $wp_path;
}
