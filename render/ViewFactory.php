<?

namespace Cart;

class ViewFactory
{

    /**
     * get cart page data
     */
    public static function cart(CustomerCart $cart): array
    {
        $with_currency = fn ($price) => $cart->with_currency($price);
        $view  = new CartView();
        $data  = $cart->get('all');
        if (empty($data['items'])) return self::emptyCart();
        $data = array_merge($data, [
            'empty'          => false,
            'page'           => $view->page(),
            'with_currency'  => $with_currency,
        ]);
        return $data;
    }

    /**
     * get checkout page data
     */
    public static function checkout(CustomerCart $cart): array
    {
        $with_currency = fn ($price) => $cart->with_currency($price);
        $view = new CheckoutView();
        $data = $cart->get('all');
        if (empty($data['items'])) return self::emptyCart();
        $data = array_merge($data, [
            'empty'          => false,
            'page'           => $view->page(),
            'with_currency'  => $with_currency,
            'payment'        => $cart->payment(),
        ]);

        return $data;
    }

    /**
     * get product table data
     */
    public static function productTable(array $products)
    {
        $data  = self::productGroups($products);
        $table = self::tableStaticData($products[0]);
        $data['table'] = $table;
        return $data;
    }


    /**
     * get thank-you page data
     */
    public static function thank_you(CustomerCart $cart, int $order_id): array
    {
        $with_currency = fn ($price) => $cart->with_currency($price);
        $view = new ThankYouView();
        $data = $cart->get('all');
        $data = array_merge($data, [
            'empty'          => false,
            'page'           => $view->page(),
            'order_id'       => $order_id,
            'with_currency'  => $with_currency
        ]);
        return $data;
    }

    /**
     * defines static table data - titles, links , etc.
     */
    public static function tableStaticData(object $product): object
    {
        return (object)[
            'package'   => _lang('package'),
            'price'     => _lang('price'),
            'per_one'   => _lang('per ' . preg_replace('~s$~', '', $product->prod_type)),
            'bonus'     => _lang('bonus'),
            'buy'       => _lang('buy'),
            'economy'   => _lang('economy'),
            'units'     => $product->units,
            'page_url'  => HttpRequestHandler::currentPageUrl(),
            'title'     => _lang($product->category),
        ];
    }

    /**
     * 
     */
    public static function productGroups(array $products)
    {
        $active       = dynamic('active', '');
        $group        = self::groupByDosage($products);
        $categories   = array_keys($group);
        $selected     = fn ($category) => $category == $categories[0] ? 'selected' : '';
        $data_target  = fn ($dosage) => 'cat' . $dosage;
        $target_class = fn ($first_class = '', $second_class = '') => trim('ed-toggle-target ' . $first_class . ' ' . $second_class);
        return [
            'product_group'  => $group,
            'categories'     => $categories,
            'active'         => $active,
            'selected'       => $selected,
            'data_target'    => $data_target,
            'target_class'   => $target_class
        ];
    }

    /**
     * sort products by dosages and amount
     * creates Product instance for each selected products
     */
    public static function groupByDosage(array $products): array
    {
        $product_group = [];
        $first = [];
        sort_by_column($products, 'amount');
        foreach ($products as $product) {
            $dose = 0 + $product->dose;
            if (empty($first["$dose"])) $first["$dose"] = $product;
            $product_group["$dose"][] = new ProductView($product,  $first["$dose"]);
        }
        ksort($product_group);
        return $product_group;
    }

    /**
     * get empty Cart data
     */
    public static function emptyCart(): array
    {
        $view    = new CartView;
        $no_data = _helper()->empty_cart();
        $empty   = ['page' => $view->page()] + $no_data;
        return $empty;
    }
}
