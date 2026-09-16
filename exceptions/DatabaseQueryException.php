<?
namespace Cart;
class DatabaseQueryException extends \Exception
{

    protected string $response_type;

    /**
     * 
     */
    public function __construct(?string $message, string $response_type = 'api')
    {
        $this->message       = $message;
        $this->response_type = $response_type;
    }


    /**
     * 
     */
    public function response(): void
    {
        _response()
            ->setType($this->response_type)
            ->setBody($this->message);
    }
}
