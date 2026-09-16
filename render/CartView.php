<?

namespace Cart;


class CartView
{

    use CurrencyTrait;

    /**
     * page common static data
     */
    protected static function _default()
    {
        $url = fn(string $uri)=> Core::getShopData('site_url'). '/' . $uri . '/';
        return  [
            'shop_title'    => Core::getShopData('shop_title'),
            'payment'       => _lang('payment'),
            'billing'       => _lang('billing'),
            'shipping'      => _lang('shipping'),
            'price'         => _lang('price'),
            'product'       => _lang('product'),
            'amount'        => _lang('amount'),
            'sub_total'     => _lang('subtotal'),
            'total'         => _lang('cart total'),
            'checkout'      => _lang('proceed_to checkout'),
            'remove'        => _lang('remove', false),
            'site_url'      => Core::getShopData('site_url'). '/',
            'empty_title'   => _lang('Your~cart~is~empty'),
            'cart_url'      => $url('shop-cart'),
            'checkout_url'  => $url('checkout'),
            'thankyou_url'  => $url('thank-you'),
            'remove_url'    => '/' . 'remove' . '/',
            'update_url'    => '/' . 'update' . '/',
            'create_order_url' => $url('cart/create/order')
        ];
    }

    /**
     * get page static data
     */
    public function page(): object
    {
        $default    = self::_default();
        $override   = $this->add_to_page();
        return (object)array_merge($default, $override);
    }


    /**
     * data to be added to default page data
     */
    public function add_to_page(): array
    {
        return [
            'page_title'    => _helper()->translate('cart', true),
        ];
    }
}
