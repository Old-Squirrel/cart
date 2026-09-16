<?

namespace Cart;

class CheckoutView extends CartView
{


    /**
     * data to be added to default page data
     */
    public function add_to_page(): array
    {
    
        return [
            'page_title'    => _lang('checkout'),
            'country'       => _lang('country_region'),
            'city'          => _lang('town_city'),
            'phone'         => _lang('phone'),
            'email'         => _lang('email'),
            'first_name'    => _lang('first_name'),
            'last_name'     => _lang('last_name'),
            'your_order'    => _lang('your_order'),
            'postcode'      => _lang('postcode_zip'),
            'order_submit'  => _lang('place_order'),
            'thank_you'     => _lang('thank you'),
            'bank'          => _lang('bank'),
            'card'          => _lang('card'),
            'credit_card'   => _lang('credit_card'),
            'card_name'     => _lang('card holder name'),
            'select_card'   => _lang("select_card_type"),
            'card_type'     => _lang('card_type', false),
            'bill_and_shipp'  => _lang('billing~and~shipping'),
            'proceed_to_cart' => _lang('proceed_to cart'),
            'comments'      => $this->attributes('order_notes, 1', 'your~notes, 1', 'additional_information ,1'),
            'address'       => $this->attributes('street_address, 1', 'Street/Square and Civic Number', ''),
            'countries'     => _object(_cart()->config()->get('countries')),
            'card_date'     => $this->attributes('expiry_date, 1', 'MM/YY', ''),
            'card_cvv'      => $this->attributes('cvv_number, 1', 'CVV/CVC', ''),
            'card_types'    => array('amex' => 'American Express', 'visa' => 'Visa', 'master' => 'MasterCard'),
            'card_number'   => $this->attributes('card account_number, 1', '•••• •••• •••• ••••', ''),
        ];
    }

    /**
     * 
     */
    private function attributes(string $label, string $placeholder, string $title): object
    {
        $parse = fn ($attr) => empty($attr) ? [''] : explode(',', $attr);
        $attr  = fn ($attr) => !empty($parse($attr)[1]) &&  $parse($attr)[1] == 1 ?
            _helper()->translate(_first($parse($attr)), true) :
            _first($parse($attr));

        return (object)[
            'label'         => $attr($label),
            'placeholder'   => $attr($placeholder),
            'title'         => $attr($title),
        ];
    }
}
