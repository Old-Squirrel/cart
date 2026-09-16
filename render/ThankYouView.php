<?

namespace Cart;

class ThankYouView extends CartView
{

    /**
     * data to be added to default page data
     */
    public function add_to_page(): array
    {
        return [
            'page_title'     => _lang('thank you'),
            'order_recieved' => _lang('your~order~has~been~recieved'),
            'thank_you'      => _lang('thank you'),
            'bank'           => _lang('bank'),
            'credit_card'    => _lang('credit_card'),
            'details'        => _lang('order_details'),
            'pay_method'     => _lang('payment_method'),
            'order_number'   => _lang('order_number'),
            'date'           => _lang('date'),
        ];
    }
}
