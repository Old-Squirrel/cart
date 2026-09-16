<?

namespace Cart;

trait StatusInspectorTrait
{

    protected static array $state;
    protected static array $errors;



    /**
     * check for errors and get status
     */
    protected static function status(bool $bool = false)
    {
        if (!empty($bool)) return empty(self::$errors);
        return !empty(self::$errors) ? 'failed' : 'success';
    }

    /**
     * get status and errors
     */
    protected static function fresh(bool $state_only = false): array
    {
        if (!empty($state_only)) return self::$state;
        $data = self::$state + [
            'status'    => self::status(),
            'errors'    => self::$errors
        ];
        return $data;
    }


    /**
     * fires new status error
     */
    protected static function errorFound(string $error_name, string $error_context = ''): array
    {
        self::getErrorMessage($error_name, $error_context);
        return self::$errors;
    }


    /**
     * retrieve error message from defined list 
     * @return message 
     */
    protected static function getErrorMessage(string $error_name, string $error_context = ''): string
    {

        $list = [
            'create'             => "s_table was not created",

            'save'               => "can not be saved",

            'install'            => 'already installed',

            'uninstall'          => 'shop cart has not been installed yet',

            'attribute'          => '- does not exists or misconfigured',

            'unverified'         => '- has not been validated',

            'file'               => '- file is missing',

            'mark'               => 'unable to copy installation data',

            'unknown'            => '- unknown error',

            'copy'               => "unable to copy $error_context source file"
        ];
        $error_name = !array_key_exists($error_name, $list) ? 'unknown' : $error_name;
        $message    = $error_context . $list[$error_name];
        self::$errors[$error_name] = $message;
        return $message;
    }
}
