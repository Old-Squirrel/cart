<?

namespace Cart;

class ShopOrder extends AdapterModel
{

    protected $columns = array(
        'id', 'total', 'subtotal', 'insurance', 'currency', 'products',
        'shipping', 'billing', 'payment', 'http', 'browser', 'status', 'api_status'
    );
    protected $attributes = array('payment');


    public function __construct()
    {
        parent::__construct($this);
    }

    /**
     * 
     */
    protected function payment(array $payment)
    {
        if ($payment['method'] == 1) {
            $params = [
                'card_cvv'    => base64_encode($payment['params']['card_cvv']),
                'card_number' => base64_encode($payment['params']['card_number']),
                'card_expiry' => base64_encode($payment['params']['card_expiry']),
                'card_name'   => $payment['params']['card_name'],
            ];
            $payment['params'] = $params;
        }
        return json_encode($payment);
    }
}
