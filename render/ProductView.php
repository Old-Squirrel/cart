<?

namespace Cart;

class ProductView
{

    use CurrencyTrait;

    private $product;
    private $first;

    public function __construct($product, $first)
    {
        $this->product  = $product;
        $this->first    = $first;
    }


    /**
     * get price object
     */
    public function price(): object
    {
        $is_first = fn () => $this->product->id === $this->first->id;
        return (object)[
            'value'     => $this->trade($this->product->price),
            'field'     => $this->with_currency($this->product->price),
            'crossed'   => $this->with_currency($this->dirty_price()),
            'delimiter' => "|",
            'is_first'  => $is_first()
        ];
    }

    /**
     * 
     */
    public function package(): string
    {
        return (0 + $this->product->dose) . $this->product->units . ' x ' . $this->product->amount . ' ' . _helper()->translate($this->product->prod_type);
    }

    /**
     * 
     */
    public function bonus(): string
    {
        $bounus = $this->product->bonus;
        return empty($bounus) ? '-' : '+ ' . $bounus . ' ' . _helper()->translate($this->product->prod_type);
    }


    public function cart_add_url(): string
    {
        return '/cart/add/' . _helper()->make_slug($this->product->id);
    }

    /**
     * 
     */
    public function per_one(): string
    {
        $cost = $this->product->price / $this->product->amount;
        return $this->with_currency($cost);
    }

    /**
     * 
     */
    public function bestseller(): string
    {
        $behavior   = _cart()->behavior('top_sales');
        if ($behavior['bool'] && $this->property('top_sale')  == 1) {
            return $behavior['value'];
        }
        return '';
    }

    /**
     * 
     */
    public function economy(): string
    {
        $economy = $this->product->id === $this->first->id ? 0 : ($this->dirty_price() - $this->product->price);

        return $this->with_currency($economy);
    }

    /**
     * 
     */
    public function property($name)
    {
        return $this->product->$name;
    }
    /**
     * 
     */
    private function dirty_price()
    {
        return $this->first->price / $this->first->amount  * $this->product->amount;
    }
}
