<?

namespace Cart;


class OrderRules
{

    private $form_type;

    public function __construct(string $form_type)
    {
        $this->form_type = $form_type;
    }

    public function validate()
    {
        $rules = [
            'bill_fname'    => fn (string $name): bool => !empty($name) && mb_strlen($name) <= 64,
            'bill_lname'    => fn (string $name): bool => !empty($name) && mb_strlen($name) <= 64,
            'bill_country'  => fn (string $country): bool => !empty($country) && is_numeric($country),
            'bill_city'     => fn (string $city): bool => !empty($city) && mb_strlen($city) <= 64,
            'bill_address'  => fn (string $address): bool => !empty($address) &&  mb_strlen($address) <= 96, //preg_match("~^(\\d{1,}) [a-zA-Z0-9\\s]+(\\,)? [a-zA-Z]+(\\,)? [A-Z]{2} [0-9]{5,6}$~", $address) &&
            'bill_phone'    => fn (string $phone): bool => !empty($phone) && mb_strlen($phone) <= 30 && preg_match("~[\s\#0-9_\-\+\/\(\)\.]~", $phone),
            'bill_email'    => fn (string $email): bool => !empty($email) && preg_match("~^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$~", $email),
            'bill_zip'      => fn (string $zip): bool => !empty($zip) && preg_match("~^[0-9]{5}(?:-[0-9]{4})?$~", $zip),
            'order_comment' => fn (string $comment): bool => empty($comment) || mb_substr($comment, 0, 100),
            'total'         => fn (string $total): bool => !empty($total) && is_numeric($total) && Session::check_data('total', $total),
            'sub_total'     => fn (string $sub_total): bool => !empty($sub_total) && is_numeric($sub_total) && Session::check_data('sub_total', $sub_total),
            'shipping'      => fn(string $ship):bool => !empty($ship) && Session::check_data('shipping', $ship),
        ];
    }


}



