<?php

namespace Cart;

trait CurrencyTrait
{


    /**
     * convert the cost according to exchange rate
     */
    public function trade($cost, bool $convert_back = false)
    {
        $currency = $this->currency();
        $trade = $convert_back == true ?
            round($cost / $currency->rate, $currency->decimal) :
            round($cost * $currency->rate, $currency->decimal);
        return '' . $trade;
    }

    /**
     * get price with currency logo 
     */
    public function with_currency($cost, bool $convert = true): string
    {
        $currency = $this->currency();
        $price = $convert != false ? $this->trade($cost) : $cost;
        return $currency->value_after_symbol ?
            $currency->symbol . html_entity_decode("&nbsp;") . $price :
            $price . html_entity_decode("&nbsp;") . $currency->symbol;
    }

    /**
     * get shop currency 
     */
    public function currency(bool $default_if_empty = false): object
    {
        $currency = _cart()->config()->get('currency');
        if (empty($currency) && $default_if_empty === true) return $this->default_currency();
        return (object)$currency;
    }

    /**
     * get default currency 
     */
    public function default_currency(): object
    {
        $json = '{
            "title": "EUR",
            "symbol": "€",
            "rate": 1,
            "value_after_symbol": 1,
            "decimal": "2"
        }';
        return json_decode($json);
    }
}
