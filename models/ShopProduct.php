<?

namespace Cart;

class ShopProduct extends AdapterModel
{


    protected $columns   = [
        'id','api_id','title','category','dose','units',
        'amount','bonus','price','prod_type','top_sale','image_path'
    ];



    public function __construct()
    {
        parent::__construct($this);
    }


    /**
     * fill products table
     */
    public function seedTable(array $data): int
    {
        $coef = _cart()->config()->get('price_coef');
        foreach ($data as $product) {
            foreach ($product['categories'] as $cat) {
                $categoty = _helper()->translate($cat['title'], true);
                $title    = $categoty . ' ' . $product['dose'] . $product['dose_type']['title'] . ' x ' . $product['amount'];
                $title    = $product['bonus'] > 0 ? $title . ' + ' . $product['bonus'] . ' ' . _helper()->translate('bonus') : $title;
                $new_product = [
                    'api_id'    => $product['id'],
                    'title'     => $title,
                    'category'  => $cat['title'],
                    'dose'      => $product['dose'],
                    'units'     => $product['dose_type']['title'],
                    'amount'    => $product['amount'],
                    'bonus'     => $product['bonus'],
                    'price'     => $product['price'] * $coef,
                    'prod_type' => $product['amount_in'],
                    'top_sale'  => $product['top_sale'],
                    'image_path' => 'public/images/file.jpg',
                ];
                $last_id =  $this->create($new_product);
            }

            usleep(500);
            
        }
        return $last_id;
    }

    /**
     * get the price value without additional digits after the decimal point 
     */
    public function pretty_price($product, int $round = 2)
    {
        $price = 0 + round($product['price'], $round);
        return $price;
    }
}
