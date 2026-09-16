<?

namespace Cart;

class CartController extends BaseController
{

    /**
     * 
     */
    public function index()
    {
        _response()->redirect('shop-cart', 308);
    }

    /**
     * add product to cart
     * start session
     */
    public function add(Request $request)
    {
        $cart = CustomerCart::start();
        $behavior = _cart()->behavior('cart_redirect');
        $uri      = $behavior['value'];
        $cart->update_items($request->get('product'));
        
        _response()->redirect($uri);
    }

    /**
     * 
     */
    public function delete(Request $request)
    {
        $cart = _cart()->customer();
        $cart->update_items($request->get('product'), 'minus');
        return $this->async($cart->get('all'));
    }


    /**
     * remove product from cart
     */
    public function remove(Request $request)
    {
        $cart = _cart()->customer();
        $product_slug = $request->get('product');
        $cart->update_items($product_slug, 'remove');
        return $this->async($cart->get('all'), ['removed' => $product_slug]);
    }



    /**
     * update cart shipping, as a result, change the total cost
     */
    public function update(Request $request)
    {
        $cart = _cart()->customer();
        $cart->update_shipping($request->get('shipping'));
        return $this->async($cart->get('all'));
    }


    /**
     * 
     */
    public function get(Request $request)
    {
        $cart = _cart()->customer();
        return $this->async($cart->get('all'));
    }


    /**
     * prepare data to frontend response 
     */
    private function async(array $cart, array $params = ['removed'=> ''])
    {
        _response()->setType('async');
        $data = (object)($cart + $params);
        
        return [
            'empty'            => false,
            'removed'          => $data->removed,
            'chosen_shipping'  => $data->chosen_shipping,
            'shipping_list'    => $data->shipping_list,
            'sub_total'        => _helper()->with_currency($data->sub_total),
            'total'            => _helper()->with_currency($data->total),
            'total_amount'     => $data->amount
        ];
    }
}
