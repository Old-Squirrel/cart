<?

namespace Cart;

class ShopController extends BaseController
{
    use CurrencyTrait;

    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }



    /**
     * render checkout page content
     */
    public function checkout(): string
    {
        if ($this->_empty()) _response()->wp_redirect('shop-cart');
        $cart_data = $this->cart->customer();
        $content   = ViewFactory::checkout($cart_data);
        $template  = 'checkout';
        return _response()->render($template, $content);
    }


    /**
     * render cart page content
     */
    public function cart(): string
    {
        $cart_data = $this->cart->customer();
        $content   = $this->_empty() ? ViewFactory::emptyCart() : ViewFactory::cart($cart_data);
        $template  = $this->_empty() ? 'empty_cart' : 'cart';
        return _response()->render($template, $content);
    }

    /**
     * render products table
     */
    public function products($category): string
    {
        $category = _helper()->str_clear($category, [';', ':', '=', '/', ',', '?', '!']);
        if (empty($category)) return '';
        $products = ShopModel::builder('product')
            ->select()
            ->where('category', $category)
            ->get(true);

        if (empty($products)) return '';
        $table    = ViewFactory::productTable($products);
        return _response()->render('product_table', $table);
    }

    /**
     * add cart logo to nav menu
     */
    public function logo($items)
    {
        $data      = $this->cart->customer(true);
        $amount    = $data->amount > 0 ? $data->amount : '';
        $sub_total = $data->sub_total > 0 ? $this->with_currency($data->sub_total) : '';
        $href      = Core::getShopData('site_url') . '/' . 'shop-cart/';
        $class  = empty($amount) ? '' : 'ax-synced ';
        $class .= 'ed-cart-logo';
        $items .= '<li><a class="' . $class . '" title="Cart" href="' . $href . '" ><span class="ed-bi-cart"><i class="bi bi-cart"></i></span>';
        $items .= '<span class="ed-ax-data cart-total_amount">' . $amount . '</span>';
        $items .= '<strong><span class="ed-ax-data cart-sub_total">' . $sub_total . '</span></strong></a></li>';
        return $items;
    }


    /**
     * render thanks page, after order has been submitted successfully 
     */
    public function thank_you(): string
    {
        if ($this->_empty()) _response()->wp_redirect('shop-cart');
        $cart   = $this->cart->customer();
        $data   = $cart->get('all');
        $status = 'sent' . md5('sent' . $data['session_id']);
        $order  = ShopModel::builder('order')
            ->select()
            ->where('status', $status)
            ->get();
        $data  = ViewFactory::thank_you($cart, $order['id']);
        CustomerCart::destroy();
        return _response()->render('thank_you', $data);
    }

    /**
     * 
     */
    private function _empty(): bool
    {
        return $this->cart->customer(true)->empty == true || empty($this->cart->customer(true)->items);
    }
}
